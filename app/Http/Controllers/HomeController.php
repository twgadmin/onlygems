<?php
namespace App\Http\Controllers;
use PDF;
use App\Imports\SheetsImport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use Auth;
use App\Models\Card;
use App\Models\User;
use App\Models\Activation;
use App\Models\Order;
use App\Models\OrderDetail;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Request as Input;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
	   public function index()
	   {
			if((Auth::user()->activated==1)&&(((Auth::user()->signup_confirmation_ip_address=='')||((Auth::user()->phone_verified_at=='')||(Auth::user()->phone_verified_at=='0000-00-00 00:00:00'))))){
			   	User::where('id',Auth::user()->id)->update(['activated'=>0]);
				return redirect()->route('home');
				exit();
		   }
		   	
		   if(Auth::user()->activated==0){
			   return redirect()->route('activation-required');
			   exit();			   			   
		   }else{
			   	return view('home');
		   }			
	   }
	
	public function importSheetFromApi(Request $request){
		if($request->hasFile('file')){
            $file          = $request->file('file');
            $extension     = $file->getClientOriginalExtension();
            $filename      = 'order-'.time() . '.' . $extension;
            $file->move(public_path('/storage/sheet/'),$filename);
			$files = public_path('/storage/sheet/'.$filename);
			$cardData = [];
		
		$data1 = Excel::toArray([],$files);		
		if(count($data1[0])>10){
			//$order = Order::create(['user_id'=>Auth::user()->id,'qty'=>(count($data1[0])-11),'status'=>'Pending']);
			foreach($data1[0] as $key=>$value):
				if($key>10){
					$cardData[]=array('name'=>$value[0],'grading_co'=>$value[1],'grading_co_serial_number'=>$value[2],'year'=>$value[3],'set'=>$value[4],'card'=>$value[5],'parralel'=>$value[6],'grade'=>$value[7],'category'=>$value[8],'estimated_value'=>$value[9]);
					/*OrderDetail::create(['user_id'=>Auth::user()->id,'order_id'=>$order->id,'name'=>$value[0],'grading_co'=>$value[1],'grading_co_serial_number'=>$value[2],'year'=>$value[3],'set'=>$value[4],'card'=>$value[5],'parralel'=>$value[6],'grade'=>$value[7],'category'=>$value[8],'estimated_value'=>$value[9]]);*/
				}			
			endforeach;
		}
		$type='xlxs';
		$gradingcos = ['BGS','CSG','HGA','PSA','SGC'];
		$grades = ['10','9.5','9','8.5','8','7.5','7','6.5','6','5.5','5','4.5','4','3.5','3','2.5','2','1.5','1','0.5'];
		$categorys =  ['Baseball','Basketball','Football','Golf','Hockey','Pocket Monsters','Pokemon','Soccer','Yu-gi-oh','Other'];
		return view('vault-submission',compact('files','cardData','gradingcos','grades','categorys','type','filename'));  		
		
		/*	$data=['first_name'=>Auth::user()->first_name,'last_name'=>Auth::user()->last_name];
            Mail::send('emails.order',['data'=>$data], function($message)use($data, $files) {
				//Auth::user()->email
				$message->to('vault@onlygems.io') 
				->subject('Only Gems - Order From '.Auth::user()->first_name." ".Auth::user()->last_name)
				->attach($files);
			});

            Mail::send('emails.orderconfirmation',['data'=>$data], function($message)use($data, $files) {
                $message->to(Auth::user()->email) 
                ->subject('Only Gems - Order Confirmation Email')
                ->attach($files);
            });

	        return redirect()
            ->route('vault.submission')
            ->with('success', 'Order uploaded successfully.');	*/					
        }
	}
	
	
	public function createFileFromWebPost(Request $request){		
		$csv_filename = "order_".date("Y-m-d_H-i",time()).".csv";
		$cardData = [];
		if(count($request->name)>0){
			//$order = Order::create(['user_id'=>Auth::user()->id,'qty'=>count($request->name),'status'=>'Pending']);
			foreach($request->name as $key=>$value):
			$cardData[$key]['name']=$value;
			$cardData[$key]['grading_co']=$request->grading_co[$key];
			$cardData[$key]['grading_co_serial_number']=$request->grading_co_serial_number[$key];
			$cardData[$key]['year']=$request->year[$key];
			$cardData[$key]['set']=$request->set[$key];
			$cardData[$key]['card']=$request->card_number[$key];
			$cardData[$key]['parralel']=$request->parralel[$key];
			$cardData[$key]['grade']=$request->grade[$key];
			$cardData[$key]['category']=$request->category[$key];
			$cardData[$key]['estimated_value']=$request->estimated_value[$key];
			/*OrderDetail::create(['user_id'=>Auth::user()->id,'order_id'=>$order->id,'name'=>$value,'grading_co'=>$request->grading_co[$key],'grading_co_serial_number'=>$request->grading_co_serial_number[$key],'year'=>$request->year[$key],'set'=>$request->set[$key],'card'=>$request->card_number[$key],'parralel'=>$request->parralel[$key],'grade'=>$request->grade[$key],'category'=>$request->category[$key],'estimated_value'=>$request->estimated_value[$key]]);*/
			endforeach;
		}		
		$fd = fopen (public_path('/storage/sheet/').$csv_filename, "w");		
		fputs($fd,"Name,Grading Co,Grading Co Serial Number,Year,Set,Card Number,Parralel,Grade,Category,Estimated Value\n");
		foreach($request->name as $key=>$value):
		fputs($fd,$value.",".$request->grading_co[$key].",".$request->grading_co_serial_number[$key].",".$request->year[$key].",".$request->set[$key].",".$request->card_number[$key].",".$request->parralel[$key].",".$request->grade[$key].",".$request->category[$key].",$".$request->estimated_value[$key]."\n");
		endforeach;
		fclose($fd);
		$type='csv';
		$files = public_path('/storage/sheet/'.$csv_filename);
		$filename = $csv_filename;
		$gradingcos = ['BGS','CSG','HGA','PSA','SGC'];
		$grades = ['10','9.5','9','8.5','8','7.5','7','6.5','6','5.5','5','4.5','4','3.5','3','2.5','2','1.5','1','0.5'];
		$categorys =  ['Baseball','Basketball','Football','Golf','Hockey','Pocket Monsters','Pokemon','Soccer','Yu-gi-oh','Other'];
		return view('vault-submission',compact('files','cardData','gradingcos','grades','categorys','type','filename'));  
		/*
		$data=['first_name'=>Auth::user()->first_name,'last_name'=>Auth::user()->last_name];
		Mail::send('emails.order',['data'=>$data], function($message)use($data, $files) {
			//Auth::user()->email
			$message->to('vault@onlygems.io') 
			->subject('Only Gems - Order From '.Auth::user()->first_name." ".Auth::user()->last_name)
			->attach($files);
		});

		Mail::send('emails.orderconfirmation',['data'=>$data], function($message)use($data, $files) {
			$message->to(Auth::user()->email) 
			->subject('Only Gems - Order Confirmation Email')
			->attach($files);
		});

		return redirect()
		->route('vault.submission')
		->with('success', 'Order posted successfully.');
		exit();
	   */
	}
	
	public function verifyPhoneNumber(Request $request){
		$sid = "ACce8651016739624969608bd1f70538fb";
		$token = "85670a8071076e034a7fd6624abfb906";
		$twilio = new Client($sid, $token);
		$verification_check = $twilio->verify->v2->services("VAeb9527e75c7f28a3c069fa9347fc0a33")
									 ->verificationChecks
									 ->create(["code"=>$request->code,"to" => "+".Auth::user()->phone]);							 
		if($verification_check->status=='approved'){
			User::where('id', Auth::user()->id)->update(['phone_verified_at'=>date('Y-m-d H:i:s'),'activated'=>'1']);
			if(Auth::user()->signup_confirmation_ip_address==''){
				return redirect()->route('activation-required')
                ->with('status', 'success')
                ->with('message', 'Phone number verified successfully.');
			}else{
				return redirect()->route('home')
                ->with('status', 'success')
                ->with('message', 'Phone number verified successfully.');
			}
			
			exit();
		}else{
			return redirect()->route('activation-required')
                ->with('status', 'info')
                ->with('message', 'Phone code is not matched.');
			exit();
		}
	}
	

	public function sendSmsAgain(){
		$sid = "ACce8651016739624969608bd1f70538fb";
		$token = "85670a8071076e034a7fd6624abfb906";
		$twilio = new Client($sid, $token);
		$verification = $twilio->verify->v2->services("VAeb9527e75c7f28a3c069fa9347fc0a33")
							   ->verifications
							   ->create("+".Auth::user()->phone, "sms");
		return redirect()->route('activation-required')
			->with('status', 'success')
			->with('message', 'Verification code successfully send.');				   
	}
	
	
	public function sendEmailCodeAgain(){

		Activation::where('user_id',Auth::user()->id)->delete();
		
		Activation::create([
            'user_id'  => Auth::user()->id,
            'token'    => 	 Auth::user()->token,
            'ip_address'  => Auth::user()->signup_ip_address
        ]);
		
		$user =[
		    'first_name'        => Auth::user()->first_name,
            'last_name'         => Auth::user()->last_name,
            'email'             => Auth::user()->email,
            'token'             => Auth::user()->token
		];
		Mail::send('emails.emailverification', ['data' => $user], function($message) use ($user){
            $message->to($user['email'])
            ->subject("Email Verification");
        });		
		return redirect()->route('activation-required')
			->with('status', 'success')
			->with('message', 'Verification email successfully send.');				   
	}

		public function vaultSubmission(){			
		   if((Auth::user()->activated==1)&&(((Auth::user()->signup_confirmation_ip_address=='')||((Auth::user()->phone_verified_at=='')||(Auth::user()->phone_verified_at=='0000-00-00 00:00:00'))))){
			   	User::where('id',Auth::user()->id)->update(['activated'=>0]);
				return redirect()->route('home');
				exit();
		   }
		   	
		   if(Auth::user()->activated==0){
			   return redirect()->route('activation-required');
			   exit();			   			   
		   }else{
			   $gradingcos = ['BGS','CSG','HGA','PSA','SGC'];
			   $grades = ['10','9.5','9','8.5','8','7.5','7','6.5','6','5.5','5','4.5','4','3.5','3','2.5','2','1.5','1','0.5'];
			   $categorys =  ['Baseball','Basketball','Football','Golf','Hockey','Pocket Monsters','Pokemon','Soccer','Yu-gi-oh','Other'];
			   return view('vault-submission', compact('gradingcos','grades','categorys'));  
		   }	
	}
	
	public function ordersListing()
    {	
		$orders = Order::allOrders();	
		return View('orders-list',compact('orders'));
    }
	
	
	
	public function ordersDetails($id)
    {	
		$orders = OrderDetail::orderDetails(['order_details.order_id'=>$id]);
		$userinfo = User::find($orders[0]->user_id);
		return View('order-details',compact('orders','userinfo'));
    }
	
	public function exportOrderPDF(Request $request)
    {
        if (isset($request->mode) && $request->mode == 'export_pdf') {
            $data     = $this->orderStructure($request);
            $data     = json_encode($data);
            $data     = json_decode($data, TRUE);
            $data     = $data['original']['data'];
            $pdfData  = ['data' => $data];
            $pdf      = PDF::loadView('order-pdf', $pdfData);
            $filename = "order-worksheet_" . date("Y_m_d_i_H") . ".pdf";
            return $pdf->download($filename);
        }
    }
	
	public function orderStructure(Request $request)
    {
		
        if (isset($request->mode) && $request->mode == 'export_pdf'){
                $orderid          = $request->orderid;
                $return["count"] = 0;
                $data            = [];
            if ($orderid != '') {

                $data['orderid'] = $orderid;
                $data['orders'] = OrderDetail::orderDetails(['order_details.order_id'=>$orderid]);
				$data['userinfo'] = User::find($data['orders'][0]->user_id);
                $error = 0;
                $message = "Worksheet data has been retrived successfully.";            
            } else {
                $data    = [];
                $error   = 1;
                $message = "Something went wrong, Worksheet data could not be load.";
            }
        
        } else {
            $data = [];
            $error = 1;
            $message = "Something went wrong, Worksheet data could not be load.";
        }

        return response()->json([
            'error'   => $error,
            'message' => $message,
            'data'    => $data
        ]);
    
	}
	
	public function confirmSheetData(Request $request){
		if($request->filename!=''){	
			if($request->filetype=='xlxs'){
					$files = public_path('/storage/sheet/'.$request->filename);
					$data1 = Excel::toArray([],$files);		
					if(count($data1[0])>10){
						$order = Order::create(['user_id'=>Auth::user()->id,'qty'=>(count($data1[0])-11),'status'=>'Pending']);
						foreach($data1[0] as $key=>$value):
							if($key>10){
								OrderDetail::create(['user_id'=>Auth::user()->id,'order_id'=>$order->id,'name'=>$value[0],'grading_co'=>$value[1],'grading_co_serial_number'=>$value[2],'year'=>$value[3],'set'=>$value[4],'card'=>$value[5],'parralel'=>$value[6],'grade'=>$value[7],'category'=>$value[8],'estimated_value'=>$value[9]]);
							}			
						endforeach;
					}			
			}

			if($request->filetype=='csv'){
				$files = public_path('/storage/sheet/'.$request->filename);
				$data1 = Excel::toArray([],$files);
				if(count($data1[0])>0){
					$order = Order::create(['user_id'=>Auth::user()->id,'stage'=>'1','qty'=>(count($data1[0])-1),'status'=>'Pending']);
					foreach($data1[0] as $key=>$value):
						if($key>0){
							OrderDetail::create(['user_id'=>Auth::user()->id,'order_id'=>$order->id,'name'=>$value[0],'grading_co'=>$value[1],'grading_co_serial_number'=>$value[2],'year'=>$value[3],'set'=>$value[4],'card'=>$value[5],'parralel'=>$value[6],'grade'=>$value[7],'category'=>$value[8],'estimated_value'=>$value[9]]);
						}			
					endforeach;
				}
			}

		
			$data=['first_name'=>Auth::user()->first_name,'last_name'=>Auth::user()->last_name];
            Mail::send('emails.order',['data'=>$data], function($message)use($data, $files) {
				//Auth::user()->email
				$message->to('vault@onlygems.io') 
				->subject('Only Gems - Order From '.Auth::user()->first_name." ".Auth::user()->last_name)
				->attach($files);
			});

            Mail::send('emails.orderconfirmation',['data'=>$data], function($message)use($data, $files) {
                $message->to(Auth::user()->email) 
                ->subject('Only Gems - Order Confirmation Email')
                ->attach($files);
            });

	        return redirect()
            ->route('order.list')
            ->with('success', 'Order uploaded successfully.');						
        }
	}
	
	public function updateOrdersProducts($orderid,$productid,$status){
		OrderDetail::where('id',$productid)->update(['status'=>ucfirst($status)]);
		$totalVerified=[];
		$totalRejected=[];
		$checkOrderStatuses = OrderDetail::orderDetails(['order_details.order_id'=>$orderid]);
		foreach($checkOrderStatuses as $checkOrderStatus):
			if($checkOrderStatus->status=='Rejected'){
				$totalRejected[]=$checkOrderStatus->status;
			}
			if($checkOrderStatus->status=='Verified'){
				$totalVerified[]=$checkOrderStatus->status;				
			}
		endforeach;
		
		if(count($totalRejected)>0){
			Order::where('id',$orderid)->update(['status'=>'Rejected/On Hold','stage'=>'3A']);			
			$orderDetails = Order::find($orderid);
			$customerDetails = User::find($orderDetails->user_id);
			$data=['first_name'=>$customerDetails->first_name,'last_name'=>$customerDetails->last_name,'email'=>$customerDetails->email];
			
			Mail::send('emails.orderrejectedforadmin',['order'=>$orderid], function($message){
                $message->to('vault@onlygems.io') 
                ->subject('Only Gems - Order Rejected/On Hold Status');
            });
			
			Mail::send('emails.orderrejected',['data'=>$data], function($message)use($data) {
				$message->to($data['email']) 
                ->subject('Only Gems - Order Rejected/On Hold Status');
            });
			
			
		}else{
			Order::where('id',$orderid)->update(['stage'=>'3']);			
		}
		
		if(count($totalVerified)==count($checkOrderStatuses)){
			Order::where('id',$orderid)->update(['status'=>'Verified','stage'=>'4']);
			$orderDetails = Order::find($orderid);
			$customerDetails = User::find($orderDetails->user_id);

			$data=['first_name'=>$customerDetails->first_name,'last_name'=>$customerDetails->last_name,'email'=>$customerDetails->email,'walletid'=>$customerDetails->walletid];

			Mail::send('emails.orderverified',['data'=>$data], function($message)use($data) {
				$message->to($data['email']) 
				->subject('Only Gems - Order Verified');
			});
		}
		
		return redirect()
		->route('order.details',$orderid)
		->with('success', 'Card '.$status.' successfully.');
	}
	
	public function updateOrderStatus($orderid,$status){
		
		if($status=='Intake'){
				Order::where('id',$orderid)->update(['status'=>'Intake','stage'=>'2']);
				$orderDetails = Order::find($orderid);
				$customerDetails = User::find($orderDetails->user_id);		
				$data=['first_name'=>$customerDetails->first_name,'last_name'=>$customerDetails->last_name,'email'=>$customerDetails->email];
				/***********************************************************************************/
				/***********************************************************************************/		
					$orders = OrderDetail::orderDetails(['order_details.order_id'=>$orderid]);			
					$csv_filename = "order_".date("Y-m-d_H-i",time()).".csv";
					$fd = fopen (public_path('/storage/sheet/').$csv_filename, "w");		
					fputs($fd,"Name,Grading Co,Grading Co Serial Number,Year,Set,Card Number,Parralel,Grade,Category,Estimated Value\n");
					foreach($orders as $key=>$value):
						fputs($fd,$value->name.",".$value->grading_co.",".$value->grading_co_serial_number.",".$value->year.",".$value->set.",".$value->card_number.",".$value->parralel.",".$value->grade.",".$value->category.",$".$value->estimated_value."\n");
					endforeach;
					fclose($fd);
					$files = public_path('/storage/sheet/'.$csv_filename);
				/***********************************************************************************/
				/***********************************************************************************/					
				Mail::send('emails.approvedforintake',['data'=>$data], function($message)use($data, $files) {
					$message->to($data['email']) 
					->subject('Only Gems - Order has been approved for intake.')
					->attach($files);
				});
						
				return redirect()
				->route('order.details',$orderid)
				->with('success', 'Order has been approved for Intake successfully.');
		}
		
		if($status=='Processed'){
				Order::where('id',$orderid)->update(['status'=>'Processed','stage'=>'2']);
				$orderDetails = Order::find($orderid);
				$customerDetails = User::find($orderDetails->user_id);
		
				$data=['first_name'=>$customerDetails->first_name,'last_name'=>$customerDetails->last_name,'email'=>$customerDetails->email,'walletid'=>$customerDetails->walletid];
		
				Mail::send('emails.approvedforprocess',['data'=>$data], function($message)use($data) {
					$message->to($data['email']) 
					->subject('Only Gems - Order has been approve for processed.');
				});
						
				return redirect()
				->route('order.details',$orderid)
				->with('success', 'Order has been approve for processed successfully.');
		}
	}
		
	public function processedOrder(Request $request){
		Order::where('id',$request->orderid)->update(['minted'=>$request->minted,'sent_to_wallet'=>$request->sent_to_wallet,'status'=>'Processed','stage'=>'4A']);
		$orderDetails = Order::find($request->orderid);
		$customerDetails = User::find($orderDetails->user_id);

		$data=['first_name'=>$customerDetails->first_name,'last_name'=>$customerDetails->last_name,'email'=>$customerDetails->email,'walletid'=>$customerDetails->walletid];

		Mail::send('emails.approvedforprocess',['data'=>$data], function($message)use($data) {
			$message->to($data['email']) 
			->subject('Only Gems - Order has been approve for processed.');
		});
				
		return redirect()
		->route('order.details',$request->orderid)
		->with('success', 'Order has been approve for process successfully.');
	}


	public function updateOrdersProductsMinted($orderid,$productid,$status){
		OrderDetail::where('id',$productid)->update(['minted'=>ucfirst($status)]);		
		
		return redirect()
		->route('order.details',$orderid)
		->with('success', 'Card for minted has been updated successfully.');
	}
	
	public function updateOrdersProductsSentToWallet($orderid,$productid,$status){
		OrderDetail::where('id',$productid)->update(['sent_to_wallet'=>ucfirst($status)]);
		
		return redirect()
		->route('order.details',$orderid)
		->with('success', 'Card for sent to wallet has been updated successfully.');

	}
}
