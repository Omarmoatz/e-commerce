<?php
session_start();

if(isset($_SESSION['userName'])){
    //content
    $title = 'members';
    include 'init.php';

    $do = '';
    $do = isset($_GET['do'])? $_GET['do']: 'main';

    if($do == 'main'){
        echo 'this is main page';
    }
    //edit page
    elseif($do == 'edit'){
        //check if id exict and numeric
        $userId = isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']):0; 
        
        $stmt = $db->prepare("SELECT * FROM `users` WHERE id=? LIMIT 1 ");
        $stmt->execute(array($userId));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        $userName = $row['name'];
        $email = $row['email'];
        $fullName = $row['full_name'];

        if($count>0){        
            ?>
        <form action="?do=update" method="POST" id="editForm">
            <h3>edit member</h3>

            <label for="userName">userName</label>
            <input type="text" name="userName" id="userName" value="<?= $userName ?>" >

            <input type="hidden" name= "id" value="<?= $userId ?>" >

            <label for="password">password</label>
            <input type="password" name="password" id="password"  placeholder="enter password">

            <label for="email">email</label>
            <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="enter email">

            <label for="full">full name</label>
            <input type="text" name="full" id="full" value="<?= $fullName ?>" placeholder="enter full name">

            <input type="submit" value="edit" value="edit">
        </form>
<?php 
        }else{
        echo 'sorry no user found';
        }  
    //THIS IS UPDATE PAGE  
    }elseif($do == 'update'){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $id = $_POST['id'];
            $name = $_POST['userName'];
            $email = $_POST['email'];
            $full = $_POST['full'];
            
            $stmt = $db->prepare("UPDATE users SET name =? , email=?, full_name=? WHERE id=?");
            $stmt->execute(array($name,$email,$full,$id));

            echo "<h1 clas='alert alert-success'> user updated </h1>";

        }else{
            echo 'you cant browes this page';
        }
    }
    else{
        echo 'eror';
    }

    include $tpl.'footer.php';
}else{
    header('Location:index.php');
}


?>