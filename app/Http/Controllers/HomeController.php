<?php

namespace App\Http\Controllers;

use App\Models\GeneratedPins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use DataTables;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return View
     */
    public function index(): View
    {
        $generatedPins = [];
        return view('dashboard', compact('generatedPins'));
    }

    /**
     * List.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $data = GeneratedPins::latest()->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
        ]);

        try {

            $pin = GeneratedPins::generatePIN();
            DB::beginTransaction();

            $generatedPins = new GeneratedPins;
            $generatedPins->name = $request->name;
            $generatedPins->pin = Hash::make($pin);
            $generatedPins->email = $request->email;
            $generatedPins->save();

            $data = $generatedPins->toArray();
            $data['pin'] = $pin;

            \Mail::to($request->email)->send(new \App\Mail\Mailer($data));

            DB::commit();
            return response()->json('PINs generated successfully', 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     */
    public function show($id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     */
    public function edit($id): void
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id): void
    {
        //
    }
}
