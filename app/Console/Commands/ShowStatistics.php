<?php

namespace App\Console\Commands;

use App\Repositories\ProductRepository;
use Illuminate\Console\Command;

class ShowStatistics extends Command
{

    private ProductRepository $productRepositry;

    public function __construct(ProductRepository $productRepositry)
    {
        parent::__construct();
        $this->productRepositry = $productRepositry;

    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show-report {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'the command for show any report about statistics , there are 5 types --> total_products [and] total_product_each_website [and] avg_total_price [and] website_highest_total_price  [and] total_price_during_month';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->argument('type');
        switch ($type) {
            case 'total_products':
                $count = $this->productRepositry->total_products();
                $this->line("total products is: $count");
                break;
            case 'total_product_each_website':
                $res = $this->productRepositry->total_urls_count_for_each_website();
                $this->line(json_encode($res, JSON_PRETTY_PRINT));
                break;
            case 'avg_total_price':
                $avg = $this->productRepositry->avg_price_products();
                $this->line("avg total price is: $avg");
                break;
            case 'website_highest_total_price':
                $res = $this->productRepositry->webiste_highest_total_prices();
                $this->line(json_encode($res, JSON_PRETTY_PRINT));
                break;
            case 'total_price_during_month':
                $total_prices = $this->productRepositry->total_price_during_month();
                $this->line("total price during month is: $total_prices");
                break;
            default:
                $this->error("Invalid report type: $type");
                break;
            }
        }
}
