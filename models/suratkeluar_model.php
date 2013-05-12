<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Suratkeluar_Model extends Surat{

    //put your code here
    var $lastId;
    private $jns_surat;
    private $user;
    private $rujukan;

    public function __construct() {
        //echo 'ini adalah model</br>';
        parent::__construct();
    }    
       
    public function setTipeSurat($value){
        $this->jns_surat = $value;
    }
    
    public function getTipeSurat(){
        return $this->jns_surat;
    }
    
    public function setUserCreate($value){
        $this->user = $value;
    }
    
    public function getUserCreate(){
        return $this->user;
    }
    
    public function setRujukan($value){
        $this->rujukan = $value;
    }
    
    public function getRujukan(){
        return $this->rujukan;
    }

    public function showAll($limit=null,$batas=null) {
        @Session::createSession();
        $role = Session::get('role');
        $bagian = Session::get('bagian');
        $user = Session::get('user');
        if((Auth::isRole($role, 2) AND !Auth::isBagian($bagian, 1) ) ){
            $sql = "SELECT a.id_suratkeluar as id_suratkeluar,
            a.rujukan as rujukan,
            a.no_surat as no_surat,
            a.tgl_surat as tgl_surat,
            b.nama_satker as tujuan,
            a.perihal as perihal,
            c.sifat_surat as sifat,
            d.klasifikasi as jenis,
            a.lampiran as lampiran,
            a.file as file,
            e.status as status,
            f.tipe_naskah as tipe
            FROM suratkeluar a LEFT JOIN alamat b ON a.tujuan = b.kode_satker
            LEFT JOIN sifat_surat c ON a.sifat = c.kode_sifat
            LEFT JOIN klasifikasi_surat d ON a.jenis = d.kode_klassurat
            LEFT JOIN status e ON a.status = e.id_status
            LEFT JOIN tipe_naskah f ON a.tipe = f.id_tipe 
            LEFT JOIN notifikasi g ON a.id_suratkeluar = g.id_surat
            WHERE g.jenis_surat='SK' AND g.id_user=".User::getIdUser($user)."
            GROUP BY a.id_suratkeluar ORDER BY a.id_suratkeluar DESC";
        }elseif(Auth::isRole($role, 3) ){
            $sql = "SELECT a.id_suratkeluar as id_suratkeluar,
            a.rujukan as rujukan,
            a.no_surat as no_surat,
            a.tgl_surat as tgl_surat,
            b.nama_satker as tujuan,
            a.perihal as perihal,
            c.sifat_surat as sifat,
            d.klasifikasi as jenis,
            a.lampiran as lampiran,
            a.file as file,
            e.status as status,
            f.tipe_naskah as tipe
            FROM suratkeluar a LEFT JOIN alamat b ON a.tujuan = b.kode_satker
            LEFT JOIN sifat_surat c ON a.sifat = c.kode_sifat
            LEFT JOIN klasifikasi_surat d ON a.jenis = d.kode_klassurat
            LEFT JOIN status e ON a.status = e.id_status
            LEFT JOIN tipe_naskah f ON a.tipe = f.id_tipe 
            WHERE a.user='".$user."'
            GROUP BY a.id_suratkeluar ORDER BY a.id_suratkeluar DESC";
            
        }else{
            $sql = "SELECT a.id_suratkeluar as id_suratkeluar,
            a.rujukan as rujukan,
            a.no_surat as no_surat,
            a.tgl_surat as tgl_surat,
            b.nama_satker as tujuan,
            a.perihal as perihal,
            c.sifat_surat as sifat,
            d.klasifikasi as jenis,
            a.lampiran as lampiran,
            a.file as file,
            e.status as status,
            f.tipe_naskah as tipe
            FROM suratkeluar a JOIN alamat b ON a.tujuan = b.kode_satker
            JOIN sifat_surat c ON a.sifat = c.kode_sifat
            JOIN klasifikasi_surat d ON a.jenis = d.kode_klassurat
            JOIN status e ON a.status = e.id_status
            JOIN tipe_naskah f ON a.tipe = f.id_tipe ORDER BY a.id_suratkeluar DESC";
        }
        
//        var_dump($sql);
        if(!is_null($limit) AND !is_null($batas)){
            $sql .= " LIMIT $limit,$batas";
        }
        $data = $this->select($sql);
        $surat = array();
        foreach ($data as $value){
            $obj = new $this;
            $obj->setId($value['id_suratkeluar']);
            $obj->setRujukan($value['rujukan']);
            $obj->setNomor($value['no_surat']);
            $obj->setTglSurat($value['tgl_surat']);
            $obj->setAlamat($value['tujuan']);
            $obj->setPerihal($value['perihal']);
            $obj->setSifat($value['sifat']);
            $obj->setJenis($value['jenis']);
            $obj->setJmlLampiran($value['lampiran']);
            $obj->setFile($value['file']);
            $obj->setStatus($value['status']);
            $obj->setTipeSurat($value['tipe']);
            $surat[] = $obj;
        }

        return $surat;
    }

    public function input($data=null) {
        
        $rekam = $this->insert('suratkeluar', $data);
        if($rekam){
            return true;
        }else{
            return false;
        }
    }

    public function getSuratById($id=null, $aksi=null) {
        if ($aksi == 'detil') {
            $sql = "SELECT a.id_suratkeluar as id_suratkeluar,
                a.rujukan as rujukan,
                a.no_surat as no_surat,
                a.tgl_surat as tgl_surat,
                b.nama_satker as tujuan,
                a.perihal as perihal,
                a.user as user,
                c.sifat_surat as sifat,
                d.klasifikasi as jenis,
                a.lampiran as lampiran,
                a.file as file,
                e.status as status,
                f.tipe_naskah as tipe
                FROM suratkeluar a JOIN alamat b ON a.tujuan = b.kode_satker
                JOIN sifat_surat c ON a.sifat = c.kode_sifat
                JOIN klasifikasi_surat d ON a.jenis = d.kode_klassurat
                JOIN status e ON a.status = e.id_status
                JOIN tipe_naskah f ON a.tipe = f.id_tipe WHERE a.id_suratkeluar=" . $id;
        }elseif ($aksi=='ubah') {
            $sql='SELECT * FROM suratkeluar WHERE id_suratkeluar='.$id;
        }
//        var_dump($sql);
        $data = $this->select($sql);
//        var_dump($data);
        foreach ($data as $value){
            $this->setId($value['id_suratkeluar']);
            $this->setRujukan($value['rujukan']);
            $this->setNomor($value['no_surat']);
            $this->setTglSurat($value['tgl_surat']);
            $this->setAlamat($value['tujuan']);
            $this->setPerihal($value['perihal']);
            $this->setSifat($value['sifat']);
            $this->setJenis($value['jenis']);
            $this->setJmlLampiran($value['lampiran']);
            $this->setTipeSurat($value['tipe']);
            $this->setFile($value['file']);
            $this->setStatus($value['status']);
            $this->setUserCreate($value['user']);
        }
//        var_dump($this->getId());
        return $this;
    }
    
    public function get($table){
        return $this->select('SELECT * FROM '.$table);
    }
    
    public function editSurat($data=null,$where=null){
        $this->update('suratkeluar', $data, $where);
    }
    
    public function remove($where=null){
        /*
         * ntar hapus juga menghapus semua hal yg 
         * berhubungan dengan surat ini, termasuk lampiran dsb
         */
        echo $where;
//        $this->delete('suratkeluar', $where);
    }
    
    public function uploadFile($data,$where){
    
        $this->update('suratkeluar', $data, $where);
    }
    
    public function addRevisi($data){
        $this->insert('revisisurat', $data);
    }
    
    public function lastIdInsert($tipe){
        $sql = "SELECT MAX(id_suratkeluar) as id FROM suratkeluar WHERE tipe=".$tipe;
        $data = $this->select($sql);
        
        foreach ($data as $val){
            $this->lastId = $val['id'];
        }
        
        return $this->lastId;
        
    }
    
    public function getUser($id){
        $user = '';
        $datas = $this->select("SELECT user FROM suratkeluar WHERE id_suratkeluar=".$id);
        foreach ($datas as $val){
            $user = $val['user'];
        }
        $datab = $this->select("SELECT id_user, role, bagian FROM user WHERE username='".$user."'");
        $user =array();
        foreach ($datab as $val){
            $user[0] = $val['id_user'];
            $user[1] = $val['role'];
            $user[2] = $val['bagian'];
        }
        
        return $user;
    }
    
    public function getHistoriRevisi($id){
        
        $sql = "SELECT a.id_revisi as id_revisi, 
            a.catatan as catatan, 
            b.namaPegawai as user, 
            a.file as file, 
            a.time as time
            FROM revisisurat a LEFT JOIN user b
            ON a.user=b.username
            WHERE a.id_surat=".$id;
        $data = $this->select($sql);
        return $data;
    }
    
    

}

?>
