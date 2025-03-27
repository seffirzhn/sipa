<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
  <meta charset="utf-8" />
  <title>Recovery | <?php echo $configsite['title'];?></title>
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <meta content="Recovery | <?php echo $configsite['title'];?>" name="description" />
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
<body>
  <?php $this->load->view("properties/remove_iklan")?>
  <div class="account-pages-cover">
    <div class="account-pages-cover-image">
      <img src="<?php echo base_url($configsite['background'])?>">
    </div>
    <div class="account-pages-cover-bg"></div>
  </div>
  <div class="home-btn d-none d-sm-block">
    <a href="<?php echo site_url(CI_LOGIN_PATH)?>" class="text-dark"><i class="fas fa-home h2"></i></a>
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
                              <p class="text-white-50">Pemulihan Katasandi</p>
                              <a href="<?php echo site_url(CI_LOGIN_PATH)?>" class="logo logo-admin">
                                  <img src="<?php echo base_url($configsite['logo'])?>" height="100%" alt="logo">
                              </a>
                          </div>
                      </div>

                      <div class="card-body p-4">
                          <div class="p-3">
                              <?php echo form_open(site_url(CI_RECOVERY_PATH.'/resetpassword').'?token='.urlencode(trim($this->input->get("token"))),array("class"=>"reset-form form-horizontal mt-4 ","data-parsley-validate"=>"true","method"=>"post"));?>
                                  <p align="center">Silahkan perbarui katasandi untuk <span class="text-primary"><?php echo privateText($user["user_name"])?></span></p>
                                  <div class="recovery-info">
                                    <?php echo $this->session->flashdata("errormessage")?>
                                  </div>
                                  <div class="form-group">
                                      <label for="username">Katasandi Baru</label>
                                      <?php echo form_password(array("id"=>"password","data-parsley-minlength"=>"8","name"=>"sandibaru","class"=>"form-control","data-parsley-required"=>"true","placeholder"=>"Katasandi Baru"));?>
                                  </div>
                                  <div class="form-group">
                                      <label for="username">Konfirmasi Katasandi</label>
                                      <?php echo form_password(array("id"=>"confirmpassword","data-parsley-minlength"=>"8","name"=>"konfirmasisandibaru","data-parsley-equalto"=>"#password","class"=>"form-control","data-parsley-required"=>"true","placeholder"=>"Konfirmasi Katasandi"));?>
                                  </div>
                                  <div class="form-group row">
                                      <div class="col-sm-12 text-right">
                                        <?php echo form_button(array("type"=>"submit","class"=>"btn btn-primary w-md waves-effect waves-light","content"=>"Reset"));?>
                                      </div>
                                  </div>
                              <?php echo form_close();?>
                          </div>
                          <hr/>
                          <div class=" text-center">
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
  <script src="<?php echo base_url()?>assets/js/resetpass.js"></script>
</html>
