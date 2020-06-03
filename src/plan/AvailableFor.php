<?php

declare(strict_types=1);
/**
 * API for Billing
 *
 * @link      https://github.com/hiqdev/billing-hiapi
 * @package   billing-hiapi
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017-2020, HiQDev (http://hiqdev.com/)
 */

namespace hiqdev\billing\hiapi\plan;

use hiqdev\DataMapper\Query\Field\FieldInterface;

final class AvailableFor implements FieldInterface
{
    public const SELLER = 0;
    public const CLIENT_ID = 1;

    public const SELLER_FIELD = 'available_for_seller';
    public const CLIENT_ID_FIELD = 'available_for_client_id';

    private string $fieldName;
    private int    $type;

    /**
     * AvailableFor constructor.
     *
     * @psalm-param self::SELLER|self::CLIENT_ID $type
     */
    public function __construct(string $fieldName, int $type)
    {
        $this->fieldName = $fieldName;
        $this->type = $type;
    }

    public static function seller(string $fieldName): self
    {
        return new self($fieldName, self::SELLER);
    }

    public static function client_id(string $fieldName): self
    {
        return new self($fieldName, self::CLIENT_ID);
    }

    public function getName(): string
    {
        return $this->fieldName;
    }

    public function getType(): int
    {
        return $this->type;
    }
}
