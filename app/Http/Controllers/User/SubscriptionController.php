<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserSubscription;
use App\Models\Transaction;
use App\CentralLogics\Helpers;
use DataTables;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum,web');
    }

    /**
     * Display user subscriptions page
     */
    public function index()
    {
        $user = Auth::user();
        $activeSubscription = $user->getActiveSubscription();
        return view('user.subscriptions.index', compact('activeSubscription'));
    }

    /**
     * Get user subscriptions data from transactions table for DataTables
     */
    public function datatables()
    {
        $userId = Auth::id();
        $transactions = Transaction::with(['subscription', 'subscription.plan', 'plan'])
            ->where('user_id', $userId)
            ->where('transaction_type', 'subscription_purchase')
            ->orderBy('id', 'desc')
            ->get();

        return DataTables::of($transactions)
                ->addIndexColumn()
                ->editColumn('amount', function(Transaction $data) {
                    return Helpers::setCurrency($data->amount);
                })
                ->editColumn('plan_slug', function(Transaction $data) {
                    if ($data->subscription && $data->subscription->plan) {
                        return '<span class="badge bg-primary">' . ucfirst(str_replace('-', ' ', $data->subscription->plan->slug)) . '</span>';
                    }
                    return '<span class="badge bg-secondary">-</span>';
                })
                ->editColumn('discount_amount', function(Transaction $data) {
                    // Calculate discount from subscription
                    if ($data->subscription) {
                        $original = $data->subscription->plan->price ?? 0;
                        $discount = $original - $data->amount;
                        if ($discount > 0) {
                            return '<span class="badge bg-success">' . Helpers::setCurrency($discount) . '</span>';
                        }
                    }
                    return '<span class="badge bg-secondary">-</span>';
                })
                ->editColumn('promo_code', function(Transaction $data) {
                    // Promo code info can be stored in metadata or transaction details
                    return '<span class="badge bg-secondary">-</span>';
                })
                ->editColumn('status', function(Transaction $data) {
                    $statusClass = match($data->status) {
                        'completed' => 'bg-success',
                        'pending' => 'bg-warning',
                        'failed' => 'bg-danger',
                        default => 'bg-secondary'
                    };
                    return '<span class="badge ' . $statusClass . '">' . ucfirst($data->status) . '</span>';
                })
                ->editColumn('subscription_expires', function(Transaction $data) {
                    if ($data->subscription && $data->subscription->expires_at) {
                        $isExpired = $data->subscription->expires_at <= now();
                        $badgeClass = $isExpired ? 'bg-danger' : 'bg-success';
                        $daysRemaining = $data->subscription->expires_at->diffInDays(now());
                        return '<span class="badge ' . $badgeClass . '">' . $data->subscription->expires_at->format('M d, Y') . ' (' . $daysRemaining . ' days)</span>';
                    }
                    return '<span class="badge bg-secondary">Lifetime</span>';
                })
                ->editColumn('created_at', function(Transaction $data) {
                    return $data->created_at->format('M d, Y');
                })
                ->rawColumns(['discount_amount', 'promo_code', 'plan_slug', 'status', 'subscription_expires'])
                ->toJson();
    }
}
