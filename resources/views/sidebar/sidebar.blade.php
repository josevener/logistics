<div id="sidebar"
    class="fixed inset-y-0 left-0 w-64 md:w-80 bg-[#2196F3] text-white transform -translate-x-full md:translate-x-0 transition-all duration-300 z-50 flex flex-col h-full font-sans">

    @php
        $isAdmin = Auth::check() && Auth::user()->role === 'Admin';
        $reportRoute = $isAdmin ? route('reports.index') : route('reports.vendor');
        $isActive = Route::is('reports.index') || Route::is('reports.vendor');
    @endphp

    <!-- Toggle Button -->
    <button onclick="toggleSidebar()"
        class="absolute -right-3 top-4 bg-white rounded-full shadow-md h-6 w-6 flex items-center justify-center text-gray-500 md:hidden">
        <svg id="toggleIcon" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>

    <!-- Sidebar Header -->
    <div class="p-4 flex items-center gap-3 border-b border-blue-700">
        <span class="font-semibold text-xl md:text-2xl tracking-tight">
            {{ Auth::check() && Auth::user()->role === 'Admin' ? 'ADMIN' : 'VENDOR' }} PORTAL
        </span>
    </div>

    <!-- Scrollable Navigation Section -->
    <nav class="flex-1 overflow-y-auto py-4 space-y-1">
        <a href="{{ route('dashboard') }}">
            <div
                class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'dashboard' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-base md:text-lg font-medium">Dashboard</span>
            </div>
        </a>

        @if (Auth::user()->role === 'Admin')
            <a href="{{ route('vehicles.index') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'vehicles.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17V7m0 10h6m-6 0a2 2 0 110-4 2 2 0 010 4zm6 0a2 2 0 110-4 2 2 0 010 4zM5 7h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium">Vehicle Inventory</span>
                </div>
            </a>
            <a href="{{ route('contracts.admin') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'contracts.admin' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium">Contract Management</span>
                </div>
            </a>
            <a href="{{ route('proposals.admin') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'proposals.admin' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9l-5-5H7a2 2 0 00-2 2v13a2 2 0 002 2zM13 4v5h5" />
                    </svg>
                    <span class="text-base md:text-lg font-medium">Proposal Management</span>
                </div>
            </a>
            <a href="{{ route('maintenance.index') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'maintenance.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37a1.724 1.724 0 002.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium">Maintenance Management</span>
                </div>
            </a>
            <a href="{{ route('billings.index') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'billings.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium">Billing and Invoicing</span>
                </div>
            </a>
            <a href="{{ route('reports.index') }}">
                <div
<<<<<<< HEAD
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'reports.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
=======
                    class="px-4 py-2 flex items-center gap-2 cursor-pointer hover:bg-blue-800 {{ Route::currentRouteName() === 'reports.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 3h18v18H3z" />
                        <path d="M7 10h10M7 14h7M7 6h10" />
>>>>>>> 85d74f093ebe507819e51f78d21083dbfacb0184
                    </svg>
                    <span class="text-base md:text-lg font-medium">Reports</span>
                </div>
            </a>
        @elseif(Auth::user()->role === 'Vendor')
            <a href="{{ route('contracts.index') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'contracts.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium truncate">Contract</span>
                </div>
            </a>
            <a href="{{ route('profile.create') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'profile.create' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium truncate">Profile</span>
                </div>
            </a>
            <a href="{{ route('proposals.index') }}" aria-label="Proposal Submission">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'proposals.index' || Route::currentRouteName() === 'proposals.approved' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium truncate max-w-[150px] md:max-w-[200px]">Proposal
                        Submission</span>
                </div>
            </a>
            <a href="{{ route('compliance.index') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'compliance.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium truncate">Compliance & Training</span>
                </div>
            </a>
            <a href="{{ route('reports.vendor') }}">
                <div
                    class="px-4 py-3 flex items-center gap-3 hover:bg-blue-800 {{ Route::currentRouteName() === 'reports.vendor' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <span class="text-base md:text-lg font-medium truncate">Write a Review</span>
                </div>
            </a>
        @endif

        <div class="mt-4 px-4 py-3 text-white text-base md:text-lg font-semibold">
            Notifications & Reminders
        </div>


        <a href="{{ route('profile.edit') }}">
            <div
                class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors
            {{ Route::currentRouteName() === 'profile.edit' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">

                <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"></circle>
                    <path
                        d="M21 12h-2m-2.64 5.36l-1.42-1.42M12 21v-2m-5.36-2.64l1.42-1.42M3 12h2m2.64-5.36l1.42 1.42M12 3v2m5.36 2.64l-1.42 1.42">
                    </path>
                </svg>

                <span class="text-xs sm:text-sm md:text-base font-medium truncate">Profile Setting</span>
            </div>

        </a>

    </nav>




    <!-- Profile Section (Fixed at Bottom) -->
    @if ($isAdmin)
        <div class="p-4 border-t border-blue-700 bg-blue-600">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 font-semibold text-lg">
                    {{ Auth::user()->name ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-base md:text-lg font-medium truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-sm md:text-base text-blue-200">{{ Auth::user()->role }}</p>
                </div>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-blue-200 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </a>
            </div>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    @endif
</div>
