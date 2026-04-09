<?php
/**
 * Created by PhpStorm.
 * User: ShaOn
 * Date: 11/29/2018
 * Time: 12:49 AM
 */

namespace App\Classes;
use App\CentralLogics\Helpers;

use App\Models\GeneralSetting;
use Config;
use Illuminate\Support\Facades\Mail;
use PDF;

class GeniusMailer
{

    public function __construct()
    {
        // $gs = Generalsetting::findOrFail(1);
        // Config::set('mail.host', $gs->smtp_host);
        // Config::set('mail.port', $gs->smtp_port);
        // Config::set('mail.encryption', $gs->email_encryption);
        // Config::set('mail.username', $gs->smtp_user);
        // Config::set('mail.password', $gs->smtp_pass);
    }




    public function sendCustomMail(array $mailData)
    {   
        $setup = GeneralSetting::find(1);

        $data = [
            'email_body' => $mailData['body'],
            'subject' => $mailData['subject']
        ];

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->from = $setup->from_email;
        $objDemo->title = $setup->from_name;
        $objDemo->subject = $mailData['subject'];

        try{
            Mail::send('emails.emailbody',$data, function ($message) use ($objDemo) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });
        }
        catch (\Exception $e){
             Helpers::logError($e);
            die($e->getMessage());
            // return $e->getMessage();
        }
        return true;
    }

     public function sendCustomMail2(array $mailData)
    {   
        $setup = GeneralSetting::find(1);

        $data = [
            'email_body' => $mailData['body'],
            'subject' => $mailData['subject']
        ];

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->from = $setup->from_email;
        $objDemo->title = $setup->from_name;
        $objDemo->subject = $mailData['subject'];

        try{
            Mail::send('emails.welcomeEmailBody',$data, function ($message) use ($objDemo) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });
        }
        catch (\Exception $e){
             Helpers::logError($e);
            die($e->getMessage());
            // return $e->getMessage();
        }
        return true;
    }
}