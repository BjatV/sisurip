<h1>Tambah Status Surat</h1>
<form method="POST" action="<?php echo URL; ?>admin/inputRekamStatus">
    
    <label>TIPE SURAT</label><input type="text" name="tipe_surat"></br>
    <label>STATUS</label><input type="text" name="status"></br>
    <label></label><input type="submit" name="submit" value="SIMPAN">
</form>

<?php if($this->count>0) { $no=1;?>
<table border="1">
    <tr><th>NO</th><th>TIPE SURAT</th><th>STATUS</th><th>AKSI</th></tr>
    <?php foreach($this->status as $key=>$value) {?>
    <tr><td><?php echo $no; ?></td>
        <td><?php echo $value['tipe_surat']; ?></td>
        <td><?php echo $value['status']; ?></td>
        <td><a href="<?php echo URL;?>admin/ubahStatusSurat/<?php echo $value['id_status'];?>">UBAH</a> | 
            <a href="<?php echo URL;?>admin/hapusStatus/<?php echo $value['id_status'];?>">HAPUS</a></td></tr>
    <?php $no++; }?>
</table>
<?php } ?>