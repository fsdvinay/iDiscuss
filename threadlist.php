<!doctype html>
<html lang="en">
<!-- db connection -->
<?php
require 'partials/_dbconnection.php';
// variable to show alert
$showAlert = false;

?>






<?php
// fetching thread category details using id from url
$threadCategorySql = "SELECT * FROM `categories` WHERE `category_id` = '$_GET[id]'";
$threadCategoryQuery = mysqli_query($conn, $threadCategorySql);
$threadCategoryResult = mysqli_fetch_assoc($threadCategoryQuery);
?>


<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>IDiscuss | <?php echo $threadCategoryResult['category_name'] ?></title>
</head>

<body>
    <?php include 'partials/_header.php'; ?>
    <!-- inserting threads into particular category -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $thread_title = str_replace("'", "\'", $_POST['threadTitle']);
        $thread_Description = str_replace("'", "\'", $_POST['threadDescription']);

        // fetching user_id using email session to insert into thread_user_id
        $userIdSql = "SELECT * FROM `users` WHERE `user_email` = '$_SESSION[userEmail]'";
        $userIdQuery = mysqli_query($conn, $userIdSql);
        $userIdResult = mysqli_fetch_assoc($userIdQuery);

        $insertThreadSql = "INSERT INTO `threads` (`thread_title`, `thread_description`, `thread_category_id`, `thread_user_id`) VALUES ('$thread_title', '$thread_Description', '$_GET[id]', '$userIdResult[user_id]')";

        $insertThreadQuery = mysqli_query($conn, $insertThreadSql);

        if ($insertThreadQuery) {
            $showAlert = true;
        }
    }
    ?>
    <!-- showing alert -->
    <?php
    if ($showAlert == 'true') {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Succes. </strong> Thread has been added succcessfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    }
    ?>
    <div class="container pb-5 mb-5">

        <div class="jumbotron mt-3 mb-5">
            <h1 class="display-4">Welcome to <?php echo $threadCategoryResult['category_name'] ?> Forums</h1>
            <p class="lead"><?php echo $threadCategoryResult['category_description'] ?></p>
            <hr class="my-4">
            <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a>
        </div>



        <!-- fetching threads from db -->
        <?php
        $threadsSql = "SELECT * FROM `threads` WHERE `thread_category_id` = '$_GET[id]'";
        $threadsQuery = mysqli_query($conn, $threadsSql);
        $exists = mysqli_num_rows($threadsQuery);

        // showing form then threads if threeads exists
        if ($exists > 0) {
            // showing form only if user logged in
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
        ?>

                <!-- form to submit thread -->
                <form class="mb-5" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
                    <div class="form-group">
                        <label for="threadTitle">Thread Title</label>
                        <input type="text" name="threadTitle" class="form-control" id="threadTitle" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="threadDescription">Elaborate your Thread</label>
                        <textarea class="form-control" name="threadDescription" id="threadDescription" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php
            } else {
                echo '<div class="alert alert-primary mb-3" role="alert">
                Please login to create thread.<button type="button" class="btn btn-primary btn-sm ml-3"><a href=partials/_loginModal.php class="text-light">Login Here</a></button> 
              </div>';
            }
            ?>
            <h2 class="mb-4 ">Threads</h2>
            <?php
            while ($threadsResult = mysqli_fetch_assoc($threadsQuery)) {
                // fetching user_email using thread_user_id to view with thread
                $userEmailSql = "SELECT * FROM `users` WHERE `user_id` = '$threadsResult[thread_user_id]'";
                $userEmailQuery = mysqli_query($conn, $userEmailSql);
                $userEmailResult = mysqli_fetch_assoc($userEmailQuery);

                echo '<div class="media mt-3 border py-2">
                <img src="images/unnamed.png" width="40" class="mr-3" alt="...">
                    <div class="media-body ">
                    <p>By : <a href=/forum/account.php?user_id=' . $threadsResult['thread_user_id'] . '>' . $userEmailResult['user_email'] . '</a></p>
                        <h5 class="mt-0"><a href=thread.php?id=' . $threadsResult['thread_id'] . '>' . $threadsResult['thread_title'] . '</a></h5>
                        ' . $threadsResult['thread_description'] . '
                    </div>
                </div>';
            }
        } else {

            // showing no threads and asking user to start thread
            echo '
            <div class="bg-primary text-light p-3 mb-5">
            <h1 class="display-6">No Threads in ' . $threadCategoryResult['category_name'] . '</h1>
            <p class="lead">Be the First person to start the Thread</p>
            </div>
            ';
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
            ?>

                <!-- form to submit thread -->
                <form class="mb-5" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
                    <div class="form-group">
                        <label for="threadTitle">Thread Title</label>
                        <input type="text" name="threadTitle" class="form-control" id="threadTitle" aria-describedby="emailHelp">
                    </div>
                    <div class="form-group">
                        <label for="threadDescription">Elaborate your Thread</label>
                        <textarea class="form-control" name="threadDescription" id="threadDescription" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php
            } else {
                echo '<div class="alert alert-primary mb-3" role="alert">
                        Please login to create thread.<button type="button" class="btn btn-primary btn-sm ml-3"><a href=partials/_loginModal.php class="text-light">Login Here</a></button> 
                      </div>';
            }
            ?>

        <?php
        }
        ?>



    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>