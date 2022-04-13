<?php
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\bill;

use DateTimeImmutable;
use hiqdev\billing\hiapi\Http\Serializer\HttpSerializer;
use hiqdev\billing\hiapi\Hydrator\Helper\DateTimeImmutableFormatterStrategyHelper;
use hiqdev\php\billing\bill\Bill;
use hiqdev\php\billing\bill\BillInterface;
use hiqdev\php\billing\bill\BillRequisite;
use hiqdev\php\billing\bill\BillState;
use hiqdev\php\billing\charge\ChargeInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\plan\Plan;
use hiqdev\php\billing\target\Target;
use hiqdev\php\billing\type\Type;
use hiqdev\php\units\Quantity;
use hiqdev\DataMapper\Hydrator\GeneratedHydrator;
use Money\Money;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use yii\web\User;

/**
 * Bill Hydrator.
 *
 * @author Andrii Vasyliev <sol@hiqdev.com>
 */
class BillHydrator extends GeneratedHydrator
{
    protected array $requiredAttributes = [
        'type' => Type::class,
        'time' => DateTimeImmutable::class,
        'quantity' => Quantity::class,
        'sum' => Money::class,
        'customer' => Customer::class,
    ];

    protected array $optionalAttributes = [
        'target' => Target::class,
        'plan' => Plan::class,
        'state' => BillState::class,
        'requisite' => BillRequisite::class,
    ];
    private HttpSerializer $httpSerializer;

    public function __construct(HttpSerializer $httpSerializer)
    {
        $this->httpSerializer = $httpSerializer;

        // TODO: make it work
        $this->addStrategy('time', DateTimeImmutableFormatterStrategyHelper::create());
    }

    /**
     * {@inheritdoc}
     * @param array $data
     * @param object|Bill $object
     * @return object
     * @throws \Exception
     */
    public function hydrate(array $data, $object): object
    {
        foreach ($this->requiredAttributes as $attr => $class) {
            if ($attr === 'time') {
                $data[$attr] = $this->hydrateValue($attr, $data[$attr]);
            } else {
                $data[$attr] = $this->hydrator->create($data[$attr], $class);
            }
        }

        foreach ($this->optionalAttributes as $attr => $class) {
            if (isset($data[$attr])) {
                if (is_array($data[$attr]) && $this->isArrayDeeplyEmpty($data[$attr])) {
                    $data[$attr] = null;
                } else {
                    $data[$attr] = $this->hydrator->create($data[$attr], $class);
                }
            }
        }

        $raw_charges = $data['charges'] ?? null;
        unset($data['charges']);

        /** @var Bill $bill */
        $bill = parent::hydrate($data, $object);

        if (\is_array($raw_charges)) {
            $charges = [];
            foreach ($raw_charges as $key => $charge) {
                if ($charge instanceof ChargeInterface) {
                    $charge->setBill($bill);
                    $charges[$key] = $charge;
                } else {
                    $charge['bill'] = $bill;
                    $charges[$key] = $this->hydrator->hydrate($charge, ChargeInterface::class);
                }
            }
            $bill->setCharges($charges);
        }

        return $bill;
    }

    private function isArrayDeeplyEmpty(array $array): bool
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));
        foreach ($iterator as $value) {
            if ($value !== null) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     * @param object|Bill $object
     */
    public function extract($object): array
    {
        return array_filter([
            'id'            => $object->getId(),
            'type'          => $this->hydrator->extract($object->getType()),
            'time'          => $this->extractValue('time', $object->getTime()),
            'sum'           => $this->hydrator->extract($object->getSum()),
            'quantity'      => $this->hydrator->extract($object->getQuantity()),
            'customer'      => $this->hydrator->extract($object->getCustomer()),
            'requisite'     => $object->getRequisite() ? $this->hydrator->extract($object->getRequisite()) : null,
            'target'        => $object->getTarget() ? $this->hydrator->extract($object->getTarget()) : null,
            'plan'          => $object->getPlan() ? $this->hydrator->extract($object->getPlan()) : null,
            'charges'       => $this->httpSerializer->ensureBeforeCall(
                function (User $user) use ($object) {
                    $plan = $object->getPlan();
                    if ($plan && $plan->getType() && in_array($plan->getType()->getName(), ['server', 'private_cloud'])) {
                        return $user->can('bill.see-server-charges');
                    }

                    return true;
                },
                fn() => $this->hydrator->extractAll($object->getCharges()), []
            ),
            'state'         => $object->getState() ? $this->hydrator->extract($object->getState()) : null,
            'comment'       => $object->getComment(),
        ], static function ($value): bool {
            return $value !== null;
        }, ARRAY_FILTER_USE_BOTH);
    }

    public function createEmptyInstance(string $className, array $data = []): object
    {
        if ($className === BillInterface::class) {
            $className = Bill::class;
        }

        return parent::createEmptyInstance($className, $data);
    }
}
