<?php

/*
v 1.0
to set the title every page
check if title variable exist or no and echo it
*/
function setTitle(){
    global $title;

    if(isset($title)){
        echo $title;
    }else{
        echo 'default';
    }
}
/*
v 1.0
*/
function redirect($class,$msg,$url,$sec=2){
    echo "<h2 class='alert alert-$class'> $msg</h2> ";
    echo "<h2 class='alert alert-info'> You Will  Be Redirect After $sec seconds </h2> ";

    header("refresh:$sec;url=$url");
}

/*
v 1.0
check database function

*/

function checkDb($col,$table,$value){
    global $db;
    $check = $db->prepare("SELECT $col FROM $table WHERE $col=?");
    $check->execute(array($value));
    $count = $check->rowCount();
    return $count;
}
?>