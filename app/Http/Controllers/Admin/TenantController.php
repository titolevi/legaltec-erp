1|<?php
2|
3|namespace App\Http\Controllers\Admin;
4|
5|use App\Http\Controllers\Controller;
6|use App\Models\AuditLog;
7|use App\Models\Tenant;
8|use App\Models\User;
9|use Illuminate\Http\Request;
10|
11|class TenantController extends Controller
12|{
13|    public function index()
14|    {
16|        $tenants = Tenant::withCount('users')
17|            ->with(['modules' => function ($q) { $q->where('activo', true); }])
18|            ->orderBy('name')->paginate(20);
19|        return view('admin.tenants.index', compact('tenants'));
20|    }
21|
22|    public function create()
23|    {
25|        return view('admin.tenants.create');
26|    }
27|
28|    public function store(Request $request)
29|    {
31|        $data = $request->validate([
32|            'name' => 'required|string|max:255',
33|            'slug' => 'required|string|max:50|unique:tenants,slug|alpha_dash',
34|            'ruc' => 'nullable|string|size:11|unique:tenants,ruc',
35|            'email' => 'nullable|email|max:255',
36|        ]);
37|
38|        $tenant = Tenant::create($data);
39|
40|        return redirect()->route('admin.tenants')
41|            ->with('message', "✅ Tenant {$tenant->name} creado exitosamente.");
42|    }
43|
44|    public function edit(Tenant $tenant)
45|    {
47|        return view('admin.tenants.edit', compact('tenant'));
48|    }
49|
50|    public function update(Request $request, Tenant $tenant)
51|    {
53|        $data = $request->validate([
54|            'name' => 'required|string|max:255',
55|            'slug' => 'required|string|max:50|alpha_dash|unique:tenants,slug,' . $tenant->id,
56|            'ruc' => 'nullable|string|size:11|unique:tenants,ruc,' . $tenant->id,
57|            'email' => 'nullable|email|max:255',
58|            'status' => 'required|in:active,suspended,trial,cancelled',
59|            'plan' => 'required|in:trial,starter,professional,enterprise',
60|        ]);
61|
62|        $tenant->update($data);
63|
64|        return redirect()->route('admin.tenants')
65|            ->with('message', "✅ Tenant {$tenant->name} actualizado.");
66|    }
67|
68|    public function destroy(Tenant $tenant)
69|    {
71|        $tenant->update(['activo' => false]);
72|        return redirect()->route('admin.tenants')
73|            ->with('message', "⛔ Tenant {$tenant->name} desactivado.");
74|    }
75|
76|    public function enter(Tenant $tenant)
77|    {
79|
80|        AuditLog::create([
81|            'tenant_id' => $tenant->id,
82|            'user_id' => auth()->id(),
83|            'accion' => 'impersonacion.entrar',
84|            'descripcion' => "Admin entró al tenant: {$tenant->name}",
85|            'ip_address' => request()->ip(),
86|            'user_agent' => request()->userAgent(),
87|            'metadata' => [
88|                'tenant_name' => $tenant->name,
89|                'tenant_slug' => $tenant->slug,
90|            ],
91|        ]);
92|
93|        session(['impersonating_tenant_id' => $tenant->id]);
94|        return redirect()->route('admin.dashboard')
95|            ->with('message', "🔀 Has entrado a: {$tenant->name}");
96|    }
97|
98|    public function exit()
99|    {
101|
102|        $tenantId = session('impersonating_tenant_id');
103|        if ($tenantId) {
104|            $tenant = Tenant::find($tenantId);
105|            AuditLog::create([
106|                'tenant_id' => $tenantId,
107|                'user_id' => auth()->id(),
108|                'accion' => 'impersonacion.salir',
109|                'descripcion' => "Admin salió del tenant: " . ($tenant->name ?? 'N/A'),
110|                'ip_address' => request()->ip(),
111|                'user_agent' => request()->userAgent(),
112|            ]);
113|        }
114|
115|        session()->forget('impersonating_tenant_id');
116|        return redirect()->route('admin.tenants')
117|            ->with('message', "🔙 Has vuelto al panel global de Legaltec.");
118|    }
119|
120|    private function authorizeAdmin(): void
121|    {
122|        abort_unless(auth()->check() && auth()->user()->esSuperAdmin(), 403);
123|    }
124|}