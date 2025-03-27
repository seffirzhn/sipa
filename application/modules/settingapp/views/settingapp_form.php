<style type="text/css">
  .nav-form-setting > li > a {
    background: #f2f2f2;
  }
</style>
<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
          <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#tabmain" role="tab">
                  <span class="d-block d-sm-none"><i class="ti ti-home"></i></span>
                  <span class="d-none d-sm-block">Utama</span> 
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabiconlogo" role="tab">
                  <span class="d-block d-sm-none"><i class="ti ti-image"></i></span>
                  <span class="d-none d-sm-block">Logo & Ikon</span> 
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabaddfeature" role="tab">
                  <span class="d-block d-sm-none"><i class="ti ti-plus"></i></span>
                  <span class="d-none d-sm-block">Fitur Tambahan</span>   
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabemailgateway" role="tab">
                  <span class="d-block d-sm-none"><i class="ti ti-email"></i></span>
                  <span class="d-none d-sm-block">Email Gateway</span>    
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabsmsgateway" role="tab">
                  <span class="d-block d-sm-none"><i class="ti ti-mobile"></i></span>
                  <span class="d-none d-sm-block">SMS Gateway</span>    
              </a>
          </li>
          <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#taboauth" role="tab">
                <span class="d-block d-sm-none"><i class="ti ti-key"></i></span>
                  <span class="d-none d-sm-block">OAuth</span>    
              </a>
          </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active p-3" id="tabmain" role="tabpanel">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Nama Aplikasi <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newname","value"=>$name,"class"=>"form-control  first-input-form "));?>
              </div>
              <div class="form-group">
                <label class="control-label">Sub Nama <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newsubname","value"=>$subname,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">Judul <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtitle","value"=>$title,"class"=>"form-control "));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Copyright <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newcopyright","value"=>$copyright,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">Flag Counter</label>
                <?php echo form_input(array("name"=>"newflagcounter","value"=>$flagcounter,"class"=>"form-control"));?>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group mb-0">
                <div class="p-l-15"  id="errpreview"></div>
                <?php echo form_textarea(array("data-parsley-class-handler"=>".note-editor","data-parsley-errors-container"=>"#errpreview","data-parsley-required"=>"true","name"=>"newpreview","id"=>"preview","value"=>$preview,"class"=>"form-control summernote ","data-height"=>"350"));?>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="tabiconlogo" role="tabpanel">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Logo</label>
                <div>
                  <div class="m-b-5">
                    <span class="btn btn-primary btn-file">
                      <span><i class="ti-search fa-lg"></i> Telusuri</span>
                      <?php 
                        echo form_upload(array("name"=>"newlogo","id"=>"newlogo","accept"=>"image/png, image/jpeg, image/gif"));
                      ?>
                    </span>
                  </div>
                </div>
                <?php 
                  if($logo!=""){
                ?>
                <div class="row m-t-5">
                  <div class="col-md-5">
                    <a href="javascript:;" class="btn-preview" data-name="LOGO" data-link="<?php echo base_url($logo)?>" data-ext="<?php echo $ext_logo?>">Klik untuk pratinjau berkas</a>
                  </div>
                </div>
                <?php
                  }
                ?>
              </div>
              <div class="form-group">
                <label class="control-label">Ikon</label>
                <div>
                  <div class="m-b-5">
                    <span class="btn btn-primary btn-file">
                      <span><i class="ti-search fa-lg"></i> Telusuri</span>
                      <?php 
                        echo form_upload(array("name"=>"newicon","id"=>"newicon","accept"=>"image/png, image/jpeg, image/gif"));
                      ?>
                    </span>
                  </div>
                </div>
                <?php 
                  if($icon!=""){
                ?>
                <div class="row m-t-5">
                  <div class="col-md-5">
                    <a href="javascript:;" class="btn-preview" data-name="IKON" data-link="<?php echo base_url($icon)?>" data-ext="<?php echo $ext_icon?>">Klik untuk pratinjau berkas</a>
                  </div>
                </div>
                <?php
                  }
                ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Latar Belakang</label>
                <div>
                  <div class="m-b-5">
                    <span class="btn btn-primary btn-file">
                      <span><i class="ti-search fa-lg"></i> Telusuri</span>
                      <?php 
                        echo form_upload(array("name"=>"newbackground","id"=>"newbackground","accept"=>"image/png, image/jpeg, image/gif"));
                      ?>
                    </span>
                  </div>
                </div>
                <?php 
                  if($background!=""){
                ?>
                <div class="row m-t-5">
                  <div class="col-md-5">
                    <a href="javascript:;" class="btn-preview" data-name="LATAR BELAKANG" data-link="<?php echo base_url($background)?>" data-ext="<?php echo $ext_background?>">Klik untuk pratinjau berkas</a>
                  </div>
                </div>
                <?php
                  }
                ?>  
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="tabaddfeature" role="tabpanel">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Maksimal Coba Masuk <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtrylogin","value"=>$trylogin,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">Lama Waktu Terkunci (detik) <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtimelock","value"=>$timelock,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label">Masuk dengan Google</label>
                <div class="mt-1">
                    <?php 
                      echo form_checkbox("newisgoogle","y",("y"==$isgoogle)?TRUE:FALSE,array("id"=>"cbgoogle","switch"=>"bool"));
                      echo form_label("","cbgoogle",array("data-on-label"=>"Aktif","data-off-label"=>"Tidak","class"=>"mb-0"));
                    ?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">Masuk dengan Facebook</label>
                <div class="mt-1">
                    <?php 
                      echo form_checkbox("newisfacebook","y",("y"==$isfacebook)?TRUE:FALSE,array("id"=>"cbfacebook","switch"=>"bool"));
                      echo form_label("","cbfacebook",array("data-on-label"=>"Aktif","data-off-label"=>"Tidak","class"=>"mb-0"));
                    ?>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label">Lupa Katasandi</label>
                <div class="mt-1">
                    <?php 
                      echo form_checkbox("newforget","y",("y"==$isforget)?TRUE:FALSE,array("id"=>"cbforget","switch"=>"bool"));
                      echo form_label("","cbforget",array("data-on-label"=>"Aktif","data-off-label"=>"Tidak","class"=>"mb-0"));
                    ?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">Kode Keamanan</label>
                <div class="mt-1">
                    <?php 
                      echo form_checkbox("newcaptcha","y",("y"==$iscaptcha)?TRUE:FALSE,array("id"=>"cbcaptcha","switch"=>"bool"));
                      echo form_label("","cbcaptcha",array("data-on-label"=>"Aktif","data-off-label"=>"Tidak","class"=>"mb-0"));
                    ?>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label">Sesi Tunggal</label>
                <div class="mt-1">
                    <?php 
                      echo form_checkbox("newsinglesession","y",("y"==$issinglesession)?TRUE:FALSE,array("id"=>"cbsinglesession","switch"=>"bool"));
                      echo form_label("","cbsinglesession",array("data-on-label"=>"Aktif","data-off-label"=>"Tidak","class"=>"mb-0"));
                    ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="tabemailgateway" role="tabpanel">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">SMTP Host </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newhostmail","value"=>$hostmail,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">SMTP Port </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newportmail","value"=>$portmail,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Email </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newemail","value"=>$email,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">Password </label>
                <?php echo form_password(array("name"=>"newpassmail","class"=>"formpassword form-control ipassword "));?>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="tabsmsgateway" role="tabpanel">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Token </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newtokensms","value"=>$tokensms,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">Device Id </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newdeviceid","value"=>$deviceid,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Email </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newemailsms","value"=>$emailsms,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">Password </label>
                <?php echo form_password(array("name"=>"newpasssms","class"=>"formpassword form-control ipassword "));?>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane p-3" id="taboauth" role="tabpanel">
          <h5>Google Login</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Google Client Id </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newgoogleclientid","value"=>$googleclientid,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Google Client Secret </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newgoogleclientsecret","value"=>$googleclientsecret,"class"=>"form-control"));?>
              </div>
            </div>
          </div>
          <h5>Facebook Login</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Facebook App Id </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newfacebookappid","value"=>$facebookappid,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Facebook App Secret </label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newfacebookappsecret","value"=>$facebookappsecret,"class"=>"form-control"));?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer text-right">
    <?php 
      $this->load->view("properties/button_submit_form");
      $this->load->view("properties/button_reset_form");
    ?>
  </div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    $('#data-tablesettingapp .forminput .formpassword').password();
    Browsefile.init({target:"#data-tablesettingapp .forminput #newlogo",text:"Max. 500KB"});
    Browsefile.init({target:"#data-tablesettingapp .forminput #newicon",text:"Max. 100KB"});
    Browsefile.init({target:"#data-tablesettingapp .forminput #newbackground",text:"Max. 2MB"});
  });
</script>