<?php

use Illuminate\Database\Seeder;
use App\Building;
use App\ManagementCompany;
use App\User;

class BuildingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $m_c = ManagementCompany::where('name', 'First Management Company')->first();
        $user = User::where('name', 'Tenant Company')->first();

        $first_building = new Building();
        $first_building->name = 'first building';
        $first_building->city = 'Beer Sheva';
        $first_building->address = 'test test';
        $first_building->description = 'A first buillding';

        $first_building->managementCompany()->associate($m_c);
        $first_building->user()->associate($user);
        $first_building->save();
    }
}
