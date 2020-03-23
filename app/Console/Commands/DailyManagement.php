<?php

namespace App\Console\Commands;

use App\Mail\CronUpdateAdminMail;
use App\Mail\TaskOverdueMail;
use App\Status;
use App\User;
use Illuminate\Console\Command;
use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\TasksController;

class DailyManagement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'management:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update system every day';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    	$overduetasks = $this->updateTaskStatus();
	    $newskus = $this->createTasks();
	    $this->updateAdmin($overduetasks, $newskus);

    }

    private function updateTaskStatus() {
	    $status_pending = Status::where( 'slug', 'pending' )->get();
	    $status_overdue = Status::where( 'slug', 'overdue' )->get();
	    $tasks   = Task::with( 'building', 'status' )->where('status_id', '=', $status_pending[0]['id'])->whereDate('last_date', '<', date( 'Y-m-d', strtotime( now()  ) ))->get();

	    foreach ($tasks as $task):
		    $task->status_id = $status_overdue[0]['id'];
		    $task->save();

		    $user = User::find($task->building->user_id);

		    Mail::to( $user->email )->send( new TaskOverdueMail( $user, $task ) );
	    endforeach;

	    return count($tasks);
    }

    private function updateAdmin($overduetasks, $newskus) {
	    $user = User::find(1);

	    Mail::to( $user->email )->send( new CronUpdateAdminMail( $user, $overduetasks, $newskus ) );
    }

	private function createTasks() {

		$tasks   = Task::whereDate('last_date', '<=', date( 'Y-m-d', strtotime( now() . " + 1 day" ) ))->whereRaw('last_date IN (select MAX(last_date) FROM tasks GROUP BY building_id,sku_id)')->get();

		foreach ($tasks as $task):

			$request = new \Illuminate\Http\Request();
			$request->setMethod('POST');
			$request->request->add(['сyclic_days' => $task->сyclic_days]);
			$request->request->add(['building_id' => $task->building_id]);
			$request->request->add(['note' => $task->note]);
			$request->request->add(['sku_id' => $task->sku_id]);
			$request->request->add(['cron' => true]);

			$user = Auth::loginUsingId(1, true);

			$request->merge(['user' => $user]);
			$request->setUserResolver(function () use ($user) {
				return $user;
			});
			$controller = new TasksController;
			$controller->store($request);
		endforeach;

		return count($tasks);
	}
}
