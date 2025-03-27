<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?php echo $configsite['title'];?></title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta content="Login | <?php echo $configsite['title'];?>" name="description" />
  <meta content="abdulionel" name="author" />
  <link href="<?php echo base_url($configsite['icon']);?>" rel="icon" type="image/png"/>
  <link href="<?php echo base_url()?>assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url()?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url()?>assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url()?>assets/css/abd.css" id="app-style" rel="stylesheet" type="text/css" />
  <script src="<?php echo base_url()?>assets/plugins/jquery/jquery.min.js"></script>
  <script type="text/javascript">
    var environment   = "<?php echo ENVIRONMENT?>";
  </script>
</head>
<body >
  <?php $this->load->view("properties/remove_iklan")?>
  <div class="account-pages-cover">
    <div class="account-pages-cover-image">
      <img src="<?php echo base_url($configsite['background'])?>">
    </div>
    <div class="account-pages-cover-bg"></div>
  </div>
  <div class="home-btn d-none d-sm-block">
    <a href="<?php echo site_url()?>" class="text-dark"><i class="fas fa-home h2"></i></a>
  </div>
  <div class="my-4 pt-4"></div>
  <div class="account-pages mb-4">
      <div class="container">
          <div class="row justify-content-center">
              <div class="col-md-8 col-lg-6 col-xl-5">
                  <div class="card overflow-hidden">

                      <div class="bg-primary">
                          <div class="text-primary text-center p-4">
                              <h5 class="text-white font-size-20"><?php echo strtoupper($configsite['name']) ?></h5>
                              <p class="text-white-50">Masuk ke akun anda</p>
                              <a href="<?php echo site_url(CI_LOGIN_PATH)?>" class="logo logo-admin">
                                  <img src="<?php echo base_url($configsite['logo'])?>" height="100%" alt="logo">
                              </a>
                          </div>
                      </div>

                      <div class="card-body p-4">
                          <div class="p-3">
                              <?php echo form_open(CI_LOGIN_PATH,array("class"=>"login-form form-horizontal mt-4 ","data-parsley-validate"=>"true","method"=>"post"));?>
                                  <div class="login-info">
                                    <?php echo $this->session->flashdata("errormessage")?>
                                  </div>
                                  <div class="form-group">
                                      <label for="username">Nama Pengguna</label>
                                      <?php echo form_input(array("id"=>"username","name"=>"username","value"=>set_value("username"),"class"=>"form-control","data-parsley-required"=>"true","placeholder"=>"Nama Pengguna","autocomplete"=>"off"));?>
                                  </div>

                                  <div class="form-group">
                                      <label for="userpassword">Katasandi</label>
                                      <?php echo form_password(array("id"=>"password","name"=>"password","class"=>"form-control","data-parsley-required"=>"true","placeholder"=>"Katasandi","autocomplete"=>"off"));?>
                                  </div>
                                  <?php if($configsite['iscaptcha']=="y"){?>
                                    <div class="form-group">
                                      <label for="captcha">Kode Keamanan</label>
                                      <div class="input-group  input-captcha" data-link="<?php echo site_url('index/reloadcaptcha')?>">
                                        <span class="input-group-btn input-group-prepend">
                                          <button class="btn btn-primary repeat-captcha" type="button"><i class="ti-reload "></i></button>
                                        </span>
                                        <span class="image-captcha "><?php echo $captcha; ?></span>
                                        <?php echo form_input(array("id"=>"captcha","data-parsley-errors-container"=>"#errcaptcha","name"=>"captcha","class"=>"form-control","data-parsley-required"=>"true","placeholder"=>"Kode Keamanan"));?>
                                      </div>
                                      <span id="errcaptcha"></span>
                                    </div>
                                  <?php }?>
                                  <div class="form-group row">
                                      <div class="col-sm-12 text-right">
                                        <?php echo form_button(array("type"=>"submit","class"=>"btn btn-primary w-md waves-effect waves-light","content"=>"Masuk"));?>
                                      </div>
                                  </div>
                              <?php echo form_close();?>
                              <?php if($google_login_url!="" or $facebook_login_url!=""){?>
                              <div class="form-group">
                                <p class="mt-0 mb-2 text-center login-desc">atau masuk dengan</p>
                                <div class="btn-group btn-block btn-group-justified">
                                <?php 
                                  if($google_login_url!=""){
                                    echo anchor($google_login_url,'Google',array("class"=>"btn btn-danger "));
                                  }
                                  if($facebook_login_url!=""){
                                    echo anchor($facebook_login_url,'Facebook',array("class"=>"btn btn-info "));
                                  }
                                ?>
                                </div>
                              </div>
                              <?php }?>
                              <?php if($configsite['isforget']=="y"){?>
                              <div class="form-group mb-0 row">
                                  <div class="col-12">
                                      <a href="<?php echo site_url(CI_RECOVERY_PATH) ?>"><i class="mdi mdi-lock"></i> Lupa katasandi anda?</a>
                                  </div>
                              </div>
                              <?php }?>
                              

                          </div>
                          <hr/>
                          <div class="text-center">
                              <p class="mb-0"><?php echo $configsite['copyright'] ?></p>
                          </div>
                      </div>

                  </div>



              </div>
          </div>
      </div>
  </div>
  <script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url()?>assets/plugins/parsleyjs/parsley.min.js"></script>
  <script src="<?php echo base_url()?>assets/js/abd.js"></script>
  <script src="<?php echo base_url()?>assets/js/login.js"></script>
</html>
