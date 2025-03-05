<?php
include 'components/header.php';
include 'components/sidebar.html';
include 'operation/fetch_transaction.php';
include 'operation/fetch_expense.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashflow</title>
    <link rel="stylesheet" href="assets/css/cashflow.css">
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
<div class="main-content">
            <div class="dash-row">
                <div class="graph">
                    <h1>Month Expense</h1>
                    <div class="chart-container" style="width:80%;display:flex;justify-content:center;">
                        <canvas id="myPieChart"></canvas>
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
            </div>
            <div class="transaction-section">
                <h1>Transactions</h1>
                <label for="filterType">Filter by Type:</label>
                <select id="filterType" onchange="filterTransactions()">
                    <option value="all">All</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
                <table border="1" id="transactionTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                          <?php if (!empty($transactions)) : ?>
                              <?php foreach ($transactions as $transaction) : ?>
                                  <tr class=<?= $transaction['cashflow'] ?>>
                                      <td><?= date("Y-m-d", strtotime($transaction['datetime'])); ?></td>
                                      <td><?= $transaction['cashflow'] ?></td>
                                      <td><span class="badge <?= ($transaction['type'] == 'income') ? 'income' : 'expense'; ?>">
                                          <?= ucfirst($transaction['type']); ?></span></td>
                                      <td><?= $transaction['description']; ?></td>
                                      <td>₹<?= $transaction['amount']; ?></td>
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
    function filterTransactions() {
        const filterValue = document.getElementById("filterType").value;
        const rows = document.querySelectorAll("#transactionTable tbody tr");

        rows.forEach(row => {
            if (filterValue === "all") {
                row.style.display = "";
            } else if (row.classList.contains(filterValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }
</script>
</body>
</html>