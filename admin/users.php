<?php
session_start();

if(isset($_SESSION['userName'])){
    //content
    $title = 'members';
    include 'init.php';

    $do = '';
    $do = isset($_GET['do'])? $_GET['do']: 'main';
    //main page/////////////////////////////////////////////////////////////
    if($do == 'main'){
        
        $stmt = $db->prepare("SELECT *FROM `users`");
        $stmt->execute();
        $row = $stmt->fetchAll();
        
        ?>
        
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
            <?php foreach($row as $x):
                $id = $x['id']; 
                $name = $x['name']; 
                $email = $x['email']; 
                $full_name = $x['full_name']; 
                $group_id = $x['group_id'] ==1 ? 'admin':'user';
                $reg_date = $x['reg_date']; 
                ?>
            <tr>
               <td><?= $id ?></td> 
               <td><?= $name ?></td> 
               <td><?= $email ?></td> 
               <td><?= $full_name ?></td> 
               <td><?= $full_name ?></td> 
               <td><?= $reg_date ?></td> 
               <td><a href="users.php?do=edit&id=<?= $id ?>"><i class='fa fa-edit text-info'></i></a></td> 
               <td><a href="users.php?do=delete&id=<?= $id ?>"><i class='fa fa-trash text-danger'></i></a></td> 
            </tr>
            <?php endforeach; ?>
        </table>
        <!-- add page //////////////////////////////////////////////////////-->
<?php   }elseif($do == 'add'){ 
    $nameError = $passErorr = $emailEror = $fullErorr = '';
    $name = $pass = $email = $full ='';

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        //check name
        if(empty($_POST['username'])){
            $nameError = 'name is required';
        }else{
            $name = $_POST['username'];
            if(strlen($name)<4){
                $nameError = 'name should be longer than 4 letter';
            }elseif(strlen($name)>15){
                $nameError = 'name should be less than 15 letter';
            }
        }
        //check password
        if(empty($_POST['password'])){
            $passErorr = 'password is required';
        }else{
            $pass = $_POST['password'];
            $hpass = sha1($pass);
        } 
        //check email
        if(empty($_POST['email'])){
            $emailEror = 'email is required';
        }else{
            $email = $_POST['email'];
        }        
        //check full_name
        if(empty($_POST['full'])){
            $fullErorr = 'full name is required';
        }else{
            $full = $_POST['full']; 
        }  
        
        //check if there's any error or not
        if(empty($nameError) && empty($passErorr) && empty($emailEror) && empty($fullErorr)){

            $checkUserName = checkDb('name','users',$name);

            if($checkUserName>0){
                redirect('danger','Sorry, This User Already Exists');
            }else{
                $stmt =$db->prepare("INSERT INTO 
                `users`(name,password,email,full_name,reg_date)
                VALUES(:username,:password,:email,:full,now())");
                $stmt->bindParam(':username',$name);
                $stmt->bindParam(':password',$hpass);
                $stmt->bindParam(':email',$email);
                $stmt->bindParam(':full',$full);
        
                $stmt->execute();
            
                redirect('success','user added successfully','users.php',2);
            }
        }
    } 
    ?>
    <form id = 'editForm' action="" method="POST">
        <h3>Add member</h3>

        <span class=" text-danger"><?= $nameError?> </span><br>
        <label for="username">User Name</label>
        <input type="text" name="username" id="username" value="<?= $name?>" placeholder="enter your username">
        
        <span class=" text-danger"><?= $passErorr?> </span><br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?= $pass?>" placeholder="enter password">
        
        <span class=" text-danger"><?= $emailEror?> </span><br>
        <label for="email">email</label>
        <input type="email" name="email" id="email" value="<?= $email?>" placeholder="enter email">
        
        <span class=" text-danger"><?= $fullErorr?> </span><br>
        <label for="full">full name</label>
        <input type="text" name="full" id="full" value="<?= $full?>" placeholder="enter full name">
        

        <input type="submit" name="add" value="Add User" >
    </form>
<?php   }
    //edit page////////////////////////////////////////////////////////////////
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
            redirect('danger','sorry no user found','users.php',2);

        }  
    //THIS IS UPDATE PAGE //////////////////////////////////////////////////////////// 
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

            
            redirect( 'success','user updated successfully','users.php',2);
        }else{
            redirect('danger','you cant browes this page','users.php',2);
        }
    // delete page //////////////////////////////////////////////////////////////
    }elseif($do='delete'){
        $id = $_GET['id'];

        if($id==1){
            redirect('danger','You Cant Delete This User','users.php',2);
            
        }else{
            $stmt =$db->prepare("DELETE FROM `users` WHERE id=:id");
            $stmt->bindParam(':id' , $id);
            $stmt->execute();
            
            redirect('danger','User Deleted Successfully','users.php',2);
            //echo " <h2 class='alert alert-success'> User Deleted Successfully </h2>";
        }

    }
    else{
        redirect('danger','no such apage','dashboard.php',2);

    }

    include $tpl.'footer.php';
}else{
    header('Location:index.php');
}


?>