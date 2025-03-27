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
                  <span class="d-none d-sm-block">Logo</span> 
              </a>
          </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active p-3" id="tabmain" role="tabpanel">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Nama <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnama","value"=>$nama,"class"=>"form-control  first-input-form "));?>
              </div>
              <div class="form-group">
                <label class="control-label">Alamat <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newalamat","value"=>$alamat,"class"=>"form-control"));?>
              </div>
              <div class="form-group">
                <label class="control-label">Kop Surat</label>
                <?php echo form_input(array("name"=>"newkop","value"=>$kop,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Email <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","data-parsley-type"=>"email","name"=>"newemail","value"=>$email,"class"=>"form-control "));?>
              </div>
              <div class="form-group">
                <label class="control-label">No Telepon <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnotelepon","value"=>$notelepon,"class"=>"form-control "));?>
              </div>
              <div class="form-group">
                <label class="control-label">Fax <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newfax","value"=>$fax,"class"=>"form-control "));?>
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
    Browsefile.init({target:"#data-tablesettingapp .forminput #newlogo",text:"Max. 500KB"});
  });
</script>