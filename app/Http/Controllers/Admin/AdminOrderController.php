<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\DemoDataService;
use App\CentralLogics\Helpers;
use Yajra\DataTables\Facades\DataTables;


class AdminOrderController extends Controller
{
    public function index()
    {
        return view('admin.order.index');
    }


    public function datatables()
    {
        // Check if demo mode is active
        if (Helpers::demo_mode()) {
            return response()->json(DemoDataService::getDemoOrdersJson());
        }

        // Real data
        $datas = Order::with(['plan', 'user', 'userActivity'])->orderBy('id', 'desc')->get();

        return DataTables::of($datas)
            
            ->addColumn('plan_name', function(Order $data) {
                $plan = $data->plan;
                $planName = $plan ? $plan->name : 'N/A';
                $paymentId = $data->payment_id ?? 'N/A';
                return '<div class="d-flex flex-column">
                            <span class="fw-bold">' . $planName . '</span>
                            <small class="text-muted">Payment ID: ' . $paymentId . '</small>
                        </div>';
            })
            ->addColumn('user_name', function(Order $data) {
                $user = $data->user;
                return $user ? $user->name : 'N/A';
            })
            ->editColumn('expires_at', function(Order $data) {
                return $data->expires_at ? $data->expires_at->format('Y-m-d H:i:s') : 'N/A';
            })


           ->editColumn('amount', function(Order $data) {
                $formattedAmount = '$' . number_format($data->amount, 2);
                // Retrieve referrer information
                $referrer = $data->transaction ? ($data->transaction->referrer_link ?? 'No Referrer') : 'No Transaction';
                // Concatenate amount with referrer information
                return $formattedAmount . "<br><small class='text-muted'>Referrer: " . $referrer . "</small>";
            })

            ->addColumn('activities', function(Order $data) {
                $activities = json_decode($data->userActivity->activities ?? '[]', true);
                return '<ul>
                            <li>Mock Interviews: '.($activities['interviewAccess'] ?? 0).'</li>
                            <li>Presentations: '.($activities['presentationAccess'] ?? 0).'</li>
                        </ul>';
            })

            ->rawColumns(['plan_name', 'user_name', 'amount', 'expires_at', 'activities','referrer'])
            ->toJson();
    }


}
