<?php
require('./vendor/autoload.php');
require('./ConnectDB.php');

/**
 * Start session.
 */
session_start();
$userId = $_SESSION['user_id'];
$message = "";

$db = new ConnectDB();
$conn = $db->connectDB();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = $_POST['name'];
  $price = $_POST['price'];
  $date = $_POST['date'];

  try {

    // Query for inser the data in the database.
    $query = "INSERT INTO stock (`name`, `price`, `created_date`, `created_by`) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $name, PDO::PARAM_STR);
    $stmt->bindParam(2, $price, PDO::PARAM_STR);
    $stmt->bindParam(3, $date, PDO::PARAM_STR);
    $stmt->bindParam(4, $userId, PDO::PARAM_STR);

    // set parameters and execute.
    if ($stmt->execute()) {

      // Locate the page in Homepage after successfully registered.
      $message = "Book added successfully.";
      exit;
    }
  } catch (PDOException $e) {
    $message = "Opps Please try again.";
  }
}


define('ALLSTOCKS', "SELECT * FROM stock WHERE stock.created_by = $userId");
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

  <!-- Linked font awesome. -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <title>Add New book</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');


    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    .roboto-thin {
      font-family: "Roboto", sans-serif;
      font-weight: 100;
      font-style: normal;
    }

    .roboto-light {
      font-family: "Roboto", sans-serif;
      font-weight: 300;
      font-style: normal;
    }

    .roboto-regular {
      font-family: "Roboto", sans-serif;
      font-weight: 400;
      font-style: normal;
    }

    .roboto-medium {
      font-family: "Roboto", sans-serif;
      font-weight: 500;
      font-style: normal;
    }

    .roboto-bold {
      font-family: "Roboto", sans-serif;
      font-weight: 700;
      font-style: normal;
    }

    .roboto-black {
      font-family: "Roboto", sans-serif;
      font-weight: 900;
      font-style: normal;
    }

    .roboto-thin-italic {
      font-family: "Roboto", sans-serif;
      font-weight: 100;
      font-style: italic;
    }

    .roboto-light-italic {
      font-family: "Roboto", sans-serif;
      font-weight: 300;
      font-style: italic;
    }

    .roboto-regular-italic {
      font-family: "Roboto", sans-serif;
      font-weight: 400;
      font-style: italic;
    }

    .roboto-medium-italic {
      font-family: "Roboto", sans-serif;
      font-weight: 500;
      font-style: italic;
    }

    .roboto-bold-italic {
      font-family: "Roboto", sans-serif;
      font-weight: 700;
      font-style: italic;
    }

    .roboto-black-italic {
      font-family: "Roboto", sans-serif;
      font-weight: 900;
      font-style: italic;
    }

    .container {
      width: 1200px;
      margin: auto;
    }

    .d-flex {
      display: flex;
    }

    .flex-col {
      flex-direction: column;
    }

    .justify-center {
      justify-content: center;
    }

    .item.center {
      align-items: center;
    }

    .registerContent {
      height: 900px;
    }

    .innerContent {
      background-color: #ffff;
      padding: 40px 25px;
      border-radius: 10px;
      width: 400px;
      margin: auto;
    }

    .innerContent h3,
    .innerContent p {
      text-align: center;
      margin: 12px;
    }

    .innerContent h3 {
      font-size: 25px;
    }

    input {
      width: 100%;
      padding: 10px 15px;
      font-size: 20px;
      margin: 10px 0;
      border: none;
      outline: none;
      box-shadow: 0 3px 10px rgb(0 0 0 / 0.2);
      border-radius: 10px;
    }

    .formDiv {
      position: relative;
    }

    .formDiv i {
      position: absolute;
      right: 15px;
      top: 30%;
      visibility: hidden;
    }

    .formDiv.error .fa-circle-exclamation {
      color: rgb(206, 12, 12);
      visibility: visible;
    }

    .formDiv.success .fa-check {
      color: rgb(11, 168, 11);
      visibility: visible;
    }

    .formDiv.error input {
      /* color: rgb(206, 12, 12); */
      border: 1px solid rgb(206, 12, 12);
    }

    .formDiv.success input {
      border: 1px solid rgb(11, 168, 11);
    }

    .formDiv small {
      visibility: hidden;
    }

    .formDiv.error small {
      margin-left: 15px;
      color: rgb(206, 12, 12);
      visibility: visible;
    }

    .submitBtn {
      background-color: rgb(60, 60, 250);
      color: #ffff;
    }

    a {
      text-decoration: none;
      color: blue;
    }
  </style>
</head>

<body>
  <section class="wrapper">
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <a href="/logout" class="btn btn-danger">Logout</a>
      </div>
    </nav>
    <div class="container">
      <div class="registerContent d-flex justify-center item.center flex-col">
        <div class="innerContent ">
          <h3 class="roboto-medium">Add Book.</h3>
          <?php echo $message; ?>
          <hr>
          <form id="form" method="post" enctype="multipart/form-data">
            <!-- Input for last name. -->
            <div class="formDiv">
              <input class="roboto-light" type="text" id="name" name="name" placeholder="Stock Name" autocomplete="off">
              <i class="fa-solid fa-check"></i>
              <i class="fa-solid fa-circle-exclamation"></i>
              <small class="roboto-light">Error msg</small>
            </div>
            <!-- Input for last name. -->
            <div class="formDiv">
              <input class="roboto-light" type="text" id="price" name="price" placeholder="Stock Price" autocomplete="off">
              <i class="fa-solid fa-check"></i>
              <i class="fa-solid fa-circle-exclamation"></i>
              <small class="roboto-light">Error msg</small>
            </div>
            <!-- Input for last name. -->
            <div class="formDiv">
              <input class="roboto-light" type="date" id="date" name="date" placeholder="Added Date" autocomplete="off">
              <i class="fa-solid fa-check"></i>
              <i class="fa-solid fa-circle-exclamation"></i>
              <small class="roboto-light">Error msg</small>
            </div>
            <!-- Input field for submit button. -->
            <input type="submit" value="Submit" name="submit" class="submitBtn roboto-medium">
          </form>
        </div>
      </div>
    </div>
  </section>

  <div class="wrapper">
    <div class="container mt-5">
      <table class="table caption-top  table-hover" id="myTable">
        <h1>Stocks</h1>
        <a class="btn btn-primary my-2" href="/home">All Stockes</a>
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
              <td><?php if ($value['created_by'] == $userId) { ?> <a href="/remove-stock?id=<?php echo $value['ID']; ?>" class=" btn btn-danger">Remove</a><a href="/edit-stock?id=<?php echo $value['ID']; ?>" class="ms-3 btn btn-primary">Edit</a><?php } ?></td>
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
