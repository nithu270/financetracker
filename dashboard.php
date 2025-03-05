<?php
include 'components/header.php';
include 'components/sidebar.html';
// Include database connection file
include 'operation/fetch_expense.php';
include 'operation/connection.php';


// Assuming the user is logged in and you have stored their id in the session
$userid = $_SESSION['userid']; // Get the logged-in user's ID

// SQL query to fetch the 5 most recent transactions for the user
$sql = "SELECT cashflow,type, description, amount, datetime FROM transaction WHERE userid = ? ORDER BY datetime DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid); // Bind the user ID to prevent SQL injection
$stmt->execute();
$result = $stmt->get_result();

$transactions = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row; // Add each transaction to the array
    }
} else {
    echo "No recent transactions found.";
}

$sav_sql = "SELECT id, sav_name, total_amount, saved_amount FROM savings WHERE userid=$userid";
$sav_result = $conn->query($sav_sql);


$rec_sql = "SELECT 
            DATE_FORMAT(datetime, '%M') as month, 
            SUM(CASE WHEN cashflow = 'income' THEN amount ELSE 0 END) as total_income, 
            SUM(CASE WHEN cashflow = 'expense' THEN amount ELSE 0 END) as total_expense 
        FROM transaction 
        WHERE userid = ? 
        GROUP BY DATE_FORMAT(datetime, '%Y-%m') 
        ORDER BY datetime DESC 
        LIMIT 5"; // Fetch data for recent 5 months
$stmt = $conn->prepare($rec_sql);
$stmt->bind_param("i", $userid); // Bind the user ID to prevent SQL injection
$stmt->execute();
$rec_result = $stmt->get_result();

$months = [];
$incomeData = [];
$expenseData = [];

if ($rec_result->num_rows > 0) {
    while ($rec_row = $rec_result->fetch_assoc()) {
        $months[] = $rec_row['month']; // Month label
        $incomeData[] = $rec_row['total_income']; // Total income for the month
        $expenseData[] = $rec_row['total_expense']; // Total expense for the month
    }
} else {
    echo "No data available for recent months.";
}

?>

<html>
    <head>
        <link rel="stylesheet" href="assets/css/dashboard.css">
    </head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <body>
        <div class="main-content">
            <div class="dash-row">
                <div class="graph">
                    <h1>Month Expense</h1>
                    <div class="chart-container" style="width:80%;display:flex;justify-content:center;">
                        <canvas id="myPieChart"></canvas>
                    </div>  
                </div>
                <div class="graph">
                    <h1>Recent months</h1>
                    <div class="chart-container">
                        <canvas id="myGroupedBarChart"></canvas>
                    </div>  
                </div>
                <div class="add-transaction">
                    <h2>Add Transaction</h2>
                    <form class="add" action="operation/addtransaction.php" method="POST">
                        <input type="text" placeholder="Transaction" list="amount" name="transaction" required><br>
                        <datalist id="amount">
                            <option value="income">
                            <option value="expense">
                        </datalist>
                        <input type="date" name="date" required><br>
                        <input type="text" list="type" placeholder="Type of Transaction" name="type" required><br>
                        <datalist id="type">
                            <option value="salary">
                            <option value="food">
                            <option value="rent">
                            <option value="vehicle">
                            <option value="education">
                            <option value="movie">
                            <option value="others">
                        </datalist>
                        <input type="number" placeholder="Amount" name="amount" required><br>
                        <input type="text" placeholder="Description" name="description"><br>
                        <button>ADD</button>
                    </form>
                </div>
                <div class="dash-savings">
                    <h2>Active Savings</h2>
                    <?php
                        if ($sav_result->num_rows > 0) {
                            // Loop through the savings and display each as a card
                            while ($sav_row = $sav_result->fetch_assoc()) {
                                $saving_name = $sav_row['sav_name'];
                                $target_amount = $sav_row['total_amount'];
                                $saved_amount = $sav_row['saved_amount'];
                                $progress = ($saved_amount / $target_amount) * 100;
                    ?>
                    <div class="savings-item">
                        <label class="slider-label"><?php echo ucfirst($saving_name); ?></label>
                        <div class="progress">
                            <div class="progress-bar" style="width: <?php echo $progress; ?>%;"><?php echo round($progress); ?>%</div>
                        </div>
                    </div>
                    <!-- <div class="savings-item">
                        <label class="slider-label">Savings Plan 2</label>
                        <div class="progress">
                            <div class="progress-bar" style="width: 40%;">40%</div>
                        </div>
                    </div>
                    <div class="savings-item">
                        <label class="slider-label">Savings Plan 3</label>
                        <div class="progress">
                            <div class="progress-bar" style="width: 85%;">85%</div>
                        </div>
                    </div> -->
                    
                    <?php
                        }
                    } else {
                        echo "<p>No savings found.</p>";
                    }
                    ?>
                </div>
                <div class="dash-recent-transactions">
                    <h2>Recent Transactions</h2>
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Transaction Type</th>
                                <th>Expense Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php if (!empty($transactions)) : ?>
                              <?php foreach ($transactions as $transaction) : ?>
                                  <tr>
                                      <td><span class="badge <?= $transaction['cashflow'] ?>">
                                          <?= ucfirst($transaction['type']); ?></span></td>
                                      <td><?= $transaction['description']; ?></td>
                                      <td>₹<?= $transaction['amount']; ?></td>
                                      <td><?= date("Y-m-d", strtotime($transaction['datetime'])); ?></td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php else : ?>
                              <tr>
                                  <td colspan="4">No recent transactions found.</td>
                              </tr>
                          <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>

<script>

    // PHP arrays passed as JSON to JavaScript
    const pieLabels = <?= json_encode($labels); ?>;
    const pieData = <?= json_encode($data); ?>;

    // Create the pie chart with the provided data
    const ctx = document.getElementById('myPieChart').getContext('2d');
    const myPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: pieLabels,
            datasets: [{
                label: 'Monthly Expenses',
                data: pieData,
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)',
                    'rgba(255, 159, 64)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

// PHP arrays passed as JSON to JavaScript for recent months' income and expense data
const months = <?= json_encode($months); ?>.reverse();
const incomeData = <?= json_encode($incomeData); ?>.reverse();
const expenseData = <?= json_encode($expenseData); ?>.reverse();

// Create the grouped bar chart with the provided dynamic data
const barchart = document.getElementById('myGroupedBarChart').getContext('2d');
const myGroupedBarChart = new Chart(barchart, {
  type: 'bar',
  data: {
    labels: months, // Use months fetched from the database
    datasets: [
      {
        label: 'Income',
        data: incomeData, // Use income data fetched from the database
        backgroundColor: 'rgba(54, 162, 235, 0.8)',
      },
      {
        label: 'Expense',
        data: expenseData, // Use expense data fetched from the database
        backgroundColor: 'rgba(255, 99, 132, 0.8)',
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
    },
    scales: {
      x: {
        stacked: false
      },
      y: {
        stacked: false
      }
    }
  }
});

</script>
</html>