<?php

namespace App\Http\Controllers\Api;

use App\Building;
use App\Http\Controllers\UsersController;
use App\Mail\WelcomeMail;
use App\ManagementCompany;
use App\Role;
use App\Task;
use App\Ticket;
use App\User;
use http\Client\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApiBuildingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getBuildings(Request $request)
    {
        $user = User::where('access_token',$request->access_token)->first();

        if (!$user->authorizeRoles(['director', 'management_company', 'tanant'])) {
            return response()->json([
                'error' => 'invalid Role'
            ], 500);
        }

        if ($user->hasrole('management_company')):
            $company = ManagementCompany::where('user_id', '=', $user->id)->first();
            $buildings = Building::with('managementCompany')->where('management_company_id', '=', $company->id);//->paginate();
        else:
            $buildings = Building::with('managementCompany');//->paginate();
        endif;
        $buildings = $buildings->get();
        return response()->json([
            'buildings' => $buildings
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCompanyManagement(Request $request)
    {
        $user = User::where('access_token',$request->access_token)->first();

        if (!$request->user()->authorizeRoles(['director', 'management_company'])) {
            return response()->json([
                'error' => 'invalid Role'
            ], 500);
        }
        $companies = ManagementCompany::all(['id', 'name']);
        return response()->json([
            'companies' => $companies
        ], 201);
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
            'city' => 'required',
            'address' => 'required',
            'management_company_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'username' => 'required',
            'useremail' => 'unique:users,email,' . $request->input('email'),
            'userphone' => 'required|numeric|min:9|unique:users,phone,' . $request->input('phone'),
            'userpassword' => 'required|string|min:6',
        ]);

        $all_inputs = $request->all();
        $name = '';
        if (isset($all_inputs['image']) && $all_inputs['image'] !== null) {
            $name = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put($name, File::get($request->file('image')));
        }

        if (isset($all_inputs['sms']) && $all_inputs['sms'] == "on"):
            $all_inputs['sms'] = true;
        endif;
        $all_inputs['name'] = $all_inputs['username'];
        $all_inputs['email'] = $all_inputs['useremail'];
        $all_inputs['phone'] = $all_inputs['userphone'];
        $company = ManagementCompany::find($request->input('management_company_id'));

        $role_id = 3;
        $password = $request->input('userpassword');
        if ($password):
            $all_inputs['password'] = Hash::make($password);
        else:
            $password = UsersController::generateRandomString();
            $all_inputs['password'] = Hash::make($password);
        endif;

        $user = User::create($all_inputs);

        $user->roles()->attach(Role::where('id', $role_id)->get());

        $user->save();
        //$request->request->add(['user_id' => $user->id]);

        $building = Building::create($request->all());

        $building->user()->associate($user->id);
        $building->image = $name;

        $building->save();

        $building->managementCompany()->associate($company->id);
        $building->save();

        Mail::to($user->email)->send(new WelcomeMail($user, $password));

        return response()->json([
            'success' => 'Building created successfully.'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function getTasksANdBuilding(Request $request, Building $building)
    {

        if (!$request->user()->authorizeRoles(['director', 'management_company', 'tenant'])) {
            return response()->redirectTo('/');
        }
        if (Auth::user()->hasrole('tenant')):
            $tasks = Task::where('status_id', '!=', 2)->where('building_id', '=', $building->id)->groupBy('sku_id');//->paginate();
        elseif (Auth::user()->hasrole('management_company')):
            $tasks = Task::where('status_id', '!=', 2)->where('building_id', '=', $building->id)->groupBy('sku_id');//->paginate();
        else:
            $tasks = Task::where('status_id', '!=', 2)->where('building_id', '=', $building->id)->groupBy('sku_id');//->paginate();
        endif;

        $tasks = $tasks->get();

        return response()->json([
            'tasks' => $tasks,
            'building' => $building
        ], 201);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, Building $building)
    {
        $user = User::where('access_token',$request->access_token)->first();

        if (!$user->authorizeRoles(['director'])) {
            return response()->json([
                'error' => 'Invalid role'
            ]);
        }

        $companies = ManagementCompany::all(['id', 'name']);
        $building = Building::with('managementCompany')->find($building->id);

        return response()->json([
            'building' => $building
        ], 201);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, Building $building)
    {
        $user = User::where('access_token',$request->access_token)->first();

        if (!$user->authorizeRoles(['director'])) {
            return response()->json([
                'error' => 'Invalid role'
            ]);
        }

        request()->validate([
            'name' => 'required',
            'city' => 'required',
            'address' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'management_company_id' => 'required',
        ]);

        $all_params = $request->all();
        if (isset($all_params['image']) && $all_params['image'] !== null) {
            $name = '';
            $name = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put($name, File::get($request->file('image')));
            $all_params['image'] = $name;
        }
        $building->update($all_params);

        return response()->json([
            'success' => 'Building updated successfully'
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Request $request, Building $building)
    {
        $user = User::where('access_token',$request->access_token)->first();

        if (!$user->authorizeRoles(['director'])) {
            return response()->json([
                'error' => 'Invalid role'
            ]);
        }

        //delete all building tickets
        $tickets = Ticket::where('user_id', '=', $building->user_id)->delete();

        //delete all building tasks
        $tasks = Task::where('building_id', '=', $building->id)->delete();

        //delete building user
        $user = User::where('id', '=', $building->user_id)->delete();

        //delete building
        $building->delete();

        return response()->json([
            'success' => 'Building updated successfully'
        ], 201);
    }
}
