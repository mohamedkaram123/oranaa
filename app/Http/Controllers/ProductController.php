<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\WebsitHighestTotalPriceCollection;
use App\Models\Product;
use App\Repositories\ProductRepository;

use Illuminate\Http\Request;
use League\CommonMark\CommonMarkConverter;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    private ProductRepository $productRepositry;

    public function __construct(ProductRepository $productRepositry)
    {
        $this->productRepositry = $productRepositry;

    }
    public function index(Request $req)
    {
        $products = $this->productRepositry->index($req);

        return success("success",ProductCollection::collection($products));
    }

    public function store(Request $req)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'url' => 'required|url|max:255',
            'description' => 'required|string',
        ];
        $validate =Validator::make($req->all(),$rules);
        if($validate->fails()){
            return fail("error",$validate->errors(),422);
        }
       // $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
       // $description = $converter->convert($req->get('description'))->getContent(); --> i dont need
        $products = $this->productRepositry->store($req->all());
        return success("success", new ProductCollection($products));


    }

    public function show($id)
    {
        $product = $this->productRepositry->show($id);
        return success("success", new ProductCollection($product));

    }

    public function update($id,Request $req)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'url' => 'required|url|max:255',
            'description' => 'required|string',
        ];
        $validate =Validator::make($req->all(),$rules);
        if($validate->fails()){
            return fail("error",$validate->errors(),422);
        }
       // $converter = new CommonMarkConverter(['html_input' => 'escape', 'allow_unsafe_links' => false]);
        //$description = $converter->convert($req->get('description'))->getContent(); --> i dont need
        $product = $this->productRepositry->update($id,$req->all());
        return success("success", new ProductCollection($product));

    }

    public function total_products()
    {
        $count = $this->productRepositry->total_products();
        return success("success",$count);
    }

    public function total_urls_count_for_each_website()
    {
        $res = $this->productRepositry->total_urls_count_for_each_website();
        return success("success",$res);
    }

    public function avg_price_products()
    {
        $res = $this->productRepositry->avg_price_products();
        return success("success",print_price($res));
    }

    public function webiste_highest_total_prices()
    {
        $res = $this->productRepositry->webiste_highest_total_prices();
        return success("success",new WebsitHighestTotalPriceCollection($res));
    }

    public function total_price_during_month()
    {
        $res = $this->productRepositry->total_price_during_month();
        return success("success",print_price($res));
    }

}
