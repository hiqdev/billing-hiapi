<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\feature\Cancel;

use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiapi\validators\RefValidator;
use hiqdev\php\billing\customer\Customer;
use hiqdev\php\billing\target\TargetInterface;
use hiqdev\php\billing\type\TypeInterface;

final class Command extends BaseCommand
{
    public $customer_id;
    public $target_id;

    public $type_name;

    public ?Customer $customer = null;

    public ?TargetInterface $target = null;
    public ?TypeInterface $type = null;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['customer_id', IdValidator::class],
            ['target_id', IdValidator::class],
            ['type_name', RefValidator::class],
            [['target_id', 'type_name'], 'required'],
        ]);
    }
}
