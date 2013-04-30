<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of suratMasuk
 *
 * @author aisyah
 */
class Suratmasuk_Controller extends Controller {

    public function __construct() {
        @parent::__construct($registry);
        Auth::handleLogin();
        $this->nomor = new Nomor();
        $this->view->kantor = Kantor::getNama();
        $this->view->js = array(
            'suratmasuk/js/default',
            'suratkeluar/js/jquery.tipTip',
            'suratkeluar/js/jquery.tipTip.minified'
        );
        //$this->view = new View;
        //echo "</br>kelas berhasil di bentuk";
    }

    //put your code here

    public function index() {
        //$this->view->render('suratmasuk/index');
        //header('location:'.URL.'suratmasuk/showall');
        $this->showAll();
    }

    public function showAll() {
        
        $this->view->listSurat = $this->model->showAll();

        $this->view->render('suratmasuk/suratmasuk');
    }

    public function edit($id_sm = null, $ids = null) {
        if(isset($_POST['submit'])){
            if($this->editSurat()){
            
                $this->view->success="Ubah data suratmasuk berhasil";
            }else{
                $this->view->error ="Ubah data suratmasuk gagal!";
            }
        }
        if (!is_null($id_sm)) {
//cek id_sm jika panjang=5 maka kode satker
            $length = strlen($id_sm);
            //echo $length . " " . $id_sm;
            if ($length == 6) {
                $this->view->alamat = $id_sm;
                $almt = new Admin_Model();
                $alamat = $almt->getAlamat($id_sm);
                //$this->view->alamat
                foreach ($alamat as $value) {
                    $this->view->alamat .= ' ' . $value['nama_satker'];
                }
                //echo $this->view->alamat;
                if (!is_null($ids)) {
                    $this->view->data = $this->model->getSuratById($ids);
                    $this->view->sifat = $this->model->get('sifat_surat');
                    $this->view->jenis = $this->model->get('klasifikasi_surat');
                    //var_dump($this->view->jenis);
                }
            } else {


                $this->view->data = $this->model->getSuratById($id_sm);
                $this->view->sifat=$this->model->get('sifat_surat');
                $this->view->jenis=$this->model->get('klasifikasi_surat');
                //var_dump($this->view->jenis);
            }
        }
        /** **/
        //$this->view->data = $this->model->getSuratMasukById($ids);
        $this->view->render('suratmasuk/ubah');
    }

    public function remove($id) {
        $this->model->remove($id);
    }

    public function editSurat() {
        $data = array(
            "tgl_terima"=>$_POST['tgl_terima'],
            "tgl_surat"=>$_POST['tgl_surat'],
            "no_surat"=>$_POST['no_surat'],
            "asal_surat"=>$_POST['asal_surat'],
            "perihal"=>$_POST['perihal'],
            "status"=>$_POST['status'],
            "sifat"=>$_POST['sifat'],
            "jenis"=>$_POST['jenis'],
            "lampiran"=>$_POST['lampiran']
        );
        
        $id = $_POST['id'];
        $where = "id_suratmasuk = '".$id."'";
        return $this->model->editSurat($data,$where);
        
    }

    public function input() {
        $tglagenda = date('Y-m-d');
        $asal = trim($_POST['asal_surat']);
        $asal = explode(' ', $asal);
        $start = date('Y-m-d h:m:s');
        $data = array(
            "no_agenda"=>$_POST['no_agenda'],
            "tgl_terima"=> $tglagenda,
            "tgl_surat"=>  Tanggal::ubahFormatTanggal($_POST['tgl_surat']),
            "no_surat"=>$_POST['no_surat'],
            "asal_surat"=>$asal[0],
            "perihal"=>$_POST['perihal'],            
            "status"=>$_POST['status'],
            "sifat"=>$_POST['sifat'],
            "jenis"=>$_POST['jenis'],
            "lampiran"=>$_POST['lampiran'],
            "stat"=>'11',
            "start"=>$start
        );
        if( $this->model->input($data)){
            $notif = new Notifikasi();
            $datakk = $this->model->select("SELECT id_user FROM user WHERE role=1 AND bagian =1 AND active='Y'");
            foreach($datakk as $val){
                $notif->set('id_user',$val['id_user']);
            }
            $notif->set('id_surat',$this->model->lastIdInsert());
            $notif->set('jenis_surat','SM');
            $notif->set('role',1);
            $notif->set('bagian',1);
            $notif->set('stat_notif',1);
            //$data1 =array(
                //'id_surat'=>$id_surat,
                //'jenis_surat'=>$jenis_surat,
                //'id_user'=>$kk,
                //'stat_notif'=>$stat_notif
            //);
            //var_dump($data1);
            $notif->addNotifikasi();
//            die($this->msg(1,"rekam data berhasil"));
            $this->view->agenda = $this->nomor->generateNumber('SM');
            $this->view->success = 'rekam data berhasil';
            $this->view->render('suratmasuk/rekam');
            
        }else{
            
//            die($this->msg(1,"rekam data tidak berhasil"));
            $this->view->agenda = $this->nomor->generateNumber('SM');
            $this->view->error = 'rekam data tidak berhasil';
            $this->view->render('suratmasuk/rekam');
        }
        
        
       
        //header('location:'.URL.'suratmasuk');
    }
    
    public function msg($status,$txt){
            return '{status:'.$status.',txt:"'.$txt.'"}';
        }
        
    public function rekam($s = null) {
        if (!is_null($s)) {

            $this->view->alamat = $s;
            $almt = new Admin_Model();
            $alamat = $almt->getAlamat($s);            
            //$this->view->alamat
            
            foreach ($alamat as $value) {
                $this->view->alamat .= ' ' . $value['nama_satker'];
            }
        }
        $this->view->sifat = $this->model->get('sifat_surat');
            $this->view->jenis = $this->model->get('klasifikasi_surat');
        $this->view->agenda = $this->nomor->generateNumber('SM');
        $this->view->render('suratmasuk/rekam');
    }

    public function detil($id) {
        $agenda = substr($id, 0, 1);
        
        if($agenda != 'S'){
            $this->view->dataSurat = $this->model->getSuratById($id);
        }else{
            $sql = "SELECT * FROM suratmasuk WHERE no_agenda='".$id."'";            
            $this->view->dataSurat = $this->model->select($sql);
        }        
        foreach ($this->view->dataSurat as $key => $value) {
            $this->view->data[0] = $value['id_suratmasuk'];
            $this->view->data[1] = $value['no_agenda'];
            $this->view->data[2] = $value['tgl_terima'];
            $this->view->data[3] = $value['tgl_surat'];
            $this->view->data[4] = $value['no_surat'];
            $this->view->data[5] = $value['asal_surat'];
            $this->view->data[6] = $value['perihal'];
            $this->view->data[7] = $value['file'];
        }
        //var_dump($this->view->dataSurat);
        $lamp = new Lampiran_Model();
        $this->view->lampiran = $lamp->getLampiranSurat($this->view->data[0], 'SM');
//        var_dump($this->view->lampiran);
        $this->view->count = count($this->view->lampiran);
        $this->view->render('suratmasuk/detilsurat');
    }

    public function disposisi($id) {
        if(isset($_POST['submit'])){
            if($this->rekamdisposisi()){
                $this->view->success="Rekam disposisi berhasil";
            }else{
                $this->view->error="Rekam disposisi gagal!";
            }
        }
        $data = $this->model->getSuratById($id);

        foreach ($data as $value) {
            $this->view->data['id_suratmasuk'] = $value['id_suratmasuk'];
            $this->view->data['no_surat'] = $value['no_surat'];
            $this->view->data['status'] = $value['status'];
            $this->view->data['tgl_terima'] = Tanggal::tgl_indo($value['tgl_terima']);
            $this->view->data['tgl_surat'] = Tanggal::tgl_indo($value['tgl_surat']);            
            $this->view->data['no_agenda'] = $value['no_agenda'];
            $this->view->data['lampiran'] = $value['lampiran'];
            $sql = "SELECT sifat_surat FROM sifat_surat WHERE kode_sifat ='" . $value['sifat']."'";
            
            $sifat = $this->model->select($sql);
            
            foreach ($sifat as $value2) {
                $this->view->data['sifat'] = $value2['sifat_surat'];
            }
            $sql2 = "SELECT klasifikasi FROM klasifikasi_surat WHERE kode_klassurat ='" . $value['jenis']."'";
            $jenis = $this->model->select($sql2);
            foreach ($jenis as $value3) {
                $this->view->data['jenis'] = $value3['klasifikasi']; 
            }
            $sql3 = 'SELECT nama_satker FROM alamat WHERE kode_satker=' . trim($value['asal_surat']);
            $asal = $this->model->select($sql3);            
            foreach ($asal as $value1) {
                $this->view->data['asal_surat'] = $value1['nama_satker'];
            }
            
            $this->view->data['perihal'] = $value['perihal'];
        }
        $this->view->seksi = $this->model->get('r_bagian');
        $this->view->petunjuk = $this->model->get('r_petunjuk');
        $this->view->data2 = $this->model->select('SELECT * FROM disposisi WHERE id_surat=' . $id);
        $this->view->count = count($this->view->data2);
        //echo $this->view->count;
        //var_dump($this->view->petunjuk);
        if ($this->view->count > 0) {

            foreach ($this->view->data2 as $key => $value) {
                $this->view->disp[0] = $value['id_disposisi'];
                $this->view->disp[1] = $value['id_surat'];
                $this->view->disp[2] = $value['sifat'];
                $this->view->disp[3] = $value['disposisi'];
                $this->view->disp[4] = $value['petunjuk'];
                $this->view->disp[5] = $value['catatan'];
            }
            $this->view->disposisi = explode(',', $this->view->disp[3]);
            $this->view->petunjuk2 = explode(',', $this->view->disp[4]);
            //var_dump($this->view->petunjuk2);

            //$this->view->render('suratmasuk/disposisi');
        } 
            $this->view->render('suratmasuk/disposisi');
       
    }
    
    //sepertinya sama dengan method sebelumnya
    public function ctkDisposisi($id) {
        $disposisi = new Disposisi();
        $this->view->darray = array();
//        $arrayid = explode(",", $id);
        if(is_array($id)){
            $count = count($id);
//            var_dump($id);
//            var_dump($count);
            $this->view->array = true;            
            for($i=0;$i<$count;$i++){
//                var_dump($i);
//                var_dump($id[$i]);
                $datas = $this->model->getSuratById($id[$i]);

                foreach ($datas as $value) {
                    $this->view->data[$i]['id_suratmasuk'] = $value['id_suratmasuk'];
                    $this->view->data[$i]['no_surat'] = $value['no_surat'];
                    $this->view->data[$i]['status'] = $value['status'];
                    $this->view->data[$i]['tgl_terima'] = Tanggal::tgl_indo($value['tgl_terima']);
                    $this->view->data[$i]['tgl_surat'] = Tanggal::tgl_indo($value['tgl_surat']);
                    $sql = 'SELECT sifat_surat FROM sifat_surat WHERE kode_sifat ="' . trim($value['sifat'].'"');
                    $sifat = $this->model->select($sql);
                    foreach ($sifat as $value1) {
                        $this->view->data[$i]['sifat'] = $value1['sifat_surat'];
                    }
//                    $this->view->data[$i]['sifat'] = $value['sifat'];
                    $this->view->data[$i]['no_agenda'] = $value['no_agenda'];
                    $this->view->data[$i]['lampiran'] = $value['lampiran'];
                    $sql = 'SELECT klasifikasi FROM klasifikasi_surat WHERE kode_klassurat ="' . trim($value['jenis'].'"');
                    $klas = $this->model->select($sql);
                    foreach ($klas as $value1) {
                        $this->view->data[$i]['jenis'] = $value1['klasifikasi'];
                    }
//                    $this->view->data[$i]['jenis'] = $value['jenis'];
                    $sql = 'SELECT nama_satker FROM alamat WHERE kode_satker=' . trim($value['asal_surat']);
                    $asal = $this->model->select($sql);
                    foreach ($asal as $value1) {
                        $this->view->data[$i]['asal_surat'] = $value1['nama_satker'];
                    }
                    $this->view->data[$i]['perihal'] = $value['perihal'];
                }
                $datad = $disposisi->getDisposisi(array('id_surat' => $id[$i]));
                $countd = count($datad);
                //var_dump($count);
                if ($countd > 0) {

                    //foreach ($datad as $value) {
                        $this->view->disp[$i][0] = $datad->id_disposisi;
                        $this->view->disp[$i][1] = $datad->id_surat;
                        $this->view->disp[$i][2] = $datad->sifat;
                        $this->view->disp[$i][3] = $datad->dist;
                        $this->view->disp[$i][4] = $datad->petunjuk;
                        $this->view->disp[$i][5] = $datad->catatan;
                    //}
                    //$this->view->disposisi = explode(',', $this->view->disp[3]);
                }
            }
//            var_dump($this->view->data);
            include_once 'views/suratmasuk/disposisisurat.php';
        }else{
            $datas = $this->model->getSuratById($id);

            foreach ($datas as $value) {
                $this->view->data[0]['id_suratmasuk'] = $value['id_suratmasuk'];
                $this->view->data[0]['no_surat'] = $value['no_surat'];
                $this->view->data[0]['status'] = $value['status'];
                $this->view->data[0]['tgl_terima'] = Tanggal::tgl_indo($value['tgl_terima']);
                $this->view->data[0]['tgl_surat'] = Tanggal::tgl_indo($value['tgl_surat']);
                $sql = 'SELECT sifat_surat FROM sifat_surat WHERE kode_sifat ="' . trim($value['sifat'].'"');
                $sifat = $this->model->select($sql);
                foreach ($sifat as $value1) {
                    $this->view->data[0]['sifat'] = $value1['sifat_surat'];
                }
//                $this->view->data[0]['sifat'] = $value['sifat'];
                $this->view->data[0]['no_agenda'] = $value['no_agenda'];
                $this->view->data[0]['lampiran'] = $value['lampiran'];
                $sql = 'SELECT klasifikasi FROM klasifikasi_surat WHERE kode_klassurat ="' . trim($value['jenis'].'"');
                $klas = $this->model->select($sql);
                foreach ($klas as $value1) {
                    $this->view->data[0]['jenis'] = $value1['klasifikasi'];
                }
//                $this->view->data[0]['jenis'] = $value['jenis'];
                $sql = 'SELECT nama_satker FROM alamat WHERE kode_satker=' . trim($value['asal_surat']);
                $asal = $this->model->select($sql);
                foreach ($asal as $value1) {
                    $this->view->data[0]['asal_surat'] = $value1['nama_satker'];
                }
                $this->view->data[0]['perihal'] = $value['perihal'];
            }
            $datad = $disposisi->getDisposisi(array('id_surat' => $id));
            $count = count($datad);
            //var_dump($count);
            if ($count > 0) {

                //foreach ($datad as $value) {
                    $this->view->disp[0][0] = $datad->id_disposisi;
                    $this->view->disp[0][1] = $datad->id_surat;
                    $this->view->disp[0][2] = $datad->sifat;
                    $this->view->disp[0][3] = $datad->dist;
                    $this->view->disp[0][4] = $datad->petunjuk;
                    $this->view->disp[0][5] = $datad->catatan;
                //}
                //$this->view->disposisi = explode(',', $this->view->disp[3]);
                //$this->view->petunjuk = explode(',', $this->view->disp[4]);
            }
            //var_dump($datad);
            //var_dump($this->view->disp[4]);
            //$this->view->load('suratmasuk/disposisisurat.php');
            //$this->view->render('suratmasuk/ctkDisposisi');
            include_once('views/suratmasuk/disposisisurat.php');
        }
        
    }
    
    public function disposisix($id){
        $x = trim($id,',');
        $x=  explode(",", $id);
//        var_dump($x);
        $this->ctkDisposisi($x);
                
    }

    public function rekamdisposisi() {
        $id_surat = $_POST['id_surat'];
        $sifat = $_POST['sifat'];
        $petunjuk = $_POST['petunjuk'];
        $catatan = $_POST['catatan'];
        $disposisi = $_POST['disposisi'];
        $disp = implode(',',$disposisi);
        $petunjuk = implode(',',$petunjuk);
        
        $data = array(
            'id_surat'=>$id_surat,
            'sifat'=>$sifat,
            'disposisi'=>$disp,
            'petunjuk'=>$petunjuk,
            'catatan'=>$catatan
            );
        $dispos = new Disposisi();
        $rekam = $dispos->addDisposisi($data);
//        $rekam = $this->model->rekamdisposisi($data);
        //var_dump($rekam);
        if(!$rekam){ //baris ini berhasil
            echo "error";
            $this->view->error = "data tidak berhasil disimpan!";
            
            
        }else{
            $this->model->distribusi($id_surat, $disposisi); 
            $notif = new Notifikasi();
            $notif->set('id_surat', $id_surat);
            $notif->set('jenis_surat', 'SM');
            $notif->set('stat_notif', 1);
            $len = count($disposisi);
            //echo $len;
            //foreach ($disposisi as $val){
            for($i=0;$i<$len;$i++){
                echo $disposisi[$i];
                $sql = "SELECT id_bagian FROM r_bagian WHERE kd_bagian='".$disposisi[$i]."'";
                $data = $this->model->select($sql);
                //var_dump($data);
                foreach($data as $value){
                    $id_bagian = $value['id_bagian'];
                    $sql1 = "SELECT id_user FROM user WHERE bagian=$id_bagian AND role=2";
                    $data1 = $this->model->select($sql1);
                    //var_dump($data1);
                    foreach($data1 as $value1){
                        $id_user = $value1['id_user'];                        
                        $notif->set('id_user', $id_user);
                        $notif->set('role', 2);
                        $notif->set('bagian', $id_bagian);
                        $notif->addNotifikasi(); //notifikasi kasi
                    }
                }
            }
            $datastat = array('stat'=>'12');
            $where = 'id_suratmasuk='.$id_surat;
            $this->model->update('suratmasuk',$datastat,$where); //update status -> disposisi
//            header('location:'.URL.'suratmasuk');
        }
        
        return true;
        
    }

    public function distribusi($id) {
        $this->view->dataSurat = $this->model->select('SELECT * FROM suratmasuk WHERE id_suratmasuk=' . $id);
        $this->view->bagian = $this->model->select('SELECT * FROM r_bagian');

        foreach ($this->view->dataSurat as $value) {
            $this->view->data[0] = $value['id_suratmasuk'];
            $this->view->data[1] = $value['no_surat'];
            $this->view->data[2] = $value['perihal'];
            $this->view->data[3] = $value['asal_surat'];
        }
        $this->view->render('suratmasuk/distribusi');
    }

    public function rekamDistribusi() {
        $id = $_POST['id'];
        $bagian = $_POST['bagian'];
        //var_dump($bagian);
        $this->model->distribusi($id, $bagian);
    }
    
    public function rekamCatatan(){
        $disposisi = new Disposisi();
        $id_surat = $_POST['id_surat'];
        $id_disposisi = $_POST['id_disp'];
        $bagian = $_POST['bagian'];
        $peg = $_POST['peg'];
        $catatan = $_POST['catatan'];        
        $data = array('id_disposisi'=>$id_disposisi,
            'bagian'=>$bagian,
            'pelaksana'=>$peg,
            'catatan'=>$catatan);
        
        //var_dump($data);
        $disposisi->addDisposisiKasi($data);
        $notif = new Notifikasi();        
        $notif->set('id_surat', $id_surat);
        $notif->set('jenis_surat', 'SM');
        $notif->set('id_user', $peg);
        $notif->set('stat_notif',1);        
        $notif->addNotifikasi(); //notifikasi pelaksana
        $datastat = array('stat'=>'13');
        $where = 'id_suratmasuk='.$id_surat;
        $this->model->update('suratmasuk',$datastat,$where); //update status surat -> disposisi kasi
        //$this->model->insert('catatan',$data);
//        header('location:'.URL.'suratmasuk');
        return true;
    }
    
    public function catatan($id){
        if(isset($_POST['submit'])){
        
            if($this->rekamCatatan()){
                $this->view->success="Rekam catatan berhasil";
            }else{
                $this->view->error="Rekam catatan gagal";
            }
        }
        $disposisi = new Disposisi();
        $this->view->datad = $disposisi->getDisposisi(array('id_surat'=>$id));      
        //$this->view->bagian = $this->view->datad->dist[0];
        $sql = "SELECT kd_bagian FROM r_bagian WHERE id_bagian=".Session::get('bagian');
        $bagian = $this->model->select($sql);
        foreach ($bagian as $val){
            $this->view->bagian = $val['kd_bagian'];
        }
        $datas = $this->model->select("SELECT * FROM suratmasuk WHERE id_suratmasuk=".$this->view->datad->id_surat);
        foreach($datas as $value){
            $this->view->data[0] = $value['id_suratmasuk'];
            $this->view->data[1] = $value['no_agenda'];
            $this->view->data[2] = $value['no_surat'];
            $asal = $this->model->select('SELECT nama_satker FROM alamat WHERE kode_satker='.trim($value['asal_surat']));
                foreach($asal as $alamat){
                    $this->view->data[3] = $alamat['nama_satker'];
                }
            $this->view->data[4] = $value['perihal'];
        }
        $sql ="SELECT id_user, namaPegawai FROM user WHERE jabatan = 6 AND bagian = ".Session::get('bagian');
        $this->view->peg = $this->model->select($sql);
        //var_dump($this->view->peg);
        $this->view->render('suratmasuk/catatan');
    }
    
    public function ctkEkspedisi(){
        $eks = new EkspedisiSurat();
//        $id = $this->model->select("SELECT id_suratmasuk FROM suratmasuk");
        $this->view->data = $eks->displayEkspedisi();
//        $this->view->data = $this->model->showAll();
        
        $this->view->load('suratmasuk/expedisi');
    }
    
    public function upload($id){
        
        if(isset($_POST['submit'])){
            if($this->uploadFileSurat()){
                $this->view->success='Upload berhasil';
            }else{
                $this->view->error='Upload gagal';
            }
        }
        
        $data = $this->model->getSuratById($id);
        foreach ($data as $value){
            $this->view->id = $value['id_suratmasuk'];
            $this->view->no_surat = $value['no_surat'];
            $this->view->no_agenda = $value['no_agenda'];
            $this->view->tgl_surat = $value['tgl_surat'];
            $this->view->satker = $value['asal_surat'];
        }
        
        $this->view->render('suratmasuk/upload');
    }
    public function uploadFileSurat(){
        $upload = new Upload('upload');
        $upload->setDirTo('arsip/');
        $tipe='M';
        $satker = $_POST['satker'];
        $nomor = $_POST['nomor'];
        //nama baru akan terdiri dari tipe naskah_nomor surat_asal(asal/tetapi asal terlaku kepanjangan)
        $ubahNama = array($tipe,$nomor,$satker);
        $upload->setUbahNama($ubahNama);
        $upload->changeFileName($upload->getFileName(), $ubahNama);
        $namafile = $upload->getFileTo();
        $where = ' id_suratmasuk='.$_POST['id'];
        $data = array(
            'file'=>$namafile
        );
        $upload->uploadFile();
        $this->model->uploadFile($data,$where);
        $datastat = array('stat'=>'14');        
        $this->model->update('suratmasuk',$datastat,$where); //update status -> pelaksana
        return true;
        //header('location:'.URL.'suratmasuk');
        
    }
    
    

}

?>
