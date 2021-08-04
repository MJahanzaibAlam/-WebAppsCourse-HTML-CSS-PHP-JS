<?php
session_start();
if ( !isset($_SESSION['email']) ){
    die("Not logged in");
}

require_once "pdo.php";
?>

<!DOCTYPE html>
<html>
<head>
<title>Mohammad Jahanzaib Alam's Automobile Tracker</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <?php echo htmlentities($_SESSION['email']); ?></h1>
<h2>Automobiles</h2>

<?php
if ( isset($_SESSION['success']) ) {
  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
  unset($_SESSION['success']);
}
?>

<ul>
<?php
$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ( $rows as $row ) {
    echo "<tr><td>";
    echo("<li>" . htmlentities($row['make']) . " " . htmlentities($row['year']) . " / " . htmlentities($row['mileage']));
}
?>
</ul>
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>
</body>
</html>