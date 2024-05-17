<?php

require("./vendor/autoload.php");
require("./ConnectDB.php");


/**
 * @var array errors.
 *   Stores the error for already used email id.
 */
$errors['nullData'] = $errors['alreadyEmail'] = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $email = $_POST['email'];
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];

  // Converting the password in hash.
  $password_hash = password_hash($password, PASSWORD_DEFAULT);
  // echo $password_hash;

  try {
    $db = new ConnectDB();
    $conn = $db->connectDB();

    // Query for inser the data in the database.
    $query = "INSERT INTO user (`email`, `password`) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->bindParam(2, $password, PDO::PARAM_STR);

    // set parameters and execute.
    if ($stmt->execute()) {

      echo "Registration successs";
      // Locate the page in Homepage after successfully registered.
      header('Location: /login');
      exit;
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
    // Check for given email is already exist or not.
    if ($e->getCode() === '23000') {

      $errors['alreadyEmail'] = "This email is already used.";
    }
  }
}
// PHP ends here.

?>

<!-- HTML starts here. -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Linked custom CSS. -->
  <link rel="stylesheet" href="style.css">
  <!-- Linked font awesome. -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Document</title>
</head>

<body>
  <section class="wrapper">
    <div class="container">
      <div class="registerContent d-flex justify-center item.center flex-col">
        <div class="innerContent ">
          <h3 class="roboto-bold">Create account here</h3>
          <!-- Printing the Error msg. -->
          <em style="color: red;">
          <?php echo $errors['alreadyEmail'];
                echo $errors['nullData']; ?>
          </em>
          <hr>
          <form action="Registration.php" method="post" id="form" onsubmit="return validateRegisterForm()">
            <!-- Input field for email section. -->
            <div class="formDiv">
              <input class="roboto-light" type="text" id="email" name="email" placeholder="Email">
              <i class="fa-solid fa-check"></i>
              <i class="fa-solid fa-circle-exclamation"></i>
              <small class="roboto-light"></small>
              <span class="roboto-light" style="display:inline-block; color: red;"><?php echo ""; ?></span>
            </div>
            <!-- Input field for pasword section. -->
            <div class="formDiv">
              <input class="roboto-light" type="password" id="password" name="password" placeholder="Password">
              <i class="fa-solid fa-check"></i>
              <i class="fa-solid fa-circle-exclamation"></i>
              <small class="roboto-light"></small>
              <span class="roboto-light" style="display:inline-block; color: red;"><?php echo "" ?></span>
            </div>
            <!-- Input field for confirm password section. -->
            <div class="formDiv">
              <input class="roboto-light" type="password" id="cpassword" name="cpassword" placeholder="Confirm password">
              <i class="fa-solid fa-check"></i>
              <i class="fa-solid fa-circle-exclamation"></i>
              <small class="roboto-light"></small>
              <span class="roboto-light" style="display:inline-block; color: red;"><?php echo "" ?></span>
            </div>
            <input type="submit" name="submit" class="submitBtn roboto-medium">
          </form>
          <p class="roboto-light">Already have account?<a href="/login">Login here</a></p>
        </div>
      </div>
    </div>
  </section>
</body>

</html>
