<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label class="control-label">Tanggal <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newtanggal","value"=>$tanggal,"class"=>"form-control date-picker"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Lokasi Kampanye <span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newlokasi_kampanye","value"=>$tempat_kegiatan,"class"=>"form-control  "));?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="control-label">File Gambar </label>
            <div>
              <div class="m-b-5">
                <span class="btn btn-primary btn-file">
                  <span><i class="ti-search fa-lg"></i> Telusuri</span>
                  <?php 
                    echo form_upload(array("name"=>"newgambar","id"=>"newgambar","accept"=>"image/*"));
                  ?>
                </span>
              </div>
            </div>
            <?php 
              if($gambar!=""){
            ?>
            <div class="row m-t-5">
              <div class="col-md-5">
                <a href="javascript:;" class="btn-preview" data-name="FILE GAMBAR" data-link="<?php echo base_url($gambar)?>" data-ext="<?php echo $ext_gambar?>">Klik untuk pratinjau berkas</a>
              </div>
            </div>
            <?php
              }
            ?>  
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group mb-0">
            <label class="control-label">Uraian Kampanye <span class="text-danger">*</span></label>
            <div class="p-l-15"  id="errdeskripsi_kampanye"></div>
            <?php echo form_textarea(array("data-parsley-class-handler"=>".note-editor","data-parsley-errors-container"=>"#errdeskripsi_kampanye","data-parsley-required"=>"false","name"=>"newdeskripsi_kampanye","id"=>"guidesdeskripsi_kampanye","value"=>$uraian_kegiatan,"class"=>"form-control summernote ","data-height"=>"350"));?>
          </div>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-12">
        <label class="control-label">Rincian Kampanye <span class="text-danger">*</span></label>
         
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
<div id="inputfile" class="d-none">
  <div>
    <div class="m-b-5">
      <span class="btn btn-primary btn-file">
        <span><i class="ti-search fa-lg"></i> Telusuri</span>
        <?php 
          echo form_upload(array("name"=>"newfilerincian[]","id"=>"newfilerincian##","accept"=>"application/pdf"));
        ?>
      </span>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function(){
    var num_file = <?php echo 3+1?>;
    for (var i = 1; i < num_file; i++) {
      Browsefile.init({target:"#data-tabledata_kampanye .forminput #newfilerincian"+i,text:"Max. 10MB"});
    }
    Browsefile.init({target:"#data-tabledata_kampanye .forminput #newgambar",text:"Max. 2MB"});
    $("#data-tabledata_kampanye .forminput").off("click",".btn-tambah-rincian").on("click",".btn-tambah-rincian",function(){
      var htmlfile      = $("#data-tabledata_kampanye .panel-form .body-form #inputfile").html();
      var htmlsatuan    = $("#data-tabledata_kampanye .panel-form .body-form #inputsatuan").html();
      htmlfile          = htmlfile.replace("newfilerincian##","newfilerincian"+num_file);
      var htmlrincian   = '<tr><td><input type="hidden" name="id_rincian_kampanye[]" value=""><input type="text" name="newurutan[]" value="" data-parsley-required="true" class="form-control "  /></td><td><input type="text" name="newnama_item[]" value="" data-parsley-required="true" class="form-control "  /></td><td>'+htmlfile+'</td><td><input type="text" name="newharga_satuan[]" value="" id="harga[]" data-parsley-required="true" class="form-control text-right harga_satuan number-picker" /></td><td><input type="text" name="newjumlah[]" value="" id="jumlah[]" data-parsley-required="true" class="form-control jumlah number-picker text-center" /></td><td>'+htmlsatuan+'</td><td><input type="text" name="newsub_total[]" id="subtotal[]" value="" data-parsley-required="true" class="form-control text-right sub_total number-picker" readonly=""/></td><td align="center"><button type="button" class="btn btn-danger btn-hapus-rincian"><i class="ti-trash"></i><span class="hidden-sm hidden-xs"></span></button></td></tr>';
      $("#data-tabledata_kampanye .forminput #bodyrincian").append(htmlrincian);
      Browsefile.init({target:"#data-tabledata_kampanye .forminput #newfilerincian"+num_file,text:"Max. 10MB"});
      num_file++;
    });
    $("#data-tabledata_kampanye .forminput #bodyrincian").off("click",".btn-hapus-rincian").on("click",".btn-hapus-rincian",function(){
        $(this).parent().parent().remove(); 
      hitung($(this));
    });

    function hitung(element){
      var harga_satuan      = $(".harga_satuan",element.parent().parent());
      var jumlah            = $(".jumlah",element.parent().parent());
      var sub_total         = $(".sub_total",element.parent().parent());
      harga_satuan          = harga_satuan.val().replace(/,/g,"");
      jumlah                = jumlah.val().replace(/,/g,"");
      if(harga_satuan!="" && jumlah!=""){
        sub_total.val((parseFloat(harga_satuan)*parseFloat(jumlah)).toLocaleString().replace(/\./g,","));
      }else{
        sub_total.val("0");
      }

      hitungtotal               = 0;
      $(".sub_total",$("#data-tabledata_kampanye .forminput #bodyrincian")).each(function(){
        hitungtotal+=parseFloat($(this).val().replace(/,/g,""));
      });
      $("#data-tabledata_kampanye .forminput input[name=newanggaran]").val(hitungtotal.toLocaleString().replace(/\./g,","))
    }

    $("#data-tabledata_kampanye .forminput #bodyrincian").off("keyup",".harga_satuan").on("keyup",".harga_satuan",function(){
      hitung($(this));
    });

    $("#data-tabledata_kampanye .forminput #bodyrincian").off("keyup",".jumlah").on("keyup",".jumlah",function(){
      hitung($(this));
    });
  });
</script>