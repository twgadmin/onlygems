<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $range = range(4, 14, 0.5);
        $data = array();
        foreach($range as $value) {
            array_push($data,['variation_id' => 1, 'option_value' => $value, 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        }

        $options = Option::insert($data);
    }
}
