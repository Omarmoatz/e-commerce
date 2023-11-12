<section>
  <nav class="navbar navbar-expand-lg bg-black">

    <div class="container-fluid">
      <a class="navbar-brand text-white" href="index.php">Home</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <li class="nav-item">
            <a class="nav-link active text-white" aria-current="page" href="users.php">Members</a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-white" href="#">Features</a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-white" href="#">Pricing</a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-white" href="users.php?do=edit&id=<?php echo $_SESSION['id'] ?>">edit</a>
          </li>

        </ul>

        <span class="navbar-text">
          <a href="logout.php" class="text-white">Logout</a>
        </span>

      </div>
    </div>
  </nav>
</section>