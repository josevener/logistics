<x-app-layout>
    <div class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <!-- Header -->
        @include('navigation.header')

        <!-- Alerts -->
        <div class="space-y-4 mb-6">
            <div id="alert_box_success" role="alert"
                class="hidden w-full sm:w-1/3 bg-green-700 border-l-4 border-green-500 text-white p-4 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 cursor-pointer">
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 mr-2 text-green-300" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p id="success_alert_message" class="text-sm font-semibold">Success - Everything went smoothly!</p>
                </div>
            </div>
            <div id="alert_box_warning" role="alert"
                class="hidden w-full sm:w-1/3 bg-yellow-700 border-l-4 border-yellow-500 text-white p-4 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 cursor-pointer">
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 mr-2 text-yellow-300" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-semibold">Warning - You cannot set a previous date.</p>
                </div>
            </div>
            <div id="alert_box_error" role="alert"
                class="hidden w-full sm:w-1/3 bg-red-700 border-l-4 border-red-500 text-white p-4 rounded-lg transition-all duration-300 ease-in-out transform hover:scale-105 cursor-pointer">
                <div class="flex items-center">
                    <svg class="h-5 w-5 flex-shrink-0 mr-2 text-red-300" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-sm font-semibold">Error - Something went wrong.</p>
                </div>
            </div>
        </div>

        <!-- Main Grid Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Register Company Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Register Your Company</h2>
                <form class="space-y-5" action="{{ route('proposals.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div>
                        <label for="company_info" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Company Information
                        </label>
                        <input type="text" id="company_info" name="company_info" value="{{ old('company_info') }}"
                            @class([
                                'mt-1 block w-full rounded-md border py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-400',
                                'border-gray-300 dark:border-gray-600' => !$errors->has('company_info'),
                                'border-red-500' => $errors->has('company_info'),
                            ]) required placeholder="Enter company name and details" />
                        @error('company_info')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Contact Details
                        </label>
                        <input type="text" id="contact_details" name="contact_details"
                            value="{{ old('contact_details') }}" @class([
                                'mt-1 block w-full rounded-md border py-2 px-3 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200 dark:placeholder-gray-400',
                                'border-gray-300 dark:border-gray-600' => !$errors->has('contact_details'),
                                'border-red-500' => $errors->has('contact_details'),
                            ]) required
                            placeholder="e.g., email or phone" />
                        @error('contact_details')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="documentation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Documentation
                        </label>
                        <input type="file" id="documentation" name="documentation" @class([
                            'mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-gray-700 dark:file:text-gray-200',
                            'border-gray-300 dark:border-gray-600' => !$errors->has('documentation'),
                            'border-red-500' => $errors->has('documentation'),
                        ])
                            accept=".pdf,.doc,.docx" required />
                        @error('documentation')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-colors dark:bg-blue-500 dark:hover:bg-blue-600">
                        Register Company
                    </button>
                </form>
            </div>

            <!-- Proposal Submitted Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                    <p class="hover:underline">
                        Proposal Submitted >
                    </p>
                </h2>
                <div class="space-y-6">
                    @forelse ($proposals as $proposal)
                        <div
                            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                            <div class="flex justify-between items-start">
                                <h3 class="font-bold text-xl text-gray-800 dark:text-gray-100 mb-2">
                                    {{ $proposal->user->name }}
                                </h3>
                                @php
                                    $statusClasses =
                                        $proposal->admin_status === 'approved'
                                            ? 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300'
                                            : ($proposal->admin_status === 'rejected'
                                                ? 'bg-red-100 text-red-600 dark:bg-red-900 dark:text-red-300'
                                                : 'bg-yellow-100 text-yellow-600 dark:bg-yellow-600 dark:text-yellow-200');
                                    $statusText = ucfirst($proposal->admin_status ?? 'pending');
                                @endphp
                                <span class="px-3 py-1 {{ $statusClasses }} rounded-full text-sm font-medium">
                                    {{ $statusText }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                <p>Email: {{ $proposal->user->email }}</p>
                                <p>Date: {{ $proposal->created_at->format('Y-m-d (l)') }}</p>
                                <p>Time: {{ $proposal->created_at->format('H:i') }}</p>
                                <p class="mt-2">Purpose: {{ $proposal->purpose }}</p>
                                <p class="mt-2">
                                    @if ($proposal->admin_status === 'approved')
                                        Approved by: {{ $proposal->actioned_by ?? 'N/A' }}
                                    @elseif ($proposal->status === 'rejected')
                                        Rejected by: {{ $proposal->actioned_by ?? 'N/A' }}
                                    @endif
                                </p>
                                <p class="mt-2">
                                    <a href="{{ route('proposals.preview', $proposal->id) }}" target="_blank"
                                        class="text-blue-600 hover:underline dark:text-blue-400">
                                        View File
                                    </a>
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 dark:text-gray-400 py-6">
                            No proposals submitted yet.
                        </div>
                    @endforelse
                </div>
                <!-- Pagination -->
                @if ($proposals->hasPages())
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $proposals->firstItem() }} to {{ $proposals->lastItem() }} of
                            {{ $proposals->total() }} proposals
                        </div>
                        <div class="flex gap-2">
                            {{ $proposals->links('pagination::tailwind') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alert Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function showAlert(type, message = null) {
                const alerts = {
                    success: document.getElementById('alert_box_success'),
                    warning: document.getElementById('alert_box_warning'),
                    error: document.getElementById('alert_box_error')
                };
                Object.values(alerts).forEach(alert => {
                    alert.classList.add('hidden');
                    alert.classList.remove('opacity-100', 'translate-y-0');
                });
                const targetAlert = alerts[type];
                if (message && type === 'success') {
                    document.getElementById('success_alert_message').textContent = message;
                }
                targetAlert.classList.remove('hidden');
                targetAlert.classList.add('opacity-100', 'translate-y-0');
                targetAlert.addEventListener('click', () => {
                    targetAlert.classList.remove('opacity-100');
                    targetAlert.classList.add('opacity-0', 'translate-y-4');
                    setTimeout(() => targetAlert.classList.add('hidden'), 300);
                }, {
                    once: true
                });
                setTimeout(() => {
                    targetAlert.classList.remove('opacity-100');
                    targetAlert.classList.add('opacity-0', 'translate-y-4');
                    setTimeout(() => targetAlert.classList.add('hidden'), 300);
                }, 5000);
            }

            @if (session('message'))
                setTimeout(() => showAlert('success', '{{ session('message') }}'), 100);
            @endif

            document.querySelectorAll('[role="alert"]').forEach(alert => {
                alert.addEventListener('click', () => {
                    alert.classList.remove('opacity-100');
                    alert.classList.add('opacity-0', 'translate-y-4');
                    setTimeout(() => alert.classList.add('hidden'), 300);
                });
            });
        });
    </script>
</x-app-layout>
