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

                          <h4 class="font-size-18 mt-3 text-center"><?php echo strtoupper($configsite['name']) ?></h4>
                          <p class="text-muted text-center">Pemulihan Katasandi</p>

                          <div class="recovery-info mt-4">
                            <?php echo $this->session->flashdata("errormessage")?>
                          </div>
                          <p align="center">Silahkan pilih metode perbarui katasandi untuk <span class="text-primary"><?php echo privateText($user["user_name"])?></span></p>
                          <div class="list-group m-b-40 p-b-40">
                            <div class="list-group-item text-inverse">
                              <?php 
                                if($user["phone"]){
                                  echo form_open(CI_RECOVERY_PATH.'/switchmethod',array("class"=>"recovery-form","method"=>"post"),array("switchmethod"=>"phone"));
                              ?>
                                  <button type="submit" class="btn btn-sm btn-primary pull-right">Pilih</button>
                                  Perbarui katasandi dengan nomor handphone <strong>(<?php echo privateText($user["phone"])?>)</strong>
                              <?php 
                                  echo form_close();
                                }else{
                                  echo "<span class='text-danger'>Nomor handphone belum tersimpan</span>";
                                }
                              ?>

                            </div>
                            <div class="list-group-item text-inverse">
                              <?php 
                                if($user["email"]){
                                  echo form_open(CI_RECOVERY_PATH.'/switchmethod',array("class"=>"recovery-form","method"=>"post"),array("switchmethod"=>"email"));?>
                                  <button type="submit" class="btn btn-sm btn-primary pull-right">Pilih</button>
                                  Perbarui katasandi dengan email <strong>(<?php echo privateEmail($user["email"])?>)</strong>
                              <?php 
                                  echo form_close();
                                }else{
                                  echo "<span class='text-danger'>Email belum tersimpan</span>";
                                }
                              ?>
                            </div>
                          </div>                       
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
  <script src="<?php echo base_url()?>assets/js/recovery.js"></script>
</html>
