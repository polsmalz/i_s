<?php
include('db_connect.php'); // Include the database connection

// Start the session to manage the logged-in user
session_start();

// Check if the user is logged in (assuming user_id is stored in the session)
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to home if not logged in
    exit();
}

// Get the current user's ID from the session
$user_id = $_SESSION['user_id'];

// Retrieve daily transaction totals for the current user
$sql = "SELECT DATE(date) AS transaction_date, SUM(amount) AS total_amount 
        FROM transactions 
        WHERE user_id = ? 
        GROUP BY DATE(date) 
        ORDER BY transaction_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Bind user_id parameter
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transaction Report</title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to your stylesheet -->
</head>
<body>
    <h2>Daily Transaction Report</h2>
    <a href="dashboard.php">Back to Dashboard</a>
    
    <table>
        <tr>
            <th>Date</th>
            <th>Total Amount</th>
        </tr>

        <?php
        // Check if there are any results from the query
        if ($result->num_rows > 0) {
            // Output data for each day
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['transaction_date'] . "</td>";
                echo "<td>$" . number_format($row['total_amount'], 2) . "</td>";
                echo "</tr>";
            }
        } else {
            // If no transactions, show a message
            echo "<tr><td colspan='2'>No transactions available for this period.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
