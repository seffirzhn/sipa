<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Nama PPID </label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newnama_ppid","value"=>$nama_ppid,"class"=>"form-control","readonly"=>"readonly"));?>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Judul <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newjudul","value"=>$judul,"class"=>"form-control  "));?>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Keterangan </label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newketerangan","value"=>$keterangan,"rows"=>"5","class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">M. Informasi </label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newm_informasi","value"=>$m_informasi,"rows"=>"5","class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Penanggung Jawab </label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newpenanggung_jawab","value"=>$penanggung_jawab,"class"=>"form-control  "));?>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Tempat Informasi </label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newtempat_informasi","value"=>$tempat_informasi,"class"=>"form-control  "));?>
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
  <div class="card-footer text-right">
    <?php 
      $this->load->view("properties/button_submit_form");
      $this->load->view("properties/button_reset_form");
    ?>
  </div>
</form>
<script type="text/javascript">
  $(document).ready(function(){
    Browsefile.init({target:"#data-tableinformasi_publik .forminput #newfile",text:"Max. 2MB"});
  });
</script>