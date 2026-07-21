@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">📊 Dashboard</h1>
        <p class="text-gray-500">Sistema de Tickets Legaltec ERP</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-yellow-500">{{ $stats['pendientes'] }}</div>
            <div class="text-sm text-gray-500">Pendientes</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-green-500">{{ $stats['aprobados'] }}</div>
            <div class="text-sm text-gray-500">Aprobados</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-blue-500">{{ $stats['total_mes'] }}</div>
            <div class="text-sm text-gray-500">Este mes</div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-indigo-500">{{ $stats['clientes'] }}</div>
            <div class="text-sm text-gray-500">Clientes activos</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('tickets.crear') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition text-center">
            <div class="text-4xl mb-2">➕</div>
            <div class="font-semibold">Nuevo Ticket</div>
            <div class="text-sm text-gray-500">Crear solicitud</div>
        </a>
        <a href="{{ route('tickets.aprobar') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition text-center">
            <div class="text-4xl mb-2">✅</div>
            <div class="font-semibold">Aprobar Tickets</div>
            <div class="text-sm text-gray-500">Revisar solicitudes</div>
        </a>
        <a href="{{ route('tickets.cajero') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition text-center">
            <div class="text-4xl mb-2">💰</div>
            <div class="font-semibold">Panel Cajero</div>
            <div class="text-sm text-gray-500">Ver todas las solicitudes</div>
        </a>
    </div>
@endsection