<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">Pilkada <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnamapilkada","value"=>$nama_pilkada,"class"=>"form-control","readonly"=>""));?>
            <?php echo form_hidden("newpilkada",$pilkada);?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">No Urut <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newno_urut","value"=>$no_urut,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <?php echo form_hidden("newdaerah",$daerah);?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Daerah <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnamadaerah","value"=>$nama_daerah,"class"=>"form-control","readonly"=>""));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Calon Kepala <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newkepala',$listkepala,$id_topol_kepala, array("data-parsley-class-handler"=>".dropdown-kepala","data-parsley-errors-container"=>"#errkepala","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-kepala btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errkepala"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Calon Wakil <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newwakil',$listwakil,$id_topol_wakil, array("data-parsley-class-handler"=>".dropdown-wakil","data-parsley-errors-container"=>"#errwakil","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-wakil btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errwakil"></span>
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
    Browsefile.init({target:"#data-tablepaslon .forminput #newfile",text:"Max. 2MB"});
  });
</script>