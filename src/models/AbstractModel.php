<?php

namespace hiqdev\billing\hiapi\models;

use yii\base\InvalidConfigException;
use Yii;

/**
 * Class AbstractModel
 *
 * @author Dmytro Naumenko <d.naumenko.a@gmail.com>
 */
abstract class AbstractModel implements ModelInterface
{
    public function hasAttribute($name)
    {
        return isset($this->attributes()[$name]);
    }

    public function hasRelation($name)
    {
        return isset($this->relations()[$name]);
    }

    /**
     * @param $name
     * @return string
     * @throws InvalidConfigException
     */
    public function getRelation($name)
    {
        if (!$this->hasRelation($name)) {
            throw new InvalidConfigException('Model ' . static::class . ' does not have relation ' . $name);
        }

        return $this->relations()[$name];
    }

    public function getAttribute($name)
    {
        if (!$this->hasAttribute($name)) {
            throw new InvalidConfigException('Attribute ' . $name . ' is not available within ' . static::class);
        }

        $className = $this->attributes()[$name];

        if (is_object($className)) {
            return $className;
        }

        return Yii::createObject($className);
    }
}
