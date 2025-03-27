<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Nama Pengguna <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newuser_name","value"=>$user_name,"class"=>"form-control  first-input-form "));?>
          </div>
          <div class="form-group">
            <label class="control-label">Nama <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newname","value"=>$name,"class"=>"form-control "));?>
          </div>
          <div class="form-group">
            <label class="control-label">Grup <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newgroup[]',$listgroup,$group_id, array("data-parsley-class-handler"=>".dropdown-group","data-parsley-errors-container"=>"#errgroup","multiple"=>"","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-group btn btn-outline-secondary waves-effect","data-size"=>"5"));?>
            <span  id="errgroup"></span>
          </div>
          <div class="form-group">
            <label class="control-label">OPD <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newopd',$listopd,$id_opd, array("data-parsley-class-handler"=>".dropdown-opd","data-parsley-errors-container"=>"#erropd","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-opd btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="erropd"></span>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Katasandi <?php echo ($required=="true")?'<span class="text-danger">*</span>':''?></label>
            <?php echo form_password(array("id"=>"passnya","data-parsley-required"=>$required,"data-parsley-minlength"=>"8","data-parsley-errors-container"=>"#errpass","name"=>"newpass","class"=>"form-control ipassword formpassword"));?>
            <span id="errpass"></span>
          </div>
          <div class="form-group">
            <label class="control-label">Konfirmasi Katasandi <?php echo ($required=="true")?'<span class="text-danger">*</span>':''?></label>
            <?php echo form_password(array("data-parsley-equalto"=>"#passnya","data-parsley-required"=>$required,"data-parsley-errors-container"=>"#errkonfirmpass","data-parsley-minlength"=>"8","name"=>"newkonfirmpass","class"=>"formpassword form-control ipassword "));?>
            <span id="errkonfirmpass"></span>
          </div>
          <div class="form-group m-b-25">
            <label class="control-label">Status</label>
            <div class="mt-1">
                <?php 
                  echo form_checkbox("newactive","1",(1==$active)?TRUE:FALSE,array("id"=>"cbactive","switch"=>"bool"));
                  echo form_label("","cbactive",array("data-on-label"=>"Aktif","data-off-label"=>"Tidak","class"=>"mb-0"));
                ?>
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
<script defer src="<?php echo base_url()?>assets/plugins/bootstrap-show-password/bootstrap-show-password.min.js"></script>        
<script type="text/javascript">
  $(document).ready(function(){
    $('#data-tableuser .forminput .formpassword').password();
  });
</script>