<?php
session_start();

if(isset($_SESSION['userName'])){
    //content
    $title = 'dashboard';
    include 'init.php';
    include $tpl.'footer.php';
}else{
    header('Location:index.php');
}


?>