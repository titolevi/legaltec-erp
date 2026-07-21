<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Caja;
use App\Models\User;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    public function index()
    {
        $cajas = Caja::withCount('tickets')
            ->with(['autorizadores', 'cajeros'])
            ->orderBy('nombre')
            ->paginate(20);
        return view('admin.cajas.index', compact('cajas'));
    }

    public function create()
    {
        $usuarios = User::where('tenant_id', auth()->user()->tenant_id)
            ->where('activo', true)
            ->orderBy('name')
            ->get();
        return view('admin.cajas.create', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'slug' => 'required|string|max:50|alpha_dash|unique:cajas,slug,NULL,id,tenant_id,' . auth()->user()->tenant_id,
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:general,movilidad',
            'moneda' => 'required|in:PEN,USD',
            'monto_maximo' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
            'icono' => 'nullable|string|max:50',
            'autorizadores' => 'nullable|array',
            'autorizadores.*' => 'exists:users,id',
            'cajeros' => 'nullable|array',
            'cajeros.*' => 'exists:users,id',
        ]);

        $data['tenant_id'] = auth()->user()->tenant_id;
        $caja = Caja::create($data);

        if ($request->has('autorizadores')) {
            $caja->autorizadores()->sync($request->autorizadores);
        }
        if ($request->has('cajeros')) {
            $caja->cajeros()->sync($request->cajeros);
        }

        return redirect()->route('admin.cajas')
            ->with('message', "✅ Caja {$caja->nombre} creada exitosamente.");
    }

    public function edit(Caja $caja)
    {
        $usuarios = User::where('tenant_id', $caja->tenant_id)
            ->where('activo', true)
            ->orderBy('name')
            ->get();
        return view('admin.cajas.edit', compact('caja', 'usuarios'));
    }

    public function update(Request $request, Caja $caja)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'slug' => 'required|string|max:50|alpha_dash|unique:cajas,slug,' . $caja->id . ',id,tenant_id,' . $caja->tenant_id,
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:general,movilidad',
            'moneda' => 'required|in:PEN,USD',
            'monto_maximo' => 'nullable|numeric|min:0',
            'color' => 'nullable|string|max:7',
            'icono' => 'nullable|string|max:50',
            'activo' => 'boolean',
            'autorizadores' => 'nullable|array',
            'autorizadores.*' => 'exists:users,id',
            'cajeros' => 'nullable|array',
            'cajeros.*' => 'exists:users,id',
        ]);

        $caja->update($data);

        if ($request->has('autorizadores')) {
            $caja->autorizadores()->sync($request->autorizadores);
        }
        if ($request->has('cajeros')) {
            $caja->cajeros()->sync($request->cajeros);
        }

        return redirect()->route('admin.cajas')
            ->with('message', "✅ Caja {$caja->nombre} actualizada.");
    }

    public function destroy(Caja $caja)
    {
        $caja->update(['activo' => false]);
        return redirect()->route('admin.cajas')
            ->with('message', "⛔ Caja {$caja->nombre} desactivada.");
    }
}