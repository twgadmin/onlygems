<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class WelcomeController extends Controller
{

    public function __construct()
    {
        $this->domain_prefix = "onlygems";

        $this->authorize_url = "https://secure.vendhq.com/connect";
        $this->token_url = "https://".$this->domain_prefix.".vendhq.com/api/1.0/token";

        //	callback URL specified when the application was defined--has to match what the application says
        $this->callback_uri = "https://onlygems.stagingwebsites.info/testing";

        $this->test_api_url = "https://".$this->domain_prefix.".vendhq.com/api/2.0/products";

        //	client (application) credentials - located at apim.byu.edu
        $this->client_id = "GxyOd8h8OAfJMjbYiJo6RPW37cGSyzIV";
        $this->client_secret = "uhhXu9T90mFTvZuQktG6WsgNsfBudwUC";

    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('welcome');
    }

    public function index()
    {
        return view('cards.demo');
    }

    public function add_test_card_data(Request $request) {

        $access_token = "BMA6K0HmjS1OQpNDv0K2u_o2KzEkVHtuKio6Js50";

        $handle = str_replace(' ', '-', $request->name); // Replaces all spaces with hyphens.

        $handle = preg_replace('/[^A-Za-z0-9\-]/', '-', $handle); // Removes special chars.

        $handle = strtolower($handle);

        $tags = explode(',',$request->tags);

        $header = array("Accept: application/json", "Authorization: Bearer {$access_token}", "Content-Type: application/json");

        $content = json_encode(array("name"=>$request->name, "handle"=>$handle, "sku"=>$request->sku, "type" => array("name"=>$request->product_type), "brand" => array("name" => $request->brand), "description" => $request->description));

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->test_api_url,
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $content
        ));
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $response = curl_exec($curl);

        $response_arr = json_decode($response,true);
        // print_r($response_arr);
        curl_close($curl);
        $url = "https://".$this->domain_prefix.".vendhq.com/barcode/select_count?product_id=".$response_arr['data'][0];

        return json_encode(array("status"=>"200", "message" => "success", "url" => $url));
    }


    public function testing(Request $request) {
        // $resource = $this->getResource('BMA6K0HmjS1OQpNDv0K2u_o2KzEkVHtuKio6Js50');
        // print_r($request->all());
    if (isset($_POST["authorization_code"])) {
        //	what to do if there's an authorization code
        $access_token = $this->getAccessToken($_POST["authorization_code"]);
        $resource = $this->getResource($access_token);
        echo '<pre>';
        print_r($resource);
        die;
    } else if (isset($_GET["code"])) {
        $access_token = $this->getAccessToken($_GET["code"]);
        $resource = $this->getResource($access_token['access_token']);
        
    } else {
        //	what to do if there's no authorization code
        $this->getAuthorizationCode();
    }
}

    //	step A - simulate a request from a browser on the authorize_url
//		will return an authorization code after the user is prompted for credentials
public function getAuthorizationCode() {
	$authorization_redirect_url = $this->authorize_url . "?response_type=code&client_id=" . $this->client_id . "&redirect_uri=" . $this->callback_uri . "&state=123";
	header("Location:".$authorization_redirect_url);
    exit;
}

//	step I, J - turn the authorization code into an access token, etc.
public function getAccessToken($authorization_code) {

	$authorization = base64_encode("$this->client_id:$this->client_secret");
	$header = array("Authorization: Basic {$authorization}","Content-Type: application/x-www-form-urlencoded");

	$content = "code=$authorization_code&client_id=$this->client_id&client_secret=$this->client_secret&grant_type=authorization_code&redirect_uri=$this->callback_uri";
	$curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $this->token_url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $content
	));

    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

	$response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo curl_error($curl);
    }

	curl_close($curl);

    $decoded_response = json_decode($response);

	if (isset($decoded_response->error)) {
		echo "Error:<br />";
		echo $authorization_code;
		echo $response;
	}

	return array('access_token' => $decoded_response->access_token);
}

//	we can now use the access_token as much as we want to access protected resources
public function getResource($access_token) {
    // echo $access_token;
    $header = array("Accept: application/json", "Authorization: Bearer {$access_token}", "Content-Type: application/json");

    $content = json_encode(array("name"=>"2019 Panini Prizm Zion Williamson 51 Base PSA 8","handle"=>"2019-Panini-Prizm-Zion-Williamson-51-Base-PSA-8","sku"=>"10000011"));

    $curl = curl_init();
	curl_setopt_array($curl, array(
		CURLOPT_URL => $this->test_api_url,
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $content
	));
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	$response = curl_exec($curl);

    $response_arr = json_decode($response,true);
    // print_r($response_arr);
    curl_close($curl);
    $url = "https://".$this->domain_prefix.".vendhq.com/barcode/select_count?product_id=".$response_arr['data'][0];

    echo '<script>window.open('.$url.')</script>';
    // header("Location:"."https://".$this->domain_prefix.".vendhq.com/barcode/select_count?product_id=".$response_arr['data'][0]);
    exit;
}

public function testing_url($id)
{
$url = URL::full();

print_r($url);
// echo $url["fragment"];
}

}


?>
