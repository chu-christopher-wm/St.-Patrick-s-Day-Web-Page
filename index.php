<?php
require_once('connect.php');
function register($conn) {
    $password = sha1($_POST['password']);
    $email = $_POST['email'];
    $token = generateToken();
    $sql = 'INSERT INTO users (password, email, token) VALUES (?, ?, ?)';
    $stmt = $conn->prepare($sql);
    try {
        if ($stmt->execute(array($password, $email, $token))) {
            setcookie('token', $token, 0, "/");
            $sql = 'INSERT INTO orders (users_id, status) (SELECT u.id, "new" FROM users u WHERE u.token = ?)';
            $stmt1 = $conn->prepare($sql);
            if ($stmt1->execute(array($token))) {
                echo 'Account Registered';
            }
        }
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function generateToken() {
    $date = date(DATE_RFC2822);
    $rand = rand();
    return sha1($date.$rand);
}
if(isset($_POST['login'])) {
    login($dbh);
}

if(isset($_POST['register'])) {
    register($dbh);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spring Break: Bunny Discovery </title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Finding Easter</h1>
<h2>Help us find the easter bunny by answering the quick survey below.</h2>
<form method="post">
    How many bunnies did you see during the break?
    <br><br><input type="text" name="bunnynum" value="Enter Number Here"><br><br>
    Were any of them carrying a sack of eggs?
    <br><br><input type="text" name="bunnycol" value="Enter Number Here"><br><br>
    <input type="submit" value="Submit">
</form>
</body>
</html>