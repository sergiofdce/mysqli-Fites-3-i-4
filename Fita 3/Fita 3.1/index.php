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
 		table,td {
 			border: 1px solid black;
 			border-spacing: 0px;
 		}
 	</style>
</head>
<body>
    <form method="post" action="">
        <label for="min">Población mínima:</label>
        <input type="number" id="min" name="min" required>
        <label for="max">Población máxima:</label>
        <input type="number" id="max" name="max" required>
        <button type="submit">Filtrar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $min = $_POST['min'];
        $max = $_POST['max'];

        // Conexión a MySQL
		$conn = mysqli_connect('127.0.0.1', 'admin', '123');

        // Seleccionar base de datos
        mysqli_select_db($conn, 'world');

        // Consulta
        $consulta = "SELECT * FROM city WHERE population BETWEEN $min AND $max ORDER BY population DESC;";
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
            echo '<h1>Resultados filtrados</h1>';
            echo '<table>';
            echo '<thead><td colspan="4" align="center" bgcolor="cyan">Llistat de ciutats</td></thead>';

            // Mostrar los resultados
            while ($registre = mysqli_fetch_assoc($resultat)) {
                echo "<tr>";
                echo "<td>".$registre["Name"]."</td>";
                echo "<td>".$registre['CountryCode']."</td>";
                echo "<td>".$registre["District"]."</td>";
                echo "<td>".$registre['Population']."</td>";
                echo "</tr>";
            }
            echo '</table>';
        }
    }
    ?>
</body>
</html>