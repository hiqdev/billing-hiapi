<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\action\Calculate;

use DateTimeImmutable;
use hiapi\commands\BaseCommand;
use hiapi\validators\RefValidator;
use hiqdev\php\billing\action\Action;
use hiqdev\php\billing\action\ActionInterface;
use hiqdev\php\billing\customer\CustomerInterface;
use hiqdev\php\billing\target\TargetInterface;
use Zend\Hydrator\HydratorInterface;
use hiqdev\php\units\QuantityInterface;
use hiqdev\php\units\Quantity;

class PaidCommand extends BaseCommand implements PaidCommandInterface
{
    public $amount;
    public $type;

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['amount'], 'number'],
            [['type'], RefValidator::class],
        ]);
    }

    protected $unit = 'items';
    protected ?TargetInterface $target;
    protected ?CustomerInterface $customer;
    protected ?CustomerInterface $targetCustomer;

    public function getType()
    {
        return $this->type;
    }

    public function getTarget(): TargetInterface
    {
        return $this->target;
    }

    public function setTarget(TargetInterface $target)
    {
        $this->target = $target;
    }

    public function setTargetCustomer(CustomerInterface $customer)
    {
        $this->targetCustomer = $customer;
    }

    public function getCustomer(): CustomerInterface
    {
        return $this->customer ?? $this->targetCustomer;
    }

    public function getTime(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }

    public function getQuantity(): QuantityInterface
    {
        return Quantity::create($this->unit, $this->amount);
    }

    public function createAction(HydratorInterface $hydrator): ActionInterface
    {
        return $hydrator->hydrate([
            'customer' => $this->getCustomer(),
            'target' => $this->getTarget(),
            'type' => $this->getType(),
            'quantity' => $this->getQuantity(),
            'time' => $this->getTime(),
        ], Action::class);
    }
}
