<?php

use Illuminate\Database\Seeder;

use App\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status1 = new Status();
        $status1->slug = 'pending';
        $status1->name = 'ממתין לביצוע';
        $status1->description = 'ממתין לביצוע';
        $status1->save();

        $status2 = new Status();
	    $status2->slug = 'done';
        $status2->name = 'בוצע';
        $status2->description = 'בוצע';
        $status2->save();

	    $status3 = new Status();
	    $status3->slug = 'overdue';
	    $status3->name = 'מאחר';
	    $status3->description = 'מאחר';
	    $status3->save();
    }
}
