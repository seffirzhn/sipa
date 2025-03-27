<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label class="control-label">Keterangan Pilkada <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newketerangan","value"=>$keterangan,"class"=>"form-control"));?>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="control-label">Tgl Pilkada <span class="text-danger">*</span></label>
                <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtgl_pilkada","value"=>$tgl_pilkada,"class"=>"form-control date-picker"));?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Tanggal Kampanye <span class="text-danger">*</span></label>
                <div class="input-group input-daterange" >
                  <?php echo form_input(array("name"=>"newtgl_awal_kampanye","value"=>$tgl_awal_kampanye,"class"=>"form-control input-md input-range","placeholder"=>"Dari Tanggal","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglkampanye"));?>
                  <span class="input-group-addon input-group-prepend input-group-append" ><span class="input-group-text">s/d</span></span>
                  <?php echo form_input(array("name"=>"newtgl_akhir_kampanye","value"=>$tgl_akhir_kampanye,"class"=>"form-control input-md input-range","placeholder"=>"Sampai Tanggal","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglkampanye"));?>
                </div>
                <span id="errtglkampanye"></span>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="control-label">Tanggal Periode Pilkada <span class="text-danger">*</span></label>
                <div class="input-group input-daterange" >
                  <?php echo form_input(array("name"=>"newperiode_awal","value"=>$periode_awal,"class"=>"form-control input-md input-range","placeholder"=>"Dari Tanggal","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglpilkada"));?>
                  <span class="input-group-addon input-group-prepend input-group-append" ><span class="input-group-text">s/d</span></span>
                  <?php echo form_input(array("name"=>"newperiode_akhir","value"=>$periode_akhir,"class"=>"form-control input-md input-range","placeholder"=>"Sampai Tanggal","data-parsley-required"=>"true","data-parsley-errors-container"=>"#errtglpilkada"));?>
                </div>
                <span id="errtglpilkada"></span>
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
    $("#data-tablepilkada .forminput").off("click","#for_cek_all").on("click","#for_cek_all",function(){
      $("#data-tablepilkada .forminput .for_cek_all").prop("checked",jQuery(this).prop("checked"));
    });

    $("#data-tablepilkada .forminput").off("click","#for_cek_all2").on("click","#for_cek_all2",function(){
      $("#data-tablepilkada .forminput .for_cek_all2").prop("checked",jQuery(this).prop("checked"));
    });

    Browsefile.init({target:"#data-tablepilkada .forminput #newpersyaratan"});
  });
</script>