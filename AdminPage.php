<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ticketing_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (($_COOKIE['AdminLogin'] ?? '') !== 'yes') {
    header('Location: LoginPage.php');
    exit;
}

$message = '';
$messageColor = 'green';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add_movie') {
        $movieName = trim($_POST['movie_name'] ?? '');

        if (!empty($movieName)) {
            $sql = "INSERT INTO movies (MovieName) VALUES ('$movieName')";

            if ($conn->query($sql) === TRUE) {
                $message = "Movie added successfully.";
            } else {
                $message = "Failed to add movie: " . $conn->error;
                $messageColor = "red";
            }
        } else {
            $message = "Movie name cannot be empty.";
            $messageColor = "red";
        }
    }

    if ($action === 'add_fnb') {
        $fnbName = trim($_POST['fnb_name'] ?? '');
        $price = (float)($_POST['price'] ?? 0);

        if (!empty($fnbName) && $price > 0) {
            $sql = "INSERT INTO fnb (FnbName, Price) VALUES ('$fnbName', $price)";

            if ($conn->query($sql) === TRUE) {
                $message = "F&B item added successfully.";
            } else {
                $message = "Failed to add F&B item: " . $conn->error;
                $messageColor = "red";
            }
        } else {
            $message = "F&B name and price are required.";
            $messageColor = "red";
        }
    }

    if ($action === 'delete_movie') {
        $movieID = (int)($_POST['movie_id'] ?? 0);

        if ($movieID > 0) {
            $sql = "DELETE FROM movies WHERE MovieID = $movieID";

            if ($conn->query($sql) === TRUE) {
                $message = "Movie deleted successfully.";
            } else {
                $message = "Failed to delete movie: " . $conn->error;
                $messageColor = "red";
            }
        }
    }

    if ($action === 'delete_fnb') {
        $fnbID = (int)($_POST['fnb_id'] ?? 0);

        if ($fnbID > 0) {
            $sql = "DELETE FROM fnb WHERE FnbID = $fnbID";

            if ($conn->query($sql) === TRUE) {
                $message = "F&B item deleted successfully.";
            } else {
                $message = "Failed to delete F&B item: " . $conn->error;
                $messageColor = "red";
            }
        }
    }

    if ($action === 'delete_sale') {
        $saleID = (int)($_POST['sale_id'] ?? 0);

        if ($saleID > 0) {
            $sql = "DELETE FROM sales WHERE SaleID = $saleID";

            if ($conn->query($sql) === TRUE) {
                $message = "Sales record deleted successfully.";
            } else {
                $message = "Failed to delete sales record: " . $conn->error;
                $messageColor = "red";
            }
        }
    }
}

$movies = $conn->query("SELECT MovieID, MovieName FROM movies ORDER BY MovieID ASC");
$fnbItems = $conn->query("SELECT FnbID, FnbName, Price FROM fnb ORDER BY FnbID ASC");

$sales = $conn->query("
    SELECT 
        sales.SaleID,
        sales.UserID,
        user_account.Username,
        movies.MovieName,
        sales.TicketType,
        sales.Quantity,
        sales.Showtime,
        fnb.FnbName,
        sales.FnbQuantity,
        sales.TotalPrice
    FROM sales
    LEFT JOIN user_account ON sales.UserID = user_account.UserID
    LEFT JOIN movies ON sales.MovieID = movies.MovieID
    LEFT JOIN fnb ON sales.FnbID = fnb.FnbID
    ORDER BY sales.SaleID DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      padding: 30px;
      background-color: #bababa;
      text-align: center;
    }

    .container {
      max-width: 1000px;
      margin: 0 auto;
      padding: 25px;
      background-color: white;
      border-radius: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }

    th {
      background-color: #fff58b;
    }

    th, td {
      border: 1px solid #999;
      padding: 10px;
    }

    input {
      padding: 8px;
      margin: 5px;
      box-sizing: border-box;
    }

    button {
      padding: 8px 14px;
      margin: 5px;
      border: 0;
      border-radius: 5px;
      background-color: #222;
      color: white;
      cursor: pointer;
    }

    .delete-btn {
      background-color: #b00020;
    }

    .form-box {
      margin: 20px auto;
      padding: 20px;
      background-color: #fff58b;
      border-radius: 10px;
      max-width: 500px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Cinema Ticketing System</h1>
    <h2>Admin Page</h2>

    <hr>

    <?php if (!empty($message)): ?>
      <p style="color: <?php echo $messageColor; ?>; font-weight: bold;">
        <?php echo htmlspecialchars($message); ?>
      </p>
    <?php endif; ?>

    <h3>Add New Movie</h3>
    <form class="form-box" method="post">
      <input type="hidden" name="action" value="add_movie">
      <input type="text" name="movie_name" placeholder="Movie name" required>
      <button type="submit">Add Movie</button>
    </form>

    <h3>Now Showing Movies</h3>
    <table>
      <tr>
        <th>Movie ID</th>
        <th>Movie Name</th>
        <th>Action</th>
      </tr>

      <?php while ($movie = $movies->fetch_assoc()): ?>
        <tr>
          <td><?php echo $movie['MovieID']; ?></td>
          <td><?php echo htmlspecialchars($movie['MovieName']); ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="action" value="delete_movie">
              <input type="hidden" name="movie_id" value="<?php echo $movie['MovieID']; ?>">
              <button class="delete-btn" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <hr>

    <h3>Add New Food / Beverage</h3>
    <form class="form-box" method="post">
      <input type="hidden" name="action" value="add_fnb">
      <input type="text" name="fnb_name" placeholder="F&B name" required>
      <input type="number" name="price" placeholder="Price" min="0.01" step="0.01" required>
      <button type="submit">Add F&B</button>
    </form>

    <h3>Food and Beverages</h3>
    <table>
      <tr>
        <th>F&B ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Action</th>
      </tr>

      <?php while ($fnb = $fnbItems->fetch_assoc()): ?>
        <tr>
          <td><?php echo $fnb['FnbID']; ?></td>
          <td><?php echo htmlspecialchars($fnb['FnbName']); ?></td>
          <td>RM <?php echo number_format($fnb['Price'], 2); ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="action" value="delete_fnb">
              <input type="hidden" name="fnb_id" value="<?php echo $fnb['FnbID']; ?>">
              <button class="delete-btn" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <hr>

    <h3>Sales Tracker</h3>
    <table>
      <tr>
        <th>Sale ID</th>
        <th>User ID</th>
        <th>Username</th>
        <th>Movie</th>
        <th>Ticket</th>
        <th>Qty</th>
        <th>Showtime</th>
        <th>F&B</th>
        <th>F&B Qty</th>
        <th>Total</th>
        <th>Action</th>
      </tr>

      <?php while ($sale = $sales->fetch_assoc()): ?>
        <tr>
          <td><?php echo $sale['SaleID']; ?></td>
          <td><?php echo $sale['UserID']; ?></td>
          <td><?php echo htmlspecialchars($sale['Username'] ?? 'Unknown'); ?></td>
          <td><?php echo htmlspecialchars($sale['MovieName'] ?? 'Deleted Movie'); ?></td>
          <td><?php echo htmlspecialchars($sale['TicketType']); ?></td>
          <td><?php echo $sale['Quantity']; ?></td>
          <td><?php echo htmlspecialchars($sale['Showtime']); ?></td>
          <td><?php echo htmlspecialchars($sale['FnbName'] ?? 'None'); ?></td>
          <td><?php echo $sale['FnbQuantity']; ?></td>
          <td>RM <?php echo number_format($sale['TotalPrice'], 2); ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="action" value="delete_sale">
              <input type="hidden" name="sale_id" value="<?php echo $sale['SaleID']; ?>">
              <button class="delete-btn" type="submit">Delete</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <hr>

    <p>
      <a href="MainPage.php">Back to Main Page</a>
    </p>
  </div>
</body>
</html>
