<?php
include('db_connect.php');

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $description = $_POST['description'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // Insert transaction into the database
    $sql = "INSERT INTO transactions (description, amount, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $description, $amount, $date);

    if ($stmt->execute()) {
        echo "<p>Transaction added successfully.</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
<a href="add_transaction.php">Back to Add Transaction</a>
