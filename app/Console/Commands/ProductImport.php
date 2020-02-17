<?php

namespace App\Console\Commands;


use App\Services\ApiService;
use App\Product;
use App\WebCategory;
use Illuminate\Console\Command;

class ProductImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'import:products';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Import products from IB\'s API';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct() {
        parent::__construct();
    }



    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle() {
        $products = ApiService::getProducts();

        $apiProducts = [];

        foreach($products as $product) {
            if(Product::where('master_part_number', $product->MasterPartNumber)->count() == 1) {
                $p = Product::where('master_part_number', $product->MasterPartNumber)->get()->first();
            }
            else {
                $p = new Product();
            }

            $p->master_part_number = $product->MasterPartNumber;
            $p->description = $product->Description;
            $p->keywords = $product->Keywords;
            $p->weight = $product->Weight;
            $p->price = $product->Price;
            $p->prop_65_warning = $product->Prop65Warning;
            $p->specifications = $product->Specifications;
            $p->api_web_category_id = $product->WebCategoryID == 0 ? WebCategory::getMiscWebCategoryId() : $product->WebCategoryID;

            //Since the product may have been previously deleted, but later shows back up, we can always just reset it to false here when importing.
            $p->deleted = false;


            $wc = WebCategory::where('api_web_category_id', $p->api_web_category_id)->get()->first();

            $p->web_category()->associate($wc);

            //$p->quantity = ApiService::getProduct($product->MasterPartNumber)[0]->Quantity;
            $p->quantity = $product->Quantity;

            $p->save();


            $apiProducts[] = $p->master_part_number;
        }


        $existingProducts = Product::all()->pluck('master_part_number')->toArray();

        $missingProducts = array_filter($existingProducts, function($existingProduct) use($apiProducts) {
            return !in_array($existingProduct, $apiProducts);
        });


        foreach($missingProducts as $productMasterPartNumber) {
            $p = Product::where('master_part_number', $productMasterPartNumber)->get()->first();
            $p->deleted = true;
            $p->update();
        }


    }
}