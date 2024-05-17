<?php
require('./vendor/autoload.php');
require('./ConnectDB.php');

// Parse the current URL
$url = parse_url($_SERVER['REQUEST_URI']);
$id = [];
if (isset($url['query'])) {
  $id = explode('=', $url['query']);
}

// Start session
session_start();
$userId = $_SESSION['user_id'];
$message = "";
$db = new ConnectDB();
$conn = $db->connectDB();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  // Get ID from query parameters.
  $id = $id[1];

  $name = $_POST['name'];
  $price = $_POST['price'];

  try {

    // Update the data in the database
    $query = "UPDATE stock SET `name`=?, `price`=? WHERE `ID`=? AND created_by=?";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $name, PDO::PARAM_STR);
    $stmt->bindParam(2, $price, PDO::PARAM_STR);
    $stmt->bindParam(3, $id, PDO::PARAM_INT);
    $stmt->bindParam(4, $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
      $message = "Stock Updated";
    }
    else {
      $message = "Failed to update stock";
    }
  }
  catch (PDOException $e) {
    $message = "Oops, an error occurred: " . $e->getMessage();
  }
}

// Fetch data for the form
$id = $id[1];

define('ALLSTOCKS', "SELECT * FROM stock WHERE stock.created_by = $userId AND ID = $id");
$stmt = $conn->prepare(ALLSTOCKS);
$stmt->execute();

// Fetch the result
$result = $stmt->fetch();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Stock</title>
  <!-- Add your stylesheets and scripts here -->
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

    /*
    .registerContent {
      height: 900px;
    } */

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

    .form-label,
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
      width: 100%;
      padding: 10px;
      border-radius: 10px;
      border: none;
      outline: none;
      font-size: 20px;
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
    <div class="container">
      <div class="registerContent d-flex justify-center item.center flex-col">
        <div class="innerContent ">
          <h3 class="roboto-medium">Update Stocks.</h3>
          <?php echo $message; ?>
          <hr>
          <form id="form" method="post" enctype="multipart/form-data">

            <!-- Input for last name. -->
            <div class="formDiv">
              <input class="roboto-light" type="text" id="name" name="name" placeholder="Stock Name" autocomplete="off" value="<?php echo $result['name'] ?>">
              <i class="fa-solid fa-check"></i>
              <i class="fa-solid fa-circle-exclamation"></i>
              <small class="roboto-light">Error msg</small>
            </div>

            <!-- Input for last name. -->
            <div class="formDiv">
              <input class="roboto-light" type="text" id="price" name="price" placeholder="Stock Price" autocomplete="off" value="<?php echo $result['price'] ?>">
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

</body>

</html>
