<script>
    // controlling navbar options like hiding

    window.onload = () => {
        let signupBtn = document.querySelector('.signupBtn');
        let loginBtn = document.querySelector('.loginBtn');
        let pageTitle = document.title;

        if (pageTitle.includes('Signup')) {
            signupBtn.style.display = 'none'
        } else if (pageTitle.includes('Login')) {
            loginBtn.style.display = 'none'
        }
    }
</script>

<!-- logout -->
<?php
session_start();
require 'partials/_dbconnection.php';


?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="/forum">iDiscuss</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/forum">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <!-- displaying categories in dropdown dynamically -->
                    <?php
                    $fetchCategoriesSql = 'SELECT * FROM `categories` LIMIT 7';
                    $fetchCategoriesQuery = mysqli_query($conn, $fetchCategoriesSql);
                    while ($row = mysqli_fetch_assoc($fetchCategoriesQuery)) {
                        echo '<a class="dropdown-item" href="/forum/threadlist.php?id=' . $row['category_id'] . '">' . $row['category_name'] . '</a>';
                    }
                    ?>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0" action="/forum/searchResults.php" method="GET">
            <input class="form-control mr-sm-2" name="searchKeyword" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
        </form>
        <?php
        // hiding signup & login btn if account logged in
        // controlling logout btn based on status
        if (isset($_SESSION['login'])) {
            if ($_SESSION["login"] == true) {
                echo '<p class="my-1 mr-3 ml-3"><a class="text-light" href="/forum/account.php">Welcome ' . $_SESSION['userEmail'] . '</a></p> <button class="btn btn-light my-2 my-sm-0 ml-1"><a href=/forum/partials/_logout.php>Logout</a></button>';
            }
        } else {
            echo '<button class="btn btn-light my-2 my-sm-0 ml-1 signupBtn"><a href="/forum/partials/_signupModal.php">Signup</a></button>
            <button class="btn btn-light my-2 my-sm-0 ml-1 loginBtn"><a href="/forum/partials/_loginModal.php">Login</a></button>';
        }
        ?>
    </div>
</nav>

<!-- showing alert -->
<?php
if (isset($_GET['signup'])) {
    if ($_GET['signup'] == 'success') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success</strong> Your account has been created and  Logged into your account.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
    } else {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Fail! </strong>' . $_GET['error'] . '
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>';
    }
}
?>