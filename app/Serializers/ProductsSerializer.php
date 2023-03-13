<?php

namespace App\Serializers;

class ProductsSerializer extends BaseSerializer
{
    private $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    protected function serialize(): void
    {
        foreach ($this->products as $item) {
            $serializer = new ProductSerializer($item);
            $this->data[] = $serializer->getData();
        }
    }
}
