<div id="sidebar"
    class="fixed inset-y-0 left-0 w-64 md:w-80 bg-[#2196F3] text-white transform -translate-x-full md:translate-x-0 transition-all duration-300 z-50 flex flex-col h-full">

    @php
        $isAdmin = Auth::check() && Auth::user()->role === 'Admin';
        $reportRoute = $isAdmin ? route('reports.index') : route('reports.vendor');
        $isActive = Route::is('reports.index') || Route::is('reports.vendor');
    @endphp

    <!-- Toggle Button -->
    <button onclick="toggleSidebar()"
        class="absolute -right-3 top-4 bg-white rounded-full shadow-md h-6 w-6 flex items-center justify-center text-gray-500 md:hidden">
        <svg id="toggleIcon" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
        </svg>
    </button>

    <!-- Sidebar Header -->
    <div class="p-4 flex items-center gap-3 border-b border-blue-700">
        <span class="font-semibold text-lg md:text-xl">
            {{ Auth::check() && Auth::user()->role === 'Admin' ? 'ADMIN' : 'VENDOR' }} PORTAL
        </span>
    </div>

    <!-- Scrollable Navigation Section -->
    <nav class="flex-1 overflow-y-auto py-4 space-y-2">
        <a href="{{ route('dashboard') }}">
            <div
                class="px-4 py-2 flex items-center gap-2 hover:bg-blue-800 {{ Route::currentRouteName() === 'dashboard' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M5 12h14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M12 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <span class="text-sm md:text-base">Dashboard</span>
            </div>
        </a>
        @if (Auth::user()->role === 'Admin')
            <a href="{{ route('vehicles.index') }}">
                <div
                    class="px-4 py-2 flex items-center gap-2 cursor-pointer hover:bg-blue-800 {{ Route::currentRouteName() === 'vehicles.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-sm md:text-base">Vehicle Inventory</span>
                </div>
            </a>
            <a href="{{ route('contracts.admin') }}">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors {{ Route::currentRouteName() === 'contracts.admin' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base">Contract Management</span>
                </div>
            </a>

            <a href="{{ route('proposals.admin') }}">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors {{ Route::currentRouteName() === 'proposals.admin' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base">Proposal Management</span>
                </div>
            </a>
            <a href="{{ route('maintenance.index') }}">
                <div
                    class="px-4 py-2 flex items-center gap-2 cursor-pointer hover:bg-blue-800 {{ Route::currentRouteName() === 'maintenance.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="12" cy="7" r="4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-sm md:text-base">Maintenance Management</span>
                </div>
            </a>
            <a href="{{ route('billings.index') }}">
                <div
                    class="px-4 py-2 flex items-center gap-2 cursor-pointer hover:bg-blue-800 {{ Route::currentRouteName() === 'billings.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-sm md:text-base">Billing and Invoicing</span>
                </div>
            </a>
            <a href="{{ route('reports.index') }}">
                <div
                    class="px-4 py-2 flex items-center gap-2 cursor-pointer hover:bg-blue-800 {{ Route::currentRouteName() === 'reports.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-sm md:text-base">Reports</span>
                </div>
            </a>
        @elseif(Auth::user()->role === 'Vendor')
            <a href="{{ route('contracts.index') }}">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors {{ Route::currentRouteName() === 'contracts.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M3 7h18M3 12h18M3 17h18" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Contract</span>
                </div>
            </a>
            <a href="{{ route('profile.create') }}">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors {{ Route::currentRouteName() === 'profile.create' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="12" cy="7" r="4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Profile</span>
                </div>
            </a>
            <a href="{{ route('proposals.index') }}" aria-label="Proposal Submission">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors {{ Route::currentRouteName() === 'proposals.index' || Route::currentRouteName() === 'proposals.approved' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate max-w-[150px] sm:max-w-[200px]">
                        Proposal Submission
                    </span>
                </div>
            </a>
            <a href="{{ route('compliance.index') }}">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors {{ Route::currentRouteName() === 'compliance.index' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Compliance & Training</span>
                </div>
            </a>
            <a href="{{ route('reports.vendor') }}">
                <div
                    class="px-3 sm:px-4 py-2 flex items-center gap-2 sm:gap-3 cursor-pointer hover:bg-blue-800 transition-colors {{ Route::currentRouteName() === 'reports.vendor' ? 'bg-blue-100 text-blue-800 hover:text-white' : '' }}">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <polyline points="7 10 12 15 17 10" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <line x1="12" y1="15" x2="12" y2="3" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="text-xs sm:text-sm md:text-base font-medium truncate">Write a review</span>
                </div>
            </a>
        @endif
        <div class="mt-4 px-4 py-2 text-white text-sm md:text-base">
            Notifications & Reminders
        </div>
    </nav>

    <!-- Profile Section (Fixed at Bottom) -->
    @if ($isAdmin)
        <div class="p-4 border-t border-blue-700 bg-blue-600">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 font-semibold">
                    {{ Auth::user()->name ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm md:text-base font-medium truncate">{{ Auth::user()->name ?? 'User' }}</p>
                    <p class="text-xs md:text-sm text-blue-200">{{ Auth::user()->role }}</p>
                </div>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-blue-200 hover:text-white transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
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
