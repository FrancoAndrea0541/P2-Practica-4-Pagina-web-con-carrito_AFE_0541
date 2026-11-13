<?php
include("conexion.php");

// Función auxiliar para obtener productos por categoría desde la BD
function obtenerProductosPorCategoria($conexion, $categoria) {
    $sql = "SELECT * FROM productos WHERE categoria = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $categoria);
    $stmt->execute();
    return $stmt->get_result();
}

$ropa = obtenerProductosPorCategoria($conexion, "Ropa");
$accesorios = obtenerProductosPorCategoria($conexion, "Accesorios y Belleza");
$peluches = obtenerProductosPorCategoria($conexion, "Peluches y figuras");
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Kuromi's Shop</title>
	<style>
		body {
			margin: 0;
			padding: 0;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			font-family: Arial, sans-serif;
		}

		header {
			background-color: #4a2e56;
			color: white;
			text-align: center;
			padding: 20px;
			width: 100%;
			box-sizing: border-box;
		}

		nav {
			background-color: #333;
			padding: 12px;
			text-align: center;
			width: 100%;
			box-sizing: border-box;
			position: sticky;
			top: 0;
			z-index: 100;
		}

		nav a {
			color: white;
			margin: 0 15px;
			text-decoration: none;
			font-weight: bold;
		}

		.btn-carrito-nav {
			background-color: #6a0dad;
			color: white;
			border: none;
			padding: 6px 12px;
			border-radius: 15px;
			cursor: pointer;
			font-weight: bold;
			margin-left: 15px;
		}

		.seccion-articulos {
			margin: auto;
			padding: 20px;
			text-align: center;
			width: 90%;
			max-width: 1000px;
		}

		.subseccion {
			margin-bottom: 40px;
		}

		.subseccion h3 {
			color: #4a2e56;
			border-bottom: 2px solid #4a2e56;
			padding-bottom: 8px;
			text-align: left;
		}

		.contenedor-articulos {
			display: flex;
			flex-wrap: wrap;
			justify-content: center;
			gap: 20px;
			margin-top: 20px;
		}

		.tarjeta-articulo {
			border: 1px solid #ccc;
			border-radius: 8px;
			padding: 15px;
			width: 200px;
			text-align: center;
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}

		.tarjeta-articulo img {
			width: 100%;
			height: 150px;
			object-fit: cover;
			border-radius: 4px;
		}

		.tarjeta-articulo h4 {
			font-size: 16px;
			margin: 10px 0 5px 0;
		}

		.tarjeta-articulo p {
			font-weight: bold;
			color: #4a2e56;
			margin: 5px 0;
		}

		.btn-agregar {
			background-color: #4a2e56;
			color: white;
			border: none;
			padding: 8px;
			border-radius: 5px;
			cursor: pointer;
			font-weight: bold;
			margin-top: 10px;
		}

		/* Panel del Carrito */
		.modal-carrito {
			display: none;
			position: fixed;
			top: 0;
			right: 0;
			width: 300px;
			height: 100%;
			background-color: #fff;
			box-shadow: -2px 0 5px rgba(0,0,0,0.3);
			z-index: 1000;
			padding: 20px;
			box-sizing: border-box;
			flex-direction: column;
		}

		.modal-carrito.activo {
			display: flex;
		}

		.encabezado-carrito {
			display: flex;
			justify-content: space-between;
			align-items: center;
			border-bottom: 2px solid #4a2e56;
		}

		.lista-carrito {
			flex: 1;
			overflow-y: auto;
			margin: 15px 0;
			padding: 0;
			list-style: none;
		}

		.item-carrito {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 10px;
			border-bottom: 1px solid #eee;
			padding-bottom: 5px;
			font-size: 14px;
		}

		.btn-eliminar {
			background-color: #ff4d4d;
			color: white;
			border: none;
			border-radius: 3px;
			padding: 2px 6px;
			cursor: pointer;
		}

		footer {
			background-color: #333;
			color: white;
			text-align: center;
			padding: 15px;
			width: 100%;
			box-sizing: border-box;
			margin-top: auto;
		}
	</style>
</head>

<body>

	<header>
		<h1>Kuromi's Shop</h1>
	</header>

	<nav>
		<a href="index.html">Inicio</a>
		<a href="iniciosesion.html">Inicio de Sesión</a>
		<a href="registro.html">Registro</a>
		<button class="btn-carrito-nav" onclick="toggleCarrito()">🛒 Carrito (<span id="contador-carrito">0</span>)</button>
	</nav>

	<div class="seccion-articulos">
		<h2>Nuestros Artículos</h2>

		<!-- Subsección Ropa -->
		<div class="subseccion">
			<h3>Ropa</h3>
			<div class="contenedor-articulos">
				<?php while($prod = $ropa->fetch_assoc()): ?>
					<div class="tarjeta-articulo">
						<img src="<?php echo htmlspecialchars($prod['imagen']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
						<h4><?php echo htmlspecialchars($prod['nombre']); ?></h4>
						<p>$<?php echo number_format($prod['precio'], 2); ?></p>
						<button class="btn-agregar" onclick="agregarAlCarrito('<?php echo htmlspecialchars(addslashes($prod['nombre'])); ?>', <?php echo $prod['precio']; ?>)">Agregar al carrito</button>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

		<!-- Subsección Accesorios y Belleza -->
		<div class="subseccion">
			<h3>Accesorios y Belleza</h3>
			<div class="contenedor-articulos">
				<?php while($prod = $accesorios->fetch_assoc()): ?>
					<div class="tarjeta-articulo">
						<img src="<?php echo htmlspecialchars($prod['imagen']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
						<h4><?php echo htmlspecialchars($prod['nombre']); ?></h4>
						<p>$<?php echo number_format($prod['precio'], 2); ?></p>
						<button class="btn-agregar" onclick="agregarAlCarrito('<?php echo htmlspecialchars(addslashes($prod['nombre'])); ?>', <?php echo $prod['precio']; ?>)">Agregar al carrito</button>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

		<!-- Subsección Peluches y Figuras -->
		<div class="subseccion">
			<h3>Peluches y figuras</h3>
			<div class="contenedor-articulos">
				<?php while($prod = $peluches->fetch_assoc()): ?>
					<div class="tarjeta-articulo">
						<img src="<?php echo htmlspecialchars($prod['imagen']); ?>" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
						<h4><?php echo htmlspecialchars($prod['nombre']); ?></h4>
						<p>$<?php echo number_format($prod['precio'], 2); ?></p>
						<button class="btn-agregar" onclick="agregarAlCarrito('<?php echo htmlspecialchars(addslashes($prod['nombre'])); ?>', <?php echo $prod['precio']; ?>)">Agregar al carrito</button>
					</div>
				<?php endwhile; ?>
			</div>
		</div>

	</div>

	<!-- Modal Flotante del Carrito -->
	<div id="modal-carrito" class="modal-carrito">
		<div class="encabezado-carrito">
			<h3>Carrito</h3>
			<button class="btn-eliminar" onclick="toggleCarrito()">✕</button>
		</div>
		<ul id="lista-carrito" class="lista-carrito"></ul>
		<div style="font-weight: bold; text-align: right;">
			Total: $<span id="total-precio">0.00</span>
		</div>
	</div>

	<footer>
		<p>| Andrea Franco Enriquez | 0541 | 5°I |</p>
	</footer>

	<script>
		let carrito = [];

		function toggleCarrito() {
			document.getElementById('modal-carrito').classList.toggle('activo');
		}

		function agregarAlCarrito(nombre, precio) {
			const producto = carrito.find(p => p.nombre === nombre);
			if (producto) {
				producto.cantidad++;
			} else {
				carrito.push({ nombre, precio, cantidad: 1 });
			}
			actualizarCarrito();
		}

		function eliminarDelCarrito(nombre) {
			carrito = carrito.filter(p => p.nombre !== nombre);
			actualizarCarrito();
		}

		function actualizarCarrito() {
			const lista = document.getElementById('lista-carrito');
			const totalElem = document.getElementById('total-precio');
			const contadorElem = document.getElementById('contador-carrito');

			lista.innerHTML = '';
			let total = 0;
			let cantidadTotal = 0;

			carrito.forEach(item => {
				const subtotal = item.precio * item.cantidad;
				total += subtotal;
				cantidadTotal += item.cantidad;

				const li = document.createElement('li');
				li.className = 'item-carrito';
				li.innerHTML = `
					<span>${item.nombre} x${item.cantidad}</span>
					<span>$${subtotal.toFixed(2)} <button class="btn-eliminar" onclick="eliminarDelCarrito('${item.nombre}')">x</button></span>
				`;
				lista.appendChild(li);
			});

			totalElem.textContent = total.toFixed(2);
			contadorElem.textContent = cantidadTotal;
		}
	</script>

</body>
</html>