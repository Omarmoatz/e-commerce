<?php

$do = '';
$do = isset($_GET['do'])?$_GET['do']:'main';

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