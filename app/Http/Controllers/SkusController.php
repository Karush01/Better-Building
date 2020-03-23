<?php

namespace App\Http\Controllers;

use App\Sku;
use App\Duration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SkusController extends Controller
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
        if (!$request->user()->authorizeRoles(['director', 'management_company'])) {
            return response()->redirectTo('/');
        }

        $skus = Sku::all();
        $durations = Duration::all();

        return view('skus.index', compact(['skus', 'durations']))->with('i', (request()->input('page', 1) - 1) * 10);
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

        $durations = Duration::all();

        return view('skus.create', compact(['durations']));
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
            'сyclic_days' => 'required|numeric', function ($input) {
                return $input->сyclic_days > 0;
            }
        ]);


        $request->request->remove('duration_id');

        $sku = $request->all();

        if (isset($sku['regression']) && $sku['regression'] == 'true') {
            $sku['regression'] = 1;
        } else {
            $sku['regression'] = 0;
        }

        Sku::create($sku);


        return redirect()->route('skus.index')
            ->with('success', 'Sku created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }


        $sku = Sku::find($id);
        $durations = Duration::all();

        return view('skus.edit', compact(['sku', 'durations']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->user()->authorizeRoles(['director'])) {
            return response()->redirectTo('/');
        }

        request()->validate([
            'name' => 'required',
            'description' => 'required',
            'сyclic_days' => 'required|numeric', function ($input) {
                return $input->сyclic_days > 0;
            }
        ]);

        $sku = Sku::find($id);
        $request->request->remove('duration_id');

        $sku_tmp = $request->all();

        if (isset($sku_tmp['regression']) && $sku_tmp['regression'] == 'true') {
            $sku_tmp['regression'] = 1;
        } else {
            $sku_tmp['regression'] = 0;
        }

        $sku->update($sku_tmp);


        return redirect()->route('skus.index')
            ->with('success', 'Sku updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
