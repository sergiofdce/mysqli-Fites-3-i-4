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

<?php
// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Conexión a la base de datos
	$conn = mysqli_connect('127.0.0.1', 'admin', '123');
	mysqli_select_db($conn, 'world');

	// Validación y saneamiento de datos
	$newCountry = mysqli_real_escape_string($conn, $_POST['newCountry']);
	$newCountryCode = mysqli_real_escape_string($conn, $_POST['newCountryCode']);
	$newLanguage = mysqli_real_escape_string($conn, $_POST['newLanguage']);
	$newisOfficial = mysqli_real_escape_string($conn, $_POST['newisOfficial']);
	$newPercentage = floatval($_POST['newPercentage']);

	// Verificar si el país ya existe
	$countryExistsQuery = "SELECT COUNT(*) AS count FROM country WHERE Code = '$newCountryCode'";
	$result = mysqli_query($conn, $countryExistsQuery);
	$row = mysqli_fetch_assoc($result);

	if ($row['count'] == 0) {
		// Insertar país si no existe
		$insertCountryQuery = "INSERT INTO country (Code, Name) VALUES ('$newCountryCode', '$newCountry')";
		if (!mysqli_query($conn, $insertCountryQuery)) {
			echo "<p style='color: red;'>Error al insertar país: " . mysqli_error($conn) . "</p>";
		}
	}

	// Insertar idioma
	$insertLanguageQuery = "INSERT INTO countrylanguage (CountryCode, Language, IsOfficial, Percentage) 
                            VALUES ('$newCountryCode', '$newLanguage', '$newisOfficial', $newPercentage)";
	if (mysqli_query($conn, $insertLanguageQuery)) {
		echo "<p style='color: green;'>Language added successfully!</p>";
	} else {
		echo "<p style='color: red;'>Error al insertar idioma: " . mysqli_error($conn) . "</p>";
	}

	// Cierre de la conexión
	mysqli_close($conn);
}
?>



<body>

	<?php
	$conn = mysqli_connect('127.0.0.1', 'admin', '123');
	mysqli_select_db($conn, 'world');

	$consulta = "SELECT * FROM countrylanguage order by countrycode DESC;";

	$resultat = mysqli_query($conn, $consulta);
	if (!$resultat) {
		$message  = 'Consulta invàlida: ' . mysqli_error($conn) . "\n";
		$message .= 'Consulta realitzada: ' . $consulta;
		die($message);
	}
	?>

	<h1>Add a new language</h1>

	<form method="POST">
		<?php
		// Conexión a la base de datos
		$conn = mysqli_connect('127.0.0.1', 'admin', '123');
		mysqli_select_db($conn, 'world');

		// Consulta a la base de datos
		$query = "SELECT Code, Name FROM country";
		$countries = mysqli_query($conn, $query);

		// Generación del dropdown
		echo '<label for="newCountry">Country:</label>';
		echo '<select id="newCountry" name="newCountry">';
		while ($country = mysqli_fetch_assoc($countries)) {
			echo "<option value='" . $country['Code'] . "'>" . $country['Name'] . "</option>";
		}
		echo '</select>';

		// Cierre de la conexión
		mysqli_close($conn);
		?>

		<br>
		<label for="newCountryCode">CountryCode:</label>
		<input type="text" id="newCountryCode" name="newCountryCode">
		<br>
		<label for="newLanguage">Language:</label>
		<input type="text" id="newLanguage" name="newLanguage">
		<br>
		<div class="radio-container">
			<input type="radio" id="newisOfficialTrue" name="newisOfficial" value="T">
			<label for="newisOfficialTrue">Yes</label>
			<input type="radio" id="newisOfficialFalse" name="newisOfficial" value="F">
			<label for="newisOfficialFalse">No</label>
		</div>
		<br>
		<label for="newPercentage">Percentage:</label>
		<input type="text" id="newPercentage" name="newPercentage">
		<br>
		<input type="submit" value="Submit">
		<br>
	</form>



	<table>
		<thead>
			<td colspan="4" align="center" bgcolor="cyan">Listado de idiomas</td>
		</thead>
		<?php
		while ($registre = mysqli_fetch_assoc($resultat)) {
			echo "\t<tr>\n";
			echo "\t\t<td>" . $registre["CountryCode"] . "</td>\n";
			echo "\t\t<td>" . $registre['Language'] . "</td>\n";
			echo "\t\t<td>" . $registre["IsOfficial"] . "</td>\n";
			echo "\t\t<td>" . $registre['Percentage'] . "</td>\n";
			echo "\t</tr>\n";
		}
		?>
	</table>
</body>

</html>