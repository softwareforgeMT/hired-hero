<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Models\Subscriptions;
use App\Models\Transaction;
use App\Models\User;
use App\Services\DemoDataService;
use App\CentralLogics\Helpers;
use Carbon\Carbon;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class UserController extends Controller
{
    public function __construct(){
     $this->middleware('auth:admin');
    }

    public function usersDataTables($value='')
    {   
        // Check if demo mode is active
        if (Helpers::demo_mode()) {
            return response()->json(DemoDataService::getDemoUsersJson());
        }

        // Real data
        $datas=User::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            ->addIndexColumn()
                            
                            ->editColumn('created_at', function(User $data) {
                                return Carbon::parse($data->created_at)->format('F d, Y');
                            })
                            ->addColumn('referralDetails', function(User $data) {
                             
                                $affiliateCode = $data->affiliate_code;
                                $uniqueUsers = $data->getUniqueUserCount();
                                $totalSales = $data->getTotalSalesFromReferrals();

                                return "Code: $affiliateCode<br>Unique Users: $uniqueUsers<br>Total Sales: $" . number_format($totalSales, 2);
                            })
                            

                            ->addColumn('status', function(User $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.users.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.users.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })
                            // ->addColumn('action', function(User $data) {
                            //     return '<div class="action-list">
                                
                            //     <a href="'.route('admin.users.transactions.index',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light">View Transactions</a> 

                            //     </div>';
                            // }) 
                            ->rawColumns(['status','created_at','referralDetails'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function users($value='')
    {
        return view('admin.users.users');
    }



    public function sellersDataTables($value='')
    {   
        $datas=User::whereIn('seller_verification',['processing','verified'])->orderBy('id','desc')->get(); 

        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->editColumn('seller_verification', function(User $data) {
                                return "<span class='badge badge-soft-success text-uppercase'>$data->seller_verification</span>";
                            })
                            ->editColumn('created_at', function(User $data) {
                                return Carbon::parse($data->created_at)->format('F d, Y');
                            })
                            ->addColumn('status', function(User $data) {
                                $class = $data->status == 1 ? 'drop-success' : 'drop-danger';
                                $s = $data->status == 1 ? 'selected' : '';
                                $ns = $data->status == 0 ? 'selected' : '';
                                return '<div class="action-list"><select class="process select droplinks '.$class.'"><option data-val="1" value="'. route('admin.users.status',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Activated</option><option data-val="0" value="'. route('admin.users.status',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>Deactivated</option></select></div>';
                            })
                            ->addColumn('action', function(User $data) {
                                // <a  href="'.route('admin.sellers.view',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light">View </a>
                                return '<div class="action-list">
                                
                                
                                <a  data-href="'. route('admin.sellers.view',$data->id) . '"  data-bs-toggle="offcanvas" href="#G2zCanvasModal" class="btn btn-info btn-sm fs-13 waves-effect waves-light viewdata_in_canvas">View </a>

                                

                                </div>';
                            }) 
                            ->rawColumns(['status','seller_verification','created_at','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function sellers($value='')
    {
        return view('admin.sellers.index');
    }
    public function sellershow($id)
    {   
        $user=User::find($id);
        return view('admin.sellers.includes.userdata',compact('user'));
    }

    public function sellerstatus($id,$status)
    { 
        $user=User::find($id);
        if($status=='verified'){
            $user->seller_verification='verified';
            $user->update();
        }
    }

    public function status($id1,$id2)
    { 
        $user=User::find($id1);
        $user->status=$id2;
        $user->update();

    }

    public function subscribedusersDataTables($value='')
    {   
        $datas=Subscriptions::orderBy('id','desc')->get();  
        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->editColumn('user_id', function(Subscriptions $data) {
                                return $data->relateduser?$data->relateduser->name:'Not Defined';
                            })
                            ->editColumn('stripe_status', function(Subscriptions $data) {
                                if($data->stripe_status=="active" || $data->stripe_status=="trialing")
                                {  
                                   if($data->stripe_status=="trialing"){
                                    $msg="<span class='badge badge-soft-secondary badge-border'>".Str::upper($data->stripe_status)."</span><br>";
                                    $msg.="<small>Trial Ends At: ".Carbon::parse($data->trial_ends_at)->format('F d, Y ')."</small>";
                                   }else{
                                     $msg="<span class='badge text-bg-success'>".Str::upper($data->stripe_status)."</span><br>";
                                     $msg.="<small>Ends At: ".Carbon::parse($data->ends_at)->format('F d, Y')."</small>";
                                   }
                                   return $msg;

                                }else{
                                   $msg="<span class='badge text-bg-danger'>".Str::upper($data->stripe_status)."</span><br>";
                                   // $msg.="<small>Ends At: ".Carbon::parse($data->ends_at)->format('F d, Y H:i A')."</small>";
                                   return $msg;
                                }
                                
                            })
                            ->editColumn('created_at', function(Subscriptions $data) {
                                return Carbon::parse($data->created_at)->format('F d, Y');
                            })
                            ->addColumn('action', function(Subscriptions $data) {
                                return '<div class="action-list">
                                
                                <a href="'.route('admin.users.transactions.index',$data->id).'" class="btn btn-info btn-sm fs-13 waves-effect waves-light">View Transactions</a> 

                                </div>';
                            }) 
                            ->rawColumns(['stripe_status','created_at','action'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function subscribedusers($value='')
    {
        return view('admin.users.subscribed');
    }

    public function userstransactionsDataTables($id='')
    {   
        if($id){
         $subscription=Subscriptions::find($id);  
         $datas=Transaction::where('subscriptions_id',$subscription->id)->orderBy('id','desc')->get();
        }else{
         $datas=Transaction::orderBy('id','desc')->get();
        }          
        return DataTables::of($datas)
                            ->addIndexColumn()
                            ->editColumn('user_id', function(Transaction $data) {
                                return $data->relateduser?$data->relateduser->name:'Not Defined';
                            })
                            ->editColumn('amount', function(Transaction $data) {

                               if($data->status=="trialing"){
                                 $msg=AppHelper::setCurrency(0);
                                 $msg.="<br><span class='badge badge-soft-secondary badge-border'>".Str::upper($data->status)."</span>";
                               }else{
                                 $msg=AppHelper::setCurrency($data->amount);
                                 $msg.="<br><span class='badge text-bg-success'>".Str::upper($data->status)."</span>";
                               }
                               return $msg;                           
                            })
                            ->editColumn('earning_net_admin', function(Transaction $data) {
                                return AppHelper::setCurrency($data->earning_net_admin);
                            })
                            ->editColumn('referrer_link', function(Transaction $data) {
                                if($data->referrer_link){
                                    $userearn=AppHelper::setCurrency($data->earning_net_user);
                                    $msg="<span>Referrer Link :".$data->referrer_link."</span><br><span>Referrer Earning :".$userearn."</span>";
                                    return $msg;
                                }else{
                                    return "No Referral";
                                }
                                
                            })
                            ->editColumn('created_at', function(Transaction $data) {
                                return Carbon::parse($data->created_at)->format('F d, Y H:i A');
                            })
                            ->rawColumns(['earning_net_admin','referrer_link','amount','created_at'])
                            ->toJson(); //--- Returning Json Data To Client Side
      
    }
    public function userstransactions($id='')
    {
        return view('admin.users.transactions',compact('id'));
    }

    public function secret($id)
    {
        try {
            // Get the user to impersonate
            $user = User::findOrFail($id);
            // Get current admin
            $admin = Auth::guard('admin')->user();

            // Store impersonation session data
            session([
                'impersonating' => true,
                'impersonating_by' => $admin->id ?? null,
                'impersonating_by_name' => $admin->name ?? 'Admin',
                'impersonating_by_guard' => 'admin',
            ]);

            // Log the impersonation
            Log::info('Admin impersonating user', [
                'admin_id' => $admin->id ?? 'Unknown',
                'admin_email' => $admin->email ?? 'Unknown',
                'user_id' => $user->id,
                'user_email' => $user->email,
                'timestamp' => now(),
            ]);

            // Logout from admin and login as user
            Auth::guard('web')->logout();
            Auth::guard('web')->login($user);
            return redirect()->route('user.dashboard')->with('success', "Impersonating {$user->name} ({$user->email})");
        } catch (\Exception $e) {
            Log::error('Failed to impersonate user', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'admin_id' => Auth::guard('admin')->id(),
            ]);

            return back()->withErrors(['error' => 'Failed to impersonate user.']);
        }
    }

    /**
     * Stop impersonating and return to admin account
     */
    public function stopImpersonate()
    {
        // Get the original admin ID from session
        $adminId = session('impersonating_by');

        if (!$adminId || !session('impersonating')) {
            return redirect()->route('admin.dashboard')->withErrors(['error' => 'No active impersonation found.']);
        }

        try {
            // Get the impersonated user info for logging
            $impersonatedUser = Auth::user();

            // Logout from user account
            Auth::guard('web')->logout();

            // Get admin back and login
            $admin = \App\Models\Admin::findOrFail($adminId);
            Auth::guard('admin')->login($admin);

            // Clear impersonation session data
            session()->forget(['impersonating', 'impersonating_by', 'impersonating_by_name', 'impersonating_by_guard']);

            // Log the revert
            Log::info('Admin stopped impersonating user', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'impersonated_user_id' => $impersonatedUser->id ?? null,
                'impersonated_user_email' => $impersonatedUser->email ?? null,
                'timestamp' => now(),
            ]);

            return redirect()->route('admin.users.index')->with('success', 'Successfully reverted to admin account.');
        } catch (\Exception $e) {
            Log::error('Failed to stop impersonation', [
                'error' => $e->getMessage(),
                'admin_id' => $adminId,
            ]);

            return redirect()->route('admin.dashboard')->withErrors(['error' => 'Failed to revert to admin account.']);
        }
    }

}
