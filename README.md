# Legaltec ERP

> **SaaS multi-tenant** para estudios de abogados — Gestión de cajas, facturación electrónica, clientes y asuntos.

[![Tests](https://github.com/titolevi/legaltec-erp/actions/workflows/tests.yml/badge.svg)](https://github.com/titolevi/legaltec-erp/actions/workflows/tests.yml)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-4.12-EA580C?logo=filament)](https://filamentphp.com)
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)

---

## Stack

| Componente | Tecnología |
|---|---|
| **Backend** | Laravel 13 + PHP 8.4 |
| **Panel Admin** | FilamentPHP 4.12 |
| **Frontend** | Livewire 4 + Tailwind CSS |
| **Base de datos** | MySQL (producción), SQLite (testing) |

## Módulos

- ✅ **Multi-tenant** por subdominio (`tenant.legaltec.pe`)
- ✅ **Tenants** — CRUD con slug auto-generado y subdominio
- ✅ **Usuarios** — Roles: super_admin, admin, autorizador, cajero, usuario
- ✅ **Cajas** — Con autorización, moneda USD/PEN, monto máximo por ticket
- ✅ **Solicitudes** — Flujo: usuario → autorizador → cajero
- 🟡 **Clientes y Asuntos** — En desarrollo
- 🔴 **Facturación SUNAT** — Pendiente
- 🔴 **Time Manager / Time Billing** — Pendiente
- 🔴 **NetDocuments** — Pendiente

## Arquitectura

```
legaltec.pe              → Super Admin
├── viera.legaltec.pe    → Viera Abogados
├── acb.legaltec.pe      → ACB Abogados
└── [cliente].legaltec.pe → Nuevo cliente
```

## Desarrollo local

```bash
git clone https://github.com/titolevi/legaltec-erp.git
cd legaltec-erp
composer install
cp .env.testing .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

## Tests

```bash
php artisan test
```

[![Tests](https://github.com/titolevi/legaltec-erp/actions/workflows/tests.yml/badge.svg)](https://github.com/titolevi/legaltec-erp/actions/workflows/tests.yml)

## Licencia

MIT — Legal Tecnologías