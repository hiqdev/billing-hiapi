<?php

namespace hiqdev\billing\hiapi\models;

use yii\base\InvalidConfigException;

abstract class AbstractModel implements ModelInterface
{
    public function hasAttribute($name): bool
    {
        return isset($this->attributes()[$name]);
    }

    public function hasRelation($name): bool
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
        return new $className;
    }

    public function getRelatedAttribute($name)
    {
        list($relationName, $attribute) = explode('-', $name, 2);

        $className = $this->getRelation($relationName);
        /** @var ModelInterface $relation */
        $relation = new $className();
        if ($relation->hasAttribute($attribute)) {
            return $relation->getAttribute($attribute);
        }

        return $relation->getRelatedAttribute($attribute);
    }
}
