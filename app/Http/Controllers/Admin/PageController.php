<?php

namespace App\Http\Controllers\Admin;


use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\GeneralSetting;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;
class PageController extends Controller
{

    public function __construct(){
     $this->middleware('auth:admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables()
    {   
        // Check if demo mode is active
        if (Helpers::demo_mode()) {
            // Get demo pages from demo_settings table
            $demoPages = \App\Models\DemoSettings::getBySection('pages') ?? [];
            
            // Convert demo pages array to collection of objects
            $datas = collect($demoPages)->map(function ($page) {
                return (object) $page;
            })->values();
        } else {
            // Get real pages from database
            $datas = Page::orderBy('id','desc')->get();
        }

        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->addColumn('status', function($data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.custompage.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.custompage.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })
                            // ->editColumn('photo', function(Page $data) {
                            //    return "<img src='".Helpers::image($data->photo, 'Page/')."' class='img-tumbnail w-100px' >";
                            // })

                            ->addColumn('action', function($data) {
                                return '<div class="action-list">
                                <a href="'.route('admin.custompage.edit',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light"><i class="ri-edit-box-fill align-middle fs-16 me-2"></i>Edit</a> 

                               

                                </div>';
                                 // <a data-href="' . route('admin.custompage.delete',$data->id) . '" data-bs-toggle="modal" data-bs-target="#confirm-delete" class="btn btn-sm fs-13 btn-danger waves-effect waves-light"><i class="ri-delete-bin-fill align-middle fs-16 me-2"></i>Delete</a>
                            }) 
                            ->rawColumns(['status','action'])
                            ->toJson(); //--- Returning Json Data To Client Side

       
    }

    public function index()
    {   
        return view('admin.pages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {   

        return view('admin.pages.create');
    }

  


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$level='')
    {   
        try{ 

             //--- Validation Section
            $slug = $request->slug;
            $main = array('home','faq','contact','blog','cart','checkout');
            if (in_array($slug, $main)) {
                return redirect()->back()->with('error','This slug has already been taken.');                   
            }

            $this->validate($request,[
                 'slug' => 'required|unique:pages,slug',
            ]);

            $data=new Page();
            $data->title= $request->title;
            $data->slug=Helpers::slug($request->slug);
            $data->details=$request->details;
            
            $data->save();

            return redirect()->route('admin.custompage.index')->with('success','Data has been saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data=Page::find($id);
        return view('admin.pages.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        try {


              //--- Validation Section
            $slug = $request->slug;
            $main = array('home','faq','contact','blog','cart','checkout');
            if (in_array($slug, $main)) {
                return redirect()->back()->with('error','This slug has already been taken.');                   
            }

            $this->validate($request,[
                'slug' => 'required|unique:pages,slug,' . $id,
            ]);

            $data=Page::find($id);
            $input=$request->all();

            $data->title= $request->title;
            $data->slug=Helpers::slug($request->slug);
            $data->details=$request->details;
            
            $data->update($input);

            return redirect()->back()->with('success', 'Data has been updated successfully!');

        } catch (\Exception $e) {
            // log the exception message and details
            //Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }

    }

        //*** GET Request
    public function status($id1,$id2)
    {
        $data = Page::findOrFail($id1);
        $data->status = $id2;
        $data->update();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Page::findOrFail($id);
        $data->delete();
    }
}