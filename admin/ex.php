<?php

$do = '';

if(isset($_GET['do'])){
    $do= $_GET['do'];
}else{
    $do = 'main';
}

if($do == 'main'){
    echo 'this is main page';
}
elseif($do == 'add'){
    echo 'this is add page';
}elseif($do == 'edit'){
    echo 'this is edit page';
}elseif($do == 'update'){
    echo 'this is update page';
}elseif($do == 'delete'){
    echo 'this is delete page';
}else{
    echo 'this is main page';
}
?>