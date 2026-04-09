<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Models\SubPlan;
use App\CentralLogics\Helpers;
use DataTables;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display subscriptions listing page
     */
    public function index()
    {
        return view('admin.subscriptions.index');
    }

    /**
     * Get subscriptions data for DataTables
     */
    public function datatables()
    {
        $subscriptions = UserSubscription::with('user', 'plan')
            ->orderBy('id', 'desc')
            ->get();

        return DataTables::of($subscriptions)
                ->addIndexColumn()
                ->editColumn('user_id', function(UserSubscription $data) {
                    return $data->user->name ?? 'N/A';
                })
                ->editColumn('email', function(UserSubscription $data) {
                    return $data->user->email ?? 'N/A';
                })
                ->editColumn('plan_type', function(UserSubscription $data) {
                    return '<span class="badge bg-primary">' . ($data->plan->name ?? $data->plan_slug) . '</span>';
                })
                ->editColumn('amount', function(UserSubscription $data) {
                    return Helpers::setCurrency($data->amount);
                })
                ->editColumn('status', function(UserSubscription $data) {
                    if ($data->isActive()) {
                        return '<span class="badge bg-success">Active</span>';
                    } elseif ($data->status === 'refunded') {
                        return '<span class="badge bg-warning">Refunded</span>';
                    } else {
                        return '<span class="badge bg-danger">Expired</span>';
                    }
                })
                ->editColumn('expires_at', function(UserSubscription $data) {
                    if ($data->expires_at) {
                        $isExpired = $data->isExpired();
                        $badgeClass = $isExpired ? 'bg-danger' : 'bg-success';
                        return '<span class="badge ' . $badgeClass . '">' . $data->expires_at->format('M d, Y') . '</span>';
                    }
                    return '<span class="badge bg-secondary">-</span>';
                })
                ->editColumn('created_at', function(UserSubscription $data) {
                    return $data->created_at->format('M d, Y H:i');
                })
                ->addColumn('action', function(UserSubscription $data) {
                    return '
                        <div class="btn-group" role="group">
                            <a href="javascript:void(0);" class="btn btn-sm btn-info" title="View Details" onclick="viewSubscription(' . $data->id . ')">
                                <i class="ri-eye-line"></i>
                            </a>
                            <button class="btn btn-sm btn-danger" title="Cancel Subscription" onclick="cancelSubscription(' . $data->id . ')">
                                <i class="ri-close-line"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['plan_type', 'status', 'expires_at', 'action'])
                ->toJson();
    }

    /**
     * Cancel a subscription
     */
    public function cancel($id, Request $request)
    {
        try {
            $subscription = UserSubscription::findOrFail($id);
            $subscription->update([
                'status' => 'cancelled',
                'cancelled_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Subscription canceled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel subscription: ' . $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get subscription details
     */
    public function show($id)
    {
        try {
            $subscription = UserSubscription::with('user', 'plan')->findOrFail($id);
            $originalAmount = $subscription->plan ? $subscription->plan->price : $subscription->amount;
            $discount = $originalAmount - $subscription->amount;

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $subscription->id,
                    'user_name' => $subscription->user->name ?? 'N/A',
                    'user_email' => $subscription->user->email ?? 'N/A',
                    'plan_type' => $subscription->plan->name ?? $subscription->plan_slug,
                    'amount' => Helpers::setCurrency($subscription->amount),
                    'original_amount' => Helpers::setCurrency($originalAmount),
                    'discount_amount' => $discount > 0 ? Helpers::setCurrency($discount) : 'N/A',
                    'promo_code' => 'N/A',
                    'status' => ucfirst($subscription->status),
                    'stripe_subscription_id' => $subscription->token ?? 'N/A',
                    'stripe_customer_id' => 'N/A',
                    'started_at' => $subscription->starts_at ? $subscription->starts_at->format('M d, Y H:i') : 'N/A',
                    'expires_at' => $subscription->expires_at ? $subscription->expires_at->format('M d, Y H:i') : 'N/A',
                    'canceled_at' => $subscription->cancelled_at ? $subscription->cancelled_at->format('M d, Y H:i') : 'N/A',
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }
    }
}
