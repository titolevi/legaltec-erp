# Legaltec ERP — SaaS Management Console (Database Schema)

## Tablas necesarias para administración de Tenants

### 1. Extensión de tabla `tenants`

```sql
-- Agregar columnas a tenants
ALTER TABLE tenants ADD COLUMN status VARCHAR(20) DEFAULT 'active';  -- active, suspended, trial, cancelled
ALTER TABLE tenants ADD COLUMN plan VARCHAR(50) DEFAULT 'trial';      -- trial, starter, professional, enterprise
ALTER TABLE tenants ADD COLUMN mrr DECIMAL(10,2) DEFAULT 0;          -- Monthly Recurring Revenue
ALTER TABLE tenants ADD COLUMN storage_limit BIGINT DEFAULT 1024;    -- Límite en MB (default 1GB)
ALTER TABLE tenants ADD COLUMN storage_used BIGINT DEFAULT 0;        -- Almacenamiento usado en MB
ALTER TABLE tenants ADD COLUMN max_users INT DEFAULT 10;             -- Máximo de usuarios permitidos
ALTER TABLE tenants ADD COLUMN maintenance_mode BOOLEAN DEFAULT 0;   -- Banner de mantenimiento
ALTER TABLE tenants ADD COLUMN maintenance_message TEXT;              -- Mensaje del banner
ALTER TABLE tenants ADD COLUMN notas TEXT;                           -- Notas internas de Legaltec
ALTER TABLE tenants ADD COLUMN created_by INT REFERENCES users(id);  -- Quién creó el tenant
```

### 2. `tenant_modules` — Módulos contratados por tenant

```sql
CREATE TABLE tenant_modules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tenant_id INT NOT NULL REFERENCES tenants(id),
    module_slug VARCHAR(50) NOT NULL,        -- 'tickets-admin', 'soporte-ti', 'facturacion', 'cajas'
    module_name VARCHAR(100) NOT NULL,       -- 'Tickets Administrativos', 'Soporte TI'
    activo BOOLEAN DEFAULT 1,
    fecha_activacion DATETIME,
    precio_mensual DECIMAL(10,2) DEFAULT 0,  -- Precio del módulo para este tenant
    config JSON,                              -- Configuración específica del módulo
    created_at DATETIME,
    updated_at DATETIME,
    UNIQUE(tenant_id, module_slug)
);
```

### 3. `audit_logs` — Registro de auditoría

```sql
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tenant_id INT REFERENCES tenants(id),      -- NULL = acción global
    user_id INT NOT NULL REFERENCES users(id), -- Quién ejecutó la acción
    accion VARCHAR(50) NOT NULL,               -- 'impersonacion.entrar', 'impersonacion.salir', 'tenant.crear', 'tenant.suspender'
    descripcion TEXT,                           -- Descripción legible de la acción
    ip_address VARCHAR(45),                     -- Dirección IP del usuario
    user_agent TEXT,                            -- User agent del navegador
    metadata JSON,                              -- Datos adicionales (ej: {tenant_origen: 1, tenant_destino: 2})
    created_at DATETIME
);

CREATE INDEX idx_audit_logs_tenant ON audit_logs(tenant_id);
CREATE INDEX idx_audit_logs_user ON audit_logs(user_id);
CREATE INDEX idx_audit_logs_accion ON audit_logs(accion);
CREATE INDEX idx_audit_logs_created ON audit_logs(created_at);
```

### 4. `feature_flags` — Feature flags para despliegue gradual

```sql
CREATE TABLE feature_flags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(100) NOT NULL UNIQUE,         -- 'cajas-modulo', 'facturacion-sunat', 'dark-mode'
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    activo_global BOOLEAN DEFAULT 0,           -- Activado para todos?
    created_at DATETIME,
    updated_at DATETIME
);

CREATE TABLE tenant_feature_flags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tenant_id INT NOT NULL REFERENCES tenants(id),
    feature_flag_id INT NOT NULL REFERENCES feature_flags(id),
    activo BOOLEAN DEFAULT 0,
    created_at DATETIME,
    UNIQUE(tenant_id, feature_flag_id)
);
```

### 5. `tenant_usage` — Métricas de uso mensual

```sql
CREATE TABLE tenant_usage (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tenant_id INT NOT NULL REFERENCES tenants(id),
    periodo DATE NOT NULL,                      -- Primer día del mes (ej: 2026-07-01)
    usuarios_activos INT DEFAULT 0,
    tickets_creados INT DEFAULT 0,
    tickets_aprobados INT DEFAULT 0,
    almacenamiento_mb INT DEFAULT 0,
    facturas_emitidas INT DEFAULT 0,
    api_calls INT DEFAULT 0,
    created_at DATETIME,
    UNIQUE(tenant_id, periodo)
);
```

### 6. `system_health` — Registros de salud del sistema

```sql
CREATE TABLE system_health (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tenant_id INT REFERENCES tenants(id),      -- NULL = salud global
    tipo VARCHAR(50) NOT NULL,                  -- 'db_connection', 'disk_space', 'memory', 'response_time'
    estado VARCHAR(20) NOT NULL,                -- 'healthy', 'warning', 'critical'
    valor VARCHAR(255),                         -- Ej: '512MB/1024MB', '200ms'
    mensaje TEXT,
    created_at DATETIME
);

CREATE INDEX idx_system_health_tipo ON system_health(tipo);
CREATE INDEX idx_system_health_estado ON system_health(estado);
```

### 7. `tenant_invoices` — Facturación del SaaS (para MRR)

```sql
CREATE TABLE tenant_invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tenant_id INT NOT NULL REFERENCES tenants(id),
    periodo DATE NOT NULL,                      -- Mes facturado
    monto DECIMAL(10,2) NOT NULL,
    moneda VARCHAR(3) DEFAULT 'PEN',
    estado VARCHAR(20) DEFAULT 'pending',       -- pending, paid, overdue, cancelled
    fecha_emision DATETIME,
    fecha_pago DATETIME,
    metodo_pago VARCHAR(50),                    -- transferencia, tarjeta, etc.
    notas TEXT,
    created_at DATETIME,
    UNIQUE(tenant_id, periodo)
);
```

## Diagrama de Relaciones

```
TENANTS ──┬── TENANT_MODULES (módulos contratados)
          ├── TENANT_USAGE (métricas mensuales)
          ├── TENANT_INVOICES (facturación)
          ├── TENANT_FEATURE_FLAGS (flags activos)
          ├── AUDIT_LOGS (registro de acciones)
          └── SYSTEM_HEALTH (salud del tenant)

FEATURE_FLAGS ──┬── TENANT_FEATURE_FLAGS (flags por tenant)
                └── (control global)

USERS ──┬── AUDIT_LOGS (quién hizo qué)
        └── TENANTS (created_by)
```

## Resumen de tablas nuevas

| Tabla | Propósito | Registros esperados |
|---|---|---|
| **tenant_modules** | Módulos contratados por cada tenant | 5-10 por tenant |
| **audit_logs** | Registro de auditoría de acciones admin | 1000s/mes |
| **feature_flags** | Catálogo de feature flags | 10-30 total |
| **tenant_feature_flags** | Flags activos por tenant | 5-10 por tenant |
| **tenant_usage** | Métricas de uso mensual | 12 por tenant/año |
| **system_health** | Health checks | 100s/día |
| **tenant_invoices** | Facturación SaaS | 12 por tenant/año |