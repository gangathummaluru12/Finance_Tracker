<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finance_tracker";
$port=10061;

$conn = new mysqli($servername, $username, $password, $dbname,$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$sql_income = "SELECT SUM(amount) AS total_income FROM expenses WHERE user_id = ? AND type = 'income'";
$sql_expense = "SELECT SUM(amount) AS total_expense FROM expenses WHERE user_id = ? AND type = 'expense'";

$stmt_income = $conn->prepare($sql_income);
$stmt_income->bind_param("i", $user_id);
$stmt_income->execute();
$result_income = $stmt_income->get_result();
$row_income = $result_income->fetch_assoc();
$total_income = $row_income['total_income'] ?? 0; // If null, set to 0

$stmt_expense = $conn->prepare($sql_expense);
$stmt_expense->bind_param("i", $user_id);
$stmt_expense->execute();
$result_expense = $stmt_expense->get_result();
$row_expense = $result_expense->fetch_assoc();
$total_expense = $row_expense['total_expense'] ?? 0; // If null, set to 0

$current_balance = $total_income - $total_expense;

// Fetch expense data grouped by description for the pie chart
$sql = "SELECT category, SUM(amount) AS total_amount FROM expenses WHERE user_id = ? AND type = 'expense' GROUP BY category"; 
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$labels = [];
$data = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['category'];
    $data[] = $row['total_amount'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>Access Your Personal Finance Tracker</h1>
        <a href="logout.php" class="logout-link">Log Out</a>
    </header>
    
    <section class="finance-tracker">
	<!-- Display Current Balance -->
        <div class="balance-container">
            <h2>Current Balance: $<span id="balance"><?php echo number_format($current_balance, 2); ?></span></h2>
        </div>
        <!-- Transaction input -->
        <div class="transaction-input">
            <label for="currency">Select Currency:</label>
            <select id="currency">
                <option value="INR">INR</option>
                <option value="USD">USD</option>
                <option value="EUR">EUR</option>
            </select>

            <label for="date">Choose Date:</label>
            <input type="date" id="date">
<label for="category">Category:</label>
<select id="category">
    <option value="food">Food</option>
    <option value="rent">Rent</option>
    <option value="groceries">Groceries</option>
    <option value="entertainment">Entertainment</option>
    <option value="travel">Travel</option>
    <option value="utilities">Utilities</option>
    <option value="budget">Budget</option>
</select>

            <input type="text" id="description" placeholder="Description">
            <input type="number" id="amount" placeholder="Amount">
            <select id="type">
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>
            <button onclick="addTransaction()" class="button add-button">Add Transaction</button>
            <button onclick="exportToCSV()" class="button export-button">Export</button>
        </div>
        
        <!-- Side-by-side layout for pie chart and table -->
        <div class="side-by-side">
            <!-- Pie chart container -->
            <div class="chart-container">
                <h2>Expense Breakdown</h2>
                <canvas id="expenseChart" width="200" height="200"></canvas>
            </div>
           <div class="chart-container">
                <h2>Income vs Expenses</h2>
                <canvas id="incomeExpenseChart" width="200" height="200"></canvas>
            </div>
        </div>

            <!-- Transaction table -->
            <div class="transaction-table">
                <table id="transaction-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $conn = new mysqli($servername, $username, $password, $dbname,$port);
                        $sql = "SELECT * FROM expenses WHERE user_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($transaction = $result->fetch_assoc()) {
                            echo "<tr data-id='{$transaction['id']}'>
                                <td>{$transaction['date']}</td>
                                <td>{$transaction['description']}</td>
                                <td>{$transaction['category']}</td>
                                <td>{$transaction['amount']}</td>
                                <td>{$transaction['type']}</td>
                                <td><button class='delete-button' onclick='deleteTransaction({$transaction['id']})'>Delete</button></td>
                            </tr>";
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script src="script.js"></script>
    <script>

        let labels = <?php echo json_encode($labels); ?>;
        let data = <?php echo json_encode($data); ?>;

        const ctx = document.getElementById('expenseChart').getContext('2d');
        const expenseChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expenses',
                    data: data,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return `${tooltipItem.label}: $${tooltipItem.raw.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
const incomeExpenseData = [<?php echo $total_income; ?>, <?php echo $total_expense; ?>];

        // Income vs Expenses Chart
        const ctxIncomeExpense = document.getElementById('incomeExpenseChart').getContext('2d');
        const incomeExpenseChart = new Chart(ctxIncomeExpense, {
            type: 'pie',
            data: {
                labels: ['Income', 'Expenses'],
                datasets: [{
                    label: 'Income vs Expenses',
                    data: incomeExpenseData,
                    backgroundColor: ['#4BC0C0', '#FF6384'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                              const value = parseFloat(tooltipItem.raw);
                    const formattedValue = !isNaN(value) ? value.toFixed(2) : tooltipItem.raw;
                    return `${tooltipItem.label}: $${formattedValue}`;
                            }
                        }
                    }
                }
            }
        });

        function updateChart(newLabels, newData) {
            expenseChart.data.labels = newLabels;
            expenseChart.data.datasets[0].data = newData;
            expenseChart.update();
        }
    </script>
</body>
</html>
