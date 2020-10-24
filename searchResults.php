<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>IDiscuss | Search Results</title>
</head>

<body>
    <?php
    require 'partials/_dbconnection.php';
    include 'partials/_header.php';
    ?>
    <div class="container">

        <?php
        // if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $searchKeyword = $_GET['searchKeyword'];
        $searchThreadSql = "SELECT * FROM `threads` WHERE MATCH (`thread_title`, `thread_description`) AGAINST ('$searchKeyword')";
        $searchThreadQuery = mysqli_query($conn, $searchThreadSql);

        if (!mysqli_num_rows($searchThreadQuery) == 0) {
            while ($result = mysqli_fetch_assoc($searchThreadQuery)) {
                echo '<div class="media border mt-2 py-2 px-2">
        <div class="media-body">
          <h5 class="mt-0"><a href= /forum/thread.php?id=' . $result['thread_id'] . '>' . $result['thread_title'] . '</a></h5>
          ' . $result['thread_description'] . '
        </div>
      </div>';
            }
        } else {
            echo '<h1>No Search Results Found</h1>';
        }
        // }
        ?>
    </div>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>