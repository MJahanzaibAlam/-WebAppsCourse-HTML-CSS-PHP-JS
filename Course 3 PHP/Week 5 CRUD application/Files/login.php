<?php
$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // pass is php123

session_start();
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION['email']);  // Logout current user
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $_SESSION['error'] = "User name and password are required";
        header('Location: login.php');
        return;
    } else if (!str_contains($_POST['email'], '@')) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header('Location: login.php');
        return;
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        if ( $check == $stored_hash ) {
            $_SESSION['email'] = $_POST['email'];
            $_SESSION['success'] = "Logged in.";
            error_log("Login success ".$_POST['email']);
            header('Location: view.php');
            return;
        } else {
            $_SESSION["error"] = "Incorrect password.";
            error_log("Login fail ".$_POST['email']." $check");
            header('Location: login.php');
            return;
        }
    }
}

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to index.php
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Mohammad Jahanzaib Alam's Login Page</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
if ( isset($_SESSION['error']) ) {
    echo('<p style="color:red">'.$_SESSION['error']."</p>\n");
    unset($_SESSION['error']);
}
?>
<form method="POST">
<label for="email">User Name</label>
<input type="text" name="email" id="email"><br/>
<label for="pass">Password</label>
<input type="text" name="pass" id="pass"><br/>
<input type="submit" value="Log In">
<a href="index.php">Cancel</a></p>
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the server-side language used
(all lower case) followed by 123. -->
</p>
</div>
</body>
</html>