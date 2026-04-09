<?php

namespace App\CentralLogics;
use App\Classes\GeniusMailer;
use App\CentralLogics\Helpers;
use App\Models\Category;
use App\Models\GameItem;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Rating;
use App\Models\Subscriptions;
use App\Models\Transaction;
use App\Models\User;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\GoPaySubscription;
class Mailchimp
{
    private static function getMailchimpClient()
    {
        $mailchimp = new  \MailchimpMarketing\ApiClient();
        $mailchimp->setConfig([
            'apiKey' => config('services.mailchimp.key'),
            'server' => config('services.mailchimp.server_prefix')
        ]);
        return $mailchimp;
    }

    private static function getSubscriberHash($email)
    {
        return md5(strtolower($email));
    }

    public static function updateTags($email, $tags)
    {
        $mailchimp = self::getMailchimpClient();
        $listId = config('services.mailchimp.list_id');
        $subscriberHash = self::getSubscriberHash($email);

        try {
            $mailchimp->lists->updateListMemberTags($listId, $subscriberHash, [
                'tags' => array_map(function ($tag) {
                    return is_array($tag) ? $tag : ['name' => $tag, 'status' => 'active'];
                }, $tags)
            ]);
        } catch (\Exception $e) {
            Helpers::logError($e);
        }
    }


    public static function SubscribeToMailChimp($user)
    {
        $mailchimp = self::getMailchimpClient();
        $listId = config('services.mailchimp.list_id');

        try {
                $response = $mailchimp->lists->addListMember($listId, [
                    "email_address" => $user->email,
                    "status" => "subscribed",
                ]);
            self::updateTags($user->email, ['All Users']);
        } catch (\Exception $e) {
            Helpers::logError($e);
        }
    }

    

    public static function unsubscribeFromMailChimp($email)
    {
        $mailchimp = self::getMailchimpClient();
        $listId = config('services.mailchimp.list_id');

        try {
            $mailchimp->lists->deleteListMember($listId, self::getSubscriberHash($email));
        } catch (\Exception $e) {
            Helpers::logError($e);
        }
    }

}
