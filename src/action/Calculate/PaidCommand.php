<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\action\Calculate;

use DateTimeImmutable;
use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiapi\validators\LongRefValidator;
use hiapi\validators\RefValidator;
use hiapi\validators\UsernameValidator;
use hiqdev\billing\hiapi\customer\CustomerLoader;
use hiqdev\billing\hiapi\plan\PlanLoader;
use hiqdev\billing\hiapi\target\TargetLoader;
use hiqdev\DataMapper\Validator\DateTimeValidator;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\plan\PlanInterface;
use hiqdev\php\billing\sale\SaleInterface;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\TypeInterface;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\QuantityInterface;
use Psr\Container\ContainerInterface;
use Laminas\Hydrator\HydratorInterface;

class PaidCommand extends BaseCommand implements PaidCommandInterface
{
    protected ContainerInterface $di;
    private HydratorInterface $hydrator;

    public function __construct(ContainerInterface $di, $config = [])
    {
        $this->di = $di;
        parent::__construct($config);
    }

    public $target_id;
    public $target_type;
    public $target_name;
    public $target_fullname;

    public $customer_id;
    public $customer_username;

    public $plan_id;
    public $plan_name;
    public $plan_seller;
    public $plan_fullname;

    public $amount;
    public $unit = 'items';

    /** @var string */
    public $type_name;
    /** @var TypeInterface|null */
    public $type;
    /** @var DateTimeImmutable|string */
    public $time;

    protected ?PlanInterface $plan = null;
    protected ?SaleInterface $sale = null;
    protected ?ActionInterface $action = null;
    protected ?TargetInterface $target = null;
    protected ?CustomerInterface $customer = null;

    /** @var ActionInterface[] */
    private $actions;

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['target_id'], IdValidator::class],
            [['target_type'], RefValidator::class],
            [['target_name'], RefValidator::class],
            [['target_fullname'], 'string'],

            [['customer_id'], IdValidator::class],
            [['customer_username'], UsernameValidator::class],

            [['plan_id'], IdValidator::class],
            [['plan_name'], 'string'],
            [['plan_seller'], UsernameValidator::class],
            [['plan_fullname'], 'string'],

            ['amount', 'number', 'min' => 0],
            [['type_name'], LongRefValidator::class],
            [['unit'], RefValidator::class],

            [['time'], DateTimeValidator::class],
        ]);
    }

    public function getPlan(): ?PlanInterface
    {
        if (!isset($this->plan)) {
            $this->plan = $this->di->get(PlanLoader::class)->findPlanByCommand($this);
        }

        return $this->plan;
    }

    public function setPlan(?PlanInterface $plan)
    {
        $this->plan = $plan;
    }

    public function getTarget(): ?TargetInterface
    {
        if ($this->target === null) {
            $this->target = $this->di->get(TargetLoader::class)->findTarget($this);
        }

        return $this->target;
    }

    public function setTarget(?TargetInterface $target)
    {
        $this->target = $target;
    }

    public function getCustomer(): ?CustomerInterface
    {
        if (!isset($this->customer)) {
            $this->customer = $this->di->get(CustomerLoader::class)->findCustomer($this);
        }

        return $this->customer;
    }

    public function setCustomer(CustomerInterface $customer)
    {
        $this->customer = $customer;
    }

    public function getQuantity(): QuantityInterface
    {
        return Quantity::create($this->unit, $this->amount);
    }

    public function getType(): TypeInterface
    {
        if ($this->type === null) {
            $this->type = $this->getHydrator()->hydrate([
                'name' => $this->type_name,
            ], TypeInterface::class);
        }

        return $this->type;
    }

    public function getTime(): DateTimeImmutable
    {
        if (empty($this->time) || is_string($this->time)) {
            $this->time = new DateTimeImmutable($this->time ?? '');
        }

        return $this->time;
    }

    public function getActions(): array
    {
        if (!isset($this->actions)) {
            $this->actions = [$this->getAction()];
        }

        return $this->actions;
    }

    protected function getAction(): ActionInterface
    {
        if (!isset($this->action)) {
            /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
            $this->action = $this->getHydrator()->hydrate(array_filter([
                'customer' => $this->getCustomer(),
                'target' => $this->getTarget(),
                'type' => $this->getActionType(),
                'quantity' => $this->getQuantity(),
                'sale' => $this->getSale(),
                'time' => $this->getTime(),
            ]), ActionInterface::class);
        }

        return $this->action;
    }

    protected function getActionType(): TypeInterface
    {
        return $this->getType();
    }

    public function getSale(): ?SaleInterface
    {
        if (!isset($this->sale)) {
            if (!empty($this->getPlan())) {
                $this->sale = $this->getHydrator()->hydrate(array_filter([
                    'customer' => $this->getCustomer(),
                    'target' => $this->getTarget(),
                    'plan' => $this->getPlan(),
                    'time' => $this->getTime(),
                ]), SaleInterface::class);
            }
        }

        return $this->sale;
    }

    protected function getHydrator(): HydratorInterface
    {
        if (!isset($this->hydrator)) {
            $this->hydrator = $this->di->get(HydratorInterface::class);
        }

        return $this->hydrator;
    }
}
