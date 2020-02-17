<?php

use App\Image;
use App\ImageThumbnail;
use App\PaymentGateway;
use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        Image::truncate();
        ImageThumbnail::truncate();
        DB::statement("SET foreign_key_checks=1");

        $images = scandir("./database/seeds/product-images/");

        // Remove "." and ".."
        if($images[0] == ".") {
            array_shift($images);
        }

        if($images[0] = "..") {
            array_shift($images);
        }

        $weightKeyValues = ["A" => 1, "B" => 2, "C" => 3, "D" => 4, "E" => 5, "F" => 6, "G" => 7, "H" => 8];

        foreach($images as $image) {
            if(!strpos($image, "psd")) {
                // Save a thumbnail image.
                InterventionImage::make("./database/seeds/product-images/".$image)->resize(450, 450)->save("./database/seeds/product-image-thumbnails/thumbnail-".$image);

                $data = explode("-", $image);
                $masterPartId = $data[0];

                $weightKeyArr = explode(".", $data[1]);
                $weightKey = $weightKeyArr[0];

                echo("Product ID: {$masterPartId} with weight of {$weightKeyValues[$weightKey]}\n");

                $p = Product::where('master_part_number', $masterPartId)->first();

                if($p) {
                    Storage::disk('s3')->put('images/products/' . $image, fopen('./database/seeds/product-images/'.$image, 'r+'), 'public');
                    Storage::disk('s3')->put('images/product-thumbnails/' . "thumbnail-".$image, fopen("./database/seeds/product-image-thumbnails/thumbnail-".$image, 'r+'), 'public');

                    $img = new Image();
                    $img->product()->associate($p);
                    $img->image_hash = hash_file('md5', './database/seeds/product-images/'.$image);
                    $img->weight = $weightKeyValues[$weightKey];
                    $img->file_path = 'images/products/' . $image;

                    $img->save();

                    $thumbnail = new ImageThumbnail();
                    $thumbnail->size = 450;
                    $thumbnail->file_path = "images/product-thumbnails/thumbnail-".$image;
                    $thumbnail->image_id = $img->id;
                    $thumbnail->save();

                    $thumbnail->image()->associate($img);

                    $img->save();
                }
            }
        }
    }
}
