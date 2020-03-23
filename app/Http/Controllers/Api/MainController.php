<?php

namespace App\Http\Controllers\Api;

use App\Building;
use App\Task;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index(Request $request)
    {
//        if (!$request->user()->authorizeRoles(['director', 'management_company', 'tenant'])) {
//            return response()->redirectTo('/');
//        }

        if (true) {
            $building = Building::where('user_id', "=", Auth::id())->first();
            $tasks = Task::whereHas('building.user', function ($q) {
                $q->select('id');
            }, '=', 1);
            $total_skus = $tasks->where('status_id', '=', 1)->groupBy('sku_id')->get();
            $total_skus = count($total_skus);
            $total_overdue_tasks = $tasks->where('status_id', '=', 3)->count();
            $total_done_tasks = $tasks->where('status_id', '=', 2)->count();
            $total_open_tickets = Ticket::all()->where('status_id', '=', 1)->where('user_id', '=',1)->count();
            $tasks_month_year = $this->getFulldateyear($tasks);
        }
        return response()->json([
            $total_skus,
            $total_overdue_tasks,
            $total_done_tasks,
            $total_open_tickets,
            $tasks_month_year
        ],201);
    }
    private function getfulldateyear( $tasks ) {
        $months = range( 1, 12 );
        $year   = date( "Y" );
        $arr    = [];

        foreach ( $months as &$month ):
            $arr['open'][] = Task::whereYear( 'last_date', $year )->whereMonth( 'last_date', date( 'm', mktime( 0, 0, 0, $month, 1 ) ) )->where( 'status_id', '=', 1 )->count();
            $arr['done'][] = Task::whereYear( 'last_date', $year )->whereMonth( 'last_date', date( 'm', mktime( 0, 0, 0, $month, 1 ) ) )->where( 'status_id', '=', 2 )->count();
            $arr['late'][] = Task::whereYear( 'last_date', $year )->whereMonth( 'last_date', date( 'm', mktime( 0, 0, 0, $month, 1 ) ) )->where( 'status_id', '=', 3 )->count();
        endforeach;

        return $arr;
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
             Auth::guard()->attempt(
                $credentials
            );
            Auth::user()->setRememberToken(str_random(10));
            var_dump(Auth::user()->getRememberToken());die;
//            return response()->json(['']);
        }
    }
}
