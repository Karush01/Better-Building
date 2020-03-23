<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role comes before User seeder here.
        $this->call(RoleTableSeeder::class);
        // User seeder will use the roles above created.
        $this->call(UserTableSeeder::class);
        // Managmant company seeder.
        $this->call(ManagementCompanySeeder::class);
        // Building seeder.
        $this->call(BuildingsTableSeeder::class);
        // Statuses seeder.
        $this->call(StatusTableSeeder::class);
        // Skus seeder.
        $this->call(SkuTableSeeder::class);
        // Task seeder.
        $this->call(TaskTableSeeder::class);
        // Ticket seeder.
        $this->call(TicketTableSeeder::class);
	    // Duration seeder.
	    $this->call(DurationTableSeeder::class);
        
    }
}
