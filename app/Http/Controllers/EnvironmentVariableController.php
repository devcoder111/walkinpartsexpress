<?php

namespace App\Http\Controllers;

class EnvironmentVariableController extends Controller
{
    public function __construct()
    {

    }

    public static function getAwsS3BasePath() {
        return json_encode(['AWS_S3_BASEPATH' => env('AWS_S3_BASEPATH')], JSON_UNESCAPED_SLASHES);
    }
}
