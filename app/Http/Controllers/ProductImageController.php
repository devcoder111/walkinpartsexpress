<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($productId)
    {
        // TODO: Remove in future if productzoomer isnt used
        // Only used for ProductZoomer, which may go away in the future.

        $product = Product::with(['images', 'images.image_thumbnail'])->where('id', (int)$productId)->first();

        $images = new \stdClass();
        $images->thumbs = [];
        $images->normal_size = [];

        foreach($product->images as $image) {
            $thumb = new \stdClass();
            $thumb->id = $image->id;
            $thumb->url = env('AWS_S3_BASEPATH')."/".$image->image_thumbnail->file_path;

            $img = new \stdClass();
            $img->id = $image->id;
            $img->url = env('AWS_S3_BASEPATH')."/".$image->file_path;

            $images->thumbs[] = $thumb;
            $images->normal_size[] = $img;
        }

        return json_encode($images, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
