<?php
include_once "C:\laragon\www\GameMonth_Proyect\modelos\conexion.php";
$db = Conexion::conectar();

$mensaje = ''; // Para mostrar confirmación

// --- ACTUALIZAR ESTADO SI SE ENVÍA FORMULARIO ---
if (isset($_POST['cambiar_id'], $_POST['cambiar_estado'])) {
    $estado = $_POST['cambiar_estado'];
    if ($estado !== 'disponible' && $estado !== 'alquilado') {
        $estado = 'disponible';
    }
    $stmt = $db->prepare("UPDATE videojuegos SET estado = :estado WHERE id = :id");
    $stmt->bindValue(':estado', $estado);
    $stmt->bindValue(':id', $_POST['cambiar_id']);
    $stmt->execute();
    $mensaje = "Estado actualizado correctamente.";
}

// --- CAPTURA DE FILTROS ---
$titulo = $_GET['titulo'] ?? '';
$calificacion = $_GET['calificacion'] ?? '';
$genero = $_GET['genero'] ?? '';
$estado_filtro = $_GET['estado'] ?? '';
$fecha = $_GET['fecha'] ?? '';

// --- CONSULTA BASE ---
$query = "SELECT * FROM videojuegos WHERE 1=1";
$params = [];

if ($titulo !== '') {
    $query .= " AND titulo LIKE :titulo";
    $params[':titulo'] = "%$titulo%";
}
if ($calificacion !== '') {
    $query .= " AND calificacion = :calificacion";
    $params[':calificacion'] = $calificacion;
}
if ($genero !== '') {
    $query .= " AND genero = :genero";
    $params[':genero'] = $genero;
}
if ($estado_filtro !== '') {
    $query .= " AND estado = :estado";
    $params[':estado'] = $estado_filtro;
}
if ($fecha !== '') {
    $query .= " AND DATE(fecha_agregado) = :fecha";
    $params[':fecha'] = $fecha;
}

$stmt = $db->prepare($query);
foreach ($params as $key => $val) {
    $stmt->bindValue($key, $val);
}
$stmt->execute();
$juegos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Administrar Juegos</title>
<style>
body { font-family: Arial; text-align: center; background: #192443ff; color: white; }
nav { background-color: #e7a923ff; padding: 10px; }
a { color: white; text-decoration: none; margin: 10px; font-size: 18px; padding: 8px 15px; border-radius: 5px; }
a:hover { background-color: #fe952dda; }
h2 { color: #bb1818ff; }
table { width: 90%; margin: auto; border-collapse: collapse; background: white; color: black; }
th, td { border: 1px solid #ccc; padding: 10px; }
th { background: #e7a923ff; color: black; }
.perfil { position: absolute; top: 15px; right: 20px; display: flex; align-items: center; gap: 10px; }
.perfil-img { width: 45px; height: 45px; object-fit: cover; }
.perfil-nombre { color: white; font-weight: bold; font-size: 16px; }
.filtros { margin: 20px auto; width: 90%; background: #203060; padding: 20px; border-radius: 10px; }
.filtros input, .filtros select { padding: 8px; margin: 5px; border-radius: 5px; border: none; }
.btn { background: #e7a923ff; padding: 8px 16px; border: none; cursor: pointer; border-radius: 5px; font-weight: bold; }
.btn:hover { background: #fe952dda; }
.mensaje { color: #00ff00; font-weight: bold; margin: 10px; }
</style>
</head>
<body>

<div class="perfil">
    <img src="../img/fotoadmin.png" class="perfil-img">
    <span class="perfil-nombre">Admin</span>
</div>

<h2>Administrar Juegos</h2>

<nav>
    <a href="adminpage.php">Pantalla Administrador</a>
    <a href="reporteclientes.php">Reporte Clientes</a>
    <a href="registrarjuegoadmin.php">Administrar Juegos</a>
</nav>

<?php if ($mensaje) echo "<div class='mensaje'>$mensaje</div>"; ?>

<!-- FILTROS -->
<form method="GET" class="filtros">
    <input type="text" name="titulo" placeholder="Buscar por nombre" value="<?= $titulo ?>">
    <select name="calificacion">
        <option value="">Calificación</option>
        <?php for ($i = 1; $i <= 5; $i++): ?>
            <option value="<?= $i ?>" <?= ((string)$calificacion === (string)$i ? 'selected' : '') ?>><?= $i ?> estrellas</option>
        <?php endfor; ?>
    </select>
    <select name="genero">
        <option value="">Género</option>
        <?php
        $generos = ["Supervivencia / Creatividad","Acción / Mundo Abierto","Puzzle","MOBA",
                    "Acción / Aventura","RPG / Acción","Aventura / Mundo Abierto","Plataformas",
                    "Battle Royale","Shooter / Héroes","Shooter / Sci-Fi","Shooter / Acción",
                    "RPG / Sci-Fi","Party / Social","Simulación / RPG","Roguelike / Acción",
                    "RPG","Sigilo / Acción","Survival Horror","Carreras"];
        foreach ($generos as $g) {
            $sel = ($g == $genero) ? 'selected' : '';
            echo "<option value=\"$g\" $sel>$g</option>";
        }
        ?>
    </select>
    <select name="estado">
        <option value="">Estado</option>
        <option value="disponible" <?= $estado_filtro == 'disponible' ? 'selected' : '' ?>>Disponible</option>
        <option value="alquilado" <?= $estado_filtro == 'alquilado' ? 'selected' : '' ?>>Alquilado</option>
    </select>
    <input type="date" name="fecha" value="<?= $fecha ?>">
    <button type="submit" class="btn">Filtrar</button>
</form>

<!-- TABLA DE RESULTADOS -->
<table>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Género</th>
    <th>Calificación</th>
    <th>Estado</th>
    <th>Fecha</th>
    <th>Acción</th>
</tr>

<?php if (count($juegos) > 0): ?>
    <?php foreach ($juegos as $j): ?>
        <tr>
            <td><?= $j['id'] ?></td>
            <td><?= $j['titulo'] ?></td>
            <td><?= $j['genero'] ?></td>
            <td><?= $j['calificacion'] ?> ⭐</td>
            <td><?= $j['estado'] ?></td>
            <td><?= $j['fecha_agregado'] ?></td>
            <td>
                <form method="POST">
                    <input type="hidden" name="cambiar_id" value="<?= $j['id'] ?>">
                    <select name="cambiar_estado">
                        <option value="disponible" <?= $j['estado'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                        <option value="alquilado" <?= $j['estado'] == 'desabilitado' ? 'selected' : '' ?>>Alquilado</option>
                    </select>
                    <button type="submit" class="btn">Guardar</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="7">No se encontraron resultados.</td>
    </tr>
<?php endif; ?>
</table>

</body>
</html>