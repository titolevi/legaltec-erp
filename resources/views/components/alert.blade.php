@props(['type' => 'success', 'message' => null])

@php
$colors = [
    'success' => 'bg-green-100 dark:bg-green-900 border-green-400 dark:border-green-700 text-green-700 dark:text-green-300',
    'error' => 'bg-red-100 dark:bg-red-900 border-red-400 dark:border-red-700 text-red-700 dark:text-red-300',
    'warning' => 'bg-yellow-100 dark:bg-yellow-900 border-yellow-400 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300',
    'info' => 'bg-blue-100 dark:bg-blue-900 border-blue-400 dark:border-blue-700 text-blue-700 dark:text-blue-300',
];
$icons = [
    'success' => '✅',
    'error' => '❌',
    'warning' => '⚠️',
    'info' => 'ℹ️',
];
@endphp

@if($message ?? session('message'))
<div class="mb-4 p-4 border rounded-lg {{ $colors[$type] }}" role="alert">
    <div class="flex items-center">
        <span class="mr-2">{{ $icons[$type] }}</span>
        <span>{{ $message ?? session('message') }}</span>
    </div>
</div>
@endif