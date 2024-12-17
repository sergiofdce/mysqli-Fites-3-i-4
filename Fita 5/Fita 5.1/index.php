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
 		table,td {
 			border: 1px solid black;
 			border-spacing: 0px;
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
                    $stmt = $pdo->prepare("SELECT c.Name as CityName, cn.Name as CountryName
                                           FROM city c 
                                           JOIN country cn ON c.CountryCode = cn.Code 
                                           WHERE cn.Name LIKE :consulta");
                    $stmt->execute(['consulta' => '%' . $consulta . '%']);

                    // Verificar si hay resultados
                    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    if (!$results) {
                        echo 'No se encontraron resultados para los valores especificados.';
                    } else {
                        echo '<h1>Cities from ' . htmlspecialchars($consulta) . '</h1>';
                        echo '<ul>';
                        foreach ($results as $fila) {
                            echo '<li>' . htmlspecialchars($fila['CountryName']) . ' > ' .  htmlspecialchars($fila['CityName']) . '</li>';
                        }
                        echo '</ul>';
                    }

                } catch (PDOException $e) {
                    echo 'Error en la conexión o consulta: ' . $e->getMessage();
                }
            }
        }
    ?>

</body>
</html>