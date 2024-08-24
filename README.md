Project Folder  : laravel_stripe
PHP version     : 7.4
PHP extension   : mysqli
Created Date    :23-08-2024

Step 1: Set Up Your Laravel Project 
======================================
Make sure your Laravel project is set up and that you've configured your .env file with your database and Stripe credentials.

.env

STRIPE_KEY=your-stripe-publishable-key
STRIPE_SECRET=your-stripe-secret-key

Step 2: Install Laravel Cashier
=====================================
Install Laravel Cashier for Stripe:


composer require laravel/cashier

and

composer require stripe/stripe-php

Step 3: Create the Migration for Products
===========================================
Create a migration for the products table:


php artisan make:migration create_products_table

Edit the migration file:

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}

Run the migration:

php artisan migrate

Step 4: Create a Seeder for Products
=====================================
Create a seeder to populate the products table with sample data:

php artisan make:seeder ProductsTableSeeder

Edit the seeder file:

php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Dell',
                'price' => 100,
                'description' => 'DELL Latitude 5490 Core i5 8th Gen Laptop, 8 GB RAM, 256GB SSD, Intel HD Graphics, 14 inch (36.83 cms) HD Screen',
            ],
            [
                'name' => 'HP',
                'price' => 200,
                'description' => 'HP Laptop 15, 13th Gen Intel Core i5-1334U, 15.6-inch (39.6 cm), FHD, 16GB DDR4, 512GB SSD, Intel Iris Xe graphics',
            ],
            [
                'name' => 'Lenovo',
                'price' => 300,
                'description' => 'Lenovo Latitude 5490 Core i5 8th Gen Laptop, 8 GB RAM, 256GB SSD, Intel HD Graphics, 14 inch (36.83 cms) HD Screen',
            ],
            [
                'name' => 'Sony Vaio',
                'price' => 400,
                'description' => 'SONY Intel Core i5 3rd Gen 3337U - (4 GB/750 GB HDD/Windows 8 Pro/2 GB Graphics) SVF15A13SNB graphics ',
            ],
        ]);
    }
}

Run the seeder:

php artisan db:seed --class=ProductsTableSeeder

Step 5: Create the Controller
=============================
Create a controller to handle product display and Stripe payment:

php artisan make:controller ProductController

Edit ProductController.php:


Step 6: Create Blade Views
===========================
products/index.blade.php (Product listing with a table and loading spinner):

products/show.blade.php (Product detail with Stripe form):

Step 7: Set Up Routes
=========================
Define the routes in web.php:

Route::get('/', [ProductController::class, 'index']);
Route::get('/product/{id}', [ProductController::class, 'show']);
Route::post('/purchase/{id}', [ProductController::class, 'purchase'])->name('purchase');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');;

