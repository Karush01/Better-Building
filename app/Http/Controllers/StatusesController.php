<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Status;

class StatusesController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
     public function index(Request $request)
     {
	     if (!$request->user()->authorizeRoles(['director', 'management_company', 'tanant'])) {
            return response()->redirectTo('/');
        }

         $statuses = Status::all();//paginate();
	     return view('statuses.index',compact('statuses'))->with('i', (request()->input('page', 1) - 1) * 10);
     }
 
     /**
      * Show the form for creating a new resource.
      *
      * @return Response
      */
     public function create(Request $request)
     {
	     if (!$request->user()->authorizeRoles(['director', 'management_company'])) {
            return response()->redirectTo('/');
        }

	     //$companies = ManagementCompany::all(['id', 'name']);
	     return view('statuses.create');//, compact('companies'));
     }
 
     /**
      * Store a newly created resource in storage.
      *
      * @return Response
      */
     public function store(Request $request)
     {
	     request()->validate([
		     'name' => 'required',
		     'description' => 'required',
	     ]);

	     Status::create($request->all());

	     return redirect()->route('statuses.index')
	                      ->with('success','Status created successfully.');
     }
 
     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return Response
      */
     public function show($id)
     {
         //
     }
 
     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return Response
      */
     public function edit($id)
     {
         //
     }
 
     /**
      * Update the specified resource in storage.
      *
      * @param  int  $id
      * @return Response
      */
     public function update($id)
     {
         //
     }
 
     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return Response
      */
     public function destroy($id)
     {
         //
     }
}
