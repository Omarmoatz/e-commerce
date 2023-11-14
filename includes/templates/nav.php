<section>
  <nav class="navbar navbar-expand-lg bg-black">

    <div class="container-fluid">
      <a class="navbar-brand text-white" href="index.php">Home</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <?php
            $stmt = $db->prepare('SELECT * FROM categories');
            $stmt->execute();
            $cats = $stmt->fetchAll();
            foreach($cats as $cat):
              $name = $cat['name'];
              $id = $cat['id'];
          ?>
          <li class="nav-item">
            <a class="nav-link text-white" href="cats.php?id=<?= $id ?>&name=<?= $name ?>"><?= $name ?></a>
          </li>
          <?php endforeach; ?>

        </ul>

        <span class="navbar-text">
          <a href="logout.php" class="text-white">Logout</a>
        </span>

      </div>
    </div>
  </nav>
</section>