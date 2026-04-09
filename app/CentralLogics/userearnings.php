<?php

namespace App\CentralLogics;

use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\User;
use Auth;
use Carbon\Carbon;

class EarningLogic
{
    //****************User Transaction & Balance Modules****************//
    // public static function RefreshMywallet() {
    //     $user = Auth::user();
    //     if (!$user) return null;
    //     $withdrawalAfterDays = GeneralSetting::value('withdrawl_after_days');
    //     $ordersToUpdate = Order::where('seller_id', $user->id)
    //         ->where('payment_cleared', 0)
    //         ->where('order_status', 'completed')
    //         ->where('payment_status', 'completed')
    //         ->where('order_completed_at', '<=', now()->subDays($withdrawalAfterDays))
    //         ->whereNotNull('order_completed_at');
            
    //     if($ordersToUpdate->count()>0) {
    //         $userbalance = $ordersToUpdate->sum('earning_net_user');
    //         $user->wallet += $userbalance;
    //         $user->save();
    //         $ordersToUpdate->update(['payment_cleared' => 1]);
    //     }
    // }

    //****************User Transaction & Balance Modules****************//

}
