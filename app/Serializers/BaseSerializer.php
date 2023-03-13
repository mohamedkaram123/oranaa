<?php

namespace App\Serializers;

abstract class BaseSerializer
{
    protected $data = [];

    abstract protected function serialize(): void;

    public function getData()
    {
        $this->serialize();

        return $this->data;
    }
}
