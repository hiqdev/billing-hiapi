<?php

namespace hiqdev\billing\hiapi\models;

interface ModelInterface
{
    public function attributes();

    public function hasRelation($name);

    public function getRelation($name);

    public function relations();

    public function hasAttribute($name);

    public function getAttribute($name);
}
