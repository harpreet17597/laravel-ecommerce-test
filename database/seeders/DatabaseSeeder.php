<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Product::truncate();
        Schema::enableForeignKeyConstraints();
        $this->call(AdminTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(ProductSeeder::class);
    }
}
