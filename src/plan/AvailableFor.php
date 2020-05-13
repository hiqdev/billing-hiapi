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

use hiqdev\yii\DataMapper\query\FieldConditionBuilderInterface;
use hiqdev\yii\DataMapper\query\FieldInterface;
use yii\db\Expression;

final class AvailableFor implements FieldInterface, FieldConditionBuilderInterface
{
    private const SELLER = 0;
    private const CLIENT_ID = 1;

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

    public function buildCondition(string $operator, string $attributeName, $value)
    {
        $params = [];

        switch ($this->type) {
            case self::SELLER:
                $params[':available_for_seller'] = (string) $value;
                $ids_sql = "
                    SELECT      dst_id
                    FROM        tie
                    WHERE       src_id=client_id(:available_for_seller)
                            AND tag_id=ztype_id('tariff')
                ";
                break;
            case self::CLIENT_ID:
                $params[':available_for_client_id'] = (int) $value;
                $ids_sql = '
                    SELECT      tariff_id
                    FROM        client2tariff
                    WHERE       client_id=:available_for_client_id
                ';
                break;
            default:
                $ids_sql = '0';
        }

        return new Expression("zt.obj_id IN ($ids_sql)", $params);
    }
}
