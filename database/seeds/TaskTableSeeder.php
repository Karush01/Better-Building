<?php

use Illuminate\Database\Seeder;

use App\Sku;
use App\Building;
use App\Status;
use App\Task;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $building = Building::where('name', 'first building')->first();
        $status = Status::where('slug', 'pending')->first();
        $sku  = Sku::where('name', 'החלפת מינורות')->first();

        $task = new Task();
        $task->note = "test note";
        $task->last_date = DateTime::createFromFormat('j-M-Y', '15-Feb-2009');
        $task->сyclic_days = 14;
        $task->sku()->associate($sku);
        $task->status()->associate($status);
        $task->building()->associate($building);
        $task->save();
    }
}
