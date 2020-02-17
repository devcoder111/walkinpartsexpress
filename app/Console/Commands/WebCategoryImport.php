<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ApiService;
use App\WebCategory;

class WebCategoryImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'import:web_categories';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Import web categories from IB\'s API';

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
        $categories = ApiService::getWebCategories();

        foreach($categories as $category) {
            $wc = WebCategory::where('api_web_category_id', $category->WebCategoryID);

            if($wc->count() == 0) {
                $_wc = new WebCategory();
                $_wc->name = trim($category->Category);
                $_wc->api_web_category_id = $category->WebCategoryID;
                $_wc->save();
            }
            else {
                $wc = $wc->get()->first();
                $wc->name = trim($category->Category);
                $wc->save();
            }
        }
    }
}