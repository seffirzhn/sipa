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
  vertical-align: top;
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
<h2 style="margin:5px 0px;font-size: 16pt"><strong>LAPORAN HARIAN PELAKSANAAN KAMPANYE <?= strtoupper($daerah); ?></strong></h2>

<table class="tablejudul">
    <tr>
        <td width="10%">Provinsi/Kab/Kota</td>
        <td width="1%">:</td>
        <td width="89%"><?php echo $daerah != "" ? $daerah : "-" ?></td>
    </tr>
    <tr>
        <td width="10%">Hari/Tanggal</td>
        <td width="1%">:</td>
        <td width="89%"><?php echo $hari_tanggal?></td>
    </tr>
</table>
 
<!-- <div style="border-top: 3px solid #000;border-bottom: 2px solid #000;padding-top:2px;margin-bottom: 5px;margin-top: 5px"></div>  -->
<br />
<table class="table">
  <thead>
    <tr >
      <th width="3%"><strong>No</strong></th>
      <th width="7%"><strong>Waktu</strong></th>
      <th width="8%"><strong>Tempat</strong></th>
      <th width="28%"><strong>Uraian Kegiatan</strong></th>
      <th width="12%"><strong>Tokoh Politik Yang Hadir</strong></th>
      <th width="15%"><strong>Paslon</strong></th>
      <th width="7%"><strong>Jumlah Peserta</strong></th>
      <th width="10%"><strong>keterangan</strong></th>
    </tr>
  </thead>
  <tbody>
    <?php  
      if($data->num_rows()>0){ 
        $num = 0;
        foreach ($data->result_array() as $key => $val) {
    ?>
      <tr>
        <td align="center"><?php echo ++$num?></td>
        <td><?php echo changedate(date("l",strtotime($val["tanggal"])))."<br />".changedate(date("d F Y",strtotime($val["tanggal"])))."<br />"."Pukul : ".$val["waktu"]?></td>
        <td><?php echo $val["tempat_kegiatan"]?></td>
        <td><?php echo $val["uraian_kegiatan"]?></td>
        <td><?php echo $val["tokoh_hadir"]?></td>
        <td><?php echo $val["paslon"]?></td>
        <td align="center"><?php echo $val["jumlah_peserta"]." Orang"?></td>
        <td><?php echo $val["keterangan"]?></td>
      </tr>
    <?php   
        }
      }
    ?> 
  </tbody>        
</table>
<div class="ttd">
<p style="margin:5px 0px;font-size: 11pt"><strong>SEKRETARIS HELP DESK PILKADA,</strong></p>
<br />
<br />
<br />
<br />
<p style="margin:2px 0px;font-size: 11pt"><strong><u>Ir. LAMIDI, MM</u></strong></p>
<p style="margin:2px 0px;font-size: 11pt">Pembina Utama Madya</p>
<p style="margin:2px 0px;font-size: 11pt">NIP. 19620626 199003 1 008</p>
</div>
<pagebreak>
<h2 style="margin:5px 0px;font-size: 16pt"><strong>DOKUMENTASI KEGIATAN TIM DESK PILKADA PEMANTAUAN PERKEMBANGAN
POLITIK DAERAH PADA PELAKSANAAN PILKADA SERENTAK TAHUN 2020 <?= strtoupper($daerah); ?></strong></h2>
<!-- <div style="border-top: 3px solid #000;border-bottom: 2px solid #000;padding-top:2px;margin-bottom: 5px;margin-top: 5px"></div>  -->
<br />
<div class="center">
<?php
if($data_foto->num_rows()>0){
    foreach($data_foto->result_array() as $key =>$dt) {
        echo '<img class="grid-item" src='.$dt["file_foto"].'>';
        }
}
?>
</div>

</div>