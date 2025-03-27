<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
		<div class="row">
			<div class="col-md-12 alert alert-warning alert-dismissible fade show" role="alert">
				<strong>Perhatian!!</strong> Sebelum mengisi Form Pendataan Aplikasi, mohon dipersiapkan <strong>File Scan SPT PIC Aplikasi (pdf)</strong> dan <strong>File Logo Aplikasi (jpg/jpeg/png)</strong>. Data tidak akan tersimpan jika File SPT dan Logo tidak di unggah.
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		</div>
	  <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Asal Aplikasi <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newasal_aplikasi',$listasal,$asal_aplikasi, array("data-parsley-class-handler"=>".dropdown-asalaplikasi","data-parsley-errors-container"=>"#errasalaplikasi","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-asalaplikasi btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errasalaplikasi"></span>
          </div>
        </div>
		<div class="col-md-4">
			  <div class="form-group">
				<label class="control-label">OPD <span class="text-danger">*</span></label>
				<?php echo form_dropdown('newopd',$listopd,$id_opd, array("data-parsley-class-handler"=>".dropdown-opd","data-parsley-errors-container"=>"#erropd","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-opd btn btn-outline-secondary waves-effect","data-live-search"=>"true","data-size"=>"5"));?>
				<span  id="erropd"></span>
			  </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Tahun Penggunaan / Launching <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newtahun',$listtahun,$tahun, array("data-parsley-class-handler"=>".dropdown-tahun","data-parsley-errors-container"=>"#errtahun","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-tahun btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errtahun"></span>
          </div>
        </div>
	  </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Nama Aplikasi / Sistem Informasi<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnama_aplikasi","value"=>$nama_aplikasi,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Jenis Layanan <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newjenis_layanan',$listjenislayanan,$id_jenis_layanan, array("data-parsley-class-handler"=>".dropdown-jenislayanan","data-parsley-errors-container"=>"#errjenislayanan","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-jenislayanan btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errjenislayanan"></span>
          </div>
        </div>
      </div>
	  <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Deskripsi Singkat <span class="text-danger">*</span></label>
            <?php echo form_textarea(array("data-parsley-required"=>"true","name"=>"newdeskripsi_aplikasi","value"=>$deskripsi_aplikasi,"rows"=>"4","class"=>"form-control","placeholder"=>"Deskripsi singkat sistem informasi / aplikasi terkait fungsi pelayanan yang dilayani"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Nama Proses Bisnis <span class="text-danger">*</span></label>
            <?php echo form_textarea(array("data-parsley-required"=>"true","name"=>"newproses_bisnis","value"=>$proses_bisnis,"rows"=>"4","class"=>"form-control","placeholder"=>"Nama Proses Bisnis Layanan"));?>
          </div>
        </div>
		</div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Basis Antar Muka <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newbasis_aplikasi[]',$listbasis,$basis_aplikasi, array("multiple"=>"","data-parsley-class-handler"=>".dropdown-basisaplikasi","data-parsley-errors-container"=>"#errbasisaplikasi","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-basisaplikasi btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errbasisaplikasi"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Bahasa Pemrograman<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newbahasa_pemrograman","value"=>$bahasa_pemrograman,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">DBMS <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newdbms',$listdbms,$id_dbms, array("data-parsley-class-handler"=>".dropdown-dbms","data-parsley-errors-container"=>"#errdbms","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-dbms btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errdbms"></span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Lokasi Hosting <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newlokasi_hosting',$listlokasihosting,$id_lokasi_hosting, array("data-parsley-class-handler"=>".dropdown-lokasihosting","data-parsley-errors-container"=>"#errlokasihosting","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-lokasihosting btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errlokasihosting"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Jumlah Pengguna <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newjumlah_pengguna',$listjumlahpengguna,$jumlah_pengguna, array("data-parsley-class-handler"=>".dropdown-jumlahpengguna","data-parsley-errors-container"=>"#errjumlahpengguna","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-jumlahpengguna btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errjumlahpengguna"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Frekuensi Penggunaan <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newfrekuensi_penggunaan',$listfrekuensi,$id_frekuensi_penggunaan, array("data-parsley-class-handler"=>".dropdown-frekuensi","data-parsley-errors-container"=>"#errfrekuensi","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-frekuensi btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errfrekuensi"></span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Nama Domain/Subdomain/Link Unduh</label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newnama_domain","value"=>$nama_domain,"placeholder"=>"contoh : siap.tanjungpinangkota.go.id","class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Penyedia Aplikasi<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newpenyedia_aplikasi","value"=>$penyedia_aplikasi,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Pengembang Aplikasi<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newpengembang_aplikasi","value"=>$pengembang_aplikasi,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Ketersediaan Integrasi <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newketersediaan',$listjenis,$ketersediaan_integrasi, array("data-parsley-class-handler"=>".dropdown-ketersediaan","data-parsley-errors-container"=>"#errketersediaan","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-ketersediaan btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errketersediaan"></span>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Metode Integrasi </label>
            <?php echo form_input(array("data-parsley-required"=>"false","name"=>"newmetode_integrasi","value"=>$metode_integrasi,"class"=>"form-control","placeholder"=>"REST API / SOAP API / dll"));?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Terintegrasi dengan Aplikasi </label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newaplikasi_integrasi","value"=>$aplikasi_integrasi,"rows"=>"4","class"=>"form-control","placeholder"=>"Contoh: Terintegrasi dengan SRIKANDI ; Satu Data Indonesia"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">OPD / Unit Kerja Terintegrasi</label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newopd_terintegrasi","value"=>$opd_terintegrasi,"rows"=>"4","class"=>"form-control","placeholder"=>"Contoh: Diskominfo;Disdukcapil;"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Regulasi </label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newregulasi","value"=>$regulasi,"rows"=>"4","class"=>"form-control","placeholder"=>"Contoh: Perpres 95 Tahun 2018 tentang Sistem Pemerintahan Berbasis Elektronik"));?>
          </div>
        </div>
      </div>
      <!-- </div> -->
      <div class="row">
        <div class="col-md-3">
            <div class="form-group">
              <label class="control-label">Nama PIC <span class="text-danger">*</span></label>
              <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newpic_aplikasi","value"=>$pic_aplikasi,"class"=>"form-control"));?>
            </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">NIP PIC<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newnip_pic","value"=>$nip_pic,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">Jabatan PIC<span class="text-danger">*</span></label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newjabatan_pic","value"=>$jabatan_pic,"class"=>"form-control"));?>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="control-label">No. Kontak PIC </label>
            <?php echo form_input(array("data-parsley-required"=>"true","name"=>"newno_telp","value"=>$no_telp,"class"=>"form-control"));?>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
              <label class="control-label">File Surat SPT <span class="text-danger">*</span></label>
              <div>
                <div class="m-b-5">
                  <span class="btn btn-primary btn-file">
                    <span><i class="ti-search fa-lg"></i> Telusuri</span>
                    <?php 
                      echo form_upload(array("name"=>"newfile","id"=>"newfile","accept"=>"image/png, image/jpeg, image/gif, application/pdf"));
                    ?>
                  </span>
                </div>
              </div>
              <?php 
                if($file!=""){
              ?>
              <div class="row m-t-5">
                <div class="col-md-5">
                  <a href="javascript:;" class="btn-preview" data-name="File SPT" data-link="<?php echo base_url($file)?>" data-ext="<?php echo $ext_file?>">Klik untuk pratinjau berkas</a>
                </div>
              </div>
              <?php
                }
              ?>  
            </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
              <label class="control-label">File Logo Aplikasi <span class="text-danger">*</span></label>
              <div>
                <div class="m-b-5">
                  <span class="btn btn-primary btn-file">
                    <span><i class="ti-search fa-lg"></i> Telusuri</span>
                    <?php 
                      echo form_upload(array("name"=>"newfilelogo","id"=>"newfilelogo","accept"=>"image/png, image/jpeg, image/gif"));
                    ?>
                  </span>
                </div>
              </div>
              <?php 
                if($file_logo!=""){
              ?>
              <div class="row m-t-5">
                <div class="col-md-5">
                  <a href="javascript:;" class="btn-preview" data-name="File SPT" data-link="<?php echo base_url($file_logo)?>" data-ext="<?php echo $ext_file_logo?>">Klik untuk pratinjau berkas</a>
                </div>
              </div>
              <?php
                }
              ?>  
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Level Privileges </label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newlevel_privileges","value"=>$level_privileges,"rows"=>"4","class"=>"form-control","placeholder"=>"Contoh: Admin Utama, adalah pengguna yang memiliki hak akses kesemua fitur, 1 orang; dst"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Rencana Aplikasi</label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newrencana_aplikasi","value"=>$rencana_aplikasi,"rows"=>"4","class"=>"form-control","placeholder"=>"Contoh: Aplikasi Parkir Satu Pintu dengan menggunakan aplikasi seluruh masyarakat dapat melakukan pembayaran retribusi parkirn dengan memanfaatkan e-Money"));?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Keterangan </label>
            <?php echo form_textarea(array("data-parsley-required"=>"false","name"=>"newketerangan","value"=>$keterangan,"rows"=>"4","class"=>"form-control"));?>
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
    Browsefile.init({target:"#data-tabledataaplikasi .forminput #newfile",text:"Max. 2MB"});
    Browsefile.init({target:"#data-tabledataaplikasi .forminput #newfilelogo",text:"Max. 2MB"});
  });
</script>