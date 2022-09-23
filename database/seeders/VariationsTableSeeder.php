<?php

namespace Database\Seeders;

use App\Models\Variation;
use Illuminate\Database\Seeder;

class VariationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $color = Variation::where('name', '=', 'Color')->first();
        $size = Variation::where('name', '=', 'Size')->first();
        // $quantity = Variation::where('name', '=', 'Quantity')->first();

        /* if ($color === null) {
            $color = Variation::create([
                'name' => 'Color',
            ]);
            $color->save();
        } */
        if($size == null) {
            $size = Variation::create([
                'name' => 'Size',
            ]);
            $size->save();
        }
        /* if($quantity == null) {
            $quantity = Variation::create([
                'name' => 'Quantity',
            ]);
            $quantity->save();
        } */
    }
}
