<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Jenis Penilaian <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newjenis_nilai","value"=>$nama_jenis_nilai,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label class="control-label">Bobot <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newbobot","value"=>$bobot,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Keterangan</label>
                <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newketerangan","value"=>$keterangan,"class"=>"form-control"));?>
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
    Browsefile.init({target:"#data-tablemasterseleksi .forminput #newfile",text:"Max. 2MB"});
  });
</script>