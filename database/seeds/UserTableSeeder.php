<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Building;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_director = Role::where('name', 'director')->first();
        $role_management_company = Role::where('name', 'management_company')->first();
        $role_tenant  = Role::where('name', 'tenant')->first();

        $director = new User();
        $director->name = 'Director Name';
        $director->email = 'director@example.com';
        $director->phone = '0544344622';
        $director->password = bcrypt('secret');
        $director->save();
        $director->roles()->attach($role_director);

        $manager = new User();
        $manager->name = 'Management Company';
        $manager->email = 'manager@example.com';
	    $manager->phone = '0544344621';
        $manager->password = bcrypt('secret');
        $manager->save();
        $manager->roles()->attach($role_management_company);

        $tenant = new User();
        $tenant->name = 'Tenant Company';
        $tenant->email = 'tenant@example.com';
	    $tenant->phone = '0544344623';
	    $tenant->sms = true;
        $tenant->password = bcrypt('secret');
        $tenant->save();
        $tenant->roles()->attach($role_tenant);
    }
}
