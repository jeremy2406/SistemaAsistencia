<?php
require_once 'Config.php';

class Usuario {
    
    /**
     * Crea un nuevo usuario en el sistema
     * 
     * @param string $nombre Nombre completo del usuario
     * @param string $email Correo electrónico del usuario
     * @param string $rol Rol del usuario (estudiante, profesor, personal, administrador)
     * @return array Resultado de la operación
     */
    public static function crearUsuario($nombre, $email, $rol) {
        // Validar datos
        if (empty($nombre) || empty($email) || empty($rol)) {
            return [
                'success' => false,
                'message' => 'Todos los campos son obligatorios'
            ];
        }
        
        // Verificar rol válido
        $rolesValidos = ['estudiante', 'profesor', 'personal', 'administrador'];
        if (!in_array($rol, $rolesValidos)) {
            return [
                'success' => false,
                'message' => 'El rol especificado no es válido'
            ];
        }
        
        // Verificar si el correo ya existe
        $existeEmail = SupabaseConfig::request('/rest/v1/usuarios?select=id&email=eq.' . urlencode($email));
        if (!empty($existeEmail)) {
            return [
                'success' => false,
                'message' => 'Ya existe un usuario con ese correo electrónico'
            ];
        }
        
        // Datos para crear el usuario
        $datos = [
            'nombre' => $nombre,
            'email' => $email,
            'rol' => $rol
        ];
        
        // Crear usuario en Supabase
        $resultado = SupabaseConfig::request('/rest/v1/usuarios', 'POST', $datos);
        
        if (isset($resultado['error'])) {
            return [
                'success' => false,
                'message' => 'Error al crear el usuario: ' . $resultado['error']
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Usuario creado exitosamente',
            'data' => $resultado
        ];
    }
    
    /**
     * Obtiene todos los usuarios del sistema
     * 
     * @param string $busqueda Término de búsqueda opcional
     * @return array Lista de usuarios
     */
    public static function obtenerUsuarios($busqueda = null) {
        $endpoint = '/rest/v1/usuarios?select=*';
        
        if ($busqueda) {
            // Búsqueda por nombre (ilike para búsqueda insensible a mayúsculas/minúsculas)
            $endpoint .= '&nombre=ilike.' . urlencode('%' . $busqueda . '%');
        }
        
        return SupabaseConfig::request($endpoint);
    }
    
    /**
     * Obtiene un usuario por su ID
     * 
     * @param string $id ID del usuario
     * @return array Datos del usuario
     */
    public static function obtenerUsuarioPorId($id) {
        $endpoint = '/rest/v1/usuarios?select=*&id=eq.' . $id;
        $resultado = SupabaseConfig::request($endpoint);
        
        return !empty($resultado) ? $resultado[0] : null;
    }
    
    /**
     * Obtiene un usuario por su código QR
     * 
     * @param string $codigoQR Código QR del usuario
     * @return array Datos del usuario
     */
    public static function obtenerUsuarioPorQR($codigoQR) {
        $endpoint = '/rest/v1/usuarios?select=*&codigo_qr=eq.' . urlencode($codigoQR);
        $resultado = SupabaseConfig::request($endpoint);
        
        return !empty($resultado) ? $resultado[0] : null;
    }
    
    /**
     * Actualiza los datos de un usuario
     * 
     * @param string $id ID del usuario
     * @param array $datos Datos a actualizar
     * @return array Resultado de la operación
     */
    public static function actualizarUsuario($id, $datos) {
        // Validar datos
        if (empty($id) || empty($datos)) {
            return [
                'success' => false,
                'message' => 'ID de usuario y datos son obligatorios'
            ];
        }
        
        // Si se intenta actualizar el email, verificar que no exista ya
        if (isset($datos['email'])) {
            $existeEmail = SupabaseConfig::request('/rest/v1/usuarios?select=id&email=eq.' . urlencode($datos['email']) . '&id=neq.' . $id);
            if (!empty($existeEmail)) {
                return [
                    'success' => false,
                    'message' => 'Ya existe otro usuario con ese correo electrónico'
                ];
            }
        }
        
        // Actualizar usuario
        $endpoint = '/rest/v1/usuarios?id=eq.' . $id;
        $resultado = SupabaseConfig::request($endpoint, 'PATCH', $datos);
        
        if (isset($resultado['error'])) {
            return [
                'success' => false,
                'message' => 'Error al actualizar el usuario: ' . $resultado['error']
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Usuario actualizado exitosamente'
        ];
    }
    
    /**
     * Elimina un usuario (desactivación lógica)
     * 
     * @param string $id ID del usuario
     * @return array Resultado de la operación
     */
    public static function eliminarUsuario($id) {
        // Desactivar usuario en lugar de eliminarlo físicamente
        $datos = ['activo' => false];
        $endpoint = '/rest/v1/usuarios?id=eq.' . $id;
        $resultado = SupabaseConfig::request($endpoint, 'PATCH', $datos);
        
        if (isset($resultado['error'])) {
            return [
                'success' => false,
                'message' => 'Error al eliminar el usuario: ' . $resultado['error']
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Usuario eliminado exitosamente'
        ];
    }
    
    /**
     * Genera un nuevo código QR para un usuario
     * 
     * @param string $id ID del usuario
     * @return array Resultado de la operación con el nuevo código QR
     */
    public static function regenerarCodigoQR($id) {
        // Generar un nuevo código QR
        $nuevoCodigoQR = 'QR-' . bin2hex(random_bytes(16));
        
        // Actualizar el código QR del usuario
        $datos = ['codigo_qr' => $nuevoCodigoQR];
        $endpoint = '/rest/v1/usuarios?id=eq.' . $id;
        $resultado = SupabaseConfig::request($endpoint, 'PATCH', $datos);
        
        if (isset($resultado['error'])) {
            return [
                'success' => false,
                'message' => 'Error al regenerar el código QR: ' . $resultado['error']
            ];
        }
        
        return [
            'success' => true,
            'message' => 'Código QR regenerado exitosamente',
            'codigo_qr' => $nuevoCodigoQR
        ];
    }
}
?>