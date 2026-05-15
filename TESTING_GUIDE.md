# Guía de Testing - Proyecto Laravel Westphalia

## Índice
1. [Pruebas Unitarias](#pruebas-unitarias)
2. [Pruebas Funcionales](#pruebas-funcionales)
3. [Pruebas Heurísticas](#pruebas-heurísticas)
4. [Ejecución de Pruebas](#ejecución-de-pruebas)

---

## Pruebas Unitarias

### ¿Qué son?
Las pruebas unitarias validan componentes individuales de código en aislamiento (funciones, métodos, modelos).

### Archivos de Pruebas Unitarias

#### 1. **ProductoTest.php** (`tests/Unit/ProductoTest.php`)
Prueba la lógica de negocio del modelo Producto:

- ✅ Creación de productos
- ✅ Cálculo de precio sin promoción
- ✅ Cálculo de precio con promoción activa
- ✅ Promociones expiradas no aplican descuento
- ✅ Relaciones entre Producto y Promocion
- ✅ Casting de tipos (precio float, stock integer)

**Casos de uso:**
```php
// Producto con 20% de descuento
Producto: $100 → Precio con descuento: $80 ✓
```

#### 2. **VentaTest.php** (`tests/Unit/VentaTest.php`)
Prueba la lógica de ventas:

- ✅ Creación de ventas
- ✅ Relación Venta → Usuario
- ✅ Relación Venta → Detalles de venta
- ✅ Casting de tipo total (float)
- ✅ Estados de pago válidos
- ✅ Cascada de eliminación

**Casos de uso:**
```php
// Una venta puede tener múltiples detalles
Venta ID 1: [Detalle 1, Detalle 2, Detalle 3] ✓
```

#### 3. **UserTest.php** (`tests/Unit/UserTest.php`)
Prueba el modelo Usuario:

- ✅ Creación de usuarios
- ✅ Atributos requeridos
- ✅ Contraseña hasheada
- ✅ Contraseña oculta en serialización
- ✅ Roles de usuario
- ✅ Tokens API
- ✅ Email único

**Casos de uso:**
```php
// Email único en la BD
User 1: admin@example.com ✓
User 2: admin@example.com ✗ ERROR
```

### Ejecución de Pruebas Unitarias
```bash
# Todas las pruebas unitarias
php artisan test tests/Unit

# Una prueba específica
php artisan test tests/Unit/ProductoTest

# Una prueba específica
php artisan test tests/Unit/ProductoTest --filter test_precio_sin_promocion
```

---

## Pruebas Funcionales

### ¿Qué son?
Las pruebas funcionales validan flujos completos del usuario, integrando múltiples componentes.

### Archivos de Pruebas Funcionales

#### 1. **AuthenticationTest.php** (`tests/Feature/AuthenticationTest.php`)
Prueba el sistema de autenticación:

- ✅ Redireccionamiento a login para no autenticados
- ✅ Página de login disponible
- ✅ Autenticación con credenciales válidas
- ✅ Rechazo de email incorrecto
- ✅ Rechazo de contraseña incorrecta
- ✅ Logout
- ✅ Usuario autenticado no accede a login
- ✅ Validación de campos requeridos

**Escenarios:**
```
CASO 1: Login exitoso
- Acceso: /login → 200 ✓
- POST /login (credenciales válidas) → Redirige a /dashboard ✓

CASO 2: Login fallido
- POST /login (email incorrecto) → Redirige a /login ✓
- Usuario sigue siendo Guest ✓
```

#### 2. **ProductoControllerTest.php** (`tests/Feature/ProductoControllerTest.php`)
Prueba la gestión de productos:

- ✅ Listar productos (autenticado)
- ✅ No listar productos (no autenticado)
- ✅ Ver detalle de producto
- ✅ Producto no encontrado (404)
- ✅ Admin puede crear producto
- ✅ Usuario normal NO puede crear
- ✅ Admin puede actualizar
- ✅ Admin puede eliminar
- ✅ Mostrar precio con descuento
- ✅ Validación de precio
- ✅ Validación de stock

**Escenarios:**
```
CASO 1: Crear producto (Admin)
- POST /productos (datos válidos) → ✓ Creado
- BD: nuevoproducto existe ✓

CASO 2: Crear producto (Customer)
- POST /productos → 403 Forbidden ✓

CASO 3: Validación
- POST /productos (precio vacío) → Error de validación ✓
- POST /productos (stock negativo) → Error de validación ✓
```

#### 3. **VentaFlowTest.php** (`tests/Feature/VentaFlowTest.php`)
Prueba el flujo completo de compra:

- ✅ Flujo completo: Ver producto → Carrito → Checkout → Compra
- ✅ Venta reduce stock
- ✅ Usuario ve historial de ventas
- ✅ Cálculo de total correcto
- ✅ No comprar sin stock
- ✅ Registra dirección de envío
- ✅ Pago rechazado marca venta como rechazada

**Escenarios:**
```
CASO 1: Flujo completo de compra
1. GET /productos/1 → 200 ✓
2. POST /carrito → producto agregado ✓
3. GET /carrito → carrito visible ✓
4. GET /checkout → formulario ✓
5. POST /comprar → Venta creada ✓

CASO 2: Compra sin stock
- Producto.stock = 0
- POST /comprar → Validación error ✓

CASO 3: Total de venta
- Detalle 1: 1 × $100 = $100
- Detalle 2: 3 × $50 = $150
- Total venta: $250 ✓
```

### Ejecución de Pruebas Funcionales
```bash
# Todas las pruebas funcionales
php artisan test tests/Feature

# Pruebas de autenticación
php artisan test tests/Feature/AuthenticationTest

# Una prueba específica
php artisan test tests/Feature/VentaFlowTest --filter test_flujo_completo_de_compra
```

---

## Pruebas Heurísticas

### ¿Qué son?
Pruebas manuales basadas en experiencia y creatividad para encontrar bugs no cubiertos por pruebas automatizadas.

### Plan de Testing Manual

#### 1. **Testing de Interfaz de Usuario (UI)**

**Sección: Productos**
```
PRUEBA 1.1: Visualización de lista de productos
□ Verificar que se cargan todos los productos
□ Verificar que se muestra nombre, marca, precio
□ Verificar que se muestra descuento (si existe)
□ Verificar paginación (si hay >10 productos)
□ Verificar que imagen se carga correctamente

PRUEBA 1.2: Búsqueda/Filtro de productos
□ Buscar por nombre: "laptop"
□ Filtrar por categoría: "Electrónica"
□ Filtrar por rango de precio: $100-$500
□ Ordenar por precio (menor a mayor)
□ Resultado correcto sin duplicados

PRUEBA 1.3: Detalle de producto
□ Ver información completa del producto
□ Ver descripción completa
□ Ver reviews/calificaciones (si aplica)
□ Ver disponibilidad de stock
□ Ver promoción activa y precio con descuento
```

**Sección: Carrito**
```
PRUEBA 2.1: Agregar al carrito
□ Agregar 1 producto → cantidad = 1 ✓
□ Agregar mismo producto → cantidad = 2 ✓
□ Agregar producto diferente → ambos en carrito ✓
□ Validar cálculo de subtotal por producto
□ Validar total del carrito

PRUEBA 2.2: Modificar carrito
□ Aumentar cantidad: 1 → 3 ✓
□ Disminuir cantidad: 3 → 1 ✓
□ Cantidad mínima permitida (1) ✓
□ No permitir cantidad > stock disponible ✓
□ Remover producto del carrito ✓

PRUEBA 2.3: Vaciar carrito
□ Click "Vaciar carrito" → carrito = vacío ✓
□ Confirmación de vaciar ✓
□ Botón "Continuar comprando" → retorno a productos ✓
```

**Sección: Checkout**
```
PRUEBA 3.1: Formulario de envío
□ Campos requeridos se validan
□ Email válido: email@domain.com ✓
□ Email inválido: "notanemail" ✗ error
□ Teléfono con formato
□ País/Ciudad autocompletado

PRUEBA 3.2: Métodos de pago
□ Tarjeta crédito → formulario completo
□ PayPal → redirección a PayPal
□ Transferencia → datos bancarios
□ Cada método muestra instrucciones claras
```

#### 2. **Testing de Funcionalidad de Negocio**

**Promociones**
```
PRUEBA 4.1: Descuento aplicado correctamente
□ Promoción activa hoy: mostrar nuevo precio ✓
□ Promoción futura: mostrar precio original ✓
□ Promoción expirada: mostrar precio original ✓
□ Múltiples promociones: usar la mayor descuento ✓

PRUEBA 4.2: Descuento en carrito/checkout
□ Precio en detalle = precio_base * (1 - descuento%) ✓
□ Total = suma de (precio_con_descuento × cantidad)
□ Descuento se revierte si promoción vence
```

**Inventario**
```
PRUEBA 5.1: Stock disponible
□ Comprar 5 de 10 → Stock = 5 ✓
□ Stock en carrito vs físico: sincronizado ✓
□ Admin puede forzar compra sin stock
□ Usuario normal NO puede comprar sin stock

PRUEBA 5.2: Notificaciones de stock bajo
□ Stock < 5 → "¡Últimas unidades!" ✓
□ Stock = 0 → "Agotado" con botón "Notificarme"
```

**Ventas/Órdenes**
```
PRUEBA 6.1: Crear venta
□ Venta completa con ID, fecha, usuario
□ Relación usuario_id correcto
□ Total calcula correctamente
□ Estado_pago inicial = "pendiente"

PRUEBA 6.2: Historial de compras
□ Usuario ve solo sus compras
□ Admin ve todas las compras
□ Filtrar por estado de pago
□ Exportar a PDF
```

#### 3. **Testing de Performance**

```
PRUEBA 7.1: Tiempo de carga
□ Página inicio: < 3 segundos
□ Listado de 100 productos: < 2 segundos
□ Búsqueda: < 1 segundo

PRUEBA 7.2: Concurrencia
□ 10 usuarios comprando simultáneamente
□ No hay duplicación de órdenes
□ Stock se reduce correctamente
□ No hay errores de base de datos
```

#### 4. **Testing de Seguridad**

```
PRUEBA 8.1: Autenticación
□ No autenticado no accede a /carrito
□ No autenticado no accede a /checkout
□ No autenticado no accede a /admin
□ Logout borra sesión

PRUEBA 8.2: Autorización
□ Customer NO puede: editar producto, crear producto, ver admin
□ Admin SÍ puede: todo ✓
□ Un usuario NO puede ver carrito de otro usuario

PRUEBA 8.3: Validación de entrada
□ SQL injection en búsqueda: no ejecuta SQL
□ XSS en descripción: no ejecuta JavaScript
□ CSRF: token requerido en forms
```

#### 5. **Testing de Casos Extremos (Edge Cases)**

```
PRUEBA 9.1: Límites numéricos
□ Precio muy alto: $999,999.99 ✓
□ Precio muy bajo: $0.01 ✓
□ Cantidad máxima: 9,999 ✓
□ Descuento 100% permitido ✓

PRUEBA 9.2: Caracteres especiales
□ Nombre con ñ: "Cañón de luz" ✓
□ Descripción con emojis: ✓
□ Email con +: "user+test@domain.com" ✓

PRUEBA 9.3: Conexión intermitente
□ Perder conexión en checkout → guardar borrador
□ Reconectar → continuar checkout
□ No duplicar orden
```

#### 6. **Testing de Flujos Negativos**

```
PRUEBA 10.1: Errores esperados
□ Comprar cantidad > stock disponible → error claro
□ Dejar campo requerido vacío → error validación
□ Email duplicado en registro → error
□ Contraseña muy débil → error

PRUEBA 10.2: Mensajes de error
□ Error técnico → "Algo salió mal. Intenta de nuevo"
□ No error técnico → detallar el problema
□ Mensajes claros en español
□ Opción de contactar soporte
```

### Plantilla de Reporte de Bug Encontrado
```markdown
## BUG #XX: [Título breve]

**Severidad:** [ ] Crítica [ ] Alta [ ] Media [ ] Baja

**Pasos para reproducir:**
1. Acceder a [URL/página]
2. Hacer [acción]
3. Resultado observado: [qué salió mal]
4. Resultado esperado: [qué debería pasar]

**Información:**
- Usuario: [admin/customer]
- Navegador: [Chrome/Firefox]
- OS: [Windows/Mac/Linux]
- Fecha: [YYYY-MM-DD HH:MM]

**Adjuntos:** [screenshot/video]
```

---

## Ejecución de Pruebas

### Configuración Inicial
```bash
# 1. Crear archivo .env.testing
cp .env .env.testing

# 2. Generar APP_KEY
php artisan key:generate --env=testing

# 3. Crear base de datos de testing (opcional)
# Descomentar en phpunit.xml:
# <env name="DB_CONNECTION" value="sqlite"/>
# <env name="DB_DATABASE" value=":memory:"/>
```

### Comandos Útiles
```bash
# Ejecutar todas las pruebas
php artisan test

# Con reportes de cobertura
php artisan test --coverage

# Ejecutar pruebas específicas
php artisan test tests/Unit/ProductoTest
php artisan test tests/Feature/AuthenticationTest

# Prueba individual
php artisan test tests/Unit/ProductoTest --filter test_precio_sin_promocion

# Parar en primer fallo
php artisan test --stop-on-failure

# Mostrar detalles verbosos
php artisan test -v

# Ejecutar en paralelo (más rápido)
php artisan test --parallel
```

### Verificar Cobertura
```bash
php artisan test --coverage

# Genera reporte HTML en: coverage/index.html
```

### Integración Continua (CI/CD)
```yaml
# .github/workflows/tests.yml (ejemplo para GitHub Actions)
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - run: composer install
      - run: php artisan test --coverage
```

---

## Conclusión

Este proyecto tiene tres niveles de pruebas:
- **Unitarias**: Validan lógica individual
- **Funcionales**: Validan flujos del usuario
- **Heurísticas**: Validan UX y casos extremos

Ejecuta todas regularmente para mantener calidad. 🚀
