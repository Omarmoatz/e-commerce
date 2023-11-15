<?php
session_start();

if(isset($_SESSION['userName'])){
    //content
    $title = 'categories';
    include 'init.php';

    $do = '';
    $do = isset($_GET['do'])? $_GET['do']:'main';
    //main//////////////////////////////////////////////////////////////////
    if($do == 'main'){

        $stmt = $db->prepare("SELECT * FROM `categories`");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        
        ?>
        <h1 class="bg-dark text-center p-4 mb-3 text-white" >Manage Categories</h1>
        <a class="text-center btn btn-dark text-white d-block m-auto mb-3 w-25 font-weight-bold" href="cats.php?do=add">Add New Category + </a>

        <table class="table table-hover table-striped table-bordered table-dark text-center w-75 m-auto">
            <tr>
                <th>cat id</th>
                <th>cat name</th>
                <th>cat desc</th>
                <th>cat vis</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        <?php foreach($cats as $cat):
            $id = $cat['id']; 
            $name = $cat['name']; 
            $desc = $cat['descr']; 
            $vis = $cat['visibility'];  
            ?>
            <tr>
               <td><?= $id ?></td> 
               <td><?= $name ?></td> 
               <td><?= $desc ?></td> 
               <td><?php if($vis==0){echo 'visible';}else{echo 'invisible';} ?></td> 
               <td><a href="cats.php?do=edit&id=<?= $id ?>"><i class='fa fa-edit text-info'></i></a></td> 
               <td><a href="cats.php?do=delete&id=<?= $id ?>"><i class='fa fa-trash text-danger'></i></a></td> 
            </tr>
        <?php endforeach; ?>
        </table>

    <?php    
    }//add///////////////////////////////////////////////////////////////
    elseif($do == 'add'){
        
        $nameErr = $dscErr ='';
        $name = $desc ='';

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //check name
            if(empty($_POST['catName'])){
                $nameErr = 'name is required';
            }else{
                $name = $_POST['catName'];
            } 
            //check desc
            if(empty($_POST['desc'])){
                $dscErr = 'desc is required';
            }else{
                $desc = $_POST['desc'];
            }
            //check visibility
            $vis = $_POST['vis'];

            if(empty($nameErr) && empty($dscErr)){
                $stmt =$db->prepare("INSERT INTO 
                `categories`(name,descr,visibility)
                VALUES(:catName,:desc,:vis)");

                $checkName = checkDb('name','categories', $name);
                if($checkName>0){
                    redirect('danger','Sorry, This category Already Exists','cats.php?do=add');
                }else{
                    $stmt->bindParam(':catName',$name);
                    $stmt->bindParam(':desc',$desc);
                    $stmt->bindParam(':vis',$vis);
                    $stmt->execute();
                    
                    redirect('success','category added successfully','cats.php',2);
                }

            }
        }
        ?>
        
        <form action="" method="POST" id="adminForm">
            <h1 class='text-center bg-primary text-white'>Add New Category</h1>

            <span class='text-danger' ><?= $nameErr ?></span><br>
            <label for="catName">category name</label>
            <input type="text" name="catName" id="catName" value='<?= $name ?>' >
            

            <span class='text-danger' ><?= $dscErr ?></span><br>
            <label for="desc">description</label>
            <input type="text" name="desc" id="desc" value='<?= $desc ?>' >

            <label>visible</label>
            <input type="radio" name="vis" id="yes" value="0" checked>
            <label for="yes">yes</label>

            <input type="radio" name="vis" id="no" value="1" >
            <label for="no">no</label><br>

            <input class="btn btn-dark" type="submit" name="add" value="add">
        </form>
         
    <?php
    //edit/////////////////////////////////////////////////////////////////////
    }elseif($do == 'edit'){
        
        //check if id exict and numeric
        $catId = isset($_GET['id']) && is_numeric($_GET['id'])? intval($_GET['id']):0; 
        
        $stmt = $db->prepare("SELECT * FROM `categories` WHERE id=? LIMIT 1 ");
        $stmt->execute(array($catId));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();

        if($count>0){
            $catName = $row['name'];
            $desc = $row['descr'];
            $vis = $row['visibility'];
            
        ?>
        
        <form action="?do=update" method="POST" id="editForm">
            <h3>edit category</h3>

            <label for="catName">Cat Name</label>
            <input type="text" name="catName" id="catName" value="<?= $catName ?>" >
            <input type="hidden" name= "id" value='<?= $catId ?>'>

            <label for="desc">cat desc</label>
            <input type="text" name="desc" id="desc" value="<?= $desc ?>"  >

            <label>visible</label>
            <input type="radio" name="vis" id="yes" <?php if($vis==0){echo 'checked';} ?>>
            <label for="yes">yes</label>

            <input type="radio" name="vis" id="no" <?php if($vis==1){echo 'checked';} ?>>
            <label for="no">no</label><br>

            <input class="btn btn-dark" type="submit" name="catEdit" value="edit">
        </form>



    <?php
        }else{
            redirect('danger', 'no category found', 'cats.php');
        }
    //update////////////////////////////////////////////////////////////
    }elseif($do == 'update'){
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $id = $_POST['id'];
            $name = $_POST['catName'];
            $desc = $_POST['desc'];
            $vis = $_POST['vis'];
        
            $stmt = $db->prepare("UPDATE `categories` SET name =? ,descr=?, visibility=? WHERE id=?");
            $stmt->execute(array($name,$desc,$vis,$id));
            
            redirect( 'success','category updated successfully','cats.php',2);
        }else{
            redirect('danger','you cant browes this page','cats.php',2);
        }
    //delete/////////////////////////////////////////////////////////////////
    }elseif($do == 'delete'){
        $id = $_GET['id'];

        $stmt =$db->prepare("DELETE FROM `categories` WHERE id=:id");
        $stmt->bindParam(':id' , $id);
        $stmt->execute();
            
        redirect('danger','User Deleted Successfully','cats.php',2);
        
    }else{
        echo 'no page';
    }  
    ?>
    <?php include $tpl.'footer.php';
}else{
    header('Location:index.php');
}