<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\ManagementCompany;
use App\Building;
use App\Task;
use App\Ticket;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'auth' );
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( Request $request ) {


        if (!$request->user()->authorizeRoles( [ 'director', 'management_company', 'tenant' ] )) {
            return response()->redirectTo('/');
        }


		if ( Auth::user()->hasrole( 'tenant' ) ):

			$building            = Building::where( 'user_id', "=", Auth::id() )->first();
			$tasks               = Task::whereHas( 'building.user', function ( $q ) {
				$q->select( 'id' );
			}, '=', Auth::user()->id );
			$total_skus          = $tasks->where( 'status_id', '=', 1 )->groupBy( 'sku_id' )->get();
			$total_skus          = count( $total_skus );
			$total_overdue_tasks = $tasks->where( 'status_id', '=', 3 )->count();
			$total_done_tasks    = $tasks->where( 'status_id', '=', 2 )->count();
			$total_open_tickets  = Ticket::all()->where( 'status_id', '=', 1 )->where( 'user_id', '=', Auth::user()->id )->count();
			$tasks_month_year    = $this->getFulldateyear( $tasks );

			return view( 'home_tenant', compact( [
				'total_skus',
				'total_overdue_tasks',
				'total_done_tasks',
				'total_open_tickets',
				'tasks_month_year',
				'building'
			] ) );
		elseif ( Auth::user()->hasrole( 'management_company' ) ):

			$company         = ManagementCompany::where( 'user_id', '=', Auth::user()->id )->first();
			$total_buildings = Building::where( 'management_company_id', '=', $company->id )->count();
			$tasks           = Task::whereHas( 'building.managementcompany', function ( $q ) {
				$q->select( 'id' );
			}, '=', $company->id );

			$total_open_tasks    = $tasks->where( 'status_id', '=', 1 )->count();
			$total_overdue_tasks = $tasks->where( 'status_id', '=', 3 )->count();
			$total_done_tasks    = $tasks->where( 'status_id', '=', 2 )->count();
			//$total_open_tickets                = Ticket::all(  )->where('status_id', '=', 1)->where('user_id', '=', ['3'])->count();
			$total_open_tickets = Ticket::select( 'tickets.*' )
			                            ->join( 'buildings', 'tickets.user_id', '=', 'buildings.user_id' )
			                            ->where( 'buildings.management_company_id', '=', $company->id )
			                            ->count();
			$tasks_month_year   = $this->getFulldateyear( $tasks );

			return view('home_company', compact( [
				'total_open_tasks',
				'total_overdue_tasks',
				'total_buildings',
				'total_open_tickets',
				'tasks_month_year'
			] ) );
		endif;

		$companies              = ManagementCompany::all( 'id' )->count();
		$buildings              = Building::all( 'id' )->count();
		$tasks                  = Task::all();
		$tickets                = Ticket::all( 'id' )->count();
		$tasks_count            = $tasks->count();
		$tasks_open_count       = $tasks->where( 'status_id', '!=', 2 )->count();
		$tasks_month_open_count = Task::whereMonth( 'last_date', date( 'm' ) )->count();
		$tasks_today_open_count = Task::whereDay( 'last_date', date( 'd' ) )->count();
		$users                  = User::with( 'roles' )->get();

		$users_count = $users->groupBy( function ( $item, $key ) {
			return [ $item->roles[0]->name ];
		} );

		$users_count = $users_count->map( function ( $item, $key ) {
			return collect( $item )->count();
		} );

		$tasks_month_year = $this->getFulldateyear( $tasks );

		return view( 'home', [
			'companies'              => $companies,
			'buildings'              => $buildings,
			'tasks_month_year'       => $tasks_month_year,
			'tasks_count'            => $tasks_count,
			'tickets'                => $tickets,
			'tasks_open_count'       => $tasks_open_count,
			'tasks_month_open_count' => $tasks_month_open_count,
			'tasks_today_open_count' => $tasks_today_open_count,
			'users_count'            => $users_count
		] );
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

	public function getAllUserTasks( Request $request ) {

        if (!$request->user()->authorizeRoles( [ 'tenant' ] )) {
            return response()->redirectTo('/');
        }


		if ( Auth::id() == $request->input( 'id' ) ):
			$tasks = Task::whereHas( 'building.user', function ( $q ) {
				$q->select( 'id' );
			}, '=', $request->input( 'id' ) )->with( 'sku' )->get();
		endif;

		return response()->json( [ 'success' => true, 'tasks' => $tasks ] );
	}

	public function getTasksByMonthYear( Request $request ) {

		if (!$request->user()->authorizeRoles( [ 'tenant' ] )) {
            return response()->redirectTo('/');
        }

		if ( Auth::id() == $request->input( 'id' ) ):
			$tasks = Task::select('last_date', 'status_id','sku_id')->whereHas( 'building.user', function ( $q ) {
				$q->select( 'id' );
			}, '=', $request->input( 'id' ) )
			             ->with( [
				             'sku' => function ( $query ) {
					             $query->select( 'skus.id','skus.description' );
				             }
			             ] )
			             ->whereYear( 'last_date', $request->input( 'year' ) )
			             ->whereMonth( 'last_date', $request->input( 'month' ) )
			             ->get();

			return response()->json( [ 'success' => true, 'tasks' => $tasks ] );
		else:
			return response()->json( [ 'error' => true, 'msg' => 'Not Allowed' ] );
		endif;
	}

	public function getTasksByMonthYear_old( Request $request ) {

		if (!$request->user()->authorizeRoles( [ 'director' ] )) {
             return response()->redirectTo('/');
         }

		$month_year = json_decode( $request->input( 'month_year' ) );

		$tasks = Task::whereYear( 'last_date', date( 'Y', strtotime( $month_year[1] ) ) )->whereMonth( 'last_date', date( 'm', strtotime( $month_year[0] ) ) )->get();

		return response()->json( [ 'success' => true, 'tasks' => $tasks ] );
	}

	public function getDoneTasksByMonth( Request $request ) {

		if (!$request->user()->authorizeRoles( [ 'director' ] )) {
             return response()->redirectTo('/');
         }

		$year = date( "Y" );

		$tasks = Task::whereYear( 'last_date', date( 'Y', strtotime( $year ) ) )->whereMonth( 'last_date', date( 'm', strtotime( $month_year[0] ) ) )->get();

		return response()->json( [ 'success' => true, 'tasks' => $tasks ] );
	}
}
