<?php

require_once "pdo.php";
$success = FALSE;
$failure = FALSE;

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}

// If the user filled in car details. add to database
if ( isset($_POST['make']) ) {

    if ( strlen($_POST['make']) < 1 ) {
        $failure = "Make is required";
    }
    else if ( is_numeric($_POST['year']) && is_numeric($_POST['mileage']) ) {
        $stmt = $pdo->prepare('INSERT INTO autos
            (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        $success = TRUE;
    } else {
        $failure = "Mileage and year must be numeric";
    }
}
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
<h1>Tracking Autos for <?php echo htmlentities($_GET['name']); ?></h1>
<?php
if ($success == TRUE)
  echo "<p style='color:green;'>Record Inserted</p>\n";

if ($failure !== FALSE)
echo('<p style="color: red;">'.$failure."</p>\n");
?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
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
</div>
</body>
</html>
