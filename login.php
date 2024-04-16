<?php 

SESSION_START();
require 'connection.php';

if(isset($_SESSION['auth']))
{
    if($_SESSION['auth']==1)
    {
        header("location:index.php");
    }
}


if (isset($_POST['submit'])) {

    // Define variables and escape user input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = trim($_POST['password']);
  
    // Prepare a select statement using parameter binding for secure authentication
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
  
    if ($stmt = $conn->prepare($sql)) {
      // Bind variables to the prepared statement
      $stmt->bind_param("s", $username);
  
      // Execute the statement
      $stmt->execute();
  
      // Store the result (important for password verification)
      $stmt->store_result();
  
      // Check if a user with the provided username exists
      if ($stmt->num_rows == 1) {
        // Bind result variables to fetch user data
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();
  
        // Verify password using password_verify (assumes hashed password in database)
        if (password_verify($password, $hashed_password)) {
          // Login successful, set session variables and redirect
          $_SESSION['auth'] = true;
          $_SESSION['id'] = $id;
          $_SESSION['username'] = $username;
          header("location: index.php");
          exit; // Terminate script after successful login
        } else {
          // Invalid password
          echo "Invalid username or password.";
        }
      } else {
        // Username not found
        echo "Invalid username or password.";
      }
  
      // Close the statement
      $stmt->close();
    } else {
      // Error preparing statement
      echo "Something went wrong. Please try again later.";
    }
  }
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center">
        <div class="card">
            <div class="card-header">
                <h3>Sign In</h3>
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
                        <input type="submit" value="Login" class="btn btn-primary" name="submit">
                    </div>
                </form>
                <div>Already have account <a href="register.php">Register</a></div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>

</body>
</html>