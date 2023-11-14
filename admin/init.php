<?php
//include data base file
include 'db.php';

//paths
$tpl ='includes/templates/';
$css = 'layouts/css/';
$js = 'layouts/js/';
$func = 'includes/functions/';
$imgs = '../layouts/images/';

//{{includes}}

//include function
include $func. 'func.php';
//include header
include $tpl.'header.php';
//include navbar
if(!isset($noNav)){
    include $tpl.'nav.php';
}


?>