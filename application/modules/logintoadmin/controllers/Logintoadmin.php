<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logintoadmin extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogin();
		$this->load->library('cicaptcha');
	}

	public function index()
	{
		$user 				= trim($this->input->post("username"));
		$pass 				= trim($this->input->post("password"));
		$param['configsite']= $this->global_model->getConfigsite();
		if ($this->input->is_ajax_request()){
			$config_input = array(
					array(
		                'field' => 'username',
		                'label' => 'Username',
		                'rules' => 'trim|required'
		        	),
					array(
		                'field' => 'password',
		                'label' => 'Password',
		                'rules' => 'trim|required'
		        	)
		        );
			if($param['configsite']['iscaptcha']=="y"){
				$config_input[] = array(
					                'field' => 'captcha',
					                'label' => 'Captcha',
					                'rules' => 'trim|required'
					        	);
			}
			$response 	= $this->auth_model->setValidasiinput($config_input);
			if($response['status']=="success"){
				if($this->session->userdata("timelock")<time()){
					$this->session->unset_userdata("timelock");
				}
				if($this->session->userdata("timelock") && $this->session->userdata("timelock")>=time() && $param['configsite']["timelock"]>0){
					$data["repeatcaptcha"]	= true;
					$response["data"]		= $data;
					$response['status'] 	= "warning";
					$response['message']	= "<div class='alert alert-danger'>Sistem terkunci hingga ".date("H:i:s",$this->session->userdata("timelock"))."</div>";
					$this->session->unset_userdata("trylogin");
				}else{
					if($param['configsite']['iscaptcha']=="y" && $this->cicaptcha->validate(trim($this->input->post("captcha")))==false){
						$data["repeatcaptcha"]		= true;
						$response["data"]		 	= $data;
						$response['message']		= "<div class='alert alert-danger'>Kode keamanan salah</div>";
						$response['status'] 		= "warning";
					}else{
						if(!$this->session->userdata("trylogin")){
							$this->session->set_userdata("trylogin",0);
						}
						$maxallowed 	= $param['configsite']["trylogin"];
						$trylogin 	= $this->session->userdata("trylogin");
						if($trylogin<($maxallowed)-1 or $maxallowed==0){
							$datauser 	= $this->auth_model->getUser();
							if($datauser){
								if($datauser['user_active']=="1"){	
									$timecheck  = date("Y-m-d H:i:s",strtotime("-5minutes"));
									$lastlogin 	= $this->global_model->getDataonefield("times","users_online",array("user_id"=>$datauser['user_id'],"times>="=>$timecheck));
									if($param['configsite']['issinglesession']=="y" and $lastlogin!=null){
										$response['message']	 	= "<div class=' alert alert-info'>Sesi masih aktif di device lain. Terakhir aktif pukul ".$lastlogin."</div>";
										$response['status'] 		= "warning";
										$data["repeatcaptcha"]		= true;
										$response["data"]		 	= $data;
									}else{
										$response['status']		= "success";
										$response['message'] 	= "<div class='alert alert-success'>Login berhasil. Mengalihkan...</div>";
										$data["linkredirect"]	= site_url(CI_LOGIN_PATH);
										$response["data"]		= $data;
										$this->session->set_userdata($datauser);
										$this->session->unset_userdata(array("timelock","trylogin"));
									}
								}else{
									$trylogin=$trylogin+1;
									$this->session->set_userdata("trylogin",$trylogin);
									$data["repeatcaptcha"]		= true;
									$response["data"]		 	= $data;
									$response['message']		= "<div class='alert alert-info'>Pengguna dinonaktifkan oleh admin</div>";
									$response['status'] 		= "warning";
								}
							}else{
								$trylogin=$trylogin+1;
								$this->session->set_userdata("trylogin",$trylogin);
								$data["repeatcaptcha"]		= true;
								$response["data"]		 	= $data;
								$response['message']		= "<div class='alert alert-danger'>Login tidak valid</div>";
								$response['status'] 		= "warning";
							}
						}else{
							$this->session->unset_userdata("trylogin");
							$response['status'] 	= "warning";
							if($param['configsite']["timelock"]>0){
								$this->session->set_userdata("timelock",strtotime("+".$param['configsite']["timelock"]."second"));
								$data["repeatcaptcha"]		= true;
								$response["data"]		 	= $data;
								$response['message']		= "<div class='alert alert-danger'>".$maxallowed." kali salah. Sistem terkunci hingga ".date("H:i:s",$this->session->userdata("timelock"))."</div>";
							}else{
								$data["repeatcaptcha"]		= true;
								$response["data"]		 	= $data;
								$response['message']		= "<div class='alert alert-danger'>".$maxallowed." kali salah. Silahkan refresh halaman <a href='".site_url(CI_LOGIN_PATH)."'>disini</a></div>";								
							}
						}
					}
				}
			}else{
				$response['message']	= "<div class='alert alert-danger'>".$response['message']."</div>";
			}
			echo json_encode($response);
		}else{
			$param['captcha'] = "";
			if($user && $pass){
				$config_input = array(
					array(
		                'field' => 'username',
		                'label' => 'Username',
		                'rules' => 'trim|required'
		        	),
					array(
		                'field' => 'password',
		                'label' => 'Password',
		                'rules' => 'trim|required'
		        	)
		        );
				if($param['configsite']['iscaptcha']=="y"){
					$config_input[] = array(
						                'field' => 'captcha',
						                'label' => 'Captcha',
						                'rules' => 'trim|required'
						        	);
				}
				$response 	= $this->auth_model->setValidasiinput($config_input);
				if($response['status']=="success"){
					if($this->session->userdata("timelock")<time()){
						$this->session->unset_userdata("timelock");
					}
					if($this->session->userdata("timelock") && $this->session->userdata("timelock")>=time() && $param['configsite']["timelock"]>0){
						$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Sistem terkunci hingga ".date("H:i:s",$this->session->userdata("timelock"))."</div>");
						$this->session->unset_userdata("trylogin");
					}else{
						if($param['configsite']['iscaptcha']=="y" && $this->cicaptcha->validate(trim($this->input->post("captcha")))==false){
							$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Kode keamanan salah</div>");
						}else{
							if(!$this->session->userdata("trylogin")){
								$this->session->set_userdata("trylogin",0);
							}
							$maxallowed 	= $param['configsite']["trylogin"];
							$trylogin 	= $this->session->userdata("trylogin");
							if($trylogin<($maxallowed)-1 or $maxallowed==0){
								$datauser 	= $this->auth_model->getUser();
								if($datauser){
									if($datauser['user_active']=="1"){	
										$timecheck  = date("Y-m-d H:i:s",strtotime("-5minutes"));
										$lastlogin 	= $this->global_model->getDataonefield("times","users_online",array("user_id"=>$datauser['user_id'],"times>="=>$timecheck));
										if($param['configsite']['issinglesession']=="y" and $lastlogin!=null){
											$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Sesi masih aktif di device lain. Terakhir aktif pukul ".$lastlogin."</div>");
										}else{
											$this->session->set_userdata($datauser);
											$this->session->unset_userdata(array("timelock","trylogin"));
											redirect(CI_LOGIN_PATH);
										}
									}else{
										$trylogin=$trylogin+1;
										$this->session->set_userdata("trylogin",$trylogin);
										$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Pengguna dinonaktifkan oleh admin</div>");
									}
								}else{
									$trylogin=$trylogin+1;
									$this->session->set_userdata("trylogin",$trylogin);
									$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Login tidak valid</div>");
								}
							}else{
								$this->session->unset_userdata("trylogin");
								if($param['configsite']["timelock"]>0){
									$this->session->set_userdata("timelock",strtotime("+".$param['configsite']["timelock"]."second"));
									$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>".$maxallowed." kali salah. Sistem terkunci hingga ".date("H:i:s",$this->session->userdata("timelock"))."</div>");
								}
							}
						}
					}
				}else{
					$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>".$response['message']."</div>");
				}
			}
			if($param['configsite']['iscaptcha']=="y"){
				$param['captcha'] 	= $this->cicaptcha->show();
			}
			if($param['configsite']["timelock"]==0){
				$this->session->unset_userdata("trylogin");
			}
			if($param['configsite']['iscaptcha']=="y"){
				$param["paddingbottom"]  = "p-b-10";
				if($param['configsite']['isforget']=="y"){
					$param["paddingbottom"]  = "p-b-0";
				}
			}else{
				$param["paddingbottom"]  = "p-b-40";
				if($param['configsite']['isforget']=="y"){
					$param["paddingbottom"]  = "p-b-20";
				}
			}
			$param['google_login_url'] 		= "";
			if($param['configsite']["isgoogle"]=="y" && $param['configsite']["google_client_id"]!="" && $param['configsite']["google_client_secret"]!=""){
				$param['configcompany']		= $this->global_model->getConfigcompany();
				$this->load->library('google',array("google_client_id"=>$param['configsite']["google_client_id"],"google_client_secret"=>$param['configsite']["google_client_secret"],"redirect_uri"=>site_url(CI_OAUTH_GOOGLE),"aplication_name"=>$param['configsite']['name']." ".$param['configcompany']["nama"]));
				$param['google_login_url'] 	= $this->google->get_login_url();
			}

			$param['facebook_login_url'] 	= "";
			if($param['configsite']["isfacebook"]=="y" && $param['configsite']["facebook_app_id"]!="" && $param['configsite']["facebook_app_secret"]!=""){
				$this->load->library('facebook',array("facebook_app_id"=>$param['configsite']["facebook_app_id"],"facebook_app_secret"=>$param['configsite']["facebook_app_secret"],"redirect_uri"=>site_url(CI_OAUTH_FACEBOOK)));
				$param['facebook_login_url']= $this->facebook->get_login_url();
			}
			$this->load->view('login'.CI_LAYOUT_VIEW,$param);
		}
	}

	function google(){
		$param['configsite'] 	= $this->global_model->getConfigsite();
		$param['configcompany']	= $this->global_model->getConfigcompany();
		if($param['configsite']["isgoogle"]=="y" && $param['configsite']["google_client_id"]!="" && $param['configsite']["google_client_secret"]!=""){
			$this->load->library('google',array("google_client_id"=>$param['configsite']["google_client_id"],"google_client_secret"=>$param['configsite']["google_client_secret"],"redirect_uri"=>site_url(CI_OAUTH_GOOGLE),"aplication_name"=>$param['configsite']['name']." ".$param['configcompany']["nama"]));
			$google_data 	= $this->google->validate();
			$datauser 		= $this->auth_model->getUserbyoauth($google_data["id"],"google");
			if($datauser){
				$this->session->set_userdata($datauser);
				$this->session->unset_userdata(array("timelock","trylogin"));
			}else{
				$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Akun anda belum terhubung ke google</div>");
			}
			redirect(CI_LOGIN_PATH);
		}else{
			show_404();
		}
	}

	function facebook(){
		$param['configsite'] 	= $this->global_model->getConfigsite();
		if($param['configsite']["isfacebook"]=="y" && $param['configsite']["facebook_app_id"]!="" && $param['configsite']["facebook_app_secret"]!=""){
			$this->load->library('facebook',array("facebook_app_id"=>$param['configsite']["facebook_app_id"],"facebook_app_secret"=>$param['configsite']["facebook_app_secret"],"redirect_uri"=>site_url(CI_OAUTH_FACEBOOK)));
			$facebook_data 	= $this->facebook->validate();
			$datauser 		= $this->auth_model->getUserbyoauth($facebook_data["id"],"facebook");
			if($datauser){
				$this->session->set_userdata($datauser);
				$this->session->unset_userdata(array("timelock","trylogin"));
			}else{
				$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Akun anda belum terhubung ke facebook</div>");
			}
			redirect(CI_LOGIN_PATH);
		}else{
			show_404();
		}
	}
}
?>
