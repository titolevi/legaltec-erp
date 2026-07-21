# Legaltec ERP — Análisis: Módulo Cajas + Clientes/Asuntos

## 1. El Problema

Cada Tenant (estudio de abogados) maneja múltiples fuentes de dinero con propósitos distintos. Cada "Caja" tiene reglas de negocio, campos y flujos diferentes.

### Ejemplo: Tenant Viera Abogados

```
Tenant: Viera Abogados
├── 💰 Caja Maviescu     → Impresiones (empresa relacionada, RUC propio)
├── 💰 Caja Best Rent    → Movilidades (origen, destino, ida/vuelta, distrito)
└── 💰 Caja Viera        → Gastos generales (cubiertos con fondos de Viera)
```

---

## 2. Arquitectura Propuesta

### 2.0 Campos COMUNES a TODAS las Cajas

Antes de analizar los tipos, estos son los campos que **TODA caja comparte**:

| Campo | Tipo | Ejemplo |
|---|---|---|
| **Cliente** | Select (desde módulo Clientes) | 000001 - Viera Abogados |
| **Asunto** | Select (filtrado por cliente) | 000001-0001 - Relación con el cliente |
| **Descripción** | Texto (textarea) | "Traslado a Corte Superior para entrega de documentos" |
| **Facturable** | Boolean (Sí/No) | Sí |
| **Fecha diligencia** | Date | 21/07/2026 |
| **Usuario solicitante** | Auto (usuario logueado) | Juan Pérez |
| **Usuario autorizador** | Select (solo autorizadores de esta caja) | Santos Viamonte |
| **Divisa** | Select | PEN / USD |
| **Monto** | Decimal | 25.00 |
| **Tipo transacción** | Select | Efectivo / Transferencia |
| └ Si **Transferencia**: | | |
| ├ **Titular cuenta** | Texto | "María Tanta" |
| ├ **N° cuenta** | Texto | "191-1234567-0-00" |
| └ **Banco** | Texto | "Interbank" |

### 2.1 Modelo de Datos

```sql
TABLA: cajas
═══════════════════════════════════════════════════════
id                  PK
tenant_id           FK → tenants
nombre              VARCHAR(100)          -- "Caja Maviescu", "Caja Best Rent"
slug                VARCHAR(50)           -- "maviescu", "best-rent"
descripcion         TEXT                  -- Opcional
tipo                VARCHAR(50)           -- 'general', 'movilidad', 'impresion'
moneda              VARCHAR(3) DEFAULT 'PEN'
saldo_inicial       DECIMAL(10,2) DEFAULT 0
saldo_actual        DECIMAL(10,2) DEFAULT 0
color               VARCHAR(7) DEFAULT '#6366f1'
icono               VARCHAR(50) DEFAULT '💰'
activo              BOOLEAN DEFAULT 1
limite_aprobacion   DECIMAL(10,2)         -- Monto máximo sin aprobación extra
─── Timestamps y soft delete ───
created_at, updated_at, deleted_at

TABLA: tickets (expansión de columnas)
═══════════════════════════════════════════════════════
id                  PK
tenant_id           FK → tenants
─── CAMPOS COMUNES A TODAS LAS CAJAS ───
caja_id             FK → cajas
numero              VARCHAR(20) UNIQUE    -- "TKT-000001"
cliente_id          FK → clientes
asunto_id           FK → asuntos
codigo_asunto       VARCHAR(20)           -- "000001-0001"
descripcion         TEXT                  -- Descripción de la diligencia
facturable          BOOLEAN DEFAULT 1
fecha_diligencia    DATE
usuario_id          FK → users (solicitante)
autorizador_id      FK → users (quien aprueba)
divisa              VARCHAR(3) DEFAULT 'PEN'
monto               DECIMAL(10,2) DEFAULT 0
tipo_transaccion    VARCHAR(20)           -- 'efectivo', 'transferencia'
titular_cuenta      VARCHAR(255)          -- Solo si transferencia
numero_cuenta       VARCHAR(100)          -- Solo si transferencia
banco               VARCHAR(100)          -- Solo si transferencia
estado              ENUM('pendiente','aprobado','rechazado','pagado','completado')
─── CAMPOS EXTRA (JSON dinámico) ───
campos_extra        JSON                  -- {origen, destino, distrito_origen, distrito_destino, ida_vuelta...}
─── Timestamps ───
created_at, updated_at, deleted_at

TABLA: caja_autorizadores
═══════════════════════════════════════════════════════
caja_id             FK → cajas
user_id             FK → users
limite_aprobacion   DECIMAL(10,2)         -- Máximo que puede aprobar este usuario
created_at          DATETIME
UNIQUE(caja_id, user_id)

TABLA: caja_cajeros
═══════════════════════════════════════════════════════
caja_id             FK → cajas
user_id             FK → users
created_at          DATETIME
UNIQUE(caja_id, user_id)
```

### 2.2 Mapa visual de campos por tipo de Caja

```
CAJA GENERAL (ej: Caja Viera)
═══════════════════════════════════════════
 ✅ Cliente          ✅ Asunto
 ✅ Descripción      ✅ Facturable
 ✅ Fecha diligen.   ✅ Solicitante
 ✅ Autorizador      ✅ Divisa + Monto
 ✅ Tipo transacción → Si transferencia:
 │                    ├ Titular cuenta
 │                    ├ N° cuenta
 │                    └ Banco
 └──────────────────────────────────────
 ❌ NO: origen, destino, distritos, ida_vuelta


CAJA MOVILIDADES (ej: Caja Best Rent)
═══════════════════════════════════════════
 ✅ Todos los campos comunes (igual que arriba)
 ─── CAMPOS ADICIONALES ───
 ✅ Lugar de origen        → Texto libre
 ✅ Lugar de destino       → Texto libre
 ✅ Distrito de origen     → Auto (geolocalización API)
 ✅ Distrito de destino    → Auto (geolocalización API)
 ✅ ¿Ida y vuelta?         → Checkbox

 Ejemplo:
   Origen: Av. Pardo 123, Miraflores
   Destino: Corte Superior de Justicia, Lima
   Distrito origen: Miraflores       ← auto
   Distrito destino: Cercado de Lima  ← auto
   Ida y vuelta: ✅
```

### 2.4 Integración con Clientes/Asuntos

#### Tabla CLIENTES

| Campo | Tipo | Ejemplo |
|---|---|---|
| **Código** | VARCHAR(6) | "000001" |
| **Nombre** | VARCHAR(255) | "Viera Abogados" |
| **Contacto** | VARCHAR(255) | "Juan Pérez - jperez@viera.pe" |
| **RUC** | VARCHAR(11) | "20123456789" |
| **Dirección fiscal** | TEXT | "Av. Pardo 123, Miraflores" |
| **PO Box** | VARCHAR(50) | "PO Box 1234" |
| **Socio responsable** | VARCHAR(255) | "Dr. Carlos Viera" |
| **Abogado asignado** | VARCHAR(255) | "María Tanta" |
| **Activo** | BOOLEAN | Sí/No |
| **Auditoría** | created_at, updated_at, deleted_at | |

#### Tabla ASUNTOS

| Campo | Tipo | Ejemplo |
|---|---|---|
| **Código cliente** | FK → clientes.codigo | "000001" |
| **Código asunto** | VARCHAR(20) | "000001-0001" |
| **Nombre** | VARCHAR(255) | "Relación con el cliente" |
| **Abogado responsable** | VARCHAR(255) | "Santos Viamonte" |
| **ID Time Manager** | INT (nullable) | 3244 ← para API futura |
| **Activo** | BOOLEAN | Sí/No |
| **Auditoría** | created_at, updated_at, deleted_at | |

#### Relación

```
CLIENTES (código: "000001") ──1:N──► ASUNTOS (código_cliente: "000001")
                                        │
                                        ├── "000001-0001 - Relación con el cliente"
                                        ├── "000001-0002 - Consultoría"
                                        └── "000001-0003 - PE-Zelig CF.10-2023"
```

#### Visual en el ERP

```
┌─────────────────────────────────────────────────────────────────────┐
│  CLIENTES                                                 [+ Nuevo] │
├──────┬──────────────┬────────────┬──────────┬──────────┬───────────┤
│ Cód. │ Nombre       │ RUC        │ Socio    │ Abogado  │ Estado    │
├──────┼──────────────┼────────────┼──────────┼──────────┼───────────┤
│000001│ Viera Abog.  │20123456789 │C. Viera  │M. Tanta  │ ✅ Activo │
│      │              │            │          │          │           │
│      ├─ ASUNTOS     │            │          │          │           │
│      │ 0001 Relación cliente    │ C. Viera │ ✅ Activo │           │
│      │ 0002 Consultoría         │ S. Viam. │ ✅ Activo │           │
│      │ 0003 PE-Zelig CF.10-2023 │ S. Viam. │ ✅ Activo │           │
└──────┴──────────────┴────────────┴──────────┴──────────┴───────────┘
```

### 2.5 Precios y Planes

```
Plan Starter (S/ 199/mes)
├── Hasta 2 Cajas por tenant
├── Tipos: general, movilidad
└── 10 usuarios max

Plan Professional (S/ 399/mes)
├── Hasta 5 Cajas por tenant
├── Tipos: todos (general, movilidad, impresion, personalizado)
├── Campos personalizados
└── 25 usuarios max

Plan Enterprise (S/ 799/mes)
├── Cajas ilimitadas
├── Todos los tipos + personalizados
├── API Time Manager
├── 100 usuarios max
└── Soporte prioritario
```

---

## 3. Flujo de Creación de un Ticket en una Caja

```
Usuario selecciona:
1. Cliente ────────► Se cargan sus Asuntos
2. Asunto ─────────► Se carga código + nombre
3. Caja ───────────► Se cargan los campos específicos de esa caja
4. Llena campos ───► Según el tipo de caja (movilidad → origen/destino)
5. Selecciona autorizador ► Solo los asignados a esa caja
6. Envía ──────────► Pendiente de aprobación
```

### Ejemplo: Ticket en Caja Best Rent (movilidad)

```
Cliente: 000001 - Viera Abogados
Asunto: 000001-0002 - Consultoría
Caja: Best Rent
Origen: Av. Pardo 123, Miraflores
Destino: Corte Superior de Lima, Centro
Ida y vuelta: ✅ Sí
Institución: Poder Judicial
Distrito: Lima (auto-completado desde la dirección)
Monto: S/ 25.00
Autorizador: Santos Viamonte
```

---

## 4. API Time Manager (Futuro)

### Webhook / Endpoint sugerido

```http
POST /api/v1/sync/cliente
{
  "accion": "crear|actualizar|desactivar",
  "codigo": "000001",
  "nombre": "Viera Abogados",
  "id_time_manager": 123
}

POST /api/v1/sync/asunto
{
  "accion": "crear|actualizar|desactivar",
  "codigo": "000001-0001",
  "nombre": "Relación con el cliente",
  "cliente_codigo": "000001",
  "id_time_manager": 3244
}
```

### Sync automático (cuando tengan la API)
- Cada N minutos: consultar cambios en Time Manager
- Insertar/actualizar/desactivar clientes y asuntos en el ERP
- Log de sincronización para auditoría

---

## 5. Recomendación de Implementación

### Fase 1: Base (Mínimo Viable)
```
✅ Tabla cajas con tipos predefinidos
✅ CRUD de Cajas (solo admin del tenant)
✅ Asignación de autorizadores y cajeros por caja
✅ Tickets vinculados a una caja
✅ CRUD de Clientes y Asuntos
```

### Fase 2: Campos personalizados
```
✅ Tabla caja_campos_personalizados
✅ Tipos dinámicos de caja
✅ Auto-completado de distrito desde dirección
```

### Fase 3: Monetización
```
✅ Límite de cajas por plan
✅ Upgrade/downgrade de plan afecta cantidad de cajas
✅ Módulo de facturación SaaS
```

### Fase 4: API Time Manager
```
✅ Endpoints de sincronización
✅ Webhook para cambios en tiempo real
✅ Log de sincronización
```