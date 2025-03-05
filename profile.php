<?php
include 'components/header.php';
include 'components/sidebar.html';
include 'operation/connection.php'; // Include the connection file

// Assuming the user is logged in and user id is stored in session
$userid = $_SESSION['userid'];

// Fetch the user's profile data
$sql = "SELECT name, email, city, state, mobile_number FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Fetch the data into an associative array
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Profile Page</title>

    <!-- Google Fonts for Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/profile.css">
</head>
<body>

<div class="main-content">
    <div class="profile-container">
        
        <!-- Profile Card -->
        <div class="profile-card">
            <!-- <img src="https://via.placeholder.com/120" alt="Profile Picture"> -->
             
      <div class="profile-btn" style="width:100px;height:100px;">
        <!-- <img src="https://via.placeholder.com/25" alt="Profile"> -->
         <h2 style="font-size:70px"><?php echo "$fstchar";?></h2>
      </div>
            <h3><?php echo ucfirst($user['name']); ?></h3>
            <div class="profile-details">
                <div class="profile-item">
                    <span class="profile-item-label">NAME:</span>
                    <span class="profile-item-value"><?php echo ucfirst($user['name']); ?></span>
                </div>
                <div class="profile-item">
                    <span class="profile-item-label">EMAIL:</span>
                    <span class="profile-item-value"><?php echo $user['email']; ?></span>
                </div>
                <div class="profile-item">
                    <span class="profile-item-label">CITY:</span>
                    <span class="profile-item-value"><?php echo ucfirst($user['city']); ?></span>
                </div>
                <div class="profile-item">
                    <span class="profile-item-label">STATE:</span>
                    <span class="profile-item-value"><?php echo ucfirst($user['state']); ?></span>
                </div>
                <div class="profile-item">
                    <span class="profile-item-label">MOBILE NUMBER:</span>
                    <span class="profile-item-value"><?php echo $user['mobile_number']; ?></span>
                </div>
            </div>
        </div>

        <!-- Profile Form for Updating -->
        <div class="profile-form">
            <h3>Update your profile here!</h3>
            <form action="operation/update_profile.php" method="POST">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>

                <label for="mobile">Mobile Number</label>
                <input type="tel" id="mobile" name="mobile" value="<?php echo $user['mobile_number']; ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo $user['city']; ?>" required>

                <label for="state">State</label>
                <input type="text" id="state" name="state" value="<?php echo $user['state']; ?>" required>

                <button type="submit">Save & Update</button>
            </form>
        </div>
    </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
