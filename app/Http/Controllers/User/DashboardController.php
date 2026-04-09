<?php

namespace App\Http\Controllers\User;
use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;
use App\Models\Country;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Models\PlacementProfile;
use App\Models\JobMatch;
use App\Models\JobApplication;
use App\Models\Resume;
use App\Models\InterviewAttempt;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Session;
use DB;


class DashboardController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth');
        $this->request = $request;
    }

   public function index($value='')
   {
       //return redirect()->route('user.order.purchased.index');
       $gs=GeneralSetting::find(1);
       $totalAmount = Wallet::where('user_to', Auth::user()->id)
           ->where('status', 'credit')
           ->sum('amount');
      
       $link=route('front.index').'?reff='.Auth::user()->affilate_code;
            $title=$gs->name;
            $socialShare = \Share::page($link,$title)
            ->facebook()
            ->twitter()
            // ->reddit()
            ->linkedin()
            ->whatsapp()
            ->getRawLinks();
            // ->telegram();

       // Fetch dashboard statistics for all features
       $userId = Auth::user()->id;
       
       // Placement Profile
       $placementProfile = PlacementProfile::where('user_id', $userId)->first();
       $placementProfileExists = $placementProfile ? true : false;
       
       // Job Matches
       $jobMatchesCount = JobMatch::where('user_id', $userId)->count();
       $pendingJobMatches = JobMatch::where('user_id', $userId)->limit(3)->get();
       
       // Job Applications
       $applicationStats = JobApplication::where('user_id', $userId)
           ->select('status', DB::raw('count(*) as count'))
           ->groupBy('status')
           ->get();
       $totalApplications = JobApplication::where('user_id', $userId)->count();
       
       // Create a keyed array of status counts
       $statusCounts = [];
       foreach ($applicationStats as $stat) {
           $statusCounts[$stat->status] = $stat->count;
       }
       
       $applicationsInProgress = $statusCounts['applied'] ?? 0;
       $applicationsInterviewing = $statusCounts['interviewing'] ?? 0;
       $applicationsOffered = $statusCounts['offered'] ?? 0;
       
       // Resumes
       $resumeCount = Resume::where('user_id', $userId)->count();
       
       // Mock Interviews
       $interviewAttempts = InterviewAttempt::where('user_id', $userId)->orderBy('created_at', 'desc')->limit(5)->get();
       $totalInterviewAttempts = InterviewAttempt::where('user_id', $userId)->count();

       return view('user.dashboard', compact(
           'gs',
           'socialShare',
           'totalAmount',
           'placementProfileExists',
           'placementProfile',
           'jobMatchesCount',
           'pendingJobMatches',
           'totalApplications',
           'applicationsInProgress',
           'applicationsInterviewing',
           'applicationsOffered',
           'resumeCount',
           'interviewAttempts',
           'totalInterviewAttempts'
       ));
   }

    public function profile()
    {
        $data = Auth::user(); 
        $countries=Country::where('status',1)->get();
        return view('user.account-settings',compact('data','countries'));
    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section
        $request->validate([
         'seller_description' => 'max:500',
         'photo' => 'mimes:jpeg,jpg,png,svg',
         'name' => 'unique:users,name,'.Auth::user()->id,
        ]);

        //--- Validation Section Ends
         $data = Auth::user();
        //image upload
        $data->photo = Helpers::update('user/avatar/', $data->photo=='user.png'?'':$data->photo, config('fileformats.image'), $request->file('photo'));
        $data->name=$request->name;
        $data->seller_description=$request->seller_description;
        if(!$data->country_id){
            $data->country_id=$request->country_id;
        }
        $data->update();

        Session::flash('message', 'Data Updated Successfully !');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }

    public function resetform()
    {
        return view('user.cpassword');
    }

    public function reset(Request $request)
    {
       $request->validate([
         'cpass'=>'required',
         'newpass'=>'required',
         'renewpass' => 'required|same:newpass'
        ]);
        $user = Auth::user();
        if ($request->cpass){
            if (Hash::check($request->cpass, $user->password)){
                    $input['password'] = Hash::make($request->newpass);               
            }else{
               Session::flash('message', 'Current password Does not match.');
               Session::flash('alert-class', 'alert-danger');
               return redirect()->back();
            }
        }
        $user->update($input);
        Session::flash('message', 'Successfully change your password');
        Session::flash('alert-class', 'alert-success');
        return redirect()->back();
    }



    public function storeMedia(Request $request)
    {
        $path = storage_path('tmp/uploads');       
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('file');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        
        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function verifySeller($step)
    {
        $user = Auth::user();
        switch ($step) {
            case 'step-1':
                return view('user.seller-verification', compact('step','user'));
                break;
            case 'step-2':
                return ($user->fname && $user->lname && $user->dob) ? view('user.seller-verification', compact('step','user')) : redirect()->route('user.verify.seller', 'step-1')->with('info','Please fill out Step 1 information');
                break;
            case 'step-3':
                if($user->fname && $user->lname && $user->dob && $user->id_card){
                    return view('user.seller-verification',compact('step','user'));
                }else{
                    $step = $user->fname && $user->lname && $user->dob ? 'step-2' : 'step-1';
                    return redirect()->route('user.verify.seller', $step)->with('info','Please fillout Step '. $step . ' information');
                }
                break;
            default:
                return view('errors.404');
        }
    }



  public function verifySellerSubmit(Request $request,$step)
  {    
        $user=Auth::user(); 
        if($user->seller_verification=="processing" || $user->seller_verification=="completed"){
            return back();
        }
        if($step=='step-1'){
            $request->validate([
                 'fname'=>'required',
                 'lname'=>'required',
                 'dob' => 'required'
                ],[
                'fname.required'=>'The first name field is required.',
                'lname.required'=>'The last name field is required.',
                'dob.required'=>'Date of Birth field is required.'
            ]);
            
            $user->fname=$request->fname;
            $user->lname=$request->lname;
            $user->dob=Carbon::parse($request->dob)->format('Y-m-d');
            $user->update();
            return redirect()->route('user.verify.seller','step-2');
        }elseif($step=='step-2'){
           $request->validate([
                'id_card' => [
                    'sometimes',
                    'required',
                ],
            ], [
                'id_card.required' => 'ID card field is required.',
            ]);

            if ($request->hasFile('id_card')) {
                if($user->id_card){
                    $user->id_card = Helpers::update('user/', $user->id_card, config('fileformats.image'), $request->file('id_card'));
                }else{
                    $user->id_card = Helpers::upload('user/', config('fileformats.image'), $request->file('id_card'));
                }
                $user->update();
            }
            return redirect()->route('user.verify.seller','step-3');
        }elseif($step=='step-3'){
           $request->validate([
             'id_card_with_avatar'=>'required',
            ]);
            $user->id_card_with_avatar=Helpers::upload('user/',config('fileformats.image'), $request->file('id_card_with_avatar'));
            $user->seller_verification='processing';
            $user->update();
            return redirect()->back()->with('info','We are processing your information');

        }  
  }
}
