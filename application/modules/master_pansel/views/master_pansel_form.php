<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Nama Pansel<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnama_pansel","value"=>$nama_pansel,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Jenis Identitas <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newjenis_identitas',$listjenis,$jenis_identitas, array("data-parsley-class-handler"=>".dropdown-jenis","data-parsley-errors-container"=>"#errjenis","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-jenis btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errjenis"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Nomor Identitas<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnomor_identitas","value"=>$nomor_identitas,"class"=>"form-control"));?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">No. Telp</label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newno_telp","value"=>$no_telp,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Email</label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newemail","value"=>$email,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Asal</label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newasal","value"=>$asal,"class"=>"form-control"));?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Alamat </label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newalamat","value"=>$alamat,"rows"=>"4","class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-2">
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
    Browsefile.init({target:"#data-tablemaster_pansel .forminput #newfile",text:"Max. 2MB"});
  });
</script>