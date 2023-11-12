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
function redirect($class,$msg,$sec=2){
    echo "<h2 class='alert alert-$class'> $msg</h2> ";
    echo "<h2 class='alert alert-info'> You Will  Be Redirect After $sec seconds </h2> ";

    header("refresh:$sec;url=users.php");
}
?>