<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect([
            [
                'name' => 'Information Technlogoy (IT)',
                
            ],
            [
                'name' => 'Human Resource (HR)',
                
            ],
        ])->each(function($division){
           Division::create($division);
        });
    }
}
