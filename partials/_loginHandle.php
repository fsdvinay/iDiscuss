<?php
include '_dbconnection.php';
echo 'login Handle';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loginEmail = $_POST['loginEmail'];
    $loginPassword = $_POST['loginPassword'];

    // check whether email is matching

    $checkSql = "SELECT * FROM `users` WHERE `user_email` = '$loginEmail'";
    $checkResult = mysqli_query($conn, $checkSql);
    $row = mysqli_fetch_assoc($checkResult);

    if (mysqli_num_rows($checkResult) == 1) {
        if (password_verify($loginPassword, $row['user_password'])) {
            session_start();
            $_SESSION["login"] = true;
            $_SESSION['userEmail'] = $loginEmail;
            header('location: /Forum/index.php?login=success');
            exit();
        } else {
            header('location: /Forum/index.php?login=fail&error=invalid password');
        }
    } else {
        header('location: /Forum/index.php?login=fail&error=invalid email');
    }
}
