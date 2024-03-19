<?php
session_start();


if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit;
}


// Connect to the database
$host = "INSERT_DB_HOST"; // Replace with DB host
$dbname = "INSERT_DB_NAME"; // Replace with DB name (you need to create the database within the databse first)
$username = "INSERT_DB_USERNAME"; // Replace with DB username
$dbPassword = "INSERT_DB_PASSWORD"; // Replace with DB Password 
$message = '';


try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $dbPassword);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    $user_id = $_SESSION['user_id'];


    $checkVoteStmt = $dbh->prepare("SELECT voted FROM users WHERE id = :user_id");
    $checkVoteStmt->bindParam(':user_id', $user_id);
    $checkVoteStmt->execute();
    $hasVoted = $checkVoteStmt->fetchColumn();


    $candidatesStmt = $dbh->prepare("SELECT name, votes FROM candidates ORDER BY id ASC LIMIT 2");
    $candidatesStmt->execute();
    $candidates = $candidatesStmt->fetchAll(PDO::FETCH_ASSOC);


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$hasVoted) {
        $vote = $_POST['vote'];


        $updateStmt = $dbh->prepare("UPDATE candidates SET votes = votes + 1 WHERE name = :name");
        $updateStmt->bindParam(':name', $vote);
        $updateStmt->execute();


        $updateVotedStmt = $dbh->prepare("UPDATE users SET voted = 1 WHERE id = :user_id");
        $updateVotedStmt->bindParam(':user_id', $user_id);
        $updateVotedStmt->execute();


        header("Location: vote.php");
        exit;
    } elseif ($hasVoted) {
        $message = "You have already voted.";
    }


} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}


// Manually assign descriptions for candidates
$descriptions = [
    "Candidate 1 Description: A visionary leader focused on innovation.",
    "Candidate 2 Description: Dedicated to community and service."
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote for Class Head</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .main-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .side-panel {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .candidates-panel {
            flex: 3;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .candidate {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(50% - 10px);
            transition: transform 0.3s ease;
        }
        .candidate:hover {
            transform: translateY(-5px);
        }
        .candidate-img {
            width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        h1, .candidate-name {
            color: #007bff;
            text-align: center;
        }
        .candidate-description, .votes-count {
            text-align: center;
        }
        .vote-button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .vote-button:hover {
            background-color: #0056b3;
        }
        .logout-button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-align: center;
        }
        .logout-button:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="side-panel">
            <h1>Vote for Class Head</h1>
            <?php if (!empty($message)): ?>
                <p><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>
        <div class="candidates-panel">
            <?php foreach ($candidates as $index => $candidate): ?>
                <div class="candidate">
                    <img src="png<?php echo $index + 1; ?>.jpg" alt="Candidate <?php echo $index + 1; ?>" class="candidate-img">
                    <h2 class="candidate-name"><?php echo htmlspecialchars($candidate['name']); ?></h2>
                    <p class="candidate-description"><?php echo $descriptions[$index]; ?></p>
                    <p class="votes-count">Votes: <?php echo htmlspecialchars($candidate['votes']); ?></p>
                    <?php if (!$hasVoted): ?>
                        <form method="POST" action="vote.php">
                            <input type="hidden" name="vote" value="<?php echo htmlspecialchars($candidate['name']); ?>">
                            <input type="submit" value="Vote" class="vote-button">
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
