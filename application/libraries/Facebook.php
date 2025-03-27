<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

include_once APPPATH."/third_party/Facebook/autoload.php";
/**
 * Facebook library for CodeIgniter with Ion auth.
 *
 * @author Benoit VRIGNAUD <benoit.vrignaud@zaclys.net>
 *
 */
class Facebook
{
    /** @var CI_Controller */
    private $ci;
    
    private $redirect_uri;
    /** @var string */
    private $accessToken;

    /** @var Facebook\Helpers\FacebookRedirectLoginHelper */
    private $helper;


	public function __construct($param)
	{
		$this->ci =& get_instance();
		$this->redirect_uri = $param["redirect_uri"];
		$this->fb = new \Facebook\Facebook([
			'app_id'                => $param["facebook_app_id"],
			'app_secret'            => $param["facebook_app_secret"],
			'default_graph_version' => "v3.2",
		]);
		$this->helper = $this->fb->getRedirectLoginHelper();	
		
	}


	/**
	 * Generates the facebook connection link
	 * @param string $redirectUrl redirection url (eg: 'auth/fb_callback')
	 * @return string
	 */
	public function get_login_url()
	{
	    $scope  = array("email");
	    return $this->helper->getLoginUrl($this->redirect_uri, $scope);
	}

	public function validate($link=null){
	    
	   	$accessToken = @$this->helper->getAccessToken(); //short lived token
    	
			
	    // The OAuth 2.0 client handler helps us manage access tokens
	    if($accessToken){
    	    if (!$accessToken->isLongLived()) {
    	        $oAuth2Client 	= @$this->fb->getOAuth2Client();
    	        $accessToken 	= @$oAuth2Client->getLongLivedAccessToken($accessToken);
    	    }
	    }else{
	        $this->ci->session->set_flashdata("errorstyle","alert-danger");
			$this->ci->session->set_flashdata("errormessage",$_GET['error_message']);
			if($link==null){
	        	redirect(CI_LOGIN_PATH);
	        }else{
	        	redirect($link);
	        }
	    }
        
	    $response 		= @$this->fb->get("me?fields=id,name,first_name,last_name,email,picture.type(large)", $accessToken);
	    
	    $user           = $response->getGraphNode()->asArray();
 	    return $user;

	}
}
