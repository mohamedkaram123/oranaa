<?php

namespace App\Serializers;

use App\Models\Product;

class ProductSerializer extends BaseSerializer
{
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    protected function serialize(): void
    {
        $this->data['id'] = $this->product->id;
        $this->data['name'] = $this->product->name;
        $this->data['price'] = number_format($this->product->price, 2, '.', ' ');
        $this->data['url'] = $this->product->url;
        $this->data['description'] = trim($this->product->description);
    }
}
