<?php
include_once "conexion.php";
class biblioteca_modelo{

    public static function obtenerLibros($id_user){
        $db =  Conexion::conectar();
        
        $userId = $id_user;
        // JOIN con tabla videojuegos para obtener datos completos del juego
        $stmt = $db->prepare("
            SELECT 
                bu.id_biblioteca,
                bu.id_usuario,
                bu.id_videojuego as id,
                v.titulo,
                v.genero,
                v.portada,
                v.descripcion,
                v.calificacion,
                bu.fecha_activacion
            FROM biblioteca_usuarios bu
            INNER JOIN videojuegos v ON bu.id_videojuego = v.id
            WHERE bu.id_usuario = ?
            ORDER BY bu.fecha_activacion DESC
        ");
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>