<?php
include 'header.php';
include '../conection/conection.php';
$class = '';

?>

<head>
	<link rel="stylesheet" href="../css/jquery.datetimepicker.css">
</head>

<body>
	<section class="cuerpo">
		<h1>Crear producto</h1><br>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			<div class="row">
				<div class="col-md-6">
					<input class="form-control" title="Código" placeholder="Código SEP-123.." id="codigo" name="codigo" required />
					<input class="form-control" title="Nombre" placeholder="Nombre" id="nombre" name="nombre" required />
					<input class="form-control" title="Descripción" placeholder="Descripción" id="descripcion" name="descripcion" required />


				</div>
				<div class="col-md-6">
					<input type="checkbox" id="check_valor" name="check_valor" value="1">
					<label for="check_valor">Precio Venta</label>
					<input class="form-control" type="number" title="Precio Venta" placeholder="Precio Venta" id="precio_v" name="precio_v" required disabled />
					<div class="float-right">
						<input class="btn btn-primary" type="submit" value="Crear Producto">
						<a class="btn btn-danger" href="lista_productos.php">Cancelar</a>
					</div>
				</div>

			</div>
		</form>

	

	<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the values submitted by the form
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    /* $precio_c = $_POST['precio_c']; */
    $stock = $_POST['stock'];
    $valor_checkbox = isset($_POST["check_valor"]) ? true : false;
    $precio_v = $valor_checkbox ? $_POST["precio_v"] : 0;

    // Prepare the SQL statement using a prepared statement
    $sql = "INSERT INTO productos (codigo, nombre, descripcion, /* precio_c, */ precio_v, stock, tipo) VALUES (?, ?, ?, /* ?, */ ?, 0, ?)";
    
    // Create a prepared statement
    $stmt = $mysqli->prepare($sql);

    // Bind the parameters to the prepared statement
    $stmt->bind_param("ssssi", $codigo, $nombre, $descripcion, $precio_v, $valor_checkbox);

    // Execute the prepared statement
    if ($stmt->execute()) {
        header("Location: lista_productos.php");
        echo '<div class="alert alert-success">Producto creado correctamente</div>';
    } else {
        echo '<div class="alert alert-danger">Ocurrió un error al crear el producto</div>' . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
    // Close the database connection
    $mysqli->close();
}
?>


	</section>
	<br>
	<?php
	include 'footer.php';
	?>


<script>
    // Obtener el elemento del checkbox y el campo "Precio Venta"
    const checkbox = document.getElementById("check_valor");
    const precioVentaInput = document.getElementById("precio_v");

    // Agregar un evento para escuchar los cambios en el checkbox
    checkbox.addEventListener("change", function() {
        // Habilitar o deshabilitar el campo "Precio Venta" según el estado del checkbox
        precioVentaInput.disabled = !this.checked;
    });
</script>
</body>