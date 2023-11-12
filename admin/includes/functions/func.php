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


?>