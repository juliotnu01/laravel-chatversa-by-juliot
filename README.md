# TaskFlow - Sistema de Gestión de Proyectos y Tareas

## 📋 Descripción del Proyecto

TaskFlow es una aplicación web moderna desarrollada con Laravel 9 y Vue.js 3 que permite gestionar proyectos y tareas de manera eficiente. La aplicación incluye autenticación completa, dashboard interactivo, API REST y una interfaz de usuario moderna construida con Inertia.js y Tailwind CSS.

## 🚀 Características Principales

### 🔐 Autenticación y Autorización
- **Laravel Jetstream** con autenticación de dos factores
- **Laravel Sanctum** para autenticación API
- **Laravel Fortify** para funcionalidades de autenticación
- Registro, login, verificación de email y recuperación de contraseña
- Gestión de sesiones y tokens API

### 📊 Gestión de Proyectos
- Crear, editar y eliminar proyectos
- Estados: Planning, Active, Paused, Completed, Cancelled
- Prioridades: Low, Medium, High, Urgent
- Fechas de vencimiento y seguimiento de progreso
- Asignación de propietarios de proyectos

### ✅ Gestión de Tareas
- Crear, editar y eliminar tareas
- Estados: Pending, In Progress, Completed, Cancelled
- Prioridades: Low, Medium, High, Urgent
- Estimación y registro de horas reales
- Asignación de tareas a usuarios específicos
- Vinculación con proyectos

### 📈 Dashboard Interactivo
- Vista general de proyectos y tareas
- Estadísticas en tiempo real
- Filtros avanzados y búsqueda
- Estado de conexión del usuario
- Interfaz responsive con Tailwind CSS

### 🔌 API REST Completa
- Endpoints para proyectos y tareas
- Autenticación con tokens
- Filtros, paginación y ordenamiento
- Validación de datos con Form Requests
- Recursos API estructurados

## 🛠️ Stack Tecnológico

### Backend
- **Laravel 9** - Framework PHP
- **PHP 8.0+** - Lenguaje de programación
- **MySQL** - Base de datos
- **Laravel Sanctum** - Autenticación API
- **Laravel Jetstream** - Autenticación y scaffolding
- **Laravel Fortify** - Funcionalidades de autenticación

### Frontend
- **Vue.js 3** - Framework JavaScript
- **Inertia.js** - SPA sin API
- **Tailwind CSS** - Framework CSS
- **Vite** - Build tool
- **Axios** - Cliente HTTP

### Herramientas de Desarrollo
- **Laravel Pint** - Code formatter
- **PHPUnit** - Testing framework
- **Laravel Sail** - Docker development environment
- **Faker** - Data generation for testing

## 📁 Estructura del Proyecto

```
laravel/
├── app/
│   ├── Actions/           # Acciones de Jetstream/Fortify
│   ├── Console/           # Comandos Artisan
│   ├── Exceptions/        # Manejo de excepciones
│   ├── Http/
│   │   ├── Controllers/   # Controladores web y API
│   │   ├── Middleware/    # Middleware personalizado
│   │   ├── Requests/      # Form Requests para validación
│   │   └── Resources/     # API Resources
│   ├── Models/           # Modelos Eloquent
│   ├── Observers/        # Model Observers
│   ├── Policies/         # Authorization Policies
│   └── Providers/        # Service Providers
├── database/
│   ├── factories/        # Model Factories
│   ├── migrations/       # Migraciones de base de datos
│   └── seeders/         # Database Seeders
├── resources/
│   ├── js/
│   │   ├── Components/   # Componentes Vue.js
│   │   ├── Layouts/      # Layouts de aplicación
│   │   └── Pages/        # Páginas de Inertia.js
│   └── css/             # Estilos CSS
├── routes/
│   ├── api.php          # Rutas API
│   └── web.php          # Rutas web
└── tests/              # Tests automatizados
```

## 🗄️ Modelo de Datos

### Usuarios (Users)
- Información básica del usuario
- Autenticación y autorización
- Relación con proyectos y tareas
- Seguimiento de último login

### Proyectos (Projects)
- Nombre, descripción y estado
- Prioridad y fecha de vencimiento
- Progreso calculado automáticamente
- Propietario del proyecto
- Soft deletes habilitado

### Tareas (Tasks)
- Nombre, descripción y estado
- Prioridad y fecha de vencimiento
- Estimación y horas reales
- Vinculación con proyecto
- Asignación a usuario específico
- Soft deletes habilitado

## 🔧 Instalación y Configuración

### Requisitos Previos
- PHP 8.0 o superior
- Composer
- Node.js y npm
- MySQL
- Git

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone <repository-url>
cd laravel
```

2. **Instalar dependencias PHP**
```bash
composer install
```

3. **Instalar dependencias JavaScript**
```bash
npm install
```

4. **Configurar variables de entorno**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configurar base de datos en `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskflow
DB_USERNAME=root
DB_PASSWORD=
```

6. **Ejecutar migraciones**
```bash
php artisan migrate
```

7. **Ejecutar seeders (opcional)**
```bash
php artisan db:seed
```

8. **Compilar assets**
```bash
npm run dev
# o para producción
npm run build
```

9. **Iniciar servidor de desarrollo**
```bash
php artisan serve
```

## 🚀 Comandos Útiles

### Desarrollo
```bash
# Servidor de desarrollo
php artisan serve

# Compilar assets en modo desarrollo
npm run dev

# Compilar assets en modo producción
npm run build

# Ejecutar tests
php artisan test

# Formatear código
./vendor/bin/pint
```

### Base de Datos
```bash
# Ejecutar migraciones
php artisan migrate

# Rollback migraciones
php artisan migrate:rollback

# Ejecutar seeders
php artisan db:seed

# Refrescar base de datos
php artisan migrate:refresh --seed
```

### Cache y Optimización
```bash
# Limpiar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimizar para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📡 API Endpoints

### Autenticación
```
POST /api/v1/auth/login
GET  /api/v1/auth/user
POST /api/v1/auth/logout
```

### Proyectos
```
GET    /api/v1/projects          # Listar proyectos
POST   /api/v1/projects          # Crear proyecto
GET    /api/v1/projects/{id}     # Ver proyecto
PUT    /api/v1/projects/{id}     # Actualizar proyecto
DELETE /api/v1/projects/{id}     # Eliminar proyecto
```

### Tareas
```
GET    /api/v1/tasks             # Listar tareas
POST   /api/v1/tasks             # Crear tarea
GET    /api/v1/tasks/{id}        # Ver tarea
PUT    /api/v1/tasks/{id}        # Actualizar tarea
DELETE /api/v1/tasks/{id}        # Eliminar tarea
POST   /api/v1/tasks/{id}/status # Actualizar estado
```

### Parámetros de Filtrado
- `status`: Filtrar por estado
- `priority`: Filtrar por prioridad
- `search`: Búsqueda en nombre/descripción
- `sort`: Ordenamiento (- para descendente)
- `per_page`: Elementos por página

## 🧪 Testing

El proyecto incluye tests automatizados con PHPUnit:

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=AuthenticationTest

# Tests con coverage
php artisan test --coverage
```

### Tipos de Tests
- **Feature Tests**: Tests de integración para endpoints API
- **Unit Tests**: Tests unitarios para modelos y lógica de negocio
- **Authentication Tests**: Tests de autenticación y autorización

## 🔒 Seguridad

### Características de Seguridad Implementadas
- **CSRF Protection**: Protección contra ataques CSRF
- **XSS Protection**: Sanitización de datos de entrada
- **SQL Injection Prevention**: Uso de Eloquent ORM
- **Rate Limiting**: Limitación de requests por minuto
- **Input Validation**: Validación estricta de datos
- **Authorization Policies**: Control de acceso granular
- **Secure Headers**: Headers de seguridad configurados

### Políticas de Autorización
- **ProjectPolicy**: Control de acceso a proyectos
- **TaskPolicy**: Control de acceso a tareas
- Solo el propietario puede modificar sus proyectos
- Solo usuarios asignados pueden modificar tareas

## 📊 Diagramas de Infraestructura

### Arquitectura General del Sistema

```mermaid
graph TB
    subgraph "Frontend Layer"
        A[Vue.js 3 Components] --> B[Inertia.js]
        B --> C[Tailwind CSS]
    end
    
    subgraph "Backend Layer"
        D[Laravel 9 Framework] --> E[Controllers]
        E --> F[Models & Eloquent]
        F --> G[Database Layer]
    end
    
    subgraph "Authentication Layer"
        H[Laravel Sanctum] --> I[Jetstream]
        I --> J[Fortify]
    end
    
    subgraph "Data Layer"
        K[MySQL Database] --> L[Migrations]
        L --> M[Seeders]
    end
    
    A --> D
    D --> H
    F --> K
```

### Flujo de Datos de la Aplicación

```mermaid
sequenceDiagram
    participant U as Usuario
    participant V as Vue.js Frontend
    participant I as Inertia.js
    participant L as Laravel Controller
    participant M as Eloquent Model
    participant D as MySQL Database
    
    U->>V: Interacción con UI
    V->>I: Request de datos
    I->>L: Llamada a Controller
    L->>M: Consulta al Modelo
    M->>D: Query SQL
    D-->>M: Datos
    M-->>L: Objeto Eloquent
    L-->>I: Response JSON
    I-->>V: Datos para Vue
    V-->>U: UI actualizada
```

### Arquitectura de la API REST

```mermaid
graph LR
    subgraph "Client Layer"
        A[Mobile App] --> B[Web App]
        B --> C[Third Party Apps]
    end
    
    subgraph "API Gateway"
        D[Laravel Sanctum] --> E[Rate Limiting]
        E --> F[Authentication]
    end
    
    subgraph "API Controllers"
        G[AuthController] --> H[ProjectController]
        H --> I[TaskController]
    end
    
    subgraph "Business Logic"
        J[Form Requests] --> K[Policies]
        K --> L[Observers]
    end
    
    subgraph "Data Access"
        M[Eloquent Models] --> N[Database]
    end
    
    A --> D
    B --> D
    C --> D
    D --> G
    G --> J
    J --> M
```

### Estructura de Base de Datos

```mermaid
erDiagram
    USERS {
        bigint id PK
        string name
        string email UK
        timestamp email_verified_at
        string password
        timestamp last_login_at
        boolean is_online
        timestamp created_at
        timestamp updated_at
    }
    
    PROJECTS {
        bigint id PK
        string name
        text description
        enum status
        enum priority
        datetime due_date
        integer progress
        bigint owner_id FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    TASKS {
        bigint id PK
        string name
        text description
        enum status
        enum priority
        datetime due_date
        integer estimated_hours
        integer actual_hours
        bigint project_id FK
        bigint assigned_to FK
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }
    
    USERS ||--o{ PROJECTS : owns
    USERS ||--o{ TASKS : assigned_to
    PROJECTS ||--o{ TASKS : contains
```

## 🔄 Flujo de Trabajo de Desarrollo

### Git Workflow
1. **Feature Branch**: Crear rama para nueva funcionalidad
2. **Development**: Desarrollar y testear localmente
3. **Testing**: Ejecutar tests automatizados
4. **Code Review**: Revisión de código
5. **Merge**: Integrar a rama principal
6. **Deploy**: Despliegue a producción

### Proceso de Desarrollo
1. **Análisis**: Definir requerimientos
2. **Diseño**: Crear migraciones y modelos
3. **Backend**: Implementar controladores y lógica
4. **Frontend**: Desarrollar componentes Vue.js
5. **Testing**: Escribir y ejecutar tests
6. **Documentación**: Actualizar documentación

## 📈 Métricas y Monitoreo

### Métricas de Rendimiento
- Tiempo de respuesta de API
- Uso de memoria
- Queries de base de datos
- Cache hit ratio

### Logs y Debugging
- Laravel Log: `/storage/logs/laravel.log`
- Error tracking con Laravel Ignition
- Debug mode en desarrollo

## 🚀 Despliegue

### Requisitos de Producción
- PHP 8.0+
- MySQL 8.0+
- Nginx/Apache
- SSL Certificate
- Composer
- Node.js (para build)

### Pasos de Despliegue
1. Configurar servidor web
2. Instalar dependencias PHP
3. Configurar variables de entorno
4. Ejecutar migraciones
5. Compilar assets de producción
6. Configurar SSL
7. Configurar cron jobs

## 🤝 Contribución

### Cómo Contribuir
1. Fork del repositorio
2. Crear rama de feature
3. Realizar cambios
4. Ejecutar tests
5. Crear Pull Request

### Estándares de Código
- PSR-12 para PHP
- ESLint para JavaScript
- Laravel Pint para formateo
- Documentación en español

## 📝 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👥 Equipo de Desarrollo

- **Desarrollador Principal**: [Tu Nombre]
- **Email**: [tu-email@ejemplo.com]
- **GitHub**: [tu-usuario-github]

## 📞 Soporte

Para soporte técnico o preguntas sobre el proyecto:
- Crear un issue en GitHub
- Contactar por email
- Revisar la documentación

---

**TaskFlow** - Gestión eficiente de proyectos y tareas con Laravel y Vue.js