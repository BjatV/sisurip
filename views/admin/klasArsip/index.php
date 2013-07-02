<div class="divleft"><h2>Pengaturan Klasifikasi Arsip</h2></div>
 <hr>
<!-- <table><tr><td width="50%" valign="top">-->
 <div id="pesan"></div>
 <div class="divleft"><div id="btn-show"></br><input  class="btn write" type="button" name="submit" value="REKAM" onclick="displayform()"></div>
 </div><div id="form-wrapper"><h1>REKAM KLASIFIKASI ARSIP</h1>
<form id="form-rekam" >
<!--    <form id="form-rekam" method="POST" action="<?php echo URL; ?>admin/inputRekamKlasArsip">-->
    <?php 
            if (isset($this->error)) {
        echo "<div id=error>$this->error</div>";
    } elseif (isset($this->success)) {
        echo "<div id=success>$this->success</div>";
    }
    ?>
    <div id="wkode"></div>   
    <label>KODE KLASIFIKASI</label><input id="kode"  type="text" size="10" name="kode" onkeyup="cekemptyfield(1,this.value)"></br>
    <div id="wklas"></div>
    <label>KLASIFIKASI</label><input id="klas"  type="text" size="40" name="klasifikasi" onkeyup="cekemptyfield(2,this.value)"></br>
    <label></label><input class="btn reset" type="reset" value="RESET"><input class="btn save" type="button" name="submit" value="SIMPAN" onclick="cek()">
</form>
</div>
</br>
<hr>
</br>
<!--         </td><td width="50%">-->
<?php if($this->count>0) { $no=1;?>
<div id="table-wrapper" style="overflow:scroll; max-height:400px;"><table class="CSSTableGenerator">
    <tr><td>NO</td><td>KODE</td><td>KLASIFIKASI</td><td>AKSI</td></tr>
    <?php foreach($this->klasArsip as $key=>$value) {?>
    <tr><td><?php echo $no; ?></td>
        <td><?php echo $value['kode']; ?></td>
        <td><?php echo $value['klasifikasi']; ?></td>
        <td><a href="<?php echo URL;?>admin/ubahKlasifikasiArsip/<?php echo $value['id_klasarsip'];?>"><input class="btn edit" type="button" value="UBAH"></a> | 
            <a ><input class="btn btn-danger" type="button" value="HAPUS" onclick="return selesai('<?php echo $value['klasifikasi'];?>',<?php echo $value['id_klasarsip'];?>);"></a></td></tr>
    <?php $no++; }?>
</table></div>
<?php } ?>
<!--         </td></tr></table>-->
<script type="text/javascript">
    
    $(document).ready(function(){
        $('#form-wrapper').fadeOut(0);
    });
    
    function displayform(){
        $('#btn-show').fadeOut(500);
        $('#form-wrapper').fadeIn(500);
    }
    
    function selesai(klas,id)
    {
        var answer = confirm ("Klasifikasi arsip : "+klas+" akan dihapus?");
        if (answer){
            $.ajax({
                type:'post',
                url:'<?php echo URL;?>admin/hapusKlasifikasiArsip',
                data:'id='+id,
                success:function(){
                    window.location.reload();
                }
            })
            return true;
        }else{
            return false;
        }  
    }
    
    
    
    function cekemptyfield(num, content){
        switch (num) {
            case 1:
                if(content==''){
                    var walamat = '<div id=warning>Kode klasifikasi harus diisi!</div>'
                    $('#wkode').fadeIn(500);
                    $('#wkode').html(walamat);
                }else{
                    $('#wkode').fadeOut(500);
                } 
                break;
            case 2:
                if(content==''){
                    var wtgl = '<div id=warning>Nama Klasifikasi harus diisi!</div>'
                    $('#wklas').fadeIn(500);
                    $('#wklas').html(wtgl);
                }else{
                    $('#wklas').fadeOut(500);
                } 
                break;
        }
    }
    
    function cek(){
        var kode = document.getElementById('kode').value;
        var klas = document.getElementById('klas').value;
        var jml = 0;
        if(kode==''){
            jml++;
            var wtgl = '<div id=warning>Kode klasifikasi harus diisi!</div>'
            $('#wkode').fadeIn(500);
            $('#wkode').html(wtgl);
        }
        
        if(klas==''){
            jml++;
            var walamat = '<div id=warning>Nama Klasifikasi harus diisi!</div>'
            $('#wklas').fadeIn(500);
            $('#wklas').html(walamat);
        }
        
        if(jml>0){
            return false;
        }else{
            rekam();
            return true;
        }
    }
    
    function rekam(){
        var kode = document.getElementById('kode').value;
        var klas = document.getElementById('klas').value;
        $.ajax({
            type:'post',
            url:'<?php echo URL;?>admin/inputRekamKlasArsip',
            data:'kode='+kode+
                '&klasifikasi='+klas,
            dataType:'json',
            success:function(data){
                if(data.status=='success'){
                    $('#pesan').fadeIn();
                    $('#pesan').html(data.message);
                }
                window.setTimeout(function(){
                    location.reload(500)
                }
                ,3000);
            }
        });
    }
</script>