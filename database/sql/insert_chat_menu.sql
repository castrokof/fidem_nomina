-- Script SQL para agregar el menú del Asistente Virtual (Chat con IA)
-- Ejecutar este script si no puedes usar el seeder

-- Verificar si ya existe el menú
SELECT
    CASE
        WHEN EXISTS (
            SELECT 1 FROM menu
            WHERE nombre = 'Asistente Virtual'
               OR url = '/admin/chat'
        )
        THEN 'El menú ya existe - NO ejecutar el INSERT'
        ELSE 'Proceder con el INSERT'
    END AS verificacion;

-- Si la verificación anterior indica que puedes proceder, ejecuta lo siguiente:

-- Obtener el próximo orden disponible
SET @ultimo_orden = (SELECT IFNULL(MAX(orden), 0) + 1 FROM menu WHERE menu_id = 0);

-- Insertar el menú del Asistente Virtual
INSERT INTO menu (menu_id, nombre, url, orden, icono, created_at, updated_at)
VALUES (
    0,                          -- menu_id = 0 (menú principal, sin padre)
    'Asistente Virtual',        -- nombre del menú
    '/admin/chat',              -- URL del chat
    @ultimo_orden,              -- orden calculado
    'fas fa-robot',             -- icono de Font Awesome
    NOW(),                      -- created_at
    NOW()                       -- updated_at
);

-- Obtener el ID del menú recién insertado
SELECT LAST_INSERT_ID() AS menu_id_insertado;

-- NOTA IMPORTANTE:
-- Después de ejecutar este script, debes:
-- 1. Ir al panel de administración
-- 2. Navegar a la sección de Menú-Rol
-- 3. Asignar el menú "Asistente Virtual" a los roles que deban tener acceso
-- 4. Configurar CLAUDE_API_KEY en el archivo .env para que el chat funcione
