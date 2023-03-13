<?php

namespace App\Repositories\Contract;

use Illuminate\Http\Request;


interface ProductContract
{
    public function index(Request $req);
    public function store(array $data);
    public function update($id,array $data);
    public function show($id);
    public function total_products();
    public function total_urls_count_for_each_website();
    public function avg_price_products();
    public function webiste_highest_total_prices();
    public function total_price_during_month();

}
