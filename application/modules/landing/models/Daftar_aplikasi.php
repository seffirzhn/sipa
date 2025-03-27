<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_aplikasi extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_aplikasi($search = '', $jenis = 'all') {
        $this->db->select("da.nama_aplikasi, 
                            da.deskripsi_aplikasi, 
                            da.nama_domain, 
                            da.file_logo, 
                            da.id_jenis_layanan, 
                            mjl.jenis_layanan");
        $this->db->from('data_aplikasi as da');
        $this->db->join('master_jenis_layanan as mjl', 'da.id_jenis_layanan = mjl.id_jenis_layanan', 'left');

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('da.nama_aplikasi', $search);
            $this->db->or_like('da.deskripsi_aplikasi', $search);
            $this->db->group_end();
        }
        if (!empty($jenis) && $jenis != 'all') {
            $this->db->where('da.id_jenis_layanan', $jenis);
        }

        return $this->db->get()->result();
    }

    public function get_jenis_layanan() {
        $this->db->select('mjl.id_jenis_layanan, mjl.jenis_layanan');
        $this->db->from('master_jenis_layanan as mjl');
        return $this->db->get()->result();
    }
}
