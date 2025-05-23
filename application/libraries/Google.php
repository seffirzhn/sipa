<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH."/third_party/Google/autoload.php";

class Google {
	protected $CI;

	public function __construct($param){
		$this->CI =& get_instance();
        $this->client = new Google_Client();
		$this->client->setClientId($param["google_client_id"]);
		$this->client->setClientSecret($param['google_client_secret']);
		$this->client->setRedirectUri($param['redirect_uri']);
		$this->client->setApplicationName($param['aplication_name']);
		$this->client->setScopes(array(
			"https://www.googleapis.com/auth/plus.login",
			"https://www.googleapis.com/auth/plus.me",
			"https://www.googleapis.com/auth/userinfo.email",
			"https://www.googleapis.com/auth/userinfo.profile"
			)
		);
  

	}


	public function get_login_url(){
		return  $this->client->createAuthUrl();
	}

	public function validate(){		
		if (isset($_GET['code'])) {
		  $this->client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $this->client->getAccessToken();
		}
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
		  	$this->client->setAccessToken($_SESSION['access_token']);
		  	$plus = new Google_Service_Plus($this->client);
		  	$person = $plus->people->get('me');
			$info['id']=$person['id'];
			$info['email']=$person['emails'][0]['value'];
			$info['name']=$person['displayName'];
			$info['link']=$person['url'];
			$info['profile_pic']=$person['image']['url']."?sz=200";

		   return  $info;
		}


	}

}