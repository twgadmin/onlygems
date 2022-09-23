<?php
namespace App\Http\Controllers\Auth;
use Mail;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\Profile;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\Activation;
use App\Traits\CaptureIpTrait;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use jeremykenedy\LaravelRoles\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, 
		    [
                'first_name'            => 'alpha_dash',
                'last_name'             => 'alpha_dash',
                'email'                 => 'required|email|max:255|unique:users',
                'phone_number'          => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'address'               => 'required',
                'walletid'              => 'required'
            ],
            [
                'first_name.required' => trans('auth.fNameRequired'),
                'last_name.required'  => trans('auth.lNameRequired'),
                'email.required'      => trans('auth.emailRequired'),
                'email.email'         => trans('auth.emailInvalid'),
                'phone_number.required' => trans('auth.phonenumRequired'),
                'address.required'    => trans('auth.AddressRequired'),
                'walletid.required'    => trans('auth.WalletIdRequired')
            ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
		
		$phoneNumber = str_replace('+','',$data['phone_number']);
        $phone = (isset($phoneNumber[0])&&$phoneNumber[0]=='1')? substr($phoneNumber,1) : $phoneNumber;		
	  	$sid = "ACce8651016739624969608bd1f70538fb";
		$token = "85670a8071076e034a7fd6624abfb906";
		$twilio = new Client($sid, $token);
		$verification = $twilio->verify->v2->services("VAeb9527e75c7f28a3c069fa9347fc0a33")
							   ->verifications
							   ->create("+1".$phone, "sms");
		$password = random_int(100000, 999999);
		$emailOtp = random_int(100000, 999999);
		$ipAddress = new CaptureIpTrait();
        $profile = new Profile();

		
		$address = json_encode(array("street"=>$data['address'],"city"=>$data['city'],"state"=>$data['state'],"zip"=>$data['zip'],"country"=>$data['country']));
        $user = User::create([
            'name'              => $data['email'],
            'first_name'        => strip_tags($data['first_name']),
            'last_name'         => strip_tags($data['last_name']),
            'email'             => $data['email'],
            'phone'             => '1'.$phone, 
            'address'           => $address,
            'token'             => str_random(64),
            'signup_ip_address' => $ipAddress->getClientIp(),
            'activated'         => 0,
            'password' 		    => Hash::make($password),
			'walletid'			=>$data['walletid']
        ]);
		
		Profile::create([
            'user_id'         => $user['id'],
            'location'        => $data['address']
        ]);
		
		RoleUser::create([
            'user_id'  => $user['id'],
            'role_id'  => '2'
        ]);

		Activation::create([
            'user_id'  => $user['id'],
            'token'    => $user['token'],
            'ip_address'  => $user['signup_ip_address']
        ]);
				
		if ($user['email'] != ''){			
		  Mail::send('emails.created', ['data' => $user,'password' => $password], function($message) use ($data) {
            $message->to($data['email'])
            ->subject("Account created.");
          });
		
		  Mail::send('emails.emailverification', ['data' => $user], function($message) use ($data){
            $message->to($data['email'])
            ->subject("Email Verification");
          });
		}				
		return $user;
    }
}
