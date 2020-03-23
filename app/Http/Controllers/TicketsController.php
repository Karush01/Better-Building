<?php

namespace App\Http\Controllers;

use App\Mail\TicketDoneMail;
use App\Ticket;
use App\Traits\Sms;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Status;
use App\ManagementCompany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class TicketsController extends Controller
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
        if (!$request->user()->authorizeRoles(['director', 'management_company', 'tenant'])) {
            return response()->redirectTo('/');
        }

        if (Auth::user()->hasrole('tenant')):
            $tickets = Ticket::with(['user', 'status'])->where('user_id', '=', Auth::user()->id)->paginate();

        elseif (Auth::user()->hasrole('management_company')):
            $company = ManagementCompany::where('user_id', '=', Auth::user()->id)->first();
            $tickets = Ticket::select('tickets.*', 'buildings.name')
                ->join('buildings', 'tickets.user_id', '=', 'buildings.user_id')
                ->where('buildings.management_company_id', '=', $company->id);
//                ->paginate();

        else:
            $tickets = Ticket::select()
                ->join('buildings', function ($join) {
                    $join->on('tickets.user_id', '=', 'buildings.user_id');
                });
//                ->paginate();
        endif;
        $tickets = $tickets->get();


        return view('tickets.index', compact('tickets'))->with('i', (request()->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!$request->user()->authorizeRoles(['tenant'])) {
            return response()->redirectTo('/');
        }

        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'message' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $request->request->add(['status_id' => 1]);
        $request->request->add(['user_id' => Auth::user()->id]);

        $name = '';

        if (isset($request->all()['image']) && $request->all()['image'] !== null) {
            $name = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put($name, File::get($request->file('image')));
        }

        $tmp = $request->all();
        $tmp['image'] = $name;
        Ticket::create($tmp);


        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Ticket $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        //
    }

    public function updateStatus(Request $request)
    {

        if (!$request->user()->authorizeRoles(['management_company'])) {
            return response()->redirectTo('/');
        }

        $ticket = Ticket::with('user')->find($request->ticket_id);

        $status = Status::where('slug', 'done')->get();

        $ticket->status_id = $status[0]['id'];
        $ticket->done_note = $request->ticket_done_note;

        if (isset($request->all()['image']) && $request->all()['image'] !== null) {
            $name = '';
            $name = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put($name, File::get($request->file('image')));
            $ticket->image = $name;
        }

        $ticket->save();

        Mail::to($ticket->user->email)->send(new TicketDoneMail($ticket->user, $ticket));

        if ($ticket->user->sms)
            $sms_result = $this->sendSms($ticket->user, ['action' => 'ticket', 'data' => $ticket->title]);

        return response()->json(['success' => true, 'status_name' => $status[0]['name'], 'done_note' => $ticket->done_note]);
    }


    public function updatePic(Request $request)
    {

        if (!$request->user()->authorizeRoles(['management_company'])) {
            return response()->redirectTo('/');
        }

        $ticket = Ticket::with('user')->find($request->ticket_id);

        if (isset($request->all()['image']) && $request->all()['image'] !== null) {
            $name = '';
            $name = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
            Storage::disk('local')->put($name, File::get($request->file('image')));
            $ticket->image = $name;
        }

        $ticket->save();

        return response()->json(['success' => true]);
    }
}
