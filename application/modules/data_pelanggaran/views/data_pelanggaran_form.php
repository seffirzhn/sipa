<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group m-b-25">
            <label class="control-label">Tanggal <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtanggal","value"=>$tanggal,"class"=>"form-control date-picker"));?>
          </div>
        </div> 
        <div class="col-md-4">
          <div class="form-group m-b-25">
            <label class="control-label">Jam Mulai <span class="text-danger">*</span></label>
            <!-- <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newjam_mulai","value"=>$jam_mulai,"class"=>"form-control input-mask","data-inputmask"=>"'mask': '99:99'","im-insert"=>"true"));?> -->
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newjam_mulai","value"=>$jam_mulai,"class"=>"form-control masked-input","id"=>"jam_mulai"));?>
          </div>
        </div> 
        <div class="col-md-9">
          <div class="form-group">
            <label class="control-label">Paslon yang Melanggar <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newid_paslon',$listpaslon,$id_paslon, array("data-parsley-class-handler"=>".dropdown-paslon","data-parsley-errors-container"=>"#errpaslon","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-paslon btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errpaslon"></span>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Kategori Pelanggaran <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newid_kategori',$listkategori,$id_kategori, array("data-parsley-class-handler"=>".dropdown-kategori","data-parsley-errors-container"=>"#errkategori","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-kategori btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errkategori"></span>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="control-label">Nama Pelanggaran <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newid_pelanggaran',$listpelanggaran,$id_pelanggaran, array("data-parsley-class-handler"=>".dropdown-pelanggaran","data-parsley-errors-container"=>"#errpelanggaran","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-pelanggaran btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
            <span  id="errpelanggaran"></span>
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
<!-- <script type="text/javascript">
  $(document).ready(function(){
    Browsefile.init({target:"#data-tabledata_pelanggaran .forminput #newfile",text:"Max. 2MB"});
  });
</script> -->
<script type="text/javascript">
  $(document).ready(function(){
    
    $("#data-tabledata_pelanggaran .forminput").off("change","select[name=newid_kategori]").on("change","select[name=newid_kategori]",function(){
      blockUI();
      var n = new FormData();
      n.append("kategori",$(this).val());
      $.ajax({
        url:"<?php echo $actionspelanggaran?>",
        type:"POST",
        data:n,
        contentType:!1,
        dataType:"JSON",
        processData:!1,
        success:function(t){
          $("#data-tabledata_pelanggaran .forminput select[name=newid_pelanggaran]").html(t.html);
          $("#data-tabledata_pelanggaran .forminput select[name=newid_pelanggaran]").selectpicker("refresh");
          unblockUI();
        },error:function(t){
          unblockUI();
        }
      });
    });
  });
</script>