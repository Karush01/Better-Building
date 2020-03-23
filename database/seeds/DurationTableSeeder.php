<?php

use Illuminate\Database\Seeder;
use App\Duration;

class DurationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $duration = new Duration();
	    $duration->name = 'כל שבועיים';
	    $duration->сyclic_days = 14;
	    $duration->save();

	    $duration1 = new Duration();
	    $duration1->name = 'כל שבוע';
	    $duration1->сyclic_days = 7;
	    $duration1->save();

	    $duration1 = new Duration();
	    $duration1->name = 'כל חודש';
	    $duration1->сyclic_days = 30;
	    $duration1->save();

	    $duration1 = new Duration();
	    $duration1->name = 'כל רבעון';
	    $duration1->сyclic_days = 90;
	    $duration1->save();

	    $duration1 = new Duration();
	    $duration1->name = 'כל חצי שנה';
	    $duration1->сyclic_days = 180;
	    $duration1->save();

	    $duration1 = new Duration();
	    $duration1->name = 'כל שנה';
	    $duration1->сyclic_days = 360;
	    $duration1->save();
    }
}
