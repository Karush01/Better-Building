<?php

use Illuminate\Database\Seeder;

use App\ManagementCompany;
use App\User;

class ManagementCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $user = User::where('name', 'Management Company')->first();

        $m_c = new ManagementCompany();
        $m_c->name = 'First Management Company';
        $m_c->city = 'Beer Sheva';
        $m_c->address = 'test test';
        $m_c->phone = '0525252254';
        $m_c->description = 'A first company';

	    $m_c->user()->associate($user);

        $m_c->save();
    }
}
