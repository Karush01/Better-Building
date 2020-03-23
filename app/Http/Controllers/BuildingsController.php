<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Building;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ManagementCompany;
use App\Task;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Routing\Route;

class BuildingsController extends Controller
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


        if (Auth::user()->hasrole('management_company')):
            $company = ManagementCompany::where('user_id', '=', Auth::user()->id)->first();
            $buildings = Building::with('managementCompany')->where('management_company_id', '=', $company->id);//->paginate();
        else:
            $buildings = Building::with('managementCompany');//->paginate();
        endif;
        $buildings = $buildings->get();
        return view('buildings.index', compact('buildings'))->with('i', (request()->input('page', 1) - 1) * 10);
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

        $companies = ManagementCompany::all(['id', 'name']);

        return view('buildings.create', compact('companies'));
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

        return redirect()->route('buildings.index')
            ->with('success', 'Building created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(Request $request, Building $building)
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

        return view('buildings.show', compact(['tasks', 'building']))->with('i', (request()->input('page', 1) - 1) * 10);
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
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $companies = ManagementCompany::all(['id', 'name']);
        $building = Building::with('managementCompany')->find($building->id);

        return view('buildings.edit', compact(['building', 'companies']));
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
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
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


        return redirect()->route('buildings.index')
            ->with('success', 'Building updated successfully');
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
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        //delete all building tickets
        $tickets = Ticket::where('user_id', '=', $building->user_id)->delete();

        //delete all building tasks
        $tasks = Task::where('building_id', '=', $building->id)->delete();

        //delete building user
        $user = User::where('id', '=', $building->user_id)->delete();

        //delete building
        $building->delete();

        return redirect()->route('buildings.index')
            ->with('success', 'Building Deleted Successfully');
    }
}
