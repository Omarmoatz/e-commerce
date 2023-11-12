<?php
session_start();

if(isset($_SESSION['userName'])){
    //content
    $title = 'members';
    include 'init.php';

    $do = '';
    $do = isset($_GET['do'])? $_GET['do']: 'main';

    if($do == 'main'){?>
        
        <h1 class="bg-danger text-center p-4 text-white" >Manage Users</h1>
        <a class="text-center btn btn-primary text-white d-block m-auto w-25 font-weight-bold" href="users.php?do=add">Add New User + </a>

        <table class="table table-hover table-striped table-bordered text-center w-75 m-auto">
            <tr>
                <th>ID</th>
                <th>User name</th>
                <th>Email</th>
                <th>Full name</th>
                <th>Registered date</th>
                <th>Type</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <tr>
               <td>2</td> 
               <td>omar</td> 
               <td>omar.o@mail.com</td> 
               <td>omar moataz</td> 
               <td>2023-10-24</td> 
               <td>user</td> 
               <td><a href=""><i class='fa fa-edit text-info'></i></a></td> 
               <td><a href=""><i class='fa fa-trash text-danger'></i></a></td> 
            </tr>
        </table>
<?php   }elseif($do == 'add'){
    echo 'add';
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
        //get the old pass from
        $password = $row['password'];


        if($count>0){        
            ?>
        <form action="?do=update" method="POST" id="editForm">
            <h3>edit member</h3>

            <label for="userName">userName</label>
            <input type="text" name="userName" id="userName" value="<?= $userName ?>" >

            <input type="hidden" name= "id" value="<?= $userId ?>" >

            <label for="password">password</label>
            <input type="hidden" name= "oldPasword" value="<?= $password ?>" >
            <input type="password" name="newPassword" id="password"  placeholder="enter password">

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

            $pass = '';
            if(empty($_POST['newPassword'])){
                $pass = $_POST['oldPasword'];
            }else{
                $pass = $_POST['newPassword'];
            }
            $hpass = sha1($pass);         
            $stmt = $db->prepare("UPDATE users SET name =? ,password=?, email=?, full_name=? WHERE id=?");
            $stmt->execute(array($name,$hpass,$email,$full,$id));

            echo '<h1 clas="alert alert-success"> user updated </h1>';

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