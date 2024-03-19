<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $email = $_POST['email'];
    $password = $_POST['password'];


    // Connect to the database
    $host = "INSERT_DB_HOST"; // Replace with DB host
    $dbname = "INSERT_DB_NAME"; // Replace with DB name (you need to create the database within the databse first)
    $username = "INSERT_DB_USERNAME"; // Replace with DB username
    $dbPassword = "INSERT_DB_PASSWORD"; // Replace with DB Password

    try {
        $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        // Authenticate the user against the database
        $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($user) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id']; // Assuming 'id' is the user identifier
            // Redirect to vote.php
            header("Location: vote.php");
            exit;
        } else {
            $loginError = "Invalid email or password!";
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 40px auto;
        }
        h1, p, label {
            text-align: center;
        }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        a {
            color: #1a2a6c;
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <form method="POST" action="signin.php">
        <h1>Sign In</h1>
        <?php if (!empty($loginError)): ?>
            <p><?php echo $loginError; ?></p>
        <?php endif; ?>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <input type="submit" value="Sign In">
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
</body>
</html>
