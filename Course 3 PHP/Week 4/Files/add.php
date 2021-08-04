<?php
session_start();
if ( ! isset($_SESSION["email"]) ) {
    die("Not logged in");
}

require_once "pdo.php";
$success = FALSE;
$failure = FALSE;

// If the user requested cancel go back to view.php
if ( isset($_POST['cancel']) ) {
    header('Location: view.php');
    return;
}

// If the user filled in car details. add to database
if ( isset($_POST['make']) ) {
    if ( strlen($_POST['make']) < 1 ) {
        $_SESSION['failure'] = "Make is required";
        header("Location: add.php");
        return;
    }
    else if ( is_numeric($_POST['year']) && is_numeric($_POST['mileage']) ) {
        $stmt = $pdo->prepare('INSERT INTO autos
            (make, year, mileage) VALUES ( :mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'])
        );
        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
        return;
    } else {
        $_SESSION['failure'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;
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
<h1>Tracking Autos for <?php echo htmlentities($_SESSION['email']); ?></h1>
<?php
if ( isset($_SESSION['failure']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['failure'])."</p>\n");
    unset($_SESSION['failure']);
}
?>

<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</form>