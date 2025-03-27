<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MX_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->auth_model->checkIslogout();
		$this->module = "profile";
		$this->load->model("profile_model");
		$this->column   = array(
                            "no"=>array("text"=>"#","sorting"=>"false","width"=>4,"align"=>"right"),
                            "inserttime"=>array("text"=>"WAKTU","sorting"=>"true","width"=>13,"align"=>"center","type_sort"=>"desc"),
                            "title"=>array("text"=>"JUDUL PEMBERITAHUAN","sorting"=>"true","width"=>20,"align"=>"left"),
                            "notifikasi"=>array("text"=>"PEMBERITAHUAN","sorting"=>"true","width"=>45,"align"=>"left"),
                            "status"=>array("text"=>"DIBACA","sorting"=>"true","width"=>10,"align"=>"left"),
                            "aksi"=>array("text"=>"AKSI","sorting"=>"false","width"=>8,"align"=>"center"),
                        );
	}

	public function logout(){
    	$this->db->where("user_id",$this->session->userdata('user_id'))->delete("users_online");
        $this->session->sess_destroy();
        redirect(CI_LOGIN_PATH);
    }

    //profil
	public function ubahsandi(){
		if($this->input->is_ajax_request()){
			$config_input = array(
							array(
				                'field' => 'sandilama',
				                'label' => 'Katasandi Lama',
				                'rules' => 'trim|required|min_length[8]'
				        	),
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
			$response 				= $this->auth_model->setValidasiinput($config_input);
			if($response['status']=="success"){
				$pass 		= trim($this->input->post("sandilama"));
				$oldpass 	= $this->global_model->getDataonefield("password","users",array("user_id"=>$this->session->userdata("user_id")));
				if(md5($this->encryption->decrypt($oldpass))==md5($pass)){
					$response['data']	= $this->profile_model->updatePassword();
					$response['status']	= "success";
					$response['message']= "Berhasil memperbaharui katasandi";					
				}else{
					$response['status']	= "warning";
					$response['message']= "Katasandi anda yang lama salah";		
				}
			}
			echo json_encode($response);
		}else{
			show_404();
		}
	}

	public function ubahnama(){
		if($this->input->is_ajax_request()){
			$config_input = array(
							array(
				                'field' => 'nama',
				                'label' => 'Nama',
				                'rules' => 'trim|required'
				        	),
				        );
			$response 				= $this->auth_model->setValidasiinput($config_input);
			$response['class']		= "danger";
			if($response['status']=="success"){
				$response['data']	= $this->profile_model->updateName();
				$response['status']	= "success";
				$response['message']= "Berhasil mengganti nama anda";
			}
			echo json_encode($response);
		}else{
			show_404();
		}
	}

	public function ubahnotelp(){
		if($this->input->is_ajax_request()){
			$config_input = array(
							array(
				                'field' => 'notelp',
				                'label' => 'No Telp',
				                'rules' => 'trim|required'
				        	),
				        );
			$response 				= $this->auth_model->setValidasiinput($config_input);
			$response['class']		= "danger";
			if($response['status']=="success"){
				$userid 	= $this->session->userdata("user_id");
				if($this->global_model->getDataonefield("phone","users",array("phone"=>trim($this->input->post("notelp")),"user_id<>"=>$userid))!=null){
		            $data['class']		= "danger";
		            $response['data']	= $data;
					$response['status']	= "error";
		            $response["message"]= "No Handphone sudah digunakan oleh pengguna lain";
		        }else{
					$response['data']	= $this->profile_model->updatePhone();;
					$response['status']	= "success";
					$response['message']= "Berhasil mengganti No. HP anda";
				}
			}
			echo json_encode($response);
		}else{
			show_404();
		}
	}

	public function ubahemail(){
		if($this->input->is_ajax_request()){
			$config_input = array(
							array(
				                'field' => 'email',
				                'label' => 'Email',
				                'rules' => 'trim|required|valid_email'
				        	),
				        );
			$response 				= $this->auth_model->setValidasiinput($config_input);
			if($response['status']=="success"){
				$userid 	= $this->session->userdata("user_id");
				if($this->global_model->getDataonefield("email","users",array("email"=>trim($this->input->post("email")),"user_id<>"=>$userid))!=null){
					$data['class']			= "danger";
		            $response['data']		= $data;
					$response['status']		= "error";
					$response['message']	= "Email sudah digunakan oleh pengguna lain";
				}else{
					$response['data']	= $this->profile_model->updateEmail();
					$response['status']	= "success";
					$response['message']= "Berhasil mengganti email anda";
				}
			}
			echo json_encode($response);
		}else{
			show_404();
		}
	}

    public function ubahfoto(){
    	if($this->input->is_ajax_request()){
			$response 		= array();
	    	$userid 		= $this->session->userdata('user_id');
			$dataupdate		= array();
			$dir  			= "resources/profile";
			makeDirektori($dir);
	    	if(!empty($_FILES["newfoto"]["name"])){
				$filename 				= $_FILES["newfoto"]["name"];
				$config['upload_path']  = realpath($dir);
				$config['overwrite']    = FALSE;
				$config['remove_spaces']= TRUE;
				$config['max_size']     = 5000;
				$config['allowed_types']= 'png|jpg|jpeg';
				$config['encrypt_name'] = FALSE;
				$config['file_name'] 	= $filename;
				$this->load->library('upload', $config);
				$this->upload->initialize($config);
				if(!$this->upload->do_upload('newfoto')){
					$response["respon"] 	= "error";
					$response["message"]	= $this->upload->display_errors();
					echo json_encode($response);
					exit();
				}else{
					$file_info 			= $this->upload->data();
					$oldphoto			= $this->global_model->getDataonefield("image_profile","users",array("extend_image"=>"n","user_id"=>$userid));
					if($oldphoto!=""){
						@unlink("./".$oldphoto);
					}
					$this->db->where("user_id",$userid)->update("users",array("extend_image"=>"n","image_profile"=>$dir."/".$file_info["file_name"]));
					$data['foto']			= '<img src="'.base_url($dir."/".$file_info["file_name"]).'" width="100%">';
					$response['data']		= $data;
					$response['status'] 	= "success";
					$response['message']	= "Berhasil memperbaharui foto ";
					$this->global_model->insertLog("mengganti foto profil");
					$this->session->set_userdata("image_profile",base_url($dir."/".$file_info["file_name"]));
				}
			}else{
				$response['status'] 	= "info";
				$response['message']	= "Tidak ada foto yang diperbaharui";
			}
			echo json_encode($response);
		}else{
			show_404();
		}
	}

	public function deletefoto(){
		if($this->input->is_ajax_request()){
			$response 	= array();
	    	$userid 	= $this->session->userdata('user_id');
	    	$oldphoto	= $this->global_model->getDataonefield("image_profile","users",array("user_id"=>$userid));
			if($oldphoto!=null){
				@unlink("./".$oldphoto);
				$data['image']		= '<img src="'.base_url().'assets/img/noavatar.png" width="100%">';$response['data']	= $data;
				$response['data']	= $data;
				$response['status']	= "success";
				$response['message']= "Berhasil menghapus foto";
				$this->session->set_userdata("image_profile",base_url("assets/img/noavatar.png"));
				$this->db->where("user_id",$userid)->update("users",array("extend_image"=>"n","image_profile"=>""));
				$this->global_model->insertLog("menghapus foto profil");
			}else{
				$response['status']	= "error";
				$response['message']= "Foto profil anda tidak tersedia untuk dihapus";
			}
			echo json_encode($response);
		}else{
			show_404();
		}
	}

	public function panduan()
	{
		$this->db->select("g.guide_id,g.name,g.description")
				->from("guides g")
				->join("guides_groups as gg","gg.guide_id=g.guide_id")
				->join("users_groups ug","gg.group_id=ug.group_id")
				->where(array("g.status"=>"y","ug.user_id"=>$this->session->userdata('user_id')));
		$param['panduan']		= $this->db->get();
		$konten                 = 'profile/panduan';
        $this->auth_model->buildViewAdmin($konten,$param);
	}

	function connecttogoogle(){
		$param['configsite'] 	= $this->global_model->getConfigsite();
		$param['configcompany']	= $this->global_model->getConfigcompany();
		if($param['configsite']["isgoogle"]=="y" &&$param['configsite']["google_client_id"]!="" && $param['configsite']["google_client_secret"]!=""){
			$this->load->library('google',array("google_client_id"=>$param['configsite']["google_client_id"],"google_client_secret"=>$param['configsite']["google_client_secret"],"redirect_uri"=>site_url('profile/connecttogoogle'),"aplication_name"=>$param['configsite']['name']." ".$param['configcompany']["nama"]));
			$google_data 	= $this->google->validate();
			$datauser 		= $this->auth_model->getUserbyoauth($google_data["id"],'google');
			$userid 		= $this->session->userdata("user_id");
			if($datauser){
				if($datauser["user_id"]==$userid){
					$this->db->where("user_id",$userid)->update("users",array("extend_image"=>"y","image_profile"=>$google_data['profile_pic']));
					$this->session->set_userdata("image_profile",$google_data['profile_pic']);
					$this->db->where(array("user_id"=>$userid,"provider"=>"google"))->delete("oauth");
					$oauthdata 	= array(
									"user_id"=>$userid,
									"oauth_uid"=>$google_data["id"],
									"oauth_name"=>$google_data["name"],
									"oauth_email"=>$google_data["email"],
									"provider"=>"google",
									"created_on"=>date("Y-m-d H:i:s"),
								);
					$this->db->insert("oauth",$oauthdata);
					$this->session->set_flashdata("errormessage","Akun google anda sudah terhubung ke akun ini");
				}else{
					$this->session->set_flashdata("errormessage","Akun google anda sudah digunakan");
				}
				$this->session->set_flashdata("errorstyle","alert-danger");
			}else{
				$this->db->where("user_id",$userid)->update("users",array("extend_image"=>"y","image_profile"=>$google_data['profile_pic']));
				$this->session->set_userdata("image_profile",$google_data['profile_pic']);
				$this->db->where(array("user_id"=>$userid,"provider"=>"google"))->delete("oauth");
				$oauthdata 	= array(
								"user_id"=>$userid,
								"oauth_uid"=>$google_data["id"],
								"oauth_name"=>$google_data["name"],
								"oauth_email"=>$google_data["email"],
								"provider"=>"google",
								"picture_url"=>$google_data['profile_pic'],
								"created_on"=>date("Y-m-d H:i:s"),
							);
				$this->db->insert("oauth",$oauthdata);
				$this->session->set_flashdata("errorstyle","alert-success");
				$this->session->set_flashdata("errormessage","Akun google anda berhasil terhubung ke akun ini");
			}
			redirect('profile');
		}else{
			show_404();
		}
	}

	function connecttofacebook(){
		$param['configsite'] 	= $this->global_model->getConfigsite();
		if($param['configsite']["isfacebook"]=="y" && $param['configsite']["facebook_app_id"]!="" && $param['configsite']["facebook_app_secret"]!=""){
			$this->load->library('facebook',array("facebook_app_id"=>$param['configsite']["facebook_app_id"],"facebook_app_secret"=>$param['configsite']["facebook_app_secret"],"redirect_uri"=>site_url("profile/connecttofacebook")));
			$facebook_data 	= $this->facebook->validate(site_url("profile"));
			$datauser 		= $this->auth_model->getUserbyoauth($facebook_data["id"],'facebook');
			$userid 		= $this->session->userdata("user_id");
			if($datauser){
				if($datauser["user_id"]==$userid){
					$this->db->where("user_id",$userid)->update("users",array("extend_image"=>"y","image_profile"=>$facebook_data['picture']['url']));
					$this->session->set_userdata("image_profile",$facebook_data['picture']['url']);
					$this->db->where(array("user_id"=>$userid,"provider"=>"facebook"))->delete("oauth");
					$oauthdata 	= array(
									"user_id"=>$userid,
									"oauth_uid"=>$facebook_data["id"],
									"oauth_name"=>$facebook_data["name"],
									"oauth_email"=>$facebook_data["email"],
									"provider"=>"facebook",
									"created_on"=>date("Y-m-d H:i:s"),
								);
					$this->db->insert("oauth",$oauthdata);
					$this->session->set_flashdata("errormessage","Akun facebook anda sudah terhubung ke akun ini");
				}else{
					$this->session->set_flashdata("errormessage","Akun facebook anda sudah digunakan");
				}
				$this->session->set_flashdata("errorstyle","alert-danger");
			}else{
				$this->db->where("user_id",$userid)->update("users",array("extend_image"=>"y","image_profile"=>$facebook_data['picture']['url']));
				$this->session->set_userdata("image_profile",$facebook_data['picture']['url']);
				$this->db->where(array("user_id"=>$userid,"provider"=>"facebook"))->delete("oauth");
				$oauthdata 	= array(
								"user_id"=>$userid,
								"oauth_uid"=>$facebook_data["id"],
								"oauth_name"=>$facebook_data["name"],
								"oauth_email"=>$facebook_data["email"],
								"picture_url"=>$facebook_data['picture']['url'],
								"provider"=>"facebook",
								"created_on"=>date("Y-m-d H:i:s"),
							);
				$this->db->insert("oauth",$oauthdata);
				$this->session->set_flashdata("errorstyle","alert-success");
				$this->session->set_flashdata("errormessage","Akun facebook anda berhasil terhubung ke akun ini");
			}
			redirect('profile');
		}else{
			show_404();
		}
	}

	function unbind($param){
		if($param=="facebook" or $param=="google"){
			$userid 	= $this->session->userdata("user_id");
			$oauth_id 	= $this->global_model->getDataonefield("oauth_id","oauth",array("user_id"=>$userid,"provider"=>$param));
			if($oauth_id!=""){
				$this->db->where("oauth_id",$oauth_id)->delete("oauth");
				$this->session->set_flashdata("errorstyle","alert-success");
				$this->session->set_flashdata("errormessage","Berhasil memutuskan dengan akun ".$param." anda.");
			}else{
				$this->session->set_flashdata("errorstyle","alert-info");
				$this->session->set_flashdata("errormessage","Akun anda tidak terhubung ke akun ".$param." manapun.");
			}
			redirect('profile');
		}else{
			show_404();
		}
	}

	function datagridnotification(){
		$param['column']        = $this->column;
        $response               = $this->profile_model->getDatanotification($param);
        echo json_encode($response);
    }

	function notification()
	{
        $param['title']         = "Pemberitahuan";
        $param["actions"]       = array("grid"=>site_url($this->module.'/datagridnotification'));
        $param["column"]        = $this->column;
        $konten                 = 'notification';
        $this->auth_model->buildViewAdmin($konten,$param);
	}

	public function index()
	{
		$param['user'] 			= $this->auth_model->getUserfull(); 
		$param['actionprofile']	= site_url()."profile";
		$param['module']		= $this->module;
		$konten                 = 'profile';
		$configsite 			= $this->global_model->getConfigsite();
		$param["google_connect_url"]	= "";
		$param["namagoogle"]	= "";
		$param["namafacebook"]	= "";
		if($configsite["isgoogle"]=="y" && $configsite["google_client_id"]!="" && $configsite["google_client_secret"]!=""){
			$param["namagoogle"]= $this->global_model->getDataonefield("oauth_name","oauth",array("user_id"=>$this->session->userdata("user_id"),"provider"=>"google"));
			$configcompany		= $this->global_model->getConfigcompany();
			$this->load->library('google',array("google_client_id"=>$configsite["google_client_id"],"google_client_secret"=>$configsite["google_client_secret"],"redirect_uri"=>site_url('profile/connecttogoogle'),"aplication_name"=>$configsite['name']." ".$configcompany["nama"]));
			$param['google_connect_url'] = $this->google->get_login_url();
		}

		$param["facebook_connect_url"]	= "";
		if($configsite["isfacebook"]=="y" && $configsite["facebook_app_id"]!="" && $configsite["facebook_app_secret"]!=""){
			$param["namafacebook"]= $this->global_model->getDataonefield("oauth_name","oauth",array("user_id"=>$this->session->userdata("user_id"),"provider"=>"facebook"));
			$this->load->library('facebook',array("facebook_app_id"=>$configsite["facebook_app_id"],"facebook_app_secret"=>$configsite["facebook_app_secret"],"redirect_uri"=>site_url('profile/connecttofacebook')));
			$param['facebook_connect_url'] = $this->facebook->get_login_url();
		}
        $this->auth_model->buildViewAdmin($konten,$param);
	}
}
?>
