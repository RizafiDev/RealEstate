@extends('layouts.app')

@section('title', 'Unit Types')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Unit Types</h1>
            <p class="text-gray-600">Manage property unit types</p>
        </div>
        <a href="{{ route('unit-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i> Add Unit Type
        </a>
    </div>

    <!-- Coming soon placeholder -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6 text-center">
            <i class="fas fa-th-large text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Unit Types Management</h3>
            <p class="text-gray-500">This feature is under development.</p>
        </div>
    </div>
</div>
@endsection
