<!DOCTYPE html>
<html>
<head>
    <title>Filtrar Ciudades</title>
    <style>
 		body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
            background-color: #f4f4f4;
        }
 		table, tr {
 			border: 1px solid black;
 			border-spacing: 0px;
 		}
 	</style>
</head>
<body>
    <form method="post" action="">
        <label for="language">Language:</label>
        <input type="text" name="language" required>
        <button type="submit">Filtrar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Conexión a MySQL
		$conn = mysqli_connect('127.0.0.1', 'admin', '123');

        // Seleccionar base de datos
        mysqli_select_db($conn, 'world');

        // Consulta
        $language = $_POST['language'];
        $consulta = "SELECT DISTINCT Language, IsOfficial from countrylanguage where language like '%$language%';";
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
            echo '<h1>Results</h1>';
            echo '<table>';
            echo '<thead><tr><th colspan="2" style="background-color: cyan; text-align: center;">Languages</th></tr></thead>';
            echo '<tbody>';

            // Mostrar los resultados
            while ($registre = mysqli_fetch_assoc($resultat)) {
                echo "<tr>";
                echo "<td>".$registre["Language"]."</td>";
                if ($registre["IsOfficial"] == "T") {
                    echo "<td> [OFICIAL] </td>";
                }
                echo "</tr>";
            }

            echo '</tbody>';
            echo '</table>';
        }
    }
    ?>
</body>
</html>