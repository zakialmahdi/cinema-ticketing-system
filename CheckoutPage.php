<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticketing_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = (int)($_COOKIE['UserID'] ?? 0);
$saleID = (int)($_GET['sale_id'] ?? 0);

if ($userID <= 0) {
    header('Location: LoginPage.php');
    exit;
}

if ($saleID <= 0) {
    header('Location: MainPage.php');
    exit;
}

$sql = "
    SELECT 
        sales.SaleID,
        sales.UserID,
        movies.MovieName,
        sales.TicketType,
        sales.Quantity,
        sales.Showtime,
        fnb.FnbName,
        sales.FnbQuantity,
        sales.TotalPrice
    FROM sales
    LEFT JOIN movies ON sales.MovieID = movies.MovieID
    LEFT JOIN fnb ON sales.FnbID = fnb.FnbID
    WHERE sales.SaleID = $saleID
    AND sales.UserID = $userID
";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    header('Location: MainPage.php');
    exit;
}

$booking = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout</title>
  <style>
    .container {
      max-width: 700px;
      margin: 0 auto;
      padding: 25px;
      background-color: white;
      border-radius: 10px;
    }

    .message {
      margin: 25px auto;
      max-width: 500px;
      padding: 20px;
      background-color: #fff58b;
      border-radius: 10px;
    }

    th {
      background-color: #fff58b;
    }

    th,
    td {
      padding: 10px;
    }

    a {
      color: #222;
    }
  </style>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; margin: 0; padding: 30px; background-color: #f0f0f0; text-align: center;">
  <div class="container">
    <h1 style="margin-bottom: 5px;">Cinema Ticketing System</h1>
    <h2 style="margin-top: 0; color: #555;">Checkout</h2>

    <hr>

    <div class="message">
      <h3 style="margin-top: 28px; color: #222;">Booking Received</h3>
      <p>Your ticket booking has been received.</p>
      <p>Please go to the cinema counter to make your payment and collect your ticket.</p>
      <p>Show your selected movie, ticket type, quantity, and showtime to the counter staff.</p>
    </div>

    <h3 style="margin-top: 28px; color: #222;">Your Booking</h3>
    <table style="margin: 0 auto; border-collapse: collapse; width: 80%;" border="1" cellpadding="8" cellspacing="0">
      <tr>
        <th>Detail</th>
        <th>Information</th>
      </tr>
      <tr>
        <td>Booking ID</td>
        <td><?php echo htmlspecialchars($booking['SaleID']); ?></td>
      </tr>
      <tr>
        <td>Movie</td>
        <td><?php echo htmlspecialchars($booking['MovieName']); ?></td>
      </tr>
      <tr>
        <td>Ticket Type</td>
        <td><?php echo htmlspecialchars(ucfirst($booking['TicketType'])); ?></td>
      </tr>
      <tr>
        <td>Ticket Quantity</td>
        <td><?php echo htmlspecialchars($booking['Quantity']); ?></td>
      </tr>
      <tr>
        <td>Showtime</td>
        <td><?php echo htmlspecialchars($booking['Showtime']); ?></td>
      </tr>
      <tr>
        <td>Food / Drink</td>
        <td><?php echo htmlspecialchars($booking['FnbName'] ?? 'None'); ?></td>
      </tr>
      <tr>
        <td>F&B Quantity</td>
        <td><?php echo htmlspecialchars($booking['FnbQuantity']); ?></td>
      </tr>
      <tr>
        <td>Total Price</td>
        <td>RM <?php echo number_format($booking['TotalPrice'], 2); ?></td>
      </tr>
    </table>

    <h3 style="margin-top: 28px; color: #222;">Important Notes</h3>
    <p>Please arrive at least 15 minutes before the movie starts.<br>
    Seats are confirmed after payment is completed.<br>
    Bring your student card if you selected a student ticket.</p>

    <hr>

    <p><a href="MainPage.php">Back to Main Menu</a></p>
    <p><a href="LoginPage.php">Back to Login</a></p>
  </div>
</body>
</html>
