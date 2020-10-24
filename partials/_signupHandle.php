<?php
session_start();
echo 'signup Handle';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '_dbconnection.php';
    $signupEmail = $_POST['signupEmail'];
    $signupPassword = $_POST['signupPassword'];
    $signupConfirmPassword = $_POST['signupConfirmPassword'];

    // check whether email is already exists

    $checkSql = "SELECT * FROM `users` WHERE `user_email` = '$signupEmail'";
    $checkResult = mysqli_query($conn, $checkSql);

    if (mysqli_num_rows($checkResult) == 0) {
        if ($signupPassword == $signupConfirmPassword) {
            $hashPassword = password_hash($signupPassword, PASSWORD_DEFAULT);
            $insertSql = "INSERT INTO `users` (`user_email`, `user_password`) VALUES ('$signupEmail', '$hashPassword')";
            $insertResult = mysqli_query($conn, $insertSql);

            $_SESSION["login"] = true;
            $_SESSION['userEmail'] = $signupEmail;

            header('location: /Forum/index.php?signup=success');
            exit();
        } else {
            header('location: _signupModal.php?signup=fail&error=password not matching');
        }
    } else {
        header('location:  _signupModal.php?signup=fail&error=username already exists');
    }
}
