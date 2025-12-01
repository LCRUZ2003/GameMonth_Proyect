<?php
require_once __DIR__ . '/conexion.php';

class PagosModelo {

    /**
     * Registra un pago en la base de datos
     * Retorna true si se guardó correctamente
     */
    public static function registrarPago($userID, $id_suscripcion, $monto, $transactionID) {
        try {
            $db = Conexion::conectar();

            // Ajuste de columnas para coincidir con la tabla 'pagos'
            // La tabla tiene columnas: id_usuario, id_suscripcion, monto, fecha, transaction_id
            $stmt = $db->prepare("\
                INSERT INTO pagos (id_usuario, id_suscripcion, monto, fecha, transaction_id)\
                VALUES (?, ?, ?, NOW(), ?)\
            ");

            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->bindParam(2, $id_suscripcion, PDO::PARAM_INT);
            $stmt->bindParam(3, $monto);  // PDO detecta automáticamente el tipo numérico
            $stmt->bindParam(4, $transactionID, PDO::PARAM_STR);

            $ok = $stmt->execute();
            if (!$ok) {
                $err = $stmt->errorInfo();
                error_log("Error ejecutar INSERT pagos: " . implode(' | ', $err));
                return false;
            }
            return true;

        } catch (PDOException $e) {
            error_log("Error Modelo Pagos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener historial de pagos del usuario
     */
    public static function obtenerPagosPorUsuario($userID) {
        try {
            $db = Conexion::conectar();

            $stmt = $db->prepare("
                SELECT * FROM pagos 
                WHERE id_usuario = ?
                ORDER BY fecha DESC
            ");

            $stmt->execute([$userID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error obtenerPagosPorUsuario: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Auxiliar para debug: obtener últimos errores de PDO
     */
    public static function debug_pdo($stmt) {
        $info = $stmt->errorInfo();
        return "SQLSTATE[" . $info[0] . "]: " . $info[1] . " - " . $info[2];
    }

}
