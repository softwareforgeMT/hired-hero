<?php

namespace App\Http\Controllers\User;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\OrderTrackLogic;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Rating;
use Auth;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;
use Validator;
class OrderPurchasedController extends Controller
{
    public function __construct(){

     $this->middleware('auth');
    }

    public function datatables()
    {
        $datas = Order::where('user_id', auth()->id())->orderBy('id', 'desc')->get();
        return DataTables::of($datas)
            ->addColumn('plan_name', function(Order $data) {
                $plan = $data->plan;
                if ($plan) {
                    return '<div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h5 class="fs-14 mb-1"><a href="" class="text-dark">'.$plan->name.'</a></h5>
                                    <p class="text-muted mb-0">Description: <span class="fw-medium">'.$plan->description.'</span></p>
                                </div>
                            </div>';
                } else {
                    return '';
                }
            })
            ->editColumn('expires_at', function(Order $data) {
                return $data->expires_at ? $data->expires_at->format('Y-m-d') : 'N/A';
            })
            ->editColumn('amount', function(Order $data) {
                return '$'.number_format($data->amount, 2);
            })
            ->addColumn('activities', function(Order $data) {
            $activities = json_decode($data->userActivity->activities ?? '[]', true);
            return '<ul>
                        <li>Mock Interviews: '.($activities['interviewAccess'] ?? 0).'</li>
                        <li>Presentations: '.($activities['presentationAccess'] ?? 0).'</li>
                    </ul>';
            })
            // ->addColumn('action', function(Order $data) {
            //     return '<div class="action-list">
            //             <a href="'.route('user.order.purchased.show', $data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light">
            //                 <i class="ri-eye-fill align-middle fs-16 me-2"></i>View
            //             </a> 
            //             </div>';
            // })
            ->rawColumns(['plan_name', 'amount', 'expires_at', 'action','activities'])
            ->toJson();
    }


    public function index($order_status='')
    {  
       $datas = Order::where('user_id',Auth::id())->get();
       return view('user.orders.purchased.index',compact('datas'));
    }


}
