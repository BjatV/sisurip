<h1>Tambah Jenis Lampiran</h1>
<form method="POST" action="<?php echo URL; ?>admin/inputRekamLampiran">    
    
    <label>TIPE NASKAH DINAS</label><input type="text" name="tipe_naskah"></br>
    <label></label><input type="submit" name="submit" value="SIMPAN">
</form>

<?php if($this->count>0) { $no=1;?>
<table border="1">
    <tr><th>NO</th><th>TIPE NASKAH</th><th>AKSI</th></tr>
    <?php foreach($this->lampiran as $key=>$value) {?>
    <tr><td><?php echo $no; ?></td>        
        <td><?php echo $value['tipe_naskah']; ?></td>
        <td><a href="<?php echo URL;?>admin/ubahLampiran/<?php echo $value['id_lampiran'];?>">UBAH</a> | 
            <a href="<?php echo URL;?>admin/hapusLampiran/<?php echo $value['id_lampiran'];?>">HAPUS</a></td></tr>
    <?php $no++; }?>
</table>
<?php } ?>