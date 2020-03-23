<?php

use Illuminate\Database\Seeder;
use App\Ticket;
use App\User;
use App\Status;

class TicketTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    $status = Status::where('slug', 'pending')->first();
        $user = User::where('name', 'Tenant Company')->first();

        $ticket = new Ticket();
        $ticket->title = "First ticket";
        $ticket->message = "Hello world";
	    $ticket->status()->associate($status);
        $ticket->user()->associate($user);
        $ticket->save();
    }
}
