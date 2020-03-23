<?php

namespace App\Http\Controllers;

use App\Building;
use App\Task;
use App\Ticket;
use App\User;
use App\ManagementCompany;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;

class ManagementCompaniesController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $companies = ManagementCompany::all();//paginate();

        return view('managementcompanies.index', compact('companies'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        //$companies = ManagementCompany::all();
        return view('managementcompanies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required',
            'city' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'username' => 'required',
            'useremail' => 'unique:users,email,' . $request->input('email'),
            'userphone' => 'required|numeric|min:9|unique:users,phone,' . $request->input('phone'),
            'userpassword' => 'required|string|min:6',
        ]);

        $all_inputs = $request->all();
        $all_inputs['name'] = $all_inputs['username'];
        $all_inputs['email'] = $all_inputs['useremail'];
        $all_inputs['phone'] = $all_inputs['userphone'];

        $role_id = 2;
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
        $request->request->add([
            'user_id' => $user->id,
            'description' => $all_inputs['description'] ?? '',
        ]);

        $managementcompany = ManagementCompany::create($request->all());
        $managementcompany->user()->associate($user->id);
        $managementcompany->save();


        Mail::to($user->email)->send(new WelcomeMail($user, $password));

        return redirect()->route('managementcompanies.index')
            ->with('success', 'Management Company created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ManagementCompany $managementCompany
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ManagementCompany $managementCompany)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\ManagementCompany $managementCompany
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $company)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $company = ManagementCompany::find($company);

        return view('managementcompanies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\ManagementCompany $managementCompany
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        request()->validate([
            'name' => 'required',
            'city' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);

        $company = ManagementCompany::find($id);


        $company->update($request->all());


        return redirect()->route('managementcompanies.index')
            ->with('success', 'Management Company updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\ManagementCompany $managementCompany
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $company = ManagementCompany::find($id);
        $buildings = Building::where('management_company_id', '=', $id)->get();

        foreach ($buildings as $building):
            //delete all building tickets
            $tickets = Ticket::where('user_id', '=', $building->user_id)->delete();

            //delete all building tasks
            $tasks = Task::where('building_id', '=', $building->id)->delete();

            //delete building user
            $user = User::where('id', '=', $building->user_id)->delete();

            //delete building
            $building->delete();
        endforeach;

        //delete Company user
        $user = User::where('id', '=', $company->user_id)->delete();

        //delete Company
        $company->delete();

        return redirect()->route('managementcompanies.index')
            ->with('success', 'Management Company Deleted Successfully');
    }
}
