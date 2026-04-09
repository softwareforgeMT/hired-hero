<?php

namespace App\CentralLogics;

use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\Withdraw;
use App\Models\User;
use App\Models\SubPlan;

class TransactionLogic
{
    public static function createTransaction(
        $order_id,
        $user_id,
        $payment_gateway,
        $totalprice,
        $checkoutfee,
        $subplan_id,
        $type,
        $txn_id = ''
    ) {
        $gs   = GeneralSetting::find(1);
        $subPlan= SubPlan::find($subplan_id);
        $user = User::findOrFail($user_id);

        $earning_net_user  = 0;
        $earning_net_admin = $totalprice;
        $referred_by       = '';

        // 20% commission to referrer on every paid order
        if ($gs->is_affilate == 1 && !empty($user->referred_by)) {
            $commissionPercent = (int) config('referrals.commission_percent', 20);
            $earning_net_user  = max(0, ($totalprice - $checkoutfee) * ($commissionPercent / 100));
            $earning_net_admin = $totalprice - $earning_net_user;
            $referred_by       = $user->referred_by; // referrer’s affiliate_code
        }

        $transaction = new Transaction();
        $transaction->order_id            = $order_id;
        $transaction->user_id             = $user_id;
        $transaction->subplan_id             = $subPlan->id;
        $transaction->payment_gateway     = $payment_gateway;
        $transaction->amount              = $totalprice;   // post-discount if coupon applied
        $transaction->taxes               = $checkoutfee;
        $transaction->txn_id              = $txn_id;
        $transaction->type                = $type;
        $transaction->status              = 'active';
        $transaction->earning_net_user    = $earning_net_user;
        $transaction->earning_net_admin   = $earning_net_admin;
        $transaction->referrer_link       = $referred_by;
        $transaction->save();
    }

    public static function addWithdrawData($user_id, $method, $amount, $type, $fee, $status, $transfer = '')
    {
        $newwithdraw = new Withdraw();
        $newwithdraw['user_id'] = $user_id;
        $newwithdraw['method']  = $method;

        if ($transfer) {
            $newwithdraw['transfer_id']          = $transfer->id;
            $newwithdraw['balance_transaction']  = $transfer->balance_transaction;
            $newwithdraw['destination']          = $transfer->destination;
            $newwithdraw['destination_payment']  = $transfer->destination_payment;
            $newwithdraw['live_mode']            = $transfer->live_mode ? 1 : 0;
        }

        $newwithdraw['amount'] = $amount;
        $newwithdraw['fee']    = $fee;
        $newwithdraw['type']   = $type;
        $newwithdraw['status'] = $status;
        $newwithdraw->save();
    }
}
