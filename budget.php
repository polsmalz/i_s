<?php
include('db_connect.php');

// Initialize variables
$current_month = date('Y-m'); // Set the current month (YYYY-MM)

// Handle form submission to set/update budget
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $month = $_POST['month'];
    $amount = $_POST['amount'];

    // Check if there is already a budget set for the month
    $sql = "SELECT * FROM budget WHERE month = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $month);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // If a budget already exists for this month, update it
        $sql = "UPDATE budget SET amount = ? WHERE month = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ds", $amount, $month);
        $stmt->execute();
        $message = "Budget updated successfully.";
    } else {
        // If no budget exists, insert a new one
        $sql = "INSERT INTO budget (month, amount) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sd", $month, $amount);
        $stmt->execute();
        $message = "Budget set successfully.";
    }
}

// Retrieve the current month's budget and total expenses
$sql = "SELECT * FROM budget WHERE month = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_month);
$stmt->execute();
$result = $stmt->get_result();
$budget = $result->fetch_assoc();

$sql = "SELECT SUM(amount) as total_expenses FROM transactions WHERE DATE_FORMAT(date, '%Y-%m') = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $current_month);
$stmt->execute();
$expense_result = $stmt->get_result();
$total_expenses = $expense_result->fetch_assoc()['total_expenses'];

$remaining_balance = $budget ? $budget['amount'] - $total_expenses : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Budget</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Monthly Budget</h2>
    <p class="message"><?php echo isset($message) ? $message : ''; ?></p>
    
    <div class="budget-info">
        <p><strong>Current Month:</strong> <?php echo $current_month; ?></p>
        <p><strong>Monthly Budget:</strong> $<?php echo $budget ? number_format($budget['amount'], 2) : 'Not set'; ?></p>
        <p><strong>Total Expenses:</strong> $<?php echo number_format($total_expenses, 2); ?></p>
        <p><strong>Remaining Balance:</strong> $<?php echo $remaining_balance !== null ? number_format($remaining_balance, 2) : 'N/A'; ?></p>
    </div>
    
    <h3>Set or Update Budget</h3>
    <form action="budget.php" method="POST">
        <label for="month">Month (YYYY-MM):</label>
        <input type="text" name="month" required placeholder="e.g., 2024-01" value="<?php echo $current_month; ?>">
        
        <label for="amount">Budget Amount:</label>
        <input type="number" name="amount" step="0.01" required>
        
        <button type="submit">Save Budget</button>
    </form>
    
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>

<?php
$conn->close();
?>
