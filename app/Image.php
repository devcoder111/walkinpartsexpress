<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['file_path', 'weight'];

    public function image_thumbnail() {
        return $this->hasOne(ImageThumbnail::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function hasHighestWeight() {
        return !Image::where('product_id', $this->product_id)->where('weight', '<', $this->weight)->count();
    }
}
