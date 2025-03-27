<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en">
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
    var url_send      = "<?php echo site_url(CI_RECOVERY_PATH.'/sendverifyphone')?>";
    var url_switch    = "<?php echo site_url(CI_RECOVERY_PATH.'/switchmethod')?>";
    var environment   = "<?php echo ENVIRONMENT?>";
  </script>
</head>
<body class="account-pages">
  <?php $this->load->view("properties/remove_iklan")?>
  <div class="accountbg">
    <div class="account-feed">
      <img src="<?php echo base_url($configsite['background'])?>">
    </div>
  </div>
  <div class="wrapper-page account-page-full account-page-full-login">
      <div class="card shadow-none">
          <div class="card-block">
              <div class="account-box">
                  <div class="card-box shadow-none p-4">
                      <div class="p-2">
                          <div class="text-center mt-4">
                              <a href="<?php echo site_url(CI_LOGIN_PATH)?>"><img src="<?php echo base_url($configsite['logo'])?>" height="100" alt="logo"></a>
                          </div>

                          <p align="center" class="m-t-15 f-s-13 text-white"></p>

                          <h4 class="font-size-18 mt-3 text-center"><?php echo strtoupper($configsite['name']) ?></h4>
                          <p class="text-muted text-center">Pemulihan Katasandi</p>
                          <?php echo form_open(CI_RECOVERY_PATH."/phone",array("class"=>"phone-verify-form mt-4","data-parsley-validate"=>"true","method"=>"post"));?>
                              <p align="center">Kode verifikasi telah dikirimkan melalui SMS ke <span class="text-primary"><?php echo $phone?></span></p>
                              <div class="recovery-info">
                                <?php echo $this->session->flashdata("errormessage")?>
                              </div>
                              <div class="form-group">
                                  <label for="username">Kode Verifikasi</label>
                                  <?php echo form_input(array("id"=>"kodeverifikasi","name"=>"kodeverifikasi","style"=>"font-size:1rem!important;height:2.875rem","class"=>"form-control input-lg text-center","data-parsley-required"=>"true","placeholder"=>"Kode Verifikasi","data-parsley-maxlength"=>"6","data-parsley-minlength"=>"6","data-parsley-errors-container"=>".err-kode"));?>
                                  <p class="text-center err-kode" align="center"></p>
                              </div>
                              <div class="form-group row">
                                  <div class="col-sm-12 text-right">
                                    <?php echo form_button(array("type"=>"submit","class"=>"btn btn-primary w-md waves-effect waves-light","content"=>"Verifikasi"));?>
                                  </div>
                              </div>
                              <p class="text-recovery m-t-15" align="center">&nbsp;</p>
                          <?php echo form_close();?>                          
                          <div class="mt-5 text-center">
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
  <script src="<?php echo base_url()?>assets/js/sendverifyphone.js"></script>
</html>
