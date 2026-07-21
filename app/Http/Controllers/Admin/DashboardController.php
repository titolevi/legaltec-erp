<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tenant;
use App\Models\TenantInvoice;
use App\Models\TenantUsage;
use App\Models\SystemHealth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->esSuperAdmin(), 403);

        $stats = [
            'total_tenants' => Tenant::where('status', 'active')->count(),
            'total_users' => \App\Models\User::whereNotNull('tenant_id')->count(),
            'mrr_total' => TenantInvoice::where('estado', 'paid')->sum('monto'),
            'alertas_criticas' => SystemHealth::where('estado', 'critical')->count(),
            'tenants_recientes' => Tenant::latest()->take(5)->get(),
            'audit_logs' => AuditLog::recientes()->take(10)->with('user')->get(),
            'health_checks' => SystemHealth::where('created_at', '>', now()->subDay())
                ->orderBy('created_at', 'desc')->take(5)->get(),
            'usage_global' => TenantUsage::select(
                DB::raw('SUM(usuarios_activos) as total_usuarios'),
                DB::raw('SUM(tickets_creados) as total_tickets'),
                DB::raw('SUM(almacenamiento_mb) as total_storage'),
                DB::raw('SUM(api_calls) as total_api')
            )->whereMonth('periodo', now()->month)->first(),
        ];

        $tenants = Tenant::withCount('users')
            ->with(['modules' => function ($q) { $q->where('activo', true); }])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.dashboard', compact('stats', 'tenants'));
    }
}