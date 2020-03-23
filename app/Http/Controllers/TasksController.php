<?php

namespace App\Http\Controllers;

use App\Building;
use App\Sku;
use App\Status;
use App\Task;
use App\ManagementCompany;
use App\User;
use Illuminate\Http\Request;
use Config;
use App\Duration;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TaskDoneMail;
use App\Traits\Sms;
use App\Calendar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TasksController extends Controller
{
    use Sms;

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
        if (Auth::user()->hasrole('management_company')):
            $company = ManagementCompany::where('user_id', '=', Auth::user()->id)->first();
            $tasks = Task::with('building', 'sku', 'status')
                ->whereHas('building.managementcompany', function ($q) {
                    $q->select('id');
                }, '=', $company->id)->whereDate('last_date', '<=', date('Y-m-d', strtotime(now() . " + 1 months")));
//                ->paginate(100);
        else:
            $tasks = Task::with('building', 'sku', 'status')->whereDate('last_date', '<=', date('Y-m-d', strtotime(now() . " + 1 months")));//->paginate(100);
        endif;

        $tasks = $tasks->get();

        return view('tasks.index', compact('tasks'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        if (!$request->user()->authorizeRoles(['director', 'management_company'])) {
            return response()->redirectTo('/');
        }
        $durations = Duration::all();

        if (Auth::user()->hasrole('management_company')):
            $company = ManagementCompany::where('user_id', '=', Auth::user()->id)->first();
            if (Input::get('building')):
                $building = Building::find(Input::get('building'));
                $skus = Sku::all(['id', 'name', 'description', 'сyclic_days']);
                $statuses = Status::all();

                return view('tasks.create', compact(['building', 'skus', 'statuses', 'durations']));
            else:
                $buildings = Building::all([
                    'id',
                    'name',
                    'management_company_id'
                ])->where('management_company_id', '=', $company->id);
                $skus = Sku::all(['id', 'name', 'description', 'сyclic_days']);
                $statuses = Status::all();

                return view('tasks.create', compact(['buildings', 'skus', 'statuses', 'durations']));
            endif;
        else:
            if (Input::get('building')):
                $building = Building::find(Input::get('building'));
                $skus = Sku::all(['id', 'name', 'description', 'сyclic_days']);
                $statuses = Status::all();

                return view('tasks.create', compact(['building', 'skus', 'statuses', 'durations']));
            else:
                $buildings = Building::all(['id', 'name']);
                $skus = Sku::all(['id', 'name', 'description', 'сyclic_days']);
                $statuses = Status::all();

                return view('tasks.create', compact(['buildings', 'skus', 'statuses', 'durations']));
            endif;
        endif;
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

        if (!$request->has('cron')):
            request()->validate([
                'building_id' => 'required',
                'note' => 'required',
                'sku_id' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'сyclic_days' => 'required',
                function ($input) {
                    return $input->сyclic_days > 0;
                },
            ]);
        endif;

        $tasks = Task::where('building_id', '=', $request->input('building_id'))->where('sku_id', '=', $request->input('sku_id'))->count();

        if ($tasks > 0 && !$request->has('cron')):
            return redirect()->route('tasks.create')
                ->withErrors(['Ctreation Faild, Sku Already exists on this building']);
        endif;

        $request->request->add(['status_id' => 1]);
        $day = $request->input('сyclic_days');
        $startDate = explode('/', $request->start_date);
        $startDate = "$startDate[2]-$startDate[1]-$startDate[0]";
        $interval = Config::get('settings.cyclic_tasks_days');
        $endDate = date('Y-m-d', strtotime($startDate . " + $interval months"));
        $all_inputs = $request->all();
        $name = '';
        if (isset($all_inputs['image']) && $all_inputs['image'] !== null) {
            $name = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put($name, File::get($request->file('image')));
        }
        while ($startDate < $endDate):
            $end = $startDate;


            $year = date('Y', strtotime($end));
            $holidays = $this->getYearHolidays($year);

            $i = 0;
            $flag = false;
            while ($flag == false):
                $pointerN = date('Y-m-d', strtotime($end . " - $i day"));
                $pointerP = date('Y-m-d', strtotime($end . " + $i day"));

                if (!$this->inHolidayList($pointerP, $holidays) && !$this->isWeekEnd($pointerP) && ($endDate > $pointerP)):
                    $result = $pointerP;
                    $flag = true;
                elseif (!$this->inHolidayList($pointerN, $holidays) && !$this->isWeekEnd($pointerN) && ($i < 3 && $i != 0) && ($startDate != $pointerN) && ($endDate > $pointerP)):
                    $result = $pointerN;
                    $flag = true;
                elseif (($endDate <= $pointerP) || ($endDate <= $pointerN)):
                    break 2;
                endif;
                $i++;
            endwhile;


            $request->request->add(['last_date' => $result]);
            $request->request->add(['image' => $name]);
            Task::create($request->all());

            $end = date('Y-m-d', strtotime("$end $day"));
            while ($this->isWeekEnd($end)) {
                $end = date('Y-m-d', strtotime("$end -1 day"));
            }

            $startDate = $end;

        endwhile;
        if (!$request->has('cron')):
            return redirect()->route('buildings.show', $request->input('building_id'))
                ->with('success', 'New Task created successfully.');
        else:
            return true;
        endif;
    }

    private function inHolidayList($date, $holidayList)
    {
        $date = date('Y-m-d', strtotime($date));

        foreach ($holidayList->items as $item) {
            if ($date == date('Y-m-d', strtotime($item->date))) {
                return true;
            }
        }

        return false;
    }

    private function isWeekEnd($day)
    {
        $dayOfWeek = date('w', strtotime($day));
        if ($dayOfWeek < 5) {
            return false;
        } else {
            return true;
        }
    }

    private function getYearHolidays($year)
    {
        $holidays = Calendar::where('year', '=', $year)->get();

        if ($holidays->count() > 0) {
            return json_decode($holidays[0]->data);
        } else {
            $urlAPI = "http://www.hebcal.com/hebcal/?v=1&cfg=json&maj=on&min=on&mod=on&nx=off&year=%u&ss=off&mf=off&c=off&geo=none&s=off";
            $urlAPI = sprintf($urlAPI, $year);
            $holidays = file_get_contents($urlAPI);

            $holidays = Calendar::create(['year' => $year, 'data' => $holidays]);

            return json_decode($holidays->data);
        }
    }

    public function updateStatus(Request $request)
    {

        if (!$request->user()->authorizeRoles(['director', 'management_company'])) {
            return response()->redirectTo('/');
        }

        $task = Task::find($request->task_id);
        if (date('Y-m-d', strtotime($task->last_date)) < now()) {
            $status = Status::where('slug', 'done_overdue')->get();
        } else {
            $status = Status::where('slug', 'done')->get();
        }

        $task->status_id = $status[0]['id'];
        $task->done_note = $request->task_done_note;

        if (isset($request->all()['image']) && $request->all()['image'] !== null) {
            $name = '';
            $name = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put($name, File::get($request->file('image')));
            $task->image = $name;
        }

        $task->save();

        $user = User::find($task->building->user_id);

        Mail::to($user->email)->send(new TaskDoneMail($user, $task));

        if ($user->sms) {
            $sms_result = $this->sendSms($user, ['action' => 'task', 'data' => $task->sku->name]);
        }

        return response()->json(['success' => true,
            'status_name' => $status[0]['name'],
            'done_note' => $task->done_note
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Task $task)
    {
        if (!$request->user()->authorizeRoles(['director', 'management_company', 'tenant'])) {
            return response()->redirectTo('/');
        }

        if (Auth::user()->hasrole('tenant')):
            $company = ManagementCompany::where('user_id', '=', Auth::user()->id)->first();

            $tasks = Task::with('building')
                ->where('sku_id', '=', $task->sku_id)
                ->where('building_id', '=', $task->building_id)
                ->whereHas('building.user', function ($q) {
                    $q->select('id');
                }, '=', Auth::user()->id)
                ->paginate(100000);
        endif;
        $tasks = Task::where('sku_id', '=', $task->sku_id)->where('building_id', '=', $task->building_id)->paginate(100000);

        return view('tasks.show', compact('tasks'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Task $task)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }
        $durations = Duration::all();

        $task = Task::with(['building', 'sku'])->find($task->id);

        return view('tasks.edit', compact('task', 'durations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        request()->validate([
            'сyclic_days' => 'required',
            'note' => 'required',
        ]);
        if ($request->input('сyclic_days') == $task->сyclic_days && empty($request->input('start_date'))):

            $tasks = Task::where('building_id', '=', $task->building_id)->where('sku_id', '=', $task->sku_id)->get();
            foreach ($tasks as $task):
                $task->update(['note' => $request->input('note')]);
            endforeach;
        else:
            Task::where('building_id', '=', $task->building_id)->where('sku_id', '=', $task->sku_id)->where('status_id', '!=', 2)->delete();
            $request->request->add(['status_id' => '1']);

            $this->store($request);
//            Task::insert($request);
        endif;

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Task $task
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Task $task)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        $building_id = $task->building_id;
        $sku_id = $task->sku_id;
        $tasks = Task::where('building_id', '=', $building_id)->where('sku_id', '=', $sku_id)->get();;

        foreach ($tasks as $task):
            $task->delete();
        endforeach;

        return redirect()->route('buildings.show', $building_id)
            ->with('success', 'Delete All Tasks Successfully');
    }
}
