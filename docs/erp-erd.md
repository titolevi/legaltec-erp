# Legaltec ERP — Diagrama Entidad-Relación (ERD)

> Módulo 1: Tickets Administrativos

```mermaid
erDiagram
    TENANTS ||--o{ USERS : "tiene"
    TENANTS ||--o{ CLIENTES : "tiene"
    TENANTS ||--o{ ASUNTOS : "tiene"
    TENANTS ||--o{ TICKETS : "contiene"

    USERS ||--o{ TICKETS : "crea"
    USERS ||--o{ TICKETS : "autoriza"

    CLIENTES ||--o{ ASUNTOS : "tiene"
    CLIENTES ||--o{ TICKETS : "asociado"

    ASUNTOS ||--o{ TICKETS : "contiene"

    TENANT {
        int id PK
        string name
        string slug UK
        string ruc UK
        boolean activo
        datetime created_at
        datetime updated_at
    }

    USERS {
        int id PK
        int tenant_id FK
        string name
        string email UK
        string password
        string telefono
        enum rol "usuario|autorizador|cajero|admin"
        boolean activo
        datetime created_at
        datetime updated_at
    }

    CLIENTES {
        int id PK
        int tenant_id FK
        string codigo UK
        string nombre
        string ruc
        boolean activo
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }

    ASUNTOS {
        int id PK
        int tenant_id FK
        int cliente_id FK
        string codigo
        string nombre
        boolean activo
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }

    TICKETS {
        int id PK
        int tenant_id FK
        int tenant_id FK
        string numero UK
        int cliente_id FK
        int asunto_id FK
        date fecha_diligencia
        text detalle
        string distrito
        boolean facturable
        decimal monto
        string moneda
        string ejecutado_por
        int autorizador_id FK
        enum estado "pendiente|aprobado|rechazado|completado"
        int usuario_id FK
        text observaciones
        datetime created_at
        datetime updated_at
        datetime deleted_at
    }
```

## Relaciones

| Relación | Tipo | Descripción |
|---|---|---|
| Tenant → Users | 1:N | Un tenant tiene muchos usuarios |
| Tenant → Clientes | 1:N | Un tenant gestiona muchos clientes |
| Tenant → Tickets | 1:N | Un tenant tiene muchos tickets |
| Users → Tickets (crea) | 1:N | Un usuario crea muchos tickets |
| Users → Tickets (autoriza) | 1:N | Un usuario autoriza muchos tickets |
| Clientes → Asuntos | 1:N | Un cliente tiene muchos asuntos |
| Clientes → Tickets | 1:N | Un cliente genera muchos tickets |
| Asuntos → Tickets | 1:N | Un asunto contiene muchos tickets |

## Reglas de Negocio

1. `codigo` en Clientes es único por tenant (3 dígitos)
2. `codigo` en Asuntos es único por cliente (formato NNN-NNN)
3. `numero` en Tickets es único (formato TKT-000001)
4. `estado` sigue el flujo: pendiente → aprobado/rechazado → completado
5. Soft deletes en clientes, asuntos y tickets (nunca se borran físicamente)