<?php

namespace App\Repositories;

use App\Models\Filters\QueryFiltersClasses\Where;
use App\Models\Filters\QueryFiltersClasses\WhereLike;
use App\Models\Product;
use App\Repositories\Contract\ProductContract;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductContract
{
    public function index(Request $req)
    {
        $product_query = Product::query();

                app(Pipeline::class)
                ->send($product_query)
                ->through([
                new WhereLike("products.name", $req->name),
                new WhereLike("products.url", $req->url),
                new WhereLike("products.description",$req->description),
                new WhereLike("products.price",$req->price)
                ])
                ->thenReturn();

                //->skip($req->skip??0)->limit($req->limit??15)
                $products = $product_query->orderByDesc("id")->get();
                return $products;
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $product;
    }

    public function store(array $data)
    {
        $product = new Product();
        $product->name = $data["name"];
        $product->price = $data["price"];
        $product->url = $data["url"];
        $product->description = $data["description"];
        $product->save();

        return $product;
    }

    public function update($id,array $data)
    {
        $product =  Product::find($id);
        $product->name = $data["name"];
        $product->price = $data["price"];
        $product->url = $data["url"];
        $product->description = $data["description"];
        $product->save();

        return $product;
    }

    public function total_products()
    {
        $count = DB::selectOne('SELECT COUNT(*) AS total FROM products')->total;
        return $count;
    }

    public function total_urls_count_for_each_website()
    {

        $results = DB::table('products')
        ->selectRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(url, ".", 1), "//", -1) as domain, COUNT(id) as total')
        ->groupBy('domain')
        ->get();
        return $results;
    }

    public function avg_price_products()
    {

        $avg_prices = DB::table('products')
        ->selectRaw('AVG(price) as avg_price')
        ->first();
        return $avg_prices->avg_price??0;
    }

    public function webiste_highest_total_prices()
    {

        $results = DB::table('products')
        ->selectRaw('SUBSTRING_INDEX(SUBSTRING_INDEX(url, ".", 1), "//", -1) as domain, SUM(price) as total_price')
        ->groupBy('domain')
        ->orderByDesc("total_price")
        ->first();

        return $results;
    }


    public function total_price_during_month()
    {

        $results = DB::table('products')
        ->selectRaw('SUM(price) as total_price')
        ->whereBetween('created_at',[now()->startOfMonth(),now()->endOfMonth()])
        ->first();

        return $results->total_price??0;
    }
}
