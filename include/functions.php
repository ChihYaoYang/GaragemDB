<?php
function alert($tipo,$texto){
    echo "<div class='alert alert-$tipo'>";
    echo $texto;
    echo "</div>";
}
function extensoes() {
    return array('jpg', 'png' ,'jpeg');
}
//固定格式
function mimes() {
    return array('image/jpg','image/png','image/jpeg');
}
?>