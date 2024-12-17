<!DOCTYPE html>
<html lang="en">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <style>
            body {
                  font-family: Arial, sans-serif;
                  margin: 20px;
                  padding: 20px;
                  background-color: #f4f4f4;
            }

            table,
            td {
                  border: 1px solid black;
                  border-spacing: 0px;
            }
      </style>
</head>

<body>
      <h1>Login</h1>

      <!-- 
        user: admin
        pwd: admin
    -->

      <?php
      if (isset($_POST['login'])) {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $hashed_password = hash('sha256', $password);

            $conn = mysqli_connect('127.0.0.1', 'admin', '123', 'login_system');
            if (!$conn) {
                  die("Connection failed: " . mysqli_connect_error());
            }

            // Consulta SQL vulnerable a SQL injection
            $sql = "SELECT username FROM users WHERE username = '$username' AND password = '$hashed_password'";
            $resultat = mysqli_query($conn, $sql);

            mysqli_close($conn);
      }
      ?>

      <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div style="margin: 20px 0;">
                  <label for="username">Usuario:</label><br>
                  <input type="text" id="username" name="username" required>
            </div>

            <div style="margin: 20px 0;">
                  <label for="password">Contraseña:</label><br>
                  <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" name="login">Iniciar Sesión</button>
      </form>

      <p>
            <?php
            if (isset($resultat)) {
                  if ($resultat->num_rows > 0) {
                        $registre = $resultat->fetch_assoc();
                        if ($registre['username'] == $username) {
                              echo "Bienvenido $username";
                        } else {
                              echo "Usuario o contraseña incorrectos";
                        }
                  } else {
                        echo "Usuario o contraseña incorrectos";
                  }
            }
            ?>
      </p>


</body>

</html>