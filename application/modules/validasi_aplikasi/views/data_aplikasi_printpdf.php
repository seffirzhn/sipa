<style type="text/css">

.printpreview tr th {
  font-size: 9pt;
  font-weight: bold;
  font-family: "Cambria",sans-serif;
  padding: 4px;
  text-transform: uppercase;
}
.printpreview tr td {
  font-size: 9pt;
  font-family: "Cambria",sans-serif;
  vertical-align: middle;
  padding: 4px;
}

.printpreview h4,.printpreview h2,.printpreview h3,.printpreview h5{
  text-align: center;
  font-family: "Cambria",sans-serif;
  font-weight: 300;
}

.printpreview table{
  width: 100%;
  border-collapse: collapse;
}
.printpreview table.table, .printpreview table.table th, .printpreview table.table td {
    border: 0.5px solid #333;
}

@page
{
  margin:1cm 1cm 1cm 1cm;
}

.printpreview table.tablejudul tr td {
    font-size: 11pt;
}

.printpreview table.tablejudul tr td {
    padding: 2px !important;
}

.printpreview{
    font-size: 9.5pt;
}

.center {
    text-align: center;
    align-items: center;
}

img {
    display: inline-block;
    vertical-align: middle;
    max-width: 28%;
    height: auto;
    margin: 0 20px 10px 20px;
}
.ttd {
  position: absolute;
  float: right;
  width: 320px;
  /* padding: 10px; */
  margin: 0;
}

</style>
<div class="printpreview">
<br/>
<h2 style="margin:5px 0px;font-size: 16pt"><strong>LAPORAN PENDATAAN APLIKASI OPD</strong></h2><br>
<h2 style="margin:5px 0px;font-size: 16pt"><strong><?= $opd ?></strong></h2>

<table class="tablejudul">
    <tr>
        <td width="15%">Data per tanggal </td>
        <td width="1%">:</td>
        <td width="84%"><?= changedate(date("l, d F Y")) ?></td>
    </tr>
    <tr>
        <td width="15%">Jenis Layanan </td>
        <td width="1%">:</td>
        <td width="84%"><?= $jenis!="" ? $jenis_layanan : "Semua Layanan" ?></td>
    </tr>
</table>
 
<!-- <div style="border-top: 3px solid #000;border-bottom: 2px solid #000;padding-top:2px;margin-bottom: 5px;margin-top: 5px"></div>  -->
<br />
<table class="table">
  <thead>
    <tr >
      <th width="3%"><strong>No</strong></th>
      <th width="10%"><strong>Nama Aplikasi</strong></th>
	  <th width="15%"><strong>Deskripsi Singkat</strong></th>
      <th width="10%"><strong>OPD</strong></th>
	  <th width="10%"><strong>Asal Aplikasi</strong></th>
      <th width="8%"><strong>Tahun</strong></th>
      <th width="12%"><strong>Jenis Layanan</strong></th>
	  <th width="12%"><strong>Antar Muka</strong></th>
      <th width="15%"><strong>PIC</strong></th>
    </tr>
  </thead>
  <tbody>
	  <?php $i=0;
	  	foreach($data->result_array() as $val){
			$i++; ?>
	  <tr>
		  <td><?= $i ?></td>
		  <td><?= $val["nama_aplikasi"] ?></td>
		  <td><?= $val["deskripsi_aplikasi"] ?></td>
		  <td><?= $val["nama_opd"] ?></td>
		  <td><?= $val["penyedia_aplikasi"] ?></td>
		  <td class="center"><?= $val["tahun"] ?></td>
		  <td><?= $val["jenis_layanan"] ?></td>
		  <td><?= $val['basis_aplikasi'].'<br><br>https://'. $val['nama_domain'] ?></td>
		  <td><?= $val["pic_aplikasi"].'<br>'.$val["nip_pic"].'<br>'.$val["jabatan_pic"] ?></td>
	  </tr>
	  
	  <?php
		}
	  ?>
  </tbody>        
</table><br>
<div class="ttd">
<p style="margin:5px 0px;font-size: 11pt"><strong>Kepala Bidang Teknologi Informasi dan Komunikasi</strong></p>
<br />
<br />
<br />
<br />
<p style="margin:2px 0px;font-size: 11pt"><strong><u>Susilo, S.Kom</u></strong></p>
<p style="margin:2px 0px;font-size: 11pt">Pembina</p>
<p style="margin:2px 0px;font-size: 11pt">NIP. 19800921 200902 1 005</p>
</div>