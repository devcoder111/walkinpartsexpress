// webpack.mix.js

const mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');
let S3Plugin = require('webpack-s3-plugin');
let types = require('node-sass').types;

require('dotenv').config();

mix
    .js('resources/js/app.js', 'public/js/')
    .sass('resources/sass/app.scss', 'public/css/app.css')
    .sass('resources/sass/tailwind.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [tailwindcss('./tailwind.js')],
    });



// Commented out since we are using S3 for everything.
//mix.copyDirectory('resources/images', 'public/images');

if (mix.inProduction()) {
    mix.sourceMaps().version();
}

mix.webpackConfig({
    plugins: [
        new S3Plugin({
            // Only upload images
            include: /.*\.(png|jpg|jpeg|gif|svg)/,
            // s3Options are required
            s3Options: {
                accessKeyId: process.env.AWS_ACCESS_KEY_ID,
                secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY,
                region: 'us-east-1',
            },
            s3UploadOptions: {
                Bucket: process.env.AWS_BUCKET,
            },
            basePath: 'images', // This is the name the uploaded directory will be given
            directory: 'resources/images', // This is the directory you want to upload
        })
    ],
    module: {
        rules: [{
            test: /\.scss$/,
            loader: "sass-loader",
            options: {
                functions: {
                    getEnvironment(){
                        return types.String(process.env.APP_ENV);
                    },
                    getAwsS3BasePath(){
                        return types.String(process.env.AWS_S3_BASEPATH);
                    }
                }
            },
        }]
    },
});
