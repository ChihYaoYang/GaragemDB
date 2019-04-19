<?php
$dbconn = mysqli_connect('localhost', 'root', 'senac123', 'garagem');
//Se for fasle exibi mensagem e chama exit() ---> 輸出一則訊息並且終止當前指令碼
if(!$dbconn) {
    echo 'Falha ao conectar a DataBase';
    exit();
}
?>