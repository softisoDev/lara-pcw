<?php

use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Brand::create([
            '_lft'      => 1,
            '_rgt'      => 2,
            'parent_id' => null,
            'name'      => 'Unbranded',
            'slug'      => 'unbranded',
        ]);
    }
}
