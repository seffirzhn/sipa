<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="control-label">Judul Seleksi JPT <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newjudul_seleksi","value"=>$judul_seleksi,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Tanggal Seleksi JPT <span class="text-danger">*</span></label>
                <div class="input-group input-daterange" >
                  <?php echo form_input(array("name"=>"newtgl_mulai","value"=>$tgl_mulai,"class"=>"form-control input-md input-range","placeholder"=>"Dari Tanggal","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglseleksi"));?>
                  <span class="input-group-addon input-group-prepend input-group-append" ><span class="input-group-text">s/d</span></span>
                  <?php echo form_input(array("name"=>"newtgl_selesai","value"=>$tgl_selesai,"class"=>"form-control input-md input-range","placeholder"=>"Sampai Tanggal","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglseleksi"));?>
                </div>
                <span id="errtglseleksi"></span>
              </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
              <div class="form-group">
                <label class="control-label">File Dokumen </label>
                <div>
                  <div class="m-b-5">
                    <span class="btn btn-primary btn-file">
                      <span><i class="ti-search fa-lg"></i> Telusuri</span>
                      <?php 
                        echo form_upload(array("name"=>"newfile","id"=>"newfile","accept"=>"application/pdf"));
                      ?>
                    </span>
                  </div>
                </div>
                <?php 
                  if($file!=""){
                ?>
                <div class="row m-t-5">
                  <div class="col-md-5">
                    <a href="javascript:;" class="btn-preview" data-name="FILE DOKUMEN" data-link="<?php echo base_url($file)?>" data-ext="<?php echo $ext_file?>">Klik untuk pratinjau berkas</a>
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
    Browsefile.init({target:"#data-tablemasterseleksi .forminput #newfile",text:"Max. 2MB"});
  });
</script>