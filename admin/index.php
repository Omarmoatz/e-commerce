<?php 
//start the session
session_start();
//set title variable
$title ='login';
//no navbar
$noNav = '';

if(isset($_SESSION['userName'])){
    header('Location:dashboard.php');
}
//include intilize file
include 'init.php';


if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $userName = $_POST['userName'];
    $password = $_POST['password'];
    $hPass = sha1($password);

    //echo $password . $hPass;

    $stmt = $db->prepare("SELECT id,name,password FROM `users` WHERE name=? AND password=? AND group_id=1 LIMIT 1" );
    $stmt->execute(array($userName,$hPass));
    $row = $stmt->fetch();

    $count = $stmt->rowCount();

    if($count>0){
        $_SESSION['userName'] = $userName;
        $_SESSION['id'] = $row['id'];
        header('Location:dashboard.php'); 
    }

}
?>

<!-- start admin login section-->
    <section class="container"  id= 'login' >
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" id="adminForm">
            <h3>Admin Login</h3>
            <input type="text" name="userName" id="userName" placeholder="enter user name">
            <input type="password" name="password" id="password" placeholder="enter password">
            <input type="submit" value="login" value="login">
        </form>

    </section>
<!-- end admin login section-->
<?php
//include footer
include $tpl.'footer.php' 

?>
