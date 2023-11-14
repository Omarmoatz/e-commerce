<?php
session_start();
$title ='login';

if(isset($_SESSION['user'])){
    header('Location:index.php');
}
include 'init.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $user = $_POST['userName'];
    $pass = $_POST['password'];
    $hPass = sha1($pass);

    $stmt = $db->prepare("SELECT id,name,password FROM `users` WHERE name=? AND password=?" );
    $stmt->execute(array($user,$hPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    if($count>0){
        $_SESSION['user'] = $user;
        $_SESSION['id'] = $row['id'];
        header('Location:index.php'); 
    }
}
?>

    <section class="container"  id= 'login' >
        <form action="" method="POST" id="adminForm">
            <h3>User Login</h3>
            <label for="userName"></label>
            <input type="text" name="userName" id="userName" placeholder="enter user name">

            <label for="password"></label>
            <input type="password" name="password" id="password" placeholder="enter password">

            
            <input type="submit" value="login" value="login">
        </form>

    </section>

<?php include $tpl.'footer.php' ?>