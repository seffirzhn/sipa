<form class="forminput" action="javascript:;"  data-parsley-validate="true">
  <div class="card-body">
    <div class="form-body ">
      <div class="row">
        <div class="list-group-item border-primary">
          <div class="flex-grow-1">
            <p class=" mb-2 text-primary">Nama Aplikasi</p>
            <h4 class="mb-0 "><?= $nama_aplikasi ?></h4>
          </div>
        </div>&nbsp;<br>
        <div class="list-group-item border-warning">
          <div class="flex-grow-1">
            <p class=" mb-2 text-warning">Nama Domain/Subdomain</p>
            <h4 class="mb-0"><a href="http://<?= $nama_domain ?>" target="_blank"><?= $nama_domain ?></a></h4>
          </div>
        </div>
      </div><p></p>
      <div class="row">
        <div class="list-group-item table-responsive col-md-12 border-success">
          <h5 class=" mb-4"> Detil Aplikasi</h5>
            <table class="table mb-0">
                <tbody>
                    <tr>
                        <th width="20%">Deskripsi Singkat Sistem Informasi </th>
                        <td width="2%">:</td>
                        <td width="78%"><?= nl2br($deskripsi_aplikasi) ?></td>
                    </tr>
					 <tr>
                        <th >Asal Aplikasi </th>
                        <td>:</td>
                        <td><?= $asal_aplikasi == 1 ? 'Aplikasi yang dikembangkan sendiri' : 'Aplikasi yang dikembangkan instansi luar' ?></td>
                    </tr>
                    <tr>
                        <th >Nama Proses Bisnis </th>
                        <td>:</td>
                        <td><?= $proses_bisnis ?></td>
                    </tr>
                    <tr>
                        <th >Jenis Layanan </th>
                        <td>:</td>
                        <td>
							<!--<?= $jenis_layanan ?>-->
							<?php echo form_dropdown('newjenis_layanan',$listjenislayanan,$id_jenis_layanan, array("data-parsley-class-handler"=>".dropdown-jenislayanan","data-parsley-errors-container"=>"#errjenislayanan","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-jenislayanan btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
						</td>
                    </tr>
                    <tr>
                        <th >Tahun Penggunaan </th>
                        <td>:</td>
                        <td><?= $tahun ?></td>
                    </tr>
                    <tr>
                        <th >Basis Antarmuka </th>
                        <td>:</td>
                        <td><?= implode(', ',$basis_aplikasi) ?></td>
                    </tr>
                    <tr>
                        <th >Bahasa Pemrograman </th>
                        <td>:</td>
                        <td><?= $bahasa_pemrograman ?></td>
                    </tr>
                    <tr>
                        <th >DBMS </th>
                        <td>:</td>
                        <td><?= $dbms ?></td>
                    </tr>
                    <tr>
                        <th >Lokasi Hosting </th>
                        <td>:</td>
                        <td><?= $lokasi_hosting ?></td>
                    </tr>
                    <tr>
                        <th >Jumlah Pengguna </th>
                        <td>:</td>
                        <td><?= $jumlah_pengguna ?></td>
                    </tr>
                    <tr>
                        <th >Frekuensi Penggunaan </th>
                        <td>:</td>
                        <td><?= $frekuensi_penggunaan ?></td>
                    </tr>
                    <tr>
                        <th >Ketersediaan Modul/Fitur Integrasi </th>
                        <td>:</td>
                        <td><?= $ketersediaan_integrasi ?></td>
                    </tr>
                    <tr>
                        <th >Terintegrasi dengan Aplikasi </th>
                        <td>:</td>
                        <td><?= $aplikasi_integrasi ?></td>
                    </tr>
                    <tr>
                        <th >Metode Integrasi </th>
                        <td>:</td>
                        <td><?= $metode_integrasi ?></td>
                    </tr>
                    <tr>
                        <th >OPD/Unit Kerja Terintegrasi </th>
                        <td>:</td>
                        <td><?= $opd_terintegrasi ?></td>
                    </tr>
                    <tr>
                        <th >Regulasi </th>
                        <td>:</td>
                        <td><?= $regulasi ?></td>
                    </tr><tr>
                      <th width="5%" >Level Privileges</th>
                      <td width="2%">:</td>
                      <td width="88%"><?= nl2br($level_privileges) ?></td>
                  </tr>
                  <tr>
                      <th >Rencana Sistem Informasi / Aplikasi</th>
                      <td>:</td>
                      <td><?= nl2br($rencana_aplikasi) ?></td>
                  </tr>
                  <tr>
                      <th >Keterangan</th>
                      <td>:</td>
                      <td><?= nl2br($keterangan) ?></td>
                  </tr>
                    <tr>
                        <th >File Logo </th>
                        <td>:</td>
                        <td><button href="javascript:;" class="btn btn-success btn-preview" data-name="FILE LOGO APLIKASI" data-link="<?php echo base_url($file_logo)?>" data-ext="<?php echo $ext_file_logo?>"><i class="ti-clip"></i> Lihat File Logo</button></td>
                    </tr>
                </tbody>
            </table>
        </div>&nbsp;<br>
      </div>
      <div class="row">
        <div class="list-group-item table-responsive col-md-6 border-success">
         <h5 class="mb-4">Sumber Daya Manusia</h5>
          <table class="table table-nowrap mb-0">
              <tbody>
                  <tr>
                      <th width="5%" >Nama PIC </th>
                      <td width="2%">:</td>
                      <td width="88%"><?= $pic_aplikasi ?></td>
                  </tr>
                  <tr>
                      <th width="5%" >NIP PIC </th>
                      <td width="2%">:</td>
                      <td width="88%"><?= $nip_pic ?></td>
                  </tr>
                  <tr>
                      <th >Jabatan PIC </th>
                      <td>:</td>
                      <td><?= $jabatan_pic ?></td>
                  </tr>
                  <tr>
                      <th >No. Kontak PIC </th>
                      <td>:</td>
                      <td><?= $no_telp ?></td>
                  </tr>
                  <tr>
                      <th >No Telp </th>
                      <td>:</td>
                      <td><?= $no_telp ?></td>
                  </tr>
                    <tr>
                        <th >File SPT </th>
                        <td>:</td>
                        <td><button href="javascript:;" class="btn btn-warning btn-preview" data-name="FILE SPT" data-link="<?php echo base_url($file)?>" data-ext="<?php echo $ext_file?>"><i class="ti-clip"></i> Lihat File</button></td>
                    </tr>
              </tbody>
          </table>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="control-label">Validasi Sistem Informasi/Aplikasi <span class="text-danger">*</span></label>
            <?php echo form_dropdown('newvalidasi',$listvalidasi,$status, array("data-parsley-class-handler"=>".dropdown-tahun","data-parsley-errors-container"=>"#errtahun","data-parsley-required"=>"true","class"=>"select-picker form-control ","data-style"=>"dropdown-tahun btn btn-outline-secondary waves-effect","data-live-search"=>"false","data-size"=>"5"));?>
            <span  id="errtahun"></span>
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
  });
</script>