<?php
session_start();

if(isset($_SESSION['user'])){
    //content
    $title = 'Home';
    include 'init.php';
    $stmt = $db->prepare("SELECT id FROM users");
    $stmt->execute();
    $userCount = $stmt->rowCount();
    
    ?>
    <section id='dashboard' >
        <div class="container">
            <h1 class="text-center m-5 text-secondary" >Home </h1>
            <div class="row">

                <div class="col-4">
                    <div class="bg-primary text-center text-white p-3 rounded ">
                        <p>Total Members</p>
                        <span class=" timer display-3" data-from="0" data-to="<?= $userCount ?>"
      data-speed="1000"></span>
                    </div>
                </div>

                <div class="col-4">
                    <div class="bg-success text-center text-white p-3 rounded">
                        <p>Pending Members</p>
                        <span class=" timer display-3" data-from="0" data-to="62"
      data-speed="800"></span>
                    </div>
                </div>

                <div class="col-4">
                    <div class="bg-danger text-center text-white p-3 rounded">
                        <p>Total Products</p>
                        <span class=" timer display-3" data-from="0" data-to="200"
      data-speed="800"></span>
                    </div>
                </div>
                
            </div>
        </div>
    </section>


    <?php include $tpl.'footer.php';
}else{
    header('Location:index.php');
}
