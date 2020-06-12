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
        $q = new DB(); 
        $res =  $q -> select() -> from('blog') 
                -> where('kategori', 'artikel') 
                -> where('id_user', 1) 
                -> get();

                // -> get(); untuk mendapatkan semua data
                // -> first(); untuk mendapatkan data pertama saja
                // -> total(); untuk mendapatkan jumlah semua data       

       return $this->response()  -> json($res, 200);
    }

    public function getOr()
    {
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
            echo 'it works, must post';
        }
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
}
