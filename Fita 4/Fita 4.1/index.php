<!DOCTYPE html>
<html>
<head>
    <title>Menu desplegable</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
        button {
            margin-top: 10px;
        }
        p {
            margin: 10px 0;
        }
 		table,td {
 			border: 1px solid black;
 			border-spacing: 0px;
 		}
 	</style>
</head>
<body>
    <form method="post" action="">
    <label for="continent">Select Continent:</label>
    <select name="continent" id="continent">
    <?php
        $conn = mysqli_connect('127.0.0.1', 'admin', '123');
        mysqli_select_db($conn, 'world');
        $query = "SELECT DISTINCT Continent FROM country";
        $result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['Continent'] . '">' . $row['Continent'] . '</option>';
        }
        mysqli_close($conn);
    ?>
    </select>
    <button type="submit">Search</button>
    </form>

    <p class="mostrarPaises">

    </p>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Conexión a MySQL
		$conn = mysqli_connect('127.0.0.1', 'admin', '123');

        // Seleccionar base de datos
        mysqli_select_db($conn, 'world');

        // Consulta
        $continente = $_POST['continent'];

        $consulta = "SELECT DISTINCT c.Name FROM country c
        WHERE c.Continent = '$continente' ;";

        $resultat = mysqli_query($conn, $consulta);

        // Comprobar si hay resultado
        if (!$resultat) {
            $message  = 'Consulta inválida: ' . mysqli_error($conn) . "\n";
            $message .= 'Consulta realizada: ' . $consulta;
            die($message);
        }

        if (mysqli_num_rows($resultat) == 0) {
            echo 'No se encontraron resultados para los valores especificados.';
        } else {
            echo '<h1>Countries from ' . $continente . '</h1>';
            echo '<ul>';
            while ($fila = mysqli_fetch_assoc($resultat)) {
                echo '<li>' . $fila['Name'] . '</li>';
            }
            echo '</ul>';
        }
    }
    ?>
</body>
</html>