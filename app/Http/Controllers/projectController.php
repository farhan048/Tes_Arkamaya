<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Requests\StoreProjectRequest;
use Carbon\Carbon;
class projectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $data = Project::with(['client']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" id="data_checkbox'.$row->id.'" name="data_checkbox[]" class="data_checkbox" value="'.$row->id.'" />';
                })
                ->addColumn('project', function ($row) {
                    return $row->project_name;
                })
                ->addColumn('Client Name', function ($row) {
                    return $row->client->client_name;
                })
                ->addColumn('start', function ($row) {
                    return $row->project_start->formatLocalized('%d %B %Y');
                })
                ->addColumn('end', function ($row) {
                    return $row->project_end;
                })
                ->addColumn('status', function ($row) {
                     if ($row->project_status == 'OPEN') {
                        return '<span class="badge badge-primary">'.$row->project_status.'</span>';
                    }elseif ($row->project_status == 'DONE') {
                        return '<span class="badge badge-success">'.$row->project_status.'</span>';
                    }else {
                        return '<span class="badge badge-warning">'.$row->project_status.'</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $edit = '<a href="javascript:void(0)" onclick="edit('.$row->id.')" class="btn btn-outline-primary btn-xs inline">EDIT</a>'; 
                    $delete = '<a href="javascript:void(0)" onclick="destroy('.$row->id.')" class="btn btn-outline-danger btn-xs">HAPUS</a>';
                    return $edit.$delete;
                })
                ->filterColumn('fullname', function($query, $keyword) {
                    $sql = "CONCAT(users.first_name,'-',users.last_name)  like ?";
                    $query->whereRaw($sql, ["%{$keyword}%"]);
                })
                ->filter(function ($row) {
                    if (request()->has('byName')) {
                        $row->orwhere('project_name', 'like', "%" . request('byName') . "%");
                    }
                    if (request()->has('byClient')) {
                            $row->orwhere('client_id', 'like', "%" . request('byClient') . "%");
                    }
                    if (request()->has('byStatus')) {
                        $row->orwhere('project_status', 'like', "%" . request('byStatus') . "%");
                    }        
                }, true)
                ->rawColumns(['checkbox','project','Client Name', 'start', 'end','status', 'action'])
                ->make(true);
        }        
        $client = Client::all();
        return view ('admin.project.index', compact('client'));
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
    public function store(StoreProjectRequest $request)
    {
        Project::updateOrCreate(['id' => $request->id], $request->validated());

        return response()->json([
            'status'   => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $data = Project::with(['client']);
        return Datatables::of($data)
        ->filter(function ($row) {
            if (request()->has('byName')) {
                $row->orwhere('project_name', 'like', "%" . request('byName') . "%");
            }
            if (request()->has('byClient')) {
                    $row->orwhere('client_id', 'like', "%" . request('byClient') . "%");
            }
            if (request()->has('byStatus')) {
                $row->orwhere('project_status', 'like', "%" . request('byStatus') . "%");
            }        
        })
        ->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $client = Project::find($id);

        return response()->json([
            'data'  => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Project::find($id);
        if ($id > 2) {
        $client->delete();
        return response()->json([
            'message' => 'success'
        ]);
        } else {
            $client->get()->delete();
            return response()->json([
                'message' => 'success'
            ]);
        }
        
    }
    public function delete($id)
    {
        $client = Project::find($id);
        $client->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }
    public function remove(Request $request)
    {
        $id_array = $request->input('id');
        $client = Project::whereIn('id', $id_array);
        $client->delete();
        return response()->json([
            'message' => 'success'
        ]);
    }
}
