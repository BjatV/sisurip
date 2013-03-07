<!DOCTYPE html>
<html>
    <head>
        <title>Sistem Informasi Penatausahaan Surat dan Arsip</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="<?php echo URL; ?>public/css/default.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <div id="wrapper">
        <div id="header">
            
                <!-- menu atas -->
                <div id="menu">
                    <div id="depkeu-logo"><img border="1" src="<?php echo URL; ?>public/images/depkeu-kecil.jpg"></div>
                    <div id="brand"> KPPN BENGKULU</div>
                    <div id="pull-right"><?php echo Session::get('user'); ?></div>
                    <div>
                    <ul id="trans-nav">
                        <?php                        
                        //echo Session::get('user');
                        if(Session::get('loggedin')==true):?>
                        <li><a href=<?php echo URL; ?>suratmasuk>Surat Masuk</a></li>
                        <li><a href=<?php echo URL; ?>suratkeluar>Surat Keluar</a></li>
                        <li><a href="">Monitoring</a>
                            <ul>
                                <li><a href="">Kinerja Pegawai</a></li>
                                <li><a href="">Laporan</a></li>
                            </ul>
                        </li>
                        <li><a href="">Pengaturan</a>
                            <ul>
                                <li><a href="<?php echo URL;?>admin/rekamKantor">Kantor</a></li>
                                <li><a href="<?php echo URL;?>admin/rekamLokasi">Lokasi Arsip</a></li>
                                <li><a href="<?php echo URL;?>admin/rekamKlasifikasiArsip">Klasifikasi Arsip</a></li>
                                <li><a href="<?php echo URL;?>admin/rekamJenisLampiran">Lampiran</a></li>
                                <li><a href="<?php echo URL;?>admin/rekamStatusSurat">Status Surat</a></li>
                                <li><a href="<?php echo URL;?>admin/rekamNomor">Penomoran</a></li>
                                <li><a href="<?php echo URL;?>admin/rekamUser">Pengguna</a></li>
                                <li><a href="<?php echo URL;?>admin/backup">Backup</a></li>
                                <li><a href="<?php echo URL;?>admin/restore">Restore</a></li>
                            </ul>
                        </li>
                        <li><a href="<?php echo URL; ?>bantuan">Bantuan</a></li>                        
                        <li><a href="<?php echo URL; ?>login/logout">Logout</a></li>
                        <?php endif; ?>
                    </ul>
                    </div>
                    
                </div>
                <div id="navbar">
                <!-- pencarian -->                
                <div id="sisurip"><h1>SiSuRIP</h1></div>
                
                <div id="">                    
                    <form method="POST" action="<?php echo URL;?>cari">
                        <input type="text" size="30" name="search" placeholder="masukkan kata kunci pencarian">
                        <input type="submit" name="submit" value="CARI">
                    </form>
                </div>
                <!-- end of pencarian -->
                
                </div>
                     
            
            
        </div>
        
        <div id="content">
    