<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebCategory extends Model
{
    protected $fillable = ['name', 'api_web_category_id'];

    const MISC_API_WEB_CATEGORY_ID = 3;

//    public function images() {
//        return $this->belongsTo(Image::class);
//    }

    public function products() {
        return $this->hasMany(Product::class);
    }

    public static function getMiscWebCategoryId() {
        return self::where('api_web_category_id', self::MISC_API_WEB_CATEGORY_ID)->get()->first()->id;
    }

}
