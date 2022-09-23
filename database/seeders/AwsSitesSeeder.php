<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use App\Models\AwsSite;
use Illuminate\Support\Facades\Config;

class AwsSitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array =  Config::get('constants.price_compare_site');
        $data = array();
        foreach($array as $value) {
            array_push($data,['site_name' => $value]);
        }
        AwsSite::insert($data);
    }
}
