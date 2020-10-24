<?php
require 'partials/_dbconnection.php';

// variable to show alert
$showAlert = false;

?>





<?php

// fetching thread details using id from url
$threadsql = "SELECT * FROM `threads` WHERE `thread_id` = '$_GET[id]'";
$threadQuery = mysqli_query($conn, $threadsql);
$threadResult = mysqli_fetch_assoc($threadQuery);
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>IDiscuss | <?php echo $threadResult['thread_title'] ?></title>
</head>

<body>
    <?php include 'partials/_header.php'; ?>
    <!-- inserting comment into particular thread -->
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $comment_content = $_POST['commentContent'];

        // fetching user_id using email session
        $userIdSql = "SELECT * FROM `users` WHERE `user_email` = '$_SESSION[userEmail]'";
        $userIdQuery = mysqli_query($conn, $userIdSql);
        $userIdResult = mysqli_fetch_assoc($userIdQuery);

        $insertCommentSql = "INSERT INTO `comments` (`comment_content`, `comment_thread_id`, `comment_user_id`) VALUES ('$comment_content', '$_GET[id]', '$userIdResult[user_id]')";

        $insertCommentQuery = mysqli_query($conn, $insertCommentSql);

        if ($insertCommentQuery) {
            $showAlert = true;
        }
    }
    ?>
    <div class="container mb-5 pb-5">
        <!-- showing alert -->
        <?php
        if ($showAlert == 'true') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Succes. </strong> Comment has been added succcessfully.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>';
        }
        ?>

        <div class="jumbotron mt-3 mb-5">
            <h1 class="display-4"><?php echo $threadResult['thread_title'] ?></h1>
            <p class="lead"><?php echo $threadResult['thread_description'] ?></p>
            <p>Posted By : <b>
                    <!-- fetching posted by suing thread_user_id -->
                    <?php
                    $threadUserIdSql = "SELECT * FROM `users` WHERE `user_id` = '$threadResult[thread_user_id]'";
                    $threadUserIdQuery = mysqli_query($conn, $threadUserIdSql);

                    echo mysqli_fetch_assoc($threadUserIdQuery)['user_email'];
                    ?>
                </b></p>
        </div>


        <!-- fetching comments from db -->
        <?php
        $commentsSql = "SELECT * FROM `comments` WHERE `comment_thread_id` = '$_GET[id]'";
        $commentsQuery = mysqli_query($conn, $commentsSql);
        $exists = mysqli_num_rows($commentsQuery);
        if ($exists > 0) {
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
        ?>
                <h2>Comment Here</h2>

                <!-- form to submit thread -->
                <form class="mb-5" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">

                    <div class="form-group">
                        <label for="threadDescription">Comment</label>
                        <textarea class="form-control" name="commentContent" id="commentContent" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            <?php
            } else {
                echo '<div class="alert alert-primary mb-3" role="alert">
                Please login to comment on thread.<button type="button" class="btn btn-primary btn-sm ml-3"><a href=partials/_loginModal.php class="text-light">Login Here</a></button> 
              </div>';
            }
            ?>
            <h2 class="mb-4 ">Discussion</h2>
            <?php
            while ($commentsResult = mysqli_fetch_assoc($commentsQuery)) {

                // fetching user_email using user_id
                $userEmailSql = "SELECT * FROM `users` WHERE `user_id` = '$commentsResult[comment_user_id]'";
                $userEmailQuery = mysqli_query($conn, $userEmailSql);
                $userEmailResult = mysqli_fetch_assoc($userEmailQuery);

                echo '<div class="media mt-3 border py-2">
                <img src="images/unnamed.png" width="40" class="mr-3" alt="...">
                    <div class="media-body">
                        <p class=my-0><b><a href=/forum/account.php?user_id=' . $commentsResult['comment_user_id'] . '>' . $userEmailResult['user_email'] . '</a></b></p>
                        ' . $commentsResult['comment_content'] . '
                    </div>
                </div>';
            }
        } else {
            // showing no comments and asking user to start thread
            echo '
            <div class="bg-primary text-light p-3 mb-5">
            <h1 class="display-6">No Discussion in ' . $threadResult['thread_title'] . '</h1>
            <p class="lead">Be the First person to start the Discussion</p>
            </div>
            ';
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
            ?>
                <h2>Comment Here</h2>

                <!-- form to submit thread -->
                <form class="mb-5" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">

                    <div class="form-group">
                        <label for="threadDescription">Comment</label>
                        <textarea class="form-control" name="commentContent" id="commentContent" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
        <?php
            } else {
                echo '<div class="alert alert-primary mb-3" role="alert">
                        Please login to comment on thread.<button type="button" class="btn btn-primary btn-sm ml-3"><a href=partials/_loginModal.php class="text-light">Login Here</a></button> 
                      </div>';
            }
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