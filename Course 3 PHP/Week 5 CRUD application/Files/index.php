<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Mohammad Jahanzaib Alam 4ab4abfa - Index Page</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Welcome to Automobiles Database</h1>

<?php
if ( isset($_SESSION['error']) ) {
  echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
  unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);
}
?>

<p><a href="login.php">Please log in</a></p>
<p>Attempt to <a href="add.php">add data</a> without logging in</p>
</div>
</body>
</html>