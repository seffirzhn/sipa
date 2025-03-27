<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Nama Partai Politik <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnama","value"=>$nama,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-3">
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
              <div class="col-md-12">
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
  <div class="card-footer text-right">
    <?php 
      $this->load->view("properties/button_submit_form");
      $this->load->view("properties/button_reset_form");
    ?>
  </div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    Browsefile.init({target:"#data-tableparpol .forminput #newlogo",text:"Max. 1Mb"});
  });
</script>