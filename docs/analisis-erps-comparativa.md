# Comparativa de Repositorios ERP — Análisis para Legaltec

## 1. Aureus ERP ⭐ (11,553 stars) — RECOMENDADO
**https://github.com/aureuserp/aureuserp**

| Aspecto | Detalle |
|---|---|
| **Stack** | **Laravel 13 + Livewire 4 + FilamentPHP 5** ✅ **IGUAL QUE LEGALTEC** |
| **PHP** | ^8.3 ✅ |
| **Licencia** | MIT |
| **Módulos** | Accounting, HRM, Inventory, Manufacturing, Sales, Purchase, PMS, Maintenance |
| **Estado** | Activo (último push: hoy) |
| **Valor** | ⭐⭐⭐⭐⭐ **Misma tecnología, se puede integrar o tomar módulos** |

## 2. LiteERP ⭐ (28 stars)
**https://github.com/liteerp-oss/liteerp**

| Aspecto | Detalle |
|---|---|
| **Stack** | Laravel + React (frontend separado) |
| **Arquitectura** | Clean Architecture + DDD |
| **Licencia** | MIT |
| **Módulos** | Accounting, Sales, Purchase |
| **Valor** | ⭐⭐ Interesante por su arquitectura limpia pero usa React, no Livewire |

## 3. Kelvzxu ERP ⭐ (187 stars)
**https://github.com/kelvzxu/erp_laravel**

| Aspecto | Detalle |
|---|---|
| **Stack** | Laravel + Vue (frontend separado) |
| **Licencia** | GPL-3.0 |
| **Módulos** | eCommerce, Warehouse, Billing, POS, HR |
| **Valor** | ⭐ Poco mantenido (último push 2024). Usa Vue, no Livewire |

## 4. LaraERP ⭐ (173 stars)
**https://github.com/laraerp/laraerp**

| Aspecto | Detalle |
|---|---|
| **Stack** | Laravel 5.x (obsoleto) |
| **Licencia** | ? |
| **Estado** | Abandonado (último push 2022) |
| **Valor** | ⭐ No recomendado. Tecnología obsoleta |

## 5. Reishandy Laravel-ERP ⭐ (6 stars)
**https://github.com/Reishandy/Laravel-ERP**

| Aspecto | Detalle |
|---|---|
| **Stack** | Laravel 12 + React + Inertia |
| **Licencia** | AGPL-3.0 |
| **Estado** | Muy nuevo, pocas estrellas |
| **Valor** | ⭐ Proyecto muy pequeño, poca comunidad |

---

## 📊 Cuadro Comparativo

| Proyecto | Stars | Stack | Licencia | Activo | Mismo stack que Legaltec? | Recomendado |
|---|---|---|---|---|---|---|
| **Aureus ERP** | ⭐ 11,553 | Laravel 13 + Livewire 4 + Filament | MIT | ✅ Sí | ✅ **IDÉNTICO** | **🔥 SÍ** |
| LiteERP | ⭐ 28 | Laravel + React | MIT | ⚠️ Parcial | ❌ React | ⚠️ Parcial |
| Kelvzxu ERP | ⭐ 187 | Laravel + Vue | GPL-3.0 | ❌ No | ❌ Vue | ❌ |
| LaraERP | ⭐ 173 | Laravel 5 | ? | ❌ Abandonado | ❌ Obsoleto | ❌ |
| Reishandy ERP | ⭐ 6 | Laravel 12 + React | AGPL | ⚠️ Nuevo | ❌ React | ❌ |

---

## 🏆 Mi recomendación: AUREUS ERP

**Aureus ERP es el único que usa EXACTAMENTE el mismo stack que Legaltec:**

| Tecnología | Legaltec ERP | Aureus ERP |
|---|---|---|
| **Laravel** | 13 ✅ | 13 ✅ |
| **Livewire** | 4 ✅ | 4 ✅ |
| **PHP** | 8.4 ✅ | 8.3+ ✅ |
| **Admin Panel** | Tailwind + Livewire | **FilamentPHP 5** |
| **BD** | MySQL/SQLite | MySQL |

### ¿Qué podemos aprovechar de Aureus?

1. **Módulo de Contabilidad** — Facturación, libros contables
2. **Módulo de Inventario** — Gestión de productos/stock
3. **Módulo de Compras** — Órdenes de compra, proveedores
4. **Arquitectura Filament** — Panel admin profesional (ellos usan Filament, nosotros podríamos migrar)

### Diferencia clave

**Aureus usa FilamentPHP 5** como panel de administración, nosotros usamos Livewire puro + Tailwind. Filament está construido sobre Livewire, así que es **compatible**. Podríamos:

- **Opción A:** Tomar módulos específicos de Aureus e integrarlos
- **Opción B:** Usar Filament como base para el panel admin de Legaltec
- **Opción C:** Solo inspirarnos en su diseño y estructura de datos

Si quieres, cuando vuelvas exploramos el repositorio de Aureus ERP más a fondo. 😊