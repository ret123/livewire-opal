<?php

namespace Database\Seeders;

use App\Models\OrganizationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizationType = array(
            array('name' => 'Oil and Gas'),
            array('name' => 'Industrial'),
            array('name' => 'Construction'),
            array('name' => 'Engineering'),
            array('name' => 'Catering Supply'),
            array('name' => 'Education and Training Providers'),
            array('name' => 'Non Profit Organizations'),
            array('name' => 'Professional Organizations'),
        );
       OrganizationType::insert($organizationType);
    }
}
