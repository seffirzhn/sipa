<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recoverypwd extends MX_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogin();
	}

	public function index()
	{
		$this->load->library('cicaptcha');
		$param['configsite']= $this->global_model->getConfigsite();
		$param['configsite']['iscaptcha'] = "y";
		if ($param['configsite']['isforget']=="y"){
			$user 				= trim($this->input->post("username"));
			if($this->input->is_ajax_request()){
				$config_input = array(
						array(
			                'field' => 'username',
			                'label' => 'Username',
			                'rules' => 'trim|required'
			        	),
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
					if($param['configsite']['iscaptcha']=="y" && $this->cicaptcha->validate(trim($this->input->post("captcha")))==false){
						$data["repeatcaptcha"]	= true;
						$response["data"]		= $data;
						$response['message']	= "<div class='alert alert-danger'>Kode Keamanan Salah</div>";
						$response['status'] 	= "warning";
					}else{
						$datauser 	= $this->auth_model->getUserbyusername();
						if($datauser){
							if($datauser['user_active']=="1"){	
								$data["linkredirect"]	= site_url(CI_RECOVERY_PATH.'/switchmethod');
								$response["data"]		= $data;
								$response['message']		= "<div class='alert alert-success'>Permintaan berhasil. Mengalihkan...</div>";
								$response['status']		= "success";
								$this->session->set_userdata("user",$datauser["user"]);
							}else{
								$data["repeatcaptcha"]	= true;
								$response["data"]		= $data;
								$response['message']	= "<div class='alert alert-info'>Pengguna dinonaktifkan oleh admin</div>";
								$response['status'] 	= "warning";
							}
						}else{
							$data["repeatcaptcha"]	= true;
							$response["data"]		= $data;
							$response['message']	= "<div class='alert alert-danger'>Nama pengguna / email salah</div>";
							$response['status'] 	= "warning";
						}
					}
				}else{
					$response['message']	= "<div class='alert alert-danger'>".$response['message']."</div>";
				}
				echo json_encode($response);
			}else{
				if($user){
					$config_input = array(
						array(
			                'field' => 'username',
			                'label' => 'Username',
			                'rules' => 'trim|required'
			        	),
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
						if($param['configsite']['iscaptcha']=="y" && $this->cicaptcha->validate(trim($this->input->post("captcha")))==false){
							$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Kode keamanan salah</div>");
						}else{
							$datauser 	= $this->auth_model->getUserbyusername();
							if($datauser){
								if($datauser['user_active']=="1"){	
									$this->session->set_userdata("user",$datauser["user"]);
									redirect(CI_RECOVERY_PATH.'/switchmethod');
								}else{
									$this->session->set_flashdata("errormessage","<div class='alert alert-info'>Pengguna dinonaktifkan oleh admin</div>");
								}
							}else{
								$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Nama pengguna / email salah</div>");
							}
						}
					}else{
						$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>".$response['message']."</div>");
					}
				}
				$param['captcha'] = "";
				if($param['configsite']['iscaptcha']=="y"){
					$param['captcha'] 	= $this->cicaptcha->show();
				}
				$this->load->view('recovery'.CI_LAYOUT_VIEW,$param);
			}
		}else{
			show_404();
		}
	}

	function switchmethod(){
		$param['configsite']= $this->global_model->getConfigsite();
		if ($param['configsite']['isforget']=="y"){
			if($this->session->userdata("user")){
				$user 			= $this->global_model->getDataonerow("*","users",array("user_id"=>$this->session->userdata("user")));
				$switch 		= trim($this->input->post("switchmethod"));
				if($this->input->is_ajax_request()){
					if($switch=="phone"){
						if($user["phone"]!=""){
							$this->session->set_userdata("switchmethod","phone");
							$data["linkredirect"]	= site_url(CI_RECOVERY_PATH.'/phone');
							$response["data"]		= $data;
							$response['message']	= "<div class='alert alert-success'>Permintaan berhasil. Mengalihkan...</div>";
							$response['status'] 	= "success";
						}else{
							$response['message']	= "<div class='alert alert-danger'>No handphone belum tersimpan diakun anda</div>";
							$response['status'] 	= "warning";
						}
					}else if($switch=="email"){
						if($user["email"]!=""){
							$this->session->set_userdata("switchmethod","email");
							$data["linkredirect"]	= site_url(CI_RECOVERY_PATH.'/email');
							$response["data"]		= $data;
							$response['message']	= "<div class='alert alert-success'>Permintaaan berhasil. Mengalihkan...</div>";
							$response['status'] 	= "success";
						}else{
							$response['message']	= "<div class='alert alert-danger'>Email belum tersimpan diakun anda</div>";
							$response['status'] 	= "warning";
						}
					}else{
						$response['message']	= "<div class='alert alert-danger'>Gagal pilih metode</div>";
						$response['status'] 	= "warning";
					}
					echo json_encode($response);
				}else{
					if($switch=="phone"){
						if($user["phone"]!=""){
							$this->session->set_userdata("switchmethod","phone");
							redirect(CI_RECOVERY_PATH.'/phone');
						}else{
							$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>No handphone belum tersimpan diakun anda</div>");
						}
					}else if($switch=="email"){
						if($user["email"]!=""){
							$this->session->set_userdata("switchmethod","email");
							redirect(CI_RECOVERY_PATH.'/email');
						}else{
							$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Email belum tersimpan diakun anda</div>");
						}
					}
					$param["user"]		= $user;
					$this->load->view('switch'.CI_LAYOUT_VIEW,$param);
				}
			}else{
				if($this->input->is_ajax_request()){
					$response['message']	= "<div class='alert alert-info'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
					$response['status'] 	= "warning";
					echo json_encode($response);
				}else{
					$this->session->set_flashdata("errormessage","<div class='alert alert-info'>Sesi anda telah habis</div>");
					redirect(CI_RECOVERY_PATH);
				}
			}	
		}else{
			show_404();
		}
	}

	function email(){
		$param['configsite']= $this->global_model->getConfigsite();
		if ($param['configsite']['isforget']=="y"){
			if($this->session->userdata("user")){
				if($this->session->userdata("switchmethod")=="email"){
					$user 			= $this->global_model->getDataonerow("*","users",array("user_id"=>$this->session->userdata("user")));
					$param["user"]		= $user;
					$param['email']		= privateEmail($user['email']);
					$param['hostemail']	= explode("@",$user['email']);
					$this->load->view('emailverify'.CI_LAYOUT_VIEW,$param);
				}else{
					if($this->input->is_ajax_request()){
						$response['message']	= "<div class='alert alert-danger'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
						$response['status'] 	= "warning";
						echo json_encode($response);
					}else{
						redirect(CI_RECOVERY_PATH.'/switchmethod');
					}
				}
			}else{
				if($this->input->is_ajax_request()){
					$response['message']	= "<div class='alert alert-danger'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
					$response['status'] 	= "warning";
					echo json_encode($response);
				}else{
					$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Sesi anda telah habis</div>");
					redirect(CI_RECOVERY_PATH);
				}
			}	
		}else{
			show_404();
		}
	}

	function phone(){
		$param['configsite']= $this->global_model->getConfigsite();
		if ($param['configsite']['isforget']=="y"){
			if($this->session->userdata("user")){
				if($this->session->userdata("switchmethod")=="phone"){
					$kodeverifikasi 	= trim($this->input->post("kodeverifikasi"));
					if($kodeverifikasi){
						$token 		= $this->global_model->getDataonefield("tokensms","password_recovery",array("user_id"=>$this->session->userdata("user"),"tokensms"=>$kodeverifikasi));
						if($this->input->is_ajax_request()){
							if($token!=""){
								$data["linkredirect"]	= site_url(CI_RECOVERY_PATH.'/resetpassword')."?token=".urlencode($this->encryption->encrypt($token));
								$response["data"]		= $data;
								$response['message']	= "<div class='alert alert-success'>Verifikasi berhasil. Mengalihkan...</div>";
								$response['status'] 	= "success";
							}else{
								$response['message']	= "<div class='alert alert-danger'>Kode Verifikasi salah</div>";
								$response['status'] 	= "error";
							}
							echo json_encode($response);
						}else{
							if($token!=""){
								redirect(site_url(CI_RECOVERY_PATH.'/resetpassword')."?token=".urlencode($this->encryption->encrypt($token)));
							}else{
								$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Kode Verifikasi salah</div>");
							}
						}
					}
					if(!$this->input->is_ajax_request()){
						$user 				= $this->global_model->getDataonerow("*","users",array("user_id"=>$this->session->userdata("user")));
						$param["user"]		= $user;
						$param['phone']		= privateText($user['phone']);
						$this->load->view('phoneverify'.CI_LAYOUT_VIEW,$param);
					}
				}else{
					if($this->input->is_ajax_request()){
						$response['message']	= "<div class='alert alert-danger'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
						$response['status'] 	= "warning";
						echo json_encode($response);
					}else{
						redirect(CI_RECOVERY_PATH.'/switchmethod');
					}
				}
			}else{
				if($this->input->is_ajax_request()){
					$response['message']	= "<div class='alert alert-danger'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
					$response['status'] 	= "warning";
					echo json_encode($response);
				}else{
					$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Sesi anda telah habis</div>");
					redirect(CI_RECOVERY_PATH);
				}
			}	
		}else{
			show_404();
		}
	}

	function sendverifyemail(){
		$param['configsite']= $this->global_model->getConfigsite();

		if ($param['configsite']['isforget']=="y"){
			
			if($this->session->userdata("user") && $this->input->is_ajax_request()){
				
				if($this->session->userdata("switchmethod")=="email" && $this->input->is_ajax_request()){
					$user 			= $this->global_model->getDataonerow("*","users",array("user_id"=>$this->session->userdata("user")));
					$time 			= strtotime(date("Y-m-d H:i:s"));
					$timecheck 		= $time - 10800;
					$this->db->where("timesemail<".$timecheck,null,false)->delete("password_recovery");
					$token 			= $this->global_model->getDataonefield("tokenemail","password_recovery",array("user_id"=>$user['user_id']));
					if($token==""){
						$token 			= $this->global_model->getRandomkey(array("password_recovery"=>"tokenemail"),10);
						$this->db->insert("password_recovery",array("tokenemail"=>$token,"user_id"=>$user['user_id'],"timesemail"=>$time));
					}
					$token 			= $this->encryption->encrypt($token);
					$instansi 		= $this->global_model->getConfigcompany("nama");
					$link 			= anchor(site_url(CI_RECOVERY_PATH.'/resetpassword').'?token='.urlencode($token),site_url(CI_RECOVERY_PATH.'/resetpassword').'?token='.urlencode($token),array("target"=>"_new"));
					$configmail 	= $this->global_model->getDataonerow("REPLACE(REPLACE(REPLACE(REPLACE(pesan,'[nama_penerima]','".$user['name']."'),'[nama_aplikasi]','".$param['configsite']['name']."'),'[link_ganti]','".$link."'),'[nama_instansi]','".$instansi."') as message,nama as subject,'".$user["email"]."' as destination","template_mail",array("template_mail_id"=>"1"));

					$resultemail 	= $this->global_model->insertMail($configmail);
					if(trim($this->input->post("param"))=="1"){
						if($resultemail["status"]=="success"){
							$response['status'] 	= "success";
							$response['message']	= "<div class='alert alert-success'>Berhasil mengirim ulang</div>";
						}else{
							$response['status'] 	= "error";
							$response['message']	= "<div class='alert alert-danger'>Email tidak terkirim</div>";
						}
					}else{
						if($resultemail["status"]=="success"){
							$response['status'] 	= "success";
							$response['message']	= "<div class='alert'>&nbsp;</div>";
						}else{
							$response['status'] 	= "error";
							$response['message']	= "<div class='alert alert-danger'>Email tidak terkirim</div>";
						}
					}
					
					echo json_encode($response);
				}else{
					redirect(CI_RECOVERY_PATH.'/switchmethod');
				}
			}else{
				if($this->input->is_ajax_request()){
					$response['message']	= "<div class='alert alert-danger'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
					$response['status'] 	= "warning";
					echo json_encode($response);
				}else{
					$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Sesi anda telah habis</div>");
					redirect(CI_RECOVERY_PATH);
				}
			}	
		}else{
			if($this->input->is_ajax_request()){
				$this->output->set_status_header("404");
			}else{
				show_404();
			}
		}
	}

	function sendverifyphone(){
		$param['configsite']= $this->global_model->getConfigsite();
		if ($param['configsite']['isforget']=="y"){
			if($this->session->userdata("user") && $this->input->is_ajax_request()){
				if($this->session->userdata("switchmethod")=="phone" && $this->input->is_ajax_request()){
					$user 			= $this->global_model->getDataonerow("*","users",array("user_id"=>$this->session->userdata("user")));
					$time 			= strtotime(date("Y-m-d H:i:s"));
					$timecheck 		= $time - 200;
					$this->db->where("timessms<".$timecheck,null,false)->delete("password_recovery");
					$token 			= $this->global_model->getDataonefield("tokensms","password_recovery",array("user_id"=>$user['user_id']));
					if($token==""){
						$token 			= $this->global_model->getRandomkey(array("password_recovery"=>"tokensms"),6,"numeric");
						$this->db->insert("password_recovery",array("tokensms"=>$token,"user_id"=>$user['user_id'],"timessms"=>$time));
					}
					$instansi 		= $this->global_model->getConfigcompany("nama");
					$configmail 	= $this->global_model->getDataonerow("REPLACE(REPLACE(REPLACE(pesan,'[nama_aplikasi]','".$param['configsite']['name']."'),'[kode_token]','".$token."'),'[nama_instansi]','".$instansi."') as message,'".$user["phone"]."' as destination","template_sms",array("template_sms_id"=>"1"));
					$resultsms 		= $this->global_model->insertSms($configmail);
					if(trim($this->input->post("param"))=="1"){
						if(!empty($resultsms["response"][0]) and ($resultsms["response"][0]["status"]=="sent" or $resultsms["response"][0]["status"]=="pending")){
							$response["status"]	= "success";
							$response['message']= "<div class='alert alert-success'>Sms telah terkirim</div>";
						}else{
							$response["status"]	= "error";
							$response['message']= "<div class='alert alert-danger'>Sms gagal dikirim</div>";
						}
					}else{
						if(!empty($resultsms["response"][0]) and ($resultsms["response"][0]["status"]=="sent" or $resultsms["response"][0]["status"]=="pending")){
							$response["status"]	= "success";
							$response['message']= "<div class='alert'>&nbsp;</div>";
						}else{
							$response["status"]	= "error";
							$response['message']= "<div class='alert alert-danger'>Sms gagal dikirim</div>";
						}
					}
					echo json_encode($response);
				}else{
					redirect(CI_RECOVERY_PATH.'/switchmethod');
				}
			}else{
				if($this->input->is_ajax_request()){
					$response['message']= "<div class='alert alert-danger'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
					$response['status'] = "warning";
					echo json_encode($response);
				}else{
					$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Sesi anda telah habis</div>");
					redirect(CI_RECOVERY_PATH);
				}
			}	
		}else{
			if($this->input->is_ajax_request()){
				$this->output->set_status_header("404");
			}else{
				show_404();
			}
		}
	}

	function resetpassword(){
		$token = trim($this->input->get("token"));
		$param['configsite']= $this->global_model->getConfigsite();
		if ($param['configsite']['isforget']=="y"){
			$token  = $this->encryption->decrypt($token);
			if($token!=""){
				$user_id = $this->global_model->getDataonefield("user_id","password_recovery","tokenemail='".$token."' or tokensms='".$token."'");
				if($user_id){
					$sandibaru 		= trim($this->input->post("sandibaru"));
					$confirmsandi	= trim($this->input->post("konfirmasisandibaru"));
					if($this->input->is_ajax_request()){
						$config_input = array(
								array(
					                'field' => 'sandibaru',
					                'label' => 'Katasandi Baru',
					                'rules' => 'trim|required|min_length[8]'
					        	),
					        	array(
					                'field' => 'konfirmasisandibaru',
					                'label' => 'Konfirmasi Katasandi',
					                'rules' => 'trim|min_length[8]|matches[sandibaru]'
					        	),
					        );
						$response 	= $this->auth_model->setValidasiinput($config_input);
						if($response['status']=="success"){
							$this->auth_model->updatePassword($user_id);
							$data['linkredirect']	= site_url(CI_LOGIN_PATH);
							$response['data']		= $data;
							$response['message']	= "<div class='alert alert-success'>Katasandi berhasil diperbarui</div>";
							$response['status']		= "success";
							$this->db->where("user_id",$user_id)->delete("password_recovery");
							$this->session->sess_destroy();
						}else{
							$response['message']	= "<div class='alert alert-danger'>".$response['message']."</div>";
						}
						echo json_encode($response);
					}else{					
						if($sandibaru && $confirmsandi){
							$config_input = array(
								array(
					                'field' => 'sandibaru',
					                'label' => 'Katasandi Baru',
					                'rules' => 'trim|required|min_length[8]'
					        	),
					        	array(
					                'field' => 'konfirmasisandibaru',
					                'label' => 'Konfirmasi Katasandi',
					                'rules' => 'trim|min_length[8]|matches[sandibaru]'
					        	),
					        );
					        $response 	= $this->auth_model->setValidasiinput($config_input);
							if($response['status']=="success"){
								$this->auth_model->updatePassword($user_id);
								$this->db->where("user_id",$user_id)->delete("password_recovery");
								$this->session->set_flashdata("errormessage","<div class='alert alert-success'>Katasandi berhasil diperbarui</div>");
								redirect(CI_LOGIN_PATH);
							}else{
								$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>".$response['message']."</div>");
							}
						}

						$param['user'] 		= $this->global_model->getDataonerow("*","users",array("user_id"=>$user_id));
						$this->load->view('resetpass'.CI_LAYOUT_VIEW,$param);
					}
				}else{
					if($this->input->is_ajax_request()){
						$response['message']	= "<div class='alert alert-danger'>Token salah</div>";
						$response['status'] 	= "warning";
						echo json_encode($response);
					}else{
						$this->session->set_flashdata("errormessage","<div class='alert alert-danger'>Token salah / sudah kadaluarsa</div>");
						redirect(CI_RECOVERY_PATH);
					}
				}
			}else{
				if($this->input->is_ajax_request()){
					$response['message']	= "<div class='alert alert-danger'>Sesi anda telah habis, silahkan kembali ke halaman login</div>";
					$response['status'] 	= "warning";
					echo json_encode($response);
				}else{
					redirect(CI_RECOVERY_PATH);
				}
			}
		}else{
			if($this->input->is_ajax_request()){
				$this->output->set_status_header("404");
			}else{
				show_404();
			}
		}
	}
}
?>
