<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Requests\StoreClientRequest;

class clientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Client::all();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('Client Name', function ($row) {
                    return $row->client_name;
                })
                ->addColumn('Address', function ($row) {
                    return $row->address;
                })
                ->addColumn('action', function ($row) {
                    $edit = '<a href="javascript:void(0)" onclick="edit('.$row->id.')" class="btn btn-outline-primary btn-xs inline">EDIT</a>'; 
                    $delete = '<a href="javascript:void(0)" onclick="destroy('.$row->id.')" class="btn btn-outline-danger btn-xs">HAPUS</a>';
                    return $edit.$delete;
                })
                ->rawColumns(['checkbox','Client Name', 'Address', 'action'])
                ->make(true);
        }
        return view ('admin.clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        Client::updateOrCreate(['id' => $request->id], $request->validated());

        return response()->json([
            'status'   => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Client::find($id);

        return response()->json([
            'data'  => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();

        return response()->json([
            'message' => 'success'
        ]);
    }
}
