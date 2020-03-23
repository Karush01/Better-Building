<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_director = new Role();
        $role_director->name = 'director';
        $role_director->description = 'A director User';
        $role_director->save();

        $role_management_company = new Role();
        $role_management_company->name = 'management_company';
        $role_management_company->description = 'A management_company User';
        $role_management_company->save();

        $role_tenant = new Role();
        $role_tenant->name = 'tenant';
        $role_tenant->description = 'A tenant User';
        $role_tenant->save();
    }
}
