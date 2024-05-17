<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require('./vendor/autoload.php');
require('./ConnectDB.php');

/**
 * Start session.
 */
session_start();
$userId = $_SESSION['user_id'];
// $userName = $_SESSION['user_name'];
// $userEmail = $_SESSION['user_email'];

/**
 * @Constant BOOKREAD.
 *  That is for the select the books read by user.
 */
define('ALLSTOCKS', "SELECT * FROM stock");


/**
 * Create the instase of the class Query.
 */
$db = new ConnectDB();
$conn = $db->connectDB();
$stmt = $conn->prepare(ALLSTOCKS);
$stmt->execute();

// Fetch the result.
$result = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Linked custom CSS. -->
  <link rel="stylesheet" href="./../../Style/Style.css">
  <!-- Linked font awesome. -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Custom script file for form validation. -->
  <script src="../../JS/script.js"></script>
  <!-- <script src="script.js"></script> -->
</head>

<body>
  <div class="wrapper">
    <div class="container mt-5">
      <table class="table caption-top  table-hover" id="myTable">
        <h1>Stocks</h1>
        <a class="btn btn-primary my-2" href="/stock-entry">Add Stocks</a>
        <a class="btn btn-danger ms-2 my-2" href="/logout">Log Out</a>
        <thead>
          <tr class="table-dark py-3">
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Price</th>
            <th scope="col">Add date</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          // Print all data in the form of HTML table.
          foreach ($result as $value) {
          ?>
            <tr>
              <th scope="row"><?php echo $i; ?></th>
              <td><?php echo $value['name']; ?></td>
              <td><?php echo $value['price']; ?></td>
              <td><?php echo $value['created_date']; ?></td>
              <td><?php if ($value['created_by'] == $userId) { ?> <a href="RemoveStock.php?id=<?php echo $value['ID']; ?>" class=" btn btn-danger">Remove</a><a href="" class="ms-3 btn btn-primary">Edit</a><?php } ?></td>
            </tr>
          <?php $i++;
          } ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
