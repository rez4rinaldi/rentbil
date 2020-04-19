<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Rental extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('transaksi_model');
        $this->load->model('rental_model');
        $this->load->model('pesan_model');
        $this->load->model('mobil_model');
    }

    public function tambah_rental($id)
    {
        check_not_login();

        $data['title'] = 'Tambah Rental';
        $data['mobil'] = $this->mobil_model->ambil_id_mobil($id);

        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/tambah_rental', $data);
        $this->load->view('template_customer/footer');
    }

    public function tambah_rental_ready_simpan()
    {
        check_not_login();

        $id = $this->input->post('id_mobil');
        $mobil = $this->mobil_model->ambil_id_mobil($id);
        $tgl_sewa = $this->input->post('tgl_sewa');
        $tgl_kembali = $this->input->post('tgl_kembali');
        $durasi = abs(round((strtotime($tgl_sewa) - strtotime($tgl_kembali)) / 86400));
        $data = array(
            'tgl_sewa' => $tgl_sewa,
            'tgl_kembali' => $tgl_kembali,
            'durasi' => $durasi,
            'mobil' => $mobil
        );
        $data['title'] = 'Tambah Rental Ready Simpan';
        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/tambah_rental_ready', $data);
        $this->load->view('template_customer/footer');
    }

    public function tambah_rental_simpan()
    {
        check_not_login();

        $id_mobil = $this->input->post('id_mobil');
        $tgl_sewa = strtotime($this->input->post('tanggal_sewa'));
        $tgl_sewa = date("Y-m-d", $tgl_sewa);
        $tgl_kembali = $this->input->post('tanggal_kembali');
        $durasi = abs(round((strtotime($tgl_sewa) - strtotime($tgl_kembali)) / 86400));
        $harga = $this->input->post('harga');
        $total_sewa = $harga * $durasi;
        $status = 1;
        $status_pembayaran = 0;

        $data = array(
            'id_user'   => $this->session->userdata('id_user'),
            'id_mobil' => $id_mobil,
            'tanggal_sewa' => $tgl_sewa,
            'tanggal_kembali' => $tgl_kembali,
            'total_sewa' => $total_sewa,
            'status' => $status,
            'status_pembayaran' => $status_pembayaran
        );

        $this->rental_model->insert_data($data, 'transaksi');
        if ($status == 1) {
            $this->transaksi_model->insert_status_mobil_kosong($id_mobil, 'mobil');
        } else {
            $this->transaksi_model->insert_status_mobil_sedia($id_mobil, 'mobil');
        };

        $tglsewa = strtotime($this->input->post('tanggal_sewa'));
        $jmlhari  = 86400 * 1;
        $tgl      = $tglsewa - $jmlhari;
        $batas_bayar = date("d-m-Y", $tgl);

        $merk = $this->input->post('nama_mobil');
        $durasi = $this->input->post('durasi');
        $data = array(
            'nama'   => $this->session->userdata('nama'),
            'merk' => $merk,
            'tanggal_sewa' => $tgl_sewa,
            'tanggal_kembali' => $tgl_kembali,
            'durasi' => $durasi,
            'total_sewa' => $total_sewa,
            'batas_bayar' => $batas_bayar
        );

        echo "<script>alert('Mobil Berhasil Dibooking')</script>";
        $data['title'] = 'Detail Sewa';
        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/detail_sewa', $data);
        $this->load->view('template_customer/footer');
    }

    public function riwayat_sewa()
    {
        check_not_login();

        $data['title'] = 'Riwayat Sewa';

        $id_user = $this->fungsi->user_login()->id_user;
        $data['transaksi'] = $this->rental_model->get_transaksi($id_user);
        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/riwayat_sewa', $data);
        $this->load->view('template_customer/footer');
    }

    //fitur cetak sewa
    public function cetak_sewa($id)
    {
        check_not_login();

        $data['title'] = 'Detail Sewa';
        $data['detail'] = $this->rental_model->get_transaksi_id($id);
        $this->load->view('customer/cetak_sewa', $data);
    }

    public function konfirmasi_pembayaran($id)
    {
        check_not_login();

        $data['id_transaksi'] = $id;
        $data['title'] = 'Konfirmasi Pembayaran';

        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/konfirmasi_pembayaran', $data);
        $this->load->view('template_customer/footer');
    }

    public function konfirmasi_pembayaran_simpan()
    {
        check_not_login();

        $id_transaksi = $this->input->post('id_transaksi');

        $bukti_pembayaran = $_FILES['bukti_pembayaran']['name'];

        $config['upload_path'] = './assets/upload/bukti_pembayaran';
        $config['allowed_types'] = 'jpg|jpeg|png';

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('bukti_pembayaran')) {
            echo "<script>alert('Bukti Pembayaran Gagal di-Upload')</script>";
        } else {
            $bukti_pembayaran = $this->upload->data('file_name');
            echo "<script>
            alert('Bukti Pembayaran Berhasil di-Upload');
            </script>";
        }

        $status_pembayaran = 1;

        $data = array(
            'bukti_pembayaran' => $bukti_pembayaran,
            'status_pembayaran' => $status_pembayaran
        );
        $where = array(
            'id_transaksi' => $id_transaksi
        );

        $this->transaksi_model->edit_data('transaksi', $data, $where);

        echo "<script>window.location='" . base_url('customer/rental/riwayat_sewa') . "';</script>";
    }

    public function tentang_kami()
    {
        $data['title'] = 'Tentang Kami';

        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/tentang_kami');
        $this->load->view('template_customer/footer');
    }

    public function faqs()
    {
        $data['title'] = 'FAQs';

        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/faqs');
        $this->load->view('template_customer/footer');
    }

    public function kotak_pesan()
    {
        check_not_login();

        $data['title'] = 'Kotak Pesan';
        $data['user'] = $this->user_model->get_data('user')->result();

        $this->load->view('template_customer/header', $data);
        $this->load->view('customer/kotak_pesan');
        $this->load->view('template_customer/footer');
    }

    public function kirim_pesan()
    {
        check_not_login();

        $this->pesan_model->insert_data('pesan');

        $this->session->set_flashdata('pesan', '
        <div class="alert alert-success alert-dismissible fade show" role="alert">
        Pesan Berhasil Dikirim
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button></div>');
        redirect('customer/rental/kotak_pesan');
    }
}
