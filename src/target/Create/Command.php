<?php
declare(strict_types=1);

namespace hiqdev\billing\hiapi\target\Create;

use hiapi\commands\BaseCommand;
use hiapi\validators\IdValidator;
use hiapi\validators\UsernameValidator;
use hiqdev\php\billing\customer\Customer;

final class Command extends BaseCommand
{
    /**
     * @var string|null a customer ID, when command is running on behalf of other user.
     * Either `customer_id` or `customer_username` can be filled.
     */
    public $customer_id;
    /**
     * @var string|null a customer ID, when command is running on behalf of other user.
     * Either `customer_id` or `customer_username` can be filled.
     */
    public $customer_username;
    /** @var string a target name */
    public $name;
    /** @var string a target type */
    public $type;
    /** @var string a target ID at the remote service */
    public $remoteid;
    /** @var Customer a customer */
    public $customer;

    public function rules(): array
    {
        return [
            [['customer_username'], UsernameValidator::class],
            [['customer_id'], IdValidator::class],

            [['name'], 'trim'],

            [['type'], 'trim'],

            [['remoteid'], 'trim'],

            [['name', 'type', 'remoteid'], 'required'],
        ];
    }
}
