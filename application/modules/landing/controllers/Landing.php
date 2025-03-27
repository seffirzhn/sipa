<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends MX_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Daftar_aplikasi');
    }
    public function index() {
        $search = $this->input->get('search');
        $jenis = $this->input->get('jenis');

        $data['aplikasi'] = $this->Daftar_aplikasi->get_aplikasi($search, $jenis);
        $data['jenis_layanan'] = $this->Daftar_aplikasi->get_jenis_layanan(); 
        $data['selected_jenis'] = $jenis;

    $this->load->view('landing_page', $data);}
}