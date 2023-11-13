<?php
session_start();

if(isset($_SESSION['userName'])){
    //content
    $title = 'products';
    include 'init.php';

    $do = '';
    $do = isset($_GET['do'])?$_GET['do']:'main';

    if($do == 'main'){
        echo 'this is main page';
    }
    //add page/////////////////////////////////////////////////////
    elseif($do == 'add'){?>
     
        <section class=' p-4 text-center' id='addProduct'>
            <h2 class='text-danger' >Add New Products</h2>
            <form class="text-white bg-info mt-3 rounded py-5 w-50 mx-auto " id="adminForm" action="" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title">
                </div>

                <div class="form-group">
                    <label for="desc">Description</label>
                    <input type="text" name="desc" id="desc">
                </div>

                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" name="price" id="price">
                </div>

                <div class="form-group">
                    <label for="Country">Country</label>
                    <select name="Country" id="Country"></select>
                    <option value="">Select Country</option>
                    <option value="egypt">egypt</option>
                    <option value="china">china</option>
                    <option value="france">france</option>
                    <option value="england">england</option>
                    <option value="germany">germany</option>

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