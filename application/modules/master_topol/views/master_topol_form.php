<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Nama <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnama","value"=>$nama,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Partai Politik <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newparpol',$listparpol,$id_parpol, array("data-parsley-class-handler"=>".dropdown-parpol","data-parsley-errors-container"=>"#errparpol","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-parpol btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errparpol"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Daerah <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newdaerah',$listdaerah,$id_daerah, array("data-parsley-class-handler"=>".dropdown-daerah","data-parsley-errors-container"=>"#errdaerah","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-daerah btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errdaerah"></span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">No. Telp <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtelp","value"=>$no_telp,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-8">
          <div class="form-group">
            <label class="control-label">Alamat </label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newalamat","value"=>$alamat,"class"=>"form-control"));?>
          </div>
        </div>
      </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">File Dokumen </label>
            <div>
              <div class="m-b-5">
                <span class="btn btn-primary btn-file">
                  <span><i class="ti-search fa-lg"></i> Telusuri</span>
                  <?php 
                    echo form_upload(array("name"=>"newfile","id"=>"newfile","accept"=>"image/png, image/jpeg, image/gif"));
                  ?>
                </span>
              </div>
            </div>
            <?php 
              if($file!=""){
            ?>
            <div class="row m-t-5">
              <div class="col-md-5">
                <a href="javascript:;" class="btn-preview" data-name="FOTO" data-link="<?php echo base_url($file)?>" data-ext="<?php echo $ext_file?>">Klik untuk pratinjau foto</a>
              </div>
            </div>
            <?php
              }
            ?>  
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
    Browsefile.init({target:"#data-tablemaster_topol .forminput #newfile",text:"Max. 2MB"});
  });
</script>