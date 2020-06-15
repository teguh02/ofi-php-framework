<?php

namespace App\Controllers;

use vendor\Controller;
use App\Core\helper as h; // Untuk mempersingkat nama class
use vendor\DB;

class indexController extends Controller
{
    /**
     * Contoh kode
     * 
     * Untuk mengembalikan nilai menjadi json
     *  return $this->response() -> json($res, 200);
     * 
     * Untuk mengembalikan menjadi error 404 atau error 500
     *  return $this->response() -> error404();
     *  return $this->response() -> error500($yourMessageHere);
     * 
     * Untuk memberikan response pengalihan halaman, ke halaman lainnya
     *  $this->response() -> redirect('http://google.com');
     *  $this->response() -> redirect('/login');
     * 
     */

    public function getAll()
    {
        // Memanggil class Database
        $q = new DB(); 
        $res =  $q -> select() -> from('blog') 
                -> where('kategori', 'artikel') 
                -> where('id_user', 1) 
                -> get();

                // -> get(); untuk mendapatkan semua data
                // -> first(); untuk mendapatkan data pertama saja  

       return $this->response()  -> json($res, 200);
    }

    public function getOr()
    {
        // Memanggil class Database
        $q = new DB(); 
        $res =  $q -> select() -> from('blog') 
                -> whereOr('kategori', 'artikel') 
                -> whereOr('kategori', 'berita') 
                -> get();

                // -> get(); untuk mendapatkan semua data
                // -> first(); untuk mendapatkan data pertama saja
                // -> total(); untuk mendapatkan jumlah semua data       

        // Memberikan response berupa json (200 merupakan http code statusnya)
       return $this->response()  -> json($res, 200);

        // Memberikan response berupa print_r yang telah dipercantik
       return $this->response() -> print_r($res);

       // Memberikan response berupa var_dump yang telah dipercantik
       return $this->response() -> var_dump($res);
    }

    public function index()
    {
        // Must post to enter this method
        if ($this->must_post()) {
            $id_user = 1;
            $kategori = 'artikel';
            $user = 'teguh';
            $judul = 'OFI PHP Framework';
            $isi = 'Sebuah framework PHP yang mengadaptasi laravel dan codeigniter, namun dibuat lebih sederhana dan ukuran yang lebih kecil';

            // Memanggil class Database
            $q = new DB(); 
            $res = $q  -> insert() 
                    -> into('blog') 
                    -> value('id_user', $id_user)
                    -> value('kategori', $kategori)
                    -> value('user', $user)
                    -> value('judul', $judul)
                    -> value('slug', h::slug($judul))
                    -> value('isi', $isi)
                    -> save()
            ;

            return $this->response() -> json($res, 200);
            
        }
    }

    public function deleteData()
    {
        // Memanggil class Database
        $q = new DB(); 

        $res = $q -> delete() 
                -> from('blog') 
                -> where('id', 23);

        return $this->response() -> json($res, 200);
    }

    public function updateData()
    {
        $id_user = 1;
        $kategori = 'berita';
        $user = 'teguh02';
        $judul = 'OFI PHP Framework';

        // Memanggil class Database
        $q = new DB(); 

        $hasil = $q -> update() 

                    // Into = nama tabelnya
                    -> into('blog')

                    // value update data
                    -> value('id_user', $id_user)
                    -> value('kategori', $kategori)
                    -> value('user', $user)
                    -> value('judul', $judul)

                    // Menentukan update berdasarkan apa
                    -> where('id', 9)
                    -> where('slug', 'berita-corona-2')

                    // Simpan data
                    -> save();


        // Berikan response proses menjadi json
        return $this->response() -> json($hasil, 200);
    }

    public function manualSql()
    {
        # Kamu bisa menuliskan kode sql apapun yang kamu mau
        # dan dijalankan oleh sistem, sebagai contoh
        # Menampilkan artikel yang bukan kategori artikel
        # dan bukan user bernama teguh

        // Memanggil class Database
        $q = new DB(); 

        // gunakan method sql() sebagai tempat meletakan kode sql
        // gunakan method run() untuk menjalankannya

        $hasil = $q -> sql("DELETE FROM blog WHERE id=25")
                    -> run();

        // tampilkan menggunakan fungsi print_r
        return $this->response() -> print_r($hasil);
    }

    public function requestExample()
    {
        // Untuk mendapatkan semua value yang ada di header
        $d = $this->request() -> header() -> all();

        // Untuk mendapatkan hanya satu value yang ada di header
        $d = $this->request() -> header() -> first('nama');

        // Untuk mendapatkan value inputan baik secara POST method
        // maupun GET method
        $d = $this->request() -> input('nama');

        print_r($d);
    }

    public function view()
    {
        $this->loadView('index', []);
    }

    public function uploadImage()
    {
        # Memanggil class helper dengan alias h dan 
        # memanggil method upload

        $upload = h::upload([
            'form' => 'gb', // Input form name here
            'folder' => 'gambar', // Folder name what do you wants to save your file
        ]);

        $upload['status']; // To get upload status
        $upload['filename']; // To get filename after upload proccess
        $upload['filesize']; // To get filesize after upload proccess
        $upload['storageLocation']; // To where file are saved

        $this->response() -> json($upload, 200);
    }
}
