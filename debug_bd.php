<?php
/**
 * Archivo de debug para verificar BD y flujo de pago
 * http://localhost/GameMonth_Proyect/debug_bd.php
 */

require_once 'modelos/conexion.php';

try {
    $db = Conexion::conectar();
    
    echo "<h1>DIAGNÓSTICO DE BD - GameMonth_Proyect</h1>";
    
    // 1. Usuarios
    echo "<h2>1. Usuarios registrados</h2>";
    $stmt = $db->query("SELECT id, usuario, email, fecha_registro FROM usuarios ORDER BY id DESC LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($users);
    echo "</pre>";
    echo "Total: " . count($users) . " usuarios<br>";
    
    // 2. Suscripciones
    echo "<h2>2. Suscripciones registradas</h2>";
    $stmt = $db->query("SELECT id_suscripcion, id_usuario, estado, monto, fecha_inicio, fecha_fin FROM suscripciones ORDER BY id_suscripcion DESC LIMIT 5");
    $suscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($suscripciones);
    echo "</pre>";
    echo "Total: " . count($suscripciones) . " suscripciones<br>";
    
    // 3. Pagos
    echo "<h2>3. Pagos registrados</h2>";
    $stmt = $db->query("SELECT id_pago, id_usuario, id_suscripcion, monto, fecha, transaction_id FROM pagos ORDER BY id_pago DESC LIMIT 5");
    $pagos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<pre>";
    print_r($pagos);
    echo "</pre>";
    echo "Total: " . count($pagos) . " pagos<br>";
    
    // 4. Columnas de cada tabla
    echo "<h2>4. Estructura de tablas</h2>";
    $tables = ['usuarios', 'suscripciones', 'pagos'];
    foreach ($tables as $table) {
        echo "<h3>Tabla '$table':</h3>";
        $stmt = $db->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
        foreach ($columns as $col) {
            echo "<tr>";
            echo "<td>" . $col['Field'] . "</td>";
            echo "<td>" . $col['Type'] . "</td>";
            echo "<td>" . $col['Null'] . "</td>";
            echo "<td>" . $col['Key'] . "</td>";
            echo "<td>" . ($col['Default'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    }
    
    // 5. Prueba de INSERT
    echo "<h2>5. Prueba manual de INSERT</h2>";
    if (isset($_GET['test_insert'])) {
        try {
            // Obtener último usuario
            $stmt = $db->query("SELECT id FROM usuarios ORDER BY id DESC LIMIT 1");
            $last_user = $stmt->fetch(PDO::FETCH_ASSOC);
            $userID = $last_user['id'] ?? null;
            
            if (!$userID) {
                echo "❌ No hay usuarios registrados<br>";
            } else {
                echo "✅ Último usuario ID: $userID<br>";
                
                // Intentar INSERT en suscripciones
                $stmt = $db->prepare("
                    INSERT INTO suscripciones (id_usuario, fecha_inicio, fecha_fin, estado, monto)
                    VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'activa', 10.00)
                ");
                $stmt->bindParam(1, $userID, PDO::PARAM_INT);
                
                if ($stmt->execute()) {
                    $suscripcion_id = $db->lastInsertId();
                    echo "✅ Suscripción insertada: ID $suscripcion_id<br>";
                    
                    // Intentar INSERT en pagos
                    $stmt = $db->prepare("
                        INSERT INTO pagos (id_usuario, id_suscripcion, monto, fecha, transaction_id)
                        VALUES (?, ?, 10.00, NOW(), 'TEST_TRANSACTION_123')
                    ");
                    $stmt->bindParam(1, $userID, PDO::PARAM_INT);
                    $stmt->bindParam(2, $suscripcion_id, PDO::PARAM_INT);
                    
                    if ($stmt->execute()) {
                        echo "✅ Pago insertado correctamente<br>";
                    } else {
                        echo "❌ Error al insertar pago: " . implode(' | ', $stmt->errorInfo()) . "<br>";
                    }
                } else {
                    echo "❌ Error al insertar suscripción: " . implode(' | ', $stmt->errorInfo()) . "<br>";
                }
            }
        } catch (Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "<a href='?test_insert=1' style='background:green;color:white;padding:10px;text-decoration:none'>Ejecutar Prueba INSERT</a><br><br>";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color:red'>Error de conexión</h2>";
    echo "Error: " . $e->getMessage();
}
?>

