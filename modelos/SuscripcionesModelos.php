<?php
require_once __DIR__ . '/conexion.php';

class SuscripcionesModelo {

    /**
     * Crea una suscripción nueva para un usuario
     * Retorna el ID de la suscripción o false si falla
     */
    public static function crearSuscripcion($userID, $monto) {
        try {
            $db = Conexion::conectar();

            $stmt = $db->prepare("
                INSERT INTO suscripciones (id_usuario, fecha_inicio, fecha_fin, estado, monto)
                VALUES (?, NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY), 'activa', ?)
            ");

            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->bindParam(2, $monto);  // PDO detecta automáticamente el tipo numérico

            $ok = $stmt->execute();
            if ($ok) {
                return $db->lastInsertId();
            }

            $err = $stmt->errorInfo();
            error_log("Error ejecutar INSERT suscripciones: " . implode(' | ', $err));
            return false;

        } catch (PDOException $e) {
            error_log("Error Modelo Suscripciones: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener suscripción activa de un usuario
     */
    public static function obtenerSuscripcionActiva($userID) {
        try {
            $db = Conexion::conectar();

            $stmt = $db->prepare("
                SELECT * FROM suscripciones 
                WHERE id_usuario = ? AND estado = 'activa'
                LIMIT 1
            ");

            $stmt->execute([$userID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error obtenerSuscripcionActiva: " . $e->getMessage());
            return false;
        }
    }

}
