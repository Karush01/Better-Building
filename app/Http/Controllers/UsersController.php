<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Building;
use App\Ticket;
use App\Task;
use App\ManagementCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Artisan;

class UsersController extends Controller
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
        if (!$request->user()->authorizeRoles(['director', 'management_company'])) {
            return response()->redirectTo('/');
        }

        if (Auth::user()->hasrole('director')):
            $users = User::all();//paginate(100000);
        elseif (Auth::user()->hasrole('management_company')):
            $company = ManagementCompany::where('user_id', '=', Auth::user()->id)->first();
            $users = User::select('users.*')
                ->join('buildings', 'users.id', '=', 'buildings.user_id')
                ->where('buildings.management_company_id', '=', $company->id)
                ->get();
//                ->paginate(100000);
        endif;

        return view('users.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 10);
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

        $buildings = Building::all(['id', 'name']);
        $roles = Role::all(['id', 'name']);

        return view('users.create');
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
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }


        request()->validate([
            'name' => 'required',
            'email' => 'unique:users,email,' . $request->input('email'),
            'phone' => 'required|numeric|min:9|unique:users,phone,' . $request->input('phone'),
            'password' => 'required|string|min:6',
        ]);

        $role_id = 1;
        $password = $request->input('password');

        if ($password):
            $request->request->add(['password' => Hash::make($password)]);
        else:
            $password = $this->generateRandomString();
            $request->request->add(['password' => Hash::make($password)]);
        endif;

        $user = User::create($request->all());

        $user->roles()->attach(Role::where('id', $role_id)->get());

        $user->save();

        Mail::to($user->email)->send(new WelcomeMail($user, $password));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        if (!$request->user()->authorizeRoles(['director', 'management_company', 'tenant'])) {
            return response()->redirectTo('/');
        }

        if ((Auth::user()->hasrole('tenant') || Auth::user()->hasrole('management_company')) && $user->id == Auth::id()):
            $user = User::with('building.managementCompany')->find($user->id);

            return view('users.edit', compact(['user']));
        else:
            $user = User::with('building.managementCompany')->find($user->id);
            $buildings = Building::all(['id', 'name']);
            $roles = Role::all(['id', 'name']);

            return view('users.edit', compact(['user', 'buildings', 'roles']));
        endif;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!$request->user()->authorizeRoles(['director', 'management_company', 'tenant'])) {
            return response()->redirectTo('/');
        }

        request()->validate([
            'name' => 'required',
            'email' => Rule::unique('users')->ignore($user->id),
            'phone' => Rule::unique('users')->ignore($user->id),
        ]);

        $password = $request->input('password');
        if ($password):
            $request->request->add(['password' => Hash::make($password)]);
        else:
            $request->request->remove('password');
        endif;
        $email = $request->input('email');
        if (!$email):
            $request->request->remove('email');
        endif;
        if ($request->input('sms') == 'on'):
            $request->request->add(['sms' => 1]);
        else:
            $request->request->add(['sms' => 0]);
        endif;
        $role_id = $request->input('role_id');
        $old_role_id = $user->roles()->get();

        if ($request->input('building_id')):
            $building = Building::where('id', $request->input('building_id'))->first();
            $building->user()->associate($user->id);
            $building->save();
        endif;

        $request->request->remove('role_id');
        $request->request->remove('building_id');

        $user->update($request->all());

        if ($role_id && $old_role_id[0]->id != $role_id):
            $user->roles()->detach(Role::where('id', $old_role_id[0]->id)->get());
            $user->roles()->attach(Role::where('id', $role_id)->get());

            $user->save();
        endif;


        if (Auth::user()->hasrole('tenant')):
            return redirect()->route('home')
                ->with('success', 'User updated successfully');
        else:
            return redirect()->route('users.index')
                ->with('success', 'User updated successfully');
        endif;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $role_id = $user->roles()->get();

        if ($role_id[0]->id == 2):
            $company = ManagementCompany::where('user_id', '=', $user->id)->first();


            if (!empty($company->id)) {

                $buildings = Building::where('management_company_id', '=', $company->id)->get();

                foreach ($buildings as $building):
                    $this->deleteBuilding($building);
                endforeach;

                //delete Company user
                $user = User::where('id', '=', $company->user_id);

                //delete Company
                $company->delete();
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'המשתמש נמחק בהצלחה');

        elseif ($role_id[0]->id == 3):
            $building = Building::where('user_id', '=', $user->id)->first();
            if ($building) {
                $this->deleteBuilding($building);
            } else {
                $user->delete();
            }

            return redirect()->route('users.index')
                ->with('success', 'המשתמש נמחק בהצלחה');
        else:
            return redirect()->route('users.index')
                ->with('error', 'לא יכולה למחוק את משתמש הניהול');
        endif;
    }

    /**
     * Create random string for password use.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    private function deleteBuilding($building)
    {
        //var_export($building);die;
        //delete all building tickets
        $tickets = Ticket::where('user_id', '=', $building->user_id)->delete();

        //delete all building tasks
        $tasks = Task::where('building_id', '=', $building->id)->delete();

        //delete building user
        $user = User::where('id', '=', $building->user_id)->delete();

        //delete building
        $building->delete();
    }

    /**
     * Create random string for password use.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
