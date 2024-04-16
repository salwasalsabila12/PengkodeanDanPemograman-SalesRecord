<?php 



?>



<?php 

SESSION_START();



require "connection.php"; // Assuming connection.php establishes a connection

// Check if form is submitted and if 'submit' button is pressed
if (isset($_POST['submit'])) {

  // Validate user input (optional, can be done here)
  // ... (implement validation logic to ensure username, password meet your criteria)

  // Escape user input to prevent SQL injection (important!)
  $username = mysqli_real_escape_string($conn, trim($_POST['username']));
  $password = trim($_POST['password']);

  // Hash password using a strong algorithm (e.g., bcrypt)
  $hashed_password = password_hash($password, PASSWORD_BCRYPT);

  // Prepare an insert statement using parameter binding
  $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

  if ($stmt = $conn->prepare($sql)) {
    // Bind variables to the prepared statement
    $stmt->bind_param("ss", $username, $hashed_password);
    if ($stmt->execute()) {
      echo "Registration successful!";
    } else {
      echo "Registration failed. Please try again later.";
    }
    // Close the statement
    $stmt->close();
  } else {
    // Error preparing statement, handle error (e.g., log the error)
    echo "Something went wrong. Please try again later.";
  }

} // End if submitted

?>

<!DOCTYPE html>
<html>
<head>
	<title>Register</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center">
        <div class="card">
            <div class="card-header">
                <h3>Register</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="input-group form-group mb-2">
                        <input type="text" class="form-control" placeholder="username" name="username">
                    </div>
                    <div class="input-group form-group mb-2">
                        <input type="password" class="form-control" placeholder="password" name="password">
                    </div>
                    <div class="form-group mt-2">
                        <input type="submit" value="Register" class="btn btn-primary" name="submit">
                    </div>
                </form>
                <div>Already have account <a href="login.php">Login</a></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
</body>
</html>