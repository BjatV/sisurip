<h2>Rekam Surat Masuk</h2>            
<hr>
<script type="text/javascript">
    
  
    $(document).ready(function(){
        $('#errorr').fadeOut();
//        $('#loading').fadeOut();
        $('#succes').fadeOut();
        $("input").blur(function(){
            $('#result').fadeOut();
            
        }); 
    });
    
    function lookup(alamat){
        if(alamat.length == 0){
            $('#result').fadeOut();
        }else{
            $.post("<?php echo URL;?>helper/alamat", {queryString:""+alamat+""},
            function(data){
                $('#result').fadeIn();
                $('#result').html(data);
            });
        }
    }
    
    function simpan(){
        hideshow('loading',1);
        error(0);
        success(0);
        $.ajax({
            type:"POST",
            url:"<?php echo URL;?>suratmasuk/input",
            data:$('#form-rekam').serialize(),
            dataType:"json",
            success: function(){
//                if(parseInt(msg.status)==1){
//                    success(1,msg.txt);
//                    $('#error').removeClass('error').addClass('success');
                    $('#agenda').val('');
                    $('#datepicker').val('');
                    $('#no_surat').val('');
                    $('#alamat').val('');
                    $('#perihal').val('');
                    $('#status').val('');
                    
                    
//                }else if(parseInt(msg.status)==0){
//                    error(1,msg.txt);
//                    $('#error').removeClass('success').addClass('error');
//                }
                
//                hideshow('loading',0);
            }
        });
    }
    
    function hideshow(el, act){
        if(act) $('#'+el).css('visibility','visible');
        else $('#'+el).css('visibility','hidden');
    }
    
    function error(act, txt){
//        hideshow('error',act);
        if(txt) {
            $('#error').fadeIn();
            $('#error').html(txt);
        }
    }
    
    function success(act, txt){
//        hideshow('success',act);
        
        if(txt) {
            $('#success').fadeIn();
            $('#success').html(txt);
        }
    }
    
    
    //$(document).ready(function(){
			//$('#alamat').autocomplete({source:'<?php echo URL; ?>helper/alamat', minLength:1});
		//});
    
</script>
<?php if(isset($this->error)) {?>
<div id="error"><?php echo $this->error;?></div><?php } ?>
<?php if(isset($this->success)) {?><div id="success"><?php echo $this->success;?></div><?php }?>
<div id="form-wrapper"><form id="form-rekam" method="POST" action="<?php echo URL; ?>suratmasuk/input">
<!--        action="<?php echo URL; ?>suratmasuk/input"-->
        <label>AGENDA</label><input id="agenda" type="text" name="no_agenda" value="<?php echo @$this->agenda; ?>" readonly></br>
        <!--<label>TANGGAL TERIMA</label><input type="text" name="tgl_terima"></br>-->
        <label>TANGGAL SURAT</label><input type="text" id="datepicker" name="tgl_surat" class="required" ></br>
        <label>NOMOR SURAT</label><input id="no_surat" class="required" type="text" name="no_surat"></br>
        <label>ASAL</label><input class="required"  id="alamat" type="text" name="asal_surat" 
                                  value="<?php if(isset($this->alamat)) echo $this->alamat; ?>" onkeyup="lookp(this.value);">
        <a href="<?php echo URL;?>helper/pilihalamat/1"><input type="button" name="" value="+"></a></br>
<!--        onclick="window.open('<?php echo URL?>helper/pilihalamat/1','pilih alamat asal','location=0,toolbar=0,menubar=0,status=0,scrollbar=1,width=500,height=400')"-->
        <div id="result"></div>
        
                                                    <label>PERIHAL</label><!--<input id="perihal" class="required" type="" name="perihal">--><textarea id="perihal" cols="10" rows="10" name="perihal" class="required"></textarea></br>
        <label>STATUS</label><input id="status" type="text" name="status"></br>
        <label>SIFAT</label><select name="sifat" class="required">
            <option value="">--PILIH SIFAT SURAT--</option>
            <?php            
                foreach($this->sifat as $value){
                    //if($value[kode_sifat]=='BS') {
                       // echo "<option value=$value[kode_sifat] selected>$value[sifat_surat]</option>";
                    //}else{
                        echo "<option value=$value[kode_sifat]>$value[sifat_surat]</option>";
                    //}
                    
                }
            ?>
        </select></br>
        <label>JENIS</label><select name="jenis" class="required">
            <option value="">--PILIH JENIS SURAT--</option>
            <?php 
                foreach($this->jenis as $value){
                    //if($value[kode_klassurat]=='BS') {
                        //echo "<option value=$value[kode_klassurat] selected>$value[klasifikasi]</option>";
                    //}else{
                        echo "<option value=$value[kode_klassurat]>$value[klasifikasi]</option>";
                    //}
                    
                }
            ?>
        </select></br>
        <label>LAMPIRAN</label><input type="text" name="lampiran"></br>    
        <label></label><input type="reset" value="RESET"><input type="submit" name="submit" value="SIMPAN" >
<!--        <div id="loading">Proses Menyimpan ... </div>-->
        <div id="errorr"></div>
        <div id="succes"></div>
    </form></div>

<?php 
    
    $mlibur = new Admin_Model();
    
    $libur = $mlibur->getLibur();
//    var_dump($libur);
    
    $count = count($libur);
    $i=0;
//    $datal = array();
    echo "<script type=text/javascript>\n";
//    echo "$(function){\n";
    echo "var holiday=[";
    foreach($libur as $data){
        
        $temp = $data['tgl'];
        $tgl = substr($temp, -2);
        $bln = ((int) substr($temp, 5,2))-1;
        $thn = substr($temp, 0,4);
        if($i<$count){
            echo "new Date($thn,$bln,$tgl).getTime(),";
        }else{
            echo "new Date($thn,$bln,$tgl).getTime()";
        }
//        $datal[] = array('tgl'=>$tgl,'bln'=>$bln,'thn'=>$thn);
        $i++;
    }
    
    echo "]\n";
    echo "$('#datepicker').datepicker({\n";
    echo "minDate: '01/01/2013',\n";
    echo "maxDate: '12/31/2013',\n";
    echo "beforeShowDay: function(date){\n";
    echo "var showDay= true;\n";
    echo "if (date.getDay() == 0 || date.getDay() == 6) {
                showDay = false;
            }";
    
    echo "if ($.inArray(date.getTime(), holiday) > -1) {
                showDay = false;
            }\n";
    echo "return [showDay];\n";
    echo "} \n
            });";
    echo "</script>";
?>