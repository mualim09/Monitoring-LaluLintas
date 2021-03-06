<?php

class DataUser extends CI_Controller{

  public function __construct(){
    parent::__construct();
    
    if($this->session->userdata('hak_akses') != '1'){
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Anda belum login!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('welcome');
    }
  }

  public function index(){
    $data['title'] = "Data User";
    $data['users'] = $this->penggajianModel->get_data('data_pegawai')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/dataUser', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahData(){
    $data['title'] = "Tambah Data Pegawai";
    $data['jabatan'] = $this->penggajianModel->get_data('data_jabatan')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/formTambahUser', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi(){
    $this->_rules();

    if($this->form_validation->run() == FALSE){
      $this->tambahData();
    }
    else{
      $nik           = $this->input->post('nik');
      $nama_pegawai  = $this->input->post('nama_pegawai');
      $jenis_kelamin = $this->input->post('jenis_kelamin');
      $tanggal_masuk = $this->input->post('tanggal_masuk');
      $jabatan       = "Staff Marketing";
      $status        = "Pegawai Tetap";
      $hak_akses     = $this->input->post('hak_akses');
      $username      = $this->input->post('username');
      $email         = $this->input->post('email');
      $alamat        = $this->input->post('alamat');
      $password      = md5($this->input->post('password'));
      $photo         = null;

      $data = array(
        'nik'           => $nik,
        'nama_pegawai'  => $nama_pegawai,
        'jenis_kelamin' => $jenis_kelamin,
        'jabatan'       => $jabatan,
        'tanggal_masuk' => $tanggal_masuk,
        'status'        => $status,
        'hak_akses'     => $hak_akses,
        'username'      => $username,
        'email'         => $email,
        'alamat'        => $alamat,
        'password'      => $password,
        'photo'         => $photo,
      );

      $this->penggajianModel->insert_data($data, 'data_pegawai');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataUser');
    }
  }

  public function updateData($id){
    // $where = array('id_pegawai' => $id);
    $data['title'] = "Update Data User";
    $data['jabatan'] = $this->penggajianModel->get_data('data_jabatan')->result();
    $data['pegawai'] = $this->db->query("SELECT * FROM data_pegawai WHERE id_pegawai = '$id'")->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/formUpdateUser', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi(){
    $this->_rules();

    if($this->form_validation->run() == FALSE){
      $id = $this->input->post('id_pegawai');
      $this->updateData($id);
    }
    else{
      $id            = $this->input->post('id_pegawai');
      $nik           = $this->input->post('nik');
      $nama_pegawai  = $this->input->post('nama_pegawai');
      $jenis_kelamin = $this->input->post('jenis_kelamin');
      $tanggal_masuk = $this->input->post('tanggal_masuk');
      $jabatan       = $this->input->post('jabatan');
      $status        = $this->input->post('status');
      $hak_akses     = $this->input->post('hak_akses');
      $username      = $this->input->post('username');
      $email         = $this->input->post('email');
      $alamat        = $this->input->post('alamat');
      $password      = md5($this->input->post('password'));

      $data = array(
        'nik'           => $nik,
        'nama_pegawai'  => $nama_pegawai,
        'jenis_kelamin' => $jenis_kelamin,
        'jabatan'       => $jabatan,
        'tanggal_masuk' => $tanggal_masuk,
        'status'        => $status,
        'hak_akses'     => $hak_akses,
        'username'      => $username,
        'email'         => $email,
        'alamat'        => $alamat,
        'password'      => $password,
      );

      $where = array('id_pegawai' => $id);

      $this->penggajianModel->update_data('data_pegawai', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataUser');
    }
  }

  public function deleteData($id){
    $where = array('id_pegawai' => $id);
    $this->penggajianModel->delete_data($where, 'data_pegawai');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataUser');
  }

  public function _rules(){
    $this->form_validation->set_rules('nik', 'NIK', 'required');
    $this->form_validation->set_rules('nama_pegawai', 'Nama Pegawai', 'required');
    $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
    $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');
    $this->form_validation->set_rules('username', 'Username', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
  }


}