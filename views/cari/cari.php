<div><form id="form-search">
    <p><input id="search" type="text" size="30" name="search" placeholder="masukkan kata kunci pencarian" onkeyup="cdari(this.value);">
        <input  type="button" name="submit" value="CARI" onClick="return cari(document.getElementById('search').value);"></p>
        <!--<form id="ui_element" class="sb_wrapper" method="POST" action="<?php echo URL; ?>cari">
        <p><input class="sb_input" type="text" size="30" name="search" placeholder="masukkan kata kunci pencarian">
        <input class="sb_search" type="submit" name="submit" value=""></p>-->
    <!--<ul class="sb_dropdown" style="display:none">
        <li class="sb_filter">Pilih Kategori Pencarian</li>
        <li><input type="checkbox"/><label for="all">Semua</label></li>
        <li><input type="checkbox"/><label for="sm">Surat Masuk</label></li>
        <li><input type="checkbox"/><label for="sk">Surat Keluar</label></li>
        <li><input type="checkbox"/><label for="lamp">Lampiran</label></li>                            
    </ul>--></form>
</div>
<br>
<div id="error"></div>
<div id="table-wrapper"><div id="result"></div></div>

<script type="text/javascript">

$(document).ready(function(){
    $('#error').fadeOut(0);
    document.search.focus();    
});

function cari(val){

    if(val==''){
        var err = "Kata kunci belum dimasukkan";
        $('#result').fadeOut(0);
        $('#error').fadeIn(500);
        $('#error').html(err);
        return false;
    }
            $.post("<?php echo URL;?>cari/find", {queryString:""+val+""},
            function(data){
                $('#error').fadeOut(0);
                $('#result').fadeIn(500);
                $('#result').html(data);
            });        
}


</script>
