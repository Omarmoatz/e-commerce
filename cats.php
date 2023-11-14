<?php
session_start();
include 'init.php';
$id = $_GET['id'];
$name = $_GET['name'];

$stmt = $db->prepare("SELECT * FROM products WHERE cat_id=? ");
$stmt->execute(array($id));
$products = $stmt->fetchAll();

?>

<h1 class='text-center my-5 bg-danger text-white' > <?= $name ?></h1>
<section>
    <div class="row container" >
    <?php foreach($products as $product):
        $title = $product['title'];
        $img = $product['image'];
        $price = $product['price']; 
        
    ?>
        <div class="col-4" >
            <div class="card" >
                <div class="card-header" >
                    <?= $title ?>
                </div>
                <div class="card-body">
                    <img width='200' src="layouts/images/<?= $img ?>" alt="">
                </div>
                <div class="card-footer" >
                    <?= $price ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
    </div>
</section>

<?php
include $tpl.'footer.php';
?>


