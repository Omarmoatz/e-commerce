<?php
session_start();

if(isset($_SESSION['userName'])){
    //content
    $title = 'products';
    include 'init.php';

    $do = '';
    $do = isset($_GET['do'])?$_GET['do']:'main';

    if($do == 'main'){

        $stmt = $db->prepare("SELECT products.* ,
        categories.name AS cat_name ,
        users.name AS user_name 
        FROM products
        INNER JOIN categories ON categories.id = products.cat_id
        INNER JOIN users on users.id = products.user_id");
        $stmt->execute();
        $row = $stmt->fetchAll();
        
        ?>
        <h1 class="bg-success text-center p-4 text-white" >Manage Products</h1>
        <a class="text-center btn btn-primary text-white d-block m-auto w-25 font-weight-bold" 
        href="products.php?do=add">Add New Prduct + </a>

        <table class="table table-hover table-striped table-bordered text-center w-75 m-auto ">
            <tr>
                <th>ID</th>
                <th>title</th>
                <th>description</th>
                <th>price</th>
                <th>added date</th>
                <th>country</th>
                <th>image</th>
                <th>rating</th>
                <th>status</th>
                <th>regester status</th>
                <th>cat name</th>
                <th>user name</th>
                <th>edit</th>
                <th>delete</th>
            </tr>
            <?php foreach($row as $x):
                $id = $x['id']; 
                $title = $x['title']; 
                $desc = $x['des']; 
                $price = $x['price']; 
                $date = $x['added_date'];
                $country = $x['country'];
                $image = $x['image'];
                $rating = $x['rating'];
                $status = $x['status'];
                $reg_status = $x['reg_status'];
                $cat_name = $x['cat_name'];
                $user_name = $x['user_name'];

                ?>
            <tr>
               <td><?= $id ?></td> 
               <td><?= $title ?></td> 
               <td><?= $desc ?></td> 
               <td><?= $price ?></td> 
               <td><?= $date ?></td> 
               <td><?= $country ?></td> 
               <td>
                    <img width='50' src="<?php echo '../layouts/images/'.$image ?>" alt="">
               </td> 
               <td>
                  <?php
                    if($rating ==1){echo '*';}
                    elseif($rating ==2){echo '**';}
                    elseif($rating ==3){echo '***';}
                    elseif($rating ==4){echo '****';}
                    else{echo '*****';}
                  ?>  
               </td> 
               <td>
               <?php
                    if($status ==1){echo 'new';}
                    elseif($status ==2){echo 'normal';}
                    else{echo 'old';}
                  ?>
               </td> 
               <td>
               <?php
                    if($reg_status ==0){echo 'active';}
                    else{echo 'not active';}
                  ?>
               </td> 
               <td><?= $cat_name ?></td> 
               <td><?= $user_name ?></td> 
               <td><a href="products.php?do=edit&id=<?= $id ?>"><i class='fa fa-edit text-info'></i></a></td> 
               <td><a href="products.php?do=delete&id=<?= $id ?>"><i class='fa fa-trash text-danger'></i></a></td> 
            </tr>
            <?php endforeach; ?>
        </table>


    <?php
    }
    //add page/////////////////////////////////////////////////////
    elseif($do == 'add'){
        $titleErr= $descErr= $priceErr= $countryErr= $ratingErr= $statusErr= $regErr= $catErr= $userErr='';
        $title= $desc= $price= $country= $rating= $status= $reg_status= $cat= $user= '';
        $imgageErr ='';

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            //check image
            $target_file = $imgs.basename($_FILES['image']['name']);

            $check='';
            if(!file_exists($_FILES['image']['tmp_name'])){
                $check = false;
            }else{
                $check = getimagesize($_FILES['image']['tmp_name']);
            }
            if($check !== false){
                if(file_exists($target_file)){
                    $imgageErr = 'sorry this image exists';
                }else{
                    move_uploaded_file($_FILES['image']['tmp_name'],$target_file);
                }
            }else{
                if(empty($_FILES['image']['tmp_name'])){
                    $imgageErr = 'image is required';
                }else{
                    $imgageErr = 'please upload only images';
                }
            }


            //check title
            if(empty($_POST['title'])){
                $titleErr = 'title is required';
            }else{
                $title = $_POST['title'];
                if(strlen($title)<4){
                    $titleErr = 'title should be longer than 4 letter';
                }elseif(strlen($title)>20){
                    $titleErr = 'title should be less than 15 letter';
                }
            }
            //check desc
            if(empty($_POST['desc'])){
                $descErr = 'description is required';
            }else{
                $desc = $_POST['desc'];
            } 
            //check price
            if(empty($_POST['price'])){
                $priceErr = 'price is required';
            }else{
                $price = $_POST['price'];
            }        
            //check country
            if(empty($_POST['country'])){
                $countryErr = 'country is required';
            }else{
                $country = $_POST['country']; 
            }
            //check rating
            if(empty($_POST['rating'])){
                $ratingErr = 'rating is required';
            }else{
                $rating = $_POST['rating'];
            }        
            //check status
            if(empty($_POST['status'])){
                $statusErr = 'status is required';
            }else{
                $status = $_POST['status']; 
            }
            //check category
            if(empty($_POST['cat'])){
                $catErr = 'cat is required';
            }else{
                $cat = $_POST['cat']; 
            }
            //check user
            if(empty($_POST['user'])){
                $userErr = 'user is required';
            }else{
                $user = $_POST['user']; 
            }
            $reg_status = $_POST['reg_status'];
            $img_name = $_FILES['image']['name'];

            
            //check if there is any error
            if(empty($titleErr) && empty($descErr) && empty($priceErr) 
            && empty($countryErr)&& empty($ratingErr) && empty($statusErr) 
            && empty($regErr) && empty($imgageErr) && empty($catErr) && empty($userErr)){

                $stmt =$db->prepare("INSERT INTO 
                `products`(title,des,price,country,rating,status,reg_status,added_date,image,cat_id,user_id)
                VALUES(:title,:des,:price,:country,:rating,:status,:reg_status,now(),:image,:cat,:user)");

                $stmt->bindParam(':title',$title);
                $stmt->bindParam(':des',$desc);
                $stmt->bindParam(':price',$price);
                $stmt->bindParam(':country',$country);
                $stmt->bindParam(':rating',$rating);
                $stmt->bindParam(':status',$status);
                $stmt->bindParam(':reg_status',$reg_status);
                $stmt->bindParam(':image',$img_name);
                $stmt->bindParam(':cat',$cat);
                $stmt->bindParam(':user',$user);

                $stmt->execute();
                
                redirect('success','prduct added successfully','products.php',2);

            } 
        }      
        ?>
     
        <section class=' p-4 text-center' id='addProduct'>
            <h2 class='text-danger' >Add New Products</h2>
            <form class="text-dark bg-info mt-3 rounded py-5 w-50 mx-auto " id="adminForm" action="" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="title">Title</label>
                    <input class="w-50 mx-auto form-control" type="text" name="title" id="title">
                    <span style="font-size: 18px;" class='text-danger'><?= $titleErr?></span>
                </div>

                <div class="form-group">
                    <label for="desc">Description</label>
                    <input class="w-50 mx-auto form-control" type="text" name="desc" id="desc">
                    <span style="font-size: 18px;" class='text-danger'><?= $descErr?></span>
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input class="w-50 mx-auto form-control" type="text" name="price" id="price">
                    <span style="font-size: 18px;" class='text-danger'><?= $priceErr?></span>

                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <select class="w-50 mx-auto form-control" name="country" id="country">
                        <option value="">Select Country</option>
                        <option value="egypt">egypt</option>
                        <option value="china">china</option>
                        <option value="france">france</option>
                        <option value="england">england</option>
                        <option value="germany">germany</option>
                    </select> 
                    <span style="font-size: 18px;" class='text-danger'><?= $countryErr?></span>           
                </div>

                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select class="w-50 mx-auto form-control" name="rating" id="rating">
                        <option value="">.....</option>
                        <option value="1">*</option>
                        <option value="2">**</option>
                        <option value="3">***</option>
                        <option value="4">****</option>
                        <option value="5">*****</option>
                    </select>  
                    <span style="font-size: 18px;" class='text-danger'><?= $ratingErr?></span>          
                </div>

                <div class="form-group">
                    <label for="status">status</label>
                    <select class="w-50 mx-auto form-control" name="status" id="status">
                        <option value="">select status</option>
                        <option value="1">new</option>
                        <option value="2">normal</option>
                        <option value="3">old</option>
                    </select>
                    <span style="font-size: 18px;" class='text-danger'><?= $statusErr?></span>            
                </div>
                
                <div class="form-group">
                    <label for="reg_status">reg status</label>
                    <select class="w-50 mx-auto form-control" name="reg_status" id="reg_status">
                        <option value="0">active</option>
                        <option value="1">not active</option>
                    </select>  
                </div>

                <div class="form-group">
                    <label for="cat">Category</label>
                    <select class="w-50 mx-auto form-control" name="cat" id="cat">
                        <option value="">Select Category</option>
                        <?php
                        $stmt2 = $db->prepare("SELECT * FROM `categories`");
                        $stmt2->execute();
                        $cats = $stmt2->fetchAll();
                        foreach($cats as $cat):
                        ?>
                        <option value="<?= $cat['id'] ?>"> <?= $cat['name'] ?> </option>
                        <?php endforeach; ?>
                    </select>  
                </div>

                <div class="form-group">
                    <label for="user">User</label>
                    <select class="w-50 mx-auto form-control" name="user" id="user">
                        <option value="">select user</option>
                        <?php
                        $stmt3 = $db->prepare("SELECT * FROM `users`");
                        $stmt3->execute();
                        $users = $stmt3->fetchAll();
                        foreach($users as $user):
                        ?>
                        <option value="<?= $user['id'] ?>"> <?= $user['name'] ?> </option>
                        <?php endforeach; ?>
                    </select>  
                </div>
                
                <div class="form-group">
                    <label for="image">image</label>
                    <input class="w-50 mx-auto form-control" type="file" name="image" id="image">
                    <span style="font-size: 18px;" class='text-danger'><?= $imgageErr?></span>            
                </div>

                <div class="form-group">
                    <input class="btn btn-secondary text-white px-5" type="submit" value="add">
                </div>
            </form>
        </section>

    <?php
    }
    //edit page////////////////////////////////////////////////////
    elseif($do == 'edit'){
        echo 'this is edit page';
    }
    // update////////////////////////////////////////////////////////
    elseif($do == 'update'){
        echo 'this is update page';
    }
    //delete page/////////////////////////////////////////////////////////
    elseif($do == 'delete'){
        echo 'this is delete page';
    }
    // approve page /////////////////////////////////////////////////
    elseif($do == 'approve' ){
        echo 'approve0';
    }
    else{
        echo 'this is main page';
    }

    
    include $tpl.'footer.php';
}else{
    header('Location:index.php');
}

?>