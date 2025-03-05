<?php
include 'components/header.php';
include 'components/sidebar.html';

include 'operation/connection.php';
$userid=$_SESSION['userid']; 
$sql = "SELECT id, sav_name, total_amount, saved_amount FROM savings WHERE userid=$userid";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savings Management</title>
    <link rel="stylesheet" href="assets\css\myplan.css">
</head>
<body>
<div class="main-content">
    <h2>Add New Saving</h2>

    <!-- Add New Saving Form -->
    <div class="saving-form">
        <form action="operation/new_savings.php" method="POST">
            <input type="text" name="saving_name" placeholder="Saving Name" required><br>
            <input type="number" name="target_amount" placeholder="Target Amount" required><br>
            <button type="submit">Add Saving</button>
        </form>
    </div>

    <!-- Savings Cards with Flexbox -->
    <h3>Your Savings</h3>
    <div class="savings-container">
    <?php
        if ($result->num_rows > 0) {
            // Loop through the savings and display each as a card
            while ($row = $result->fetch_assoc()) {
                $saving_name = $row['sav_name'];
                $target_amount = $row['total_amount'];
                $saved_amount = $row['saved_amount'];
                $progress = ($saved_amount / $target_amount) * 100;
        ?>
        <div class="saving-card">
            <h4><?php echo ucfirst($saving_name); ?></h4>

            <p>Amount Saved: ₹<?php echo number_format($saved_amount); ?></p>
            <p>Total Target: ₹<?php echo number_format($target_amount); ?></p>
            <div class="progress-bar">
                <div style="width: <?php echo $progress; ?>%;"><?php echo round($progress); ?>%</div>
            </div>
            <div class="card-actions">
                <button class="add-amount" data-id="<?php echo $row['id']; ?>" data-saving="<?php echo $saving_name; ?>">Add Amount</button>
                <button class="delete" data-id="<?php echo $row['id']; ?>" data-saving="<?php echo $saving_name; ?>">Delete</button>
               
                <!-- <form class="deleteform" action="operation/delete_saving.php" method="POST" style="display:inline;">
                    <input type="hidden" name="saving_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="delete" onclick="return confirm('Are you sure you want to delete this saving?');">Delete</button>
                </form> -->
            </div>
        </div>
        <?php
            }
        } else {
            echo "<p>No savings found.</p>";
        }
        ?>
    </div>
</div>

<!-- Modal for Adding Amount -->
<div class="modal" id="addAmountModal">
    <span class="modal-close">&times;</span>
    <h4>Add Amount to <span id="savingName"></span></h4>
    <form action="operation/add_savings_amount.php" method="POST">
        <input type="hidden" id="savingId" name="saving_id">
        <input type="number" name="add_amount" placeholder="Enter Amount" required><br>
        <button type="submit">Add Amount</button>
    </form>
</div>

<script>
    // Pop-up modal functionality for adding amount
    const modal = document.getElementById('addAmountModal');
    const closeModal = document.querySelector('.modal-close');
    const addAmountButtons = document.querySelectorAll('.add-amount');
    const deleteButtons = document.querySelectorAll('.delete');
    const savingNameDisplay = document.getElementById('savingName');
    const savingIdInput = document.getElementById('savingId'); // Hidden input for saving ID

    addAmountButtons.forEach(button => {
        button.addEventListener('click', () => {
            const savingName = button.getAttribute('data-saving');
            const savingId = button.getAttribute('data-id'); // Get saving ID

            savingNameDisplay.textContent = savingName;
            savingIdInput.value = savingId; // Set the hidden input value

            modal.style.display = 'block';
        });
    });

    deleteButtons.forEach(button => {
        button.addEventListener('click', () => {
            const savingName = button.getAttribute('data-saving');
            const savingId = button.getAttribute('data-id'); // Get saving ID
            confirm('Are you sure you want to delete this saving?');
            window.location.href = 'operation/delete_savings.php?id='+savingId;
        });
    });

    closeModal.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }

</script>

</body>
</html>
