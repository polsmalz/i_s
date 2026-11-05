<?php include('db_connect.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="process_signup.php" method="POST">
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first_name" placeholder="Enter your first name" required>

            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last_name" placeholder="Enter your last name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email address" required>

            <label for="create-password">Create Password</label>
            <input type="password" id="create-password" name="password" placeholder="Create a password" required>

            <label for="confirm-password">Confirm Password</label>
            <input type="password" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>

            <button type="submit" class="btn">Sign Up</button>
        </form>
        <p>Already have an account? <a href="log_in.php">Login here</a></p>
    </div>
</body>
</html>
