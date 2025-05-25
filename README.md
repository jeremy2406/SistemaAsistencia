# Sistema de Asistencia QR

Sistema web para registrar asistencia mediante códigos QR, desarrollado con PHP y Supabase como base de datos.

## 🚀 Características

- **Escaneo de códigos QR** en tiempo real usando la cámara del dispositivo
- **Lista de asistencia** en tiempo real con estadísticas
- **Interface responsive** compatible con móviles y desktop
- **Backend con Supabase** para almacenamiento en la nube
- **Prevención de registros duplicados** por día
- **Estadísticas de asistencia** en tiempo real

## 📋 Requisitos

- Servidor web con PHP 7.4 o superior
- Extensión cURL de PHP habilitada
- Cuenta en [Supabase](https://supabase.com)
- Navegador web moderno con soporte para cámara

## 🛠️ Instalación

### 1. Configuración de Supabase

1. Crea una cuenta en [supabase.com](https://supabase.com)
2. Crea un nuevo proyecto
3. Ve a **SQL Editor** y ejecuta el siguiente código para crear las tablas:

```sql
-- Tabla de usuarios
CREATE TABLE usuarios (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    ocupacion VARCHAR(100) NOT NULL,
    codigo_qr VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Tabla de asistencias
CREATE TABLE asistencias (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    usuario_id UUID REFERENCES usuarios(id) ON DELETE CASCADE,
    hora_llegada TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
    estatus VARCHAR(20) DEFAULT 'Presente',
    fecha DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

-- Índices para mejorar rendimiento
CREATE INDEX idx_usuarios_codigo_qr ON usuarios(codigo_qr);
CREATE INDEX idx_asistencias_usuario_id ON asistencias(usuario_id);
CREATE INDEX idx_asistencias_fecha ON asistencias(fecha);

-- Política de seguridad (Row Level Security)
ALTER TABLE usuarios ENABLE ROW LEVEL SECURITY;
ALTER TABLE asistencias ENABLE ROW LEVEL SECURITY;

-- Políticas básicas
CREATE POLICY "Enable read access for all users" ON usuarios FOR SELECT USING (true);
CREATE POLICY "Enable insert access for all users" ON usuarios FOR INSERT WITH CHECK (true);

CREATE POLICY "Enable read access for all users" ON asistencias FOR SELECT USING (true);
CREATE POLICY "Enable insert access for all users" ON asistencias FOR INSERT WITH CHECK (true);

-- Datos de ejemplo (opcional)
INSERT INTO usuarios (nombre, apellido, ocupacion, codigo_qr) VALUES
('Juan', 'Pérez', 'Desarrollador', 'QR001'),
('María', 'González', 'Diseñadora', 'QR002'),
('Carlos', 'Rodríguez', 'Gerente', 'QR003'),
('Ana', 'López', 'Analista', 'QR004');
```

4. Ve a **Settings → API** y copia:
   - **URL** del proyecto
   - **anon public** key

### 2. Configuración del Sistema

1. Descarga o clona este repositorio
2. Sube los archivos a tu servidor web
3. Ve a `tu-dominio.com/install.php` en tu navegador
4. Introduce tus credenciales de Supabase
5. ¡Listo! El sistema estará configurado

### 3. Estructura de Archivos

```
SistemaAsistencia/
├── Componentes/
│   ├── Header.php          # Encabezado HTML
│   ├── Nav.php            # Navegación
│   └── Footer.php         # Pie de página
├── php/
│   ├── registrar_asistencia.php    # Procesa el registro QR
│   ├── obtener_asistencias.php     # Obtiene lista de asistencia
│   └── funciones_adicionales.php   # Funciones extra y estadísticas
├── config.php             # Configuración de Supabase
├── index.php             # Página principal
├── escanear.php          # Escáner de códigos QR
├── lista.php             # Lista de asistencia
├── install.php           # Configuración inicial
└── README.md             # Este archivo
```

## 📱 Uso del Sistema

### Para Usuarios

1. **Obtener código QR**: Cada usuario debe tener un código QR único asignado
2. **Escanear**: Ir a `/escanear.php` y permitir acceso a la cámara
3. **Registro**: Apuntar la cámara al código QR para registrar asistencia

### Para Administradores

1. **Ver asistencias**: Ir a `/lista.php` para ver la lista en tiempo real
2. **Estadísticas**: Las estadísticas se actualizan automáticamente
3. **Filtrar por fecha**: Usar el selector de fecha para ver días específicos

## 🔧 Funcionalidades del Backend

### Endpoints Disponibles

#### `php/registrar_asistencia.php`
- **Método**: POST
- **Parámetros**: `{ "codigo_qr": "QR001" }`
- **Función**: Registra la asistencia del usuario

#### `php/obtener_asistencias.php`
- **Método**: GET
- **Parámetros**: `?fecha=2024-01-15` (opcional)
- **Función**: Obtiene lista de asistencias

#### `php/funciones_adicionales.php`
- **Estadísticas**: `?accion=estadisticas&fecha=2024-01-15`
- **Usuarios**: `?accion=usuarios`
- **Historial**: `?accion=historial&usuario_id=uuid`
- **Reporte**: `?accion=reporte&fecha_inicio=2024-01-01&fecha_fin=2024-01-31`

### Características de Seguridad

- **Prevención de duplicados**: No permite registrar asistencia múltiples veces el mismo día
- **Validación de códigos QR**: Verifica que el código exista en la base de datos
- **Manejo de errores**: Respuestas informativas para diferentes escenarios

## 🎨 Personalización

### Estilos
El sistema usa **Tailwind CSS** para los estilos. Puedes personalizar:
- Colores en las clases CSS
- Layout en los archivos de componentes
- Mensajes en los archivos PHP

### Base de Datos
Puedes agregar más campos a las tablas según tus necesidades:
- Campos adicionales en `usuarios` (teléfono, email, etc.)
- Campos adicionales en `asistencias` (comentarios, ubicación, etc.)

## 🐛 Solución de Problemas

### Error de Cámara
- Verificar permisos de cámara en el navegador
- Usar HTTPS (requerido para acceso a cámara)
- Verificar que el dispositivo tenga cámara

### Error de Conexión
- Verificar credenciales de Supabase en `config.php`
- Verificar que las tablas estén creadas correctamente
- Verificar conectividad a internet

### Códigos QR no funcionan
- Verificar que el código QR existe en la tabla `usuarios`
- Verificar formato del código QR
- Verificar que el código no esté duplicado

## 📧 Soporte

Para reportar problemas o sugerir mejoras, puedes:
1. Crear un issue en el repositorio
2. Contactar al desarrollador
3. Verificar la documentación de Supabase

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Puedes usarlo libremente para proyectos personales y comerciales.

---

**¡Gracias por usar el Sistema de Asistencia QR!** 🎉
