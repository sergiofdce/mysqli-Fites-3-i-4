<!DOCTYPE html>
<html>
<head>
    <title>Document</title>
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
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
 	</style>
</head>
<body>

    <!-- Formulario -->
    <form method="post" action="">
        <label for="pais">Type a Country:</label>
        <input type="text" name="pais" id="pais">
        <button type="submit">Search</button>
    </form>

    <p class="mostrarPaises"></p>

    <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['pais']) && !empty($_POST['pais'])) {
                try {
                    // Conexión a MySQL
                    $pdo = new PDO('mysql:host=127.0.0.1;dbname=world', 'admin', '123');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Consulta segura con sentencias preparadas
                    $consulta = $_POST['pais'];
                    $stmt = $pdo->prepare("SELECT cl.Language as LanguageName, cn.Name as CountryName, cl.IsOfficial as Official, cl.Percentage as PercentageValue
                                           FROM countrylanguage cl
                                           JOIN country cn ON cl.CountryCode = cn.Code 
                                           WHERE cn.Name LIKE :consulta");
                    $stmt->execute(['consulta' => '%' . $consulta . '%']);

                    // Verificar si hay resultados
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!$results) {
                        echo 'No se encontraron resultados para los valores especificados.';
                    } else {
                        echo '<h1>Languages from country: ' . htmlspecialchars($consulta) . '</h1>';
                        echo '<table>';
                        echo '<tr><th>Country</th><th>Language</th><th>Official</th><th>Percentage</th></tr>';
                        foreach ($results as $fila) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($fila['CountryName']) . '</td>' .
                            '<td>' . htmlspecialchars($fila['LanguageName']) . '</td>' . 
                            '<td>' . htmlspecialchars($fila['Official']) . '</td>' . 
                            '<td>' . htmlspecialchars($fila['PercentageValue']) . '%</td>' . 
                            '</tr>';
                        }
                        echo '</table>';
                    }

                } catch (PDOException $e) {
                    echo 'Error en la conexión o consulta: ' . $e->getMessage();
                }
            }
        }
    ?>

</body>
</html>