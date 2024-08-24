<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
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
