<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
</head>
<body class="h-full">
    <!-- Sidebar/Navigation -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div class="flex min-h-0 flex-1 flex-col bg-indigo-700">
                <div class="flex h-16 flex-shrink-0 items-center px-4 bg-indigo-800">
                    <h1 class="text-lg font-bold text-white">{{ config('app.name') }}</h1>
                </div>
                <div class="flex flex-1 flex-col overflow-y-auto">
                    <nav class="flex-1 space-y-1 px-2 py-4">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="@if(Request::is('dashboard')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-tachometer-alt mr-3 flex-shrink-0"></i>
                            Dashboard
                        </a>

                        <!-- Projects -->
                        <a href="{{ route('projects.index') }}" class="@if(Request::is('projects*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-building mr-3 flex-shrink-0"></i>
                            Projects
                        </a>

                        <!-- Units -->
                        <a href="{{ route('units.index') }}" class="@if(Request::is('units*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-home mr-3 flex-shrink-0"></i>
                            Units
                        </a>

                        <!-- Unit Types -->
                        <a href="{{ route('unit-types.index') }}" class="@if(Request::is('unit-types*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-th-large mr-3 flex-shrink-0"></i>
                            Unit Types
                        </a>

                        <!-- Developers -->
                        <a href="{{ route('developers.index') }}" class="@if(Request::is('developers*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-building mr-3 flex-shrink-0"></i>
                            Developers
                        </a>

                        <!-- Locations -->
                        <a href="{{ route('locations.index') }}" class="@if(Request::is('locations*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-map-marker-alt mr-3 flex-shrink-0"></i>
                            Locations
                        </a>

                        <!-- Leads -->
                        <a href="{{ route('leads.index') }}" class="@if(Request::is('leads*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-users mr-3 flex-shrink-0"></i>
                            Leads
                        </a>

                        <!-- Bookings -->
                        <a href="{{ route('bookings.index') }}" class="@if(Request::is('bookings*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-calendar-check mr-3 flex-shrink-0"></i>
                            Bookings
                        </a>

                        <!-- Customers -->
                        <a href="{{ route('customers.index') }}" class="@if(Request::is('customers*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-address-book mr-3 flex-shrink-0"></i>
                            Customers
                        </a>

                        <!-- Reports -->
                        <a href="{{ route('reports.index') }}" class="@if(Request::is('reports*')) bg-indigo-800 text-white @else text-indigo-100 hover:bg-indigo-600 @endif group flex items-center px-2 py-2 text-sm font-medium rounded-md">
                            <i class="fas fa-chart-bar mr-3 flex-shrink-0"></i>
                            Reports
                        </a>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Mobile top navigation -->
            <div class="md:hidden bg-indigo-700">
                <div class="flex items-center justify-between px-4 py-2">
                    <h1 class="text-lg font-bold text-white">{{ config('app.name') }}</h1>
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile sidebar (hidden by default) -->
            <div x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" class="md:hidden bg-indigo-700">
                <nav class="space-y-1 px-2 pb-3 pt-2">
                    <!-- Mobile menu items same as sidebar -->
                </nav>
            </div>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto p-4">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    @stack('scripts')
</body>
</html>