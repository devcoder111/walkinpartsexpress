

To get started, ensure that you have composer and node with an npm version of at least 6.9.0 installed.  You can check this with `npm -v`.  The composer version is not as important as the NPM version being up to date. 

Run the following commands to import PHP and Javascript dependencies:
`composer install`  
`npm install`  

Next, to get the site up and running locally, update your .env file's DB and other credentials, then run the following command:

`php artisan migrate:fresh && php artisan import:web_categories && php artisan import:products && php artisan db:seed --class=StateSeeder && php artisan db:seed --class=AddressTypeSeeder && php artisan db:seed --class=ProductImageSeeder && php artisan db:seed --class=PaymentGatewaySeeder && php artisan db:seed --class=OrderStatusSeeder && php artisan db:seed --class=ConfigSeeder && php artisan db:seed --class=UserRoleSeeder && php artisan db:seed --class=SocialMediaSeeder`

The above set of commands (can be run as a single command since each separate command is delimited with `&&`) will create all database tables fresh, import web categories and products from IB's API, and create seed data needed for required tables.

Since we are using `npm` and `webpack` (with Laravel Mix), all changes to assets for the site need to be done inside of the `resources` folder.  

Once a change is made to SASS, JS, or an image that needs to be uploaded to AWS S3, `npm run watch` should be left running in your background so that everything is compiled and packaged properly.

On production servers, ensure to copy over `.env.prod` to `.env` and it is important to run `npm run production` which will also minify site assets.  Note, once the deployment process with CI using Jenkins is sorted out, this will be automatically handled 
