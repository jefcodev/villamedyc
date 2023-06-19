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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="row">
                <div class="col-md-6">
                    <input class="form-control" title="Código" placeholder="Código SEP-123.." id="codigo" name="codigo" required />
                    <input class="form-control" title="Nombre" placeholder="Nombre" id="nombre" name="nombre" required />
                    <input class="form-control" title="Descripción" placeholder="Descripción" id="descripcion" name="descripcion"  />


                </div>
                <div class="col-md-6">
                    <input class="form-control" type="number" title="Precio Compra" placeholder="Precio Compra" id="precio_c" name="precio_c" required />
                    <input class="form-control" type="number" title="Precio Venta" placeholder="Precio Venta" id="precio_v" name="precio_v" required/>
                    <div class="float-right">
                        <input class="btn btn-primary" type="submit" value="Crear Producto">
                        <a class="btn btn-danger" href="lista_productos.php">Cancelar</a>
                    </div>

                </div>

            </div>
        </form> 
        
   <!-- 
	<h2>Create Product</h2>
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label for="codigo">Code:</label>
		<input type="text" id="codigo" name="codigo"><br><br>
        <label for="descripcion">Nombre:</label>
		<input type="text" id="nombre" name="nombre"><br><br>
 		<label for="descripcion">Description:</label>
		<input type="text" id="descripcion" name="descripcion"><br><br>
 		<label for="precio_compra">Purchase Price:</label>
		<input type="number" id="precio_c" name="precio_c"><br><br>
 		<label for="precio_venta">Sale Price:</label>
		<input type="number" id="precio_v" name="precio_v"><br><br>
 		<label for="stock">Stock:</label>
		<input type="number" id="stock" name="stock" value="0"><br><br>
 		<input type="submit" value="Create Product">
	</form> -->
   
 	<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		
		// Get the values submitted by the form
		$codigo = $_POST['codigo'];
        $nombre = $_POST['nombre'];
		$descripcion = $_POST['descripcion'];
		$precio_c = $_POST['precio_c'];
		$precio_v = $_POST['precio_v'];
		$stock = $_POST['stock'];

		// Prepare the SQL statement to insert a new product

		$sql = "INSERT INTO productos (codigo,nombre, descripcion, precio_c, precio_v, stock) VALUES ('$codigo','$nombre', '$descripcion', $precio_c, $precio_v, 0)";
		// Execute the SQL statement

		if ($mysqli->query($sql) === TRUE) {
            header("Location: lista_productos.php");
            echo '<div class="alert alert-success"> Producto creado  correctamente </div>';
		} else {
           echo '<div class="alert alert-danger"> Ocurrió un error al crear el producto </div>'. $mysqli->error;
		}
		// Close the database mysqliection
		$mysqli->close();

    
	}
	?>

    </section>
    <br>
    <?php
    include 'footer.php';
    ?>
    
</body>