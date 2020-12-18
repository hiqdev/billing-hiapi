<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature\Purchase;

use DateTimeImmutable;
use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiapi\validators\RefValidator;
use hiqdev\billing\hiapi\action\Calculate\PaidCommandInterface;
use hiqdev\php\billing\action\Action;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\Type;
use hiqdev\php\billing\type\TypeInterface;
use hiqdev\php\units\Quantity;
use Zend\Hydrator\HydratorInterface;

final class Command extends BaseCommand implements PaidCommandInterface
{
    public $target_id;

    public $type_name;

    public ?Customer $customer = null;

    public ?TargetInterface $target = null;

    public ?TypeInterface $type = null;

    public float $amount = 1;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['target_id', IdValidator::class],
            ['type_name', RefValidator::class],
            ['amount', 'number', 'min' => 0],
            [['target_id', 'type_name', 'amount'], 'required'],
        ]);
    }

    public function createAction(HydratorInterface $hydrator): ActionInterface
    {
        assert($this->customer !== null);
        assert($this->target !== null);
        assert($this->type !== null);

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        /** @noinspection PhpParamsInspection */
        return $hydrator->hydrate([
            'customer' => $this->customer,
            'target' => $this->target,
            'type' => new Type(TypeInterface::ANY, $this->type->getName()),
            'quantity' => Quantity::create('items', $this->amount),
            'time' => new DateTimeImmutable('now'),
        ], Action::class);
    }
}
