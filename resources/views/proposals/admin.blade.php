<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            @include('navigation.header')
            <div class="grid grid-cols-1 gap-4 sm:gap-6">
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 dark:bg-gray-800 dark:text-white">
                    <h2 class="text-lg sm:text-xl md:text-2xl font-semibold mb-4 sm:mb-6">
                        <span class="text-blue-600 dark:text-blue-400">Proposals</span>
                    </h2>

                    <!-- New Proposal Button -->
                    <div class="mb-4">
                        <button id="openProposalModal"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            New Proposal
                        </button>
                    </div>

                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('proposals.index') }}"
                        class="mb-6 flex flex-col sm:flex-row gap-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name, email, or purpose..."
                            class="w-full sm:w-1/3 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <select name="admin_status"
                            class="w-full sm:w-1/4 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Admin Status</option>
                            <option value="approved" {{ request('admin_status') === 'approved' ? 'selected' : '' }}>
                                Approved</option>
                            <option value="pending" {{ request('admin_status') === 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="rejected" {{ request('admin_status') === 'rejected' ? 'selected' : '' }}>
                                Rejected</option>
                        </select>
                        <select name="status"
                            class="w-full sm:w-1/4 p-2 border rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All AI Analysis</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>
                                Approved</option>
                            <option value="flagged" {{ request('status') === 'flagged' ? 'selected' : '' }}>Flagged
                            </option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="error" {{ request('status') === 'error' ? 'selected' : '' }}>Error
                            </option>
                        </select>
                        <button type="submit"
                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition">
                            Filter
                        </button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Rank</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Title</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Email</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Type</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Pricing</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Timeline</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Valid Until</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        AI Score</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Status</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($proposals as $index => $proposal)
                                    <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                        <td
                                            class="px-4 py-3 font-medium {{ $index === 0 ? 'text-green-700 dark:text-green-300' : '' }}">
                                            {{ $index + 1 }}
                                            {{ $index === 0 ? ' (Best)' : '' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->proposal_title }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->user->email }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->product_service_type }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->pricing }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $proposal->delivery_timeline }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            {{ \Carbon\Carbon::parse($proposal->valid_until)->format('F d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $scoreClasses = match (true) {
                                                    $proposal->ai_score >= 80 => 'bg-green-200 text-green-800',
                                                    $proposal->ai_score >= 50 => 'bg-yellow-200 text-yellow-800',
                                                    default => 'bg-red-200 text-red-800',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $scoreClasses }}">
                                                {{ $proposal->ai_score }}/100
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            @php
                                                $adminStatusClasses = match ($proposal->admin_status ?? 'pending') {
                                                    'approved' => 'bg-green-200 text-green-800',
                                                    'rejected' => 'bg-red-200 text-red-800',
                                                    'pending' => 'bg-yellow-100 text-yellow-600',
                                                    default => 'bg-gray-100 text-gray-600',
                                                };
                                            @endphp
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $adminStatusClasses }}">
                                                {{ ucfirst($proposal->admin_status ?? 'Pending') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <button data-id="{{ $proposal->id }}"
                                                class="viewProposal bg-blue-500 text-white px-3 py-1 rounded-md text-xs">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10"
                                            class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No proposals found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6 flex justify-between items-center">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $proposals->firstItem() }} to {{ $proposals->lastItem() }} of
                            {{ $proposals->total() }} proposals
                        </div>
                        <div class="flex gap-2">
                            @if ($proposals->onFirstPage())
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Previous</span>
                            @else
                                <a href="{{ $proposals->previousPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">Previous</a>
                            @endif
                            @foreach ($proposals->links()->elements[0] as $page => $url)
                                @if ($page == $proposals->currentPage())
                                    <span
                                        class="px-3 py-1 bg-blue-600 text-white rounded-md">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if ($proposals->hasMorePages())
                                <a href="{{ $proposals->nextPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">Next</a>
                            @else
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Next</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bid Submission Modal -->
        <div id="proposalModal"
            class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 opacity-0 pointer-events-none transition-all duration-300 ease-in-out"
            role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
            <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col transform scale-95 transition-transform duration-200 ease-out"
                aria-modal="true">
                <header
                    class="px-4 py-3 flex justify-between items-center border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <h2 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Submit New Bid</h2>
                    <button id="closeProposalModal"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-150"
                        aria-label="Close modal">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <title>Close</title>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </header>
                <form id="bidForm" class="p-4 flex-1" method="POST" action="{{ route('proposals.store') }}"
                    aria-label="Bid Submission Form">
                    @csrf
                    <div class="space-y-4">
                        <!-- Vendor Details -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                Vendor Details</h3>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-3">
                                <div>
                                    <label for="vendor_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Vendor Name <span class="text-red-500 text-xs">*</span>
                                    </label>
                                    <input type="text" name="vendor_name" id="vendor_name"
                                        value="{{ old('vendor_name') }}"
                                        class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150"
                                        placeholder="e.g., Acme Transport" required>
                                    @error('vendor_name')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email <span class="text-red-500 text-xs">*</span>
                                    </label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', auth()->user()->email) }}"
                                        class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150"
                                        placeholder="e.g., user@domain.com" required>
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bid Details -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                Bid Details</h3>
                            <div class="space-y-4">
                                <!-- Row 1 -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 sm:gap-3">
                                    <div>
                                        <label for="proposal_title"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Bid Title <span class="text-red-500 text-xs">*</span>
                                        </label>
                                        <input type="text" name="proposal_title" id="proposal_title"
                                            value="{{ old('proposal_title') }}"
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150"
                                            placeholder="e.g., Bus Transport Bid" required>
                                        @error('proposal_title')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="product_service_type"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Type
                                        </label>
                                        <select name="product_service_type" id="product_service_type"
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150">
                                            <option value="" disabled
                                                {{ old('product_service_type') ? '' : 'selected' }}>Select type
                                            </option>
                                            <option value="service"
                                                {{ old('product_service_type') === 'service' ? 'selected' : '' }}>
                                                Service</option>
                                            <option value="product"
                                                {{ old('product_service_type') === 'product' ? 'selected' : '' }}>
                                                Product</option>
                                            <option value="both"
                                                {{ old('product_service_type') === 'both' ? 'selected' : '' }}>Both
                                            </option>
                                        </select>
                                        @error('product_service_type')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="pricing"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Pricing (₱)
                                        </label>
                                        <div class="relative mt-1">
                                            <span
                                                class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500 dark:text-gray-400 text-sm">₱</span>
                                            <input type="number" name="pricing" id="pricing"
                                                value="{{ old('pricing') }}" step="0.01" min="0"
                                                class="mt-1 block w-full pl-6 rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150"
                                                placeholder="50000">
                                        </div>
                                        @error('pricing')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Row 2 -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 sm:gap-3">
                                    <div>
                                        <label for="delivery_timeline"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Delivery Timeline
                                        </label>
                                        <input type="date" name="delivery_timeline" id="delivery_timeline"
                                            value="{{ old('delivery_timeline') }}"
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150"
                                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                        @error('delivery_timeline')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="valid_until"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Valid Until
                                        </label>
                                        <input type="date" name="valid_until" id="valid_until"
                                            value="{{ old('valid_until') }}"
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150"
                                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                        @error('valid_until')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Row 3 (Description) -->
                                <div>
                                    <label for="description"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Description
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                        class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-150 resize-y"
                                        placeholder="e.g., Daily bus transport with 40-seat buses, including maintenance and fuel costs.">{{ old('description') }}</textarea>
                                    @error('description')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer
                        class="px-4 py-3 bg-gray-50 dark:bg-gray-800 flex justify-end gap-3 border-t border-gray-200 dark:border-gray-700">
                        <button type="reset" id="cancelBtn"
                            class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:ring-2 focus:ring-blue-500 transition-all duration-150">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-150 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">Submit Bid</span>
                            <svg id="loadingSpinner" class="hidden w-4 h-4 ml-2 animate-spin" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <title>Loading</title>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8v-8H4z" />
                            </svg>
                        </button>
                    </footer>
                </form>
            </div>
        </div>

        <!-- View Proposal Modal -->
        <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl relative">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold text-gray-800">Proposal Details</h2>
                        <button id="closeViewModal" class="text-gray-600 hover:text-gray-900 text-2xl">×</button>
                    </div>
                    <div id="proposalDetails" class="text-sm text-gray-700">
                        <table class="w-full border-collapse">
                            <tbody>
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end gap-4 mt-8">
                        <button id="cancelView"
                            class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                            Close
                        </button>
                        <button id="declineProposal"
                            class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 hidden">
                            Decline Proposal
                        </button>
                        <button id="approveProposal"
                            class="bg-green-600 text-white px-5 py-2 rounded-lg hover:bg-green-700 transition focus:outline-none focus:ring-2 focus:ring-green-500 hidden">
                            Approve Proposal
                        </button>
                        <span id="statusIndicator" class="text-sm font-medium text-gray-600 hidden"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approve Confirmation Modal -->
        <div id="approveConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Confirm Proposal Approval</h2>
                        <button id="closeApproveConfirm" class="text-gray-600 hover:text-gray-900 text-2xl">×</button>
                    </div>
                    <p class="text-sm text-gray-700 mb-4">Are you sure you want to approve this proposal?</p>
                    <textarea id="approveNotes" class="w-full border p-2 rounded mb-4" rows="3"
                        placeholder="Enter approval notes (optional)"></textarea>
                    <div class="flex justify-end gap-3">
                        <button id="cancelApproveConfirm"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button id="confirmApprove"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                            Approve Proposal
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Decline Confirmation Modal -->
        <div id="declineConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
            <div class="flex items-center justify-center min-h-screen">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md relative">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Confirm Proposal Rejection</h2>
                        <button id="closeDeclineConfirm" class="text-gray-600 hover:text-gray-900 text-2xl">×</button>
                    </div>
                    <p class="text-sm text-gray-700 mb-4">Are you sure you want to reject this proposal?</p>
                    <textarea id="declineNotes" class="w-full border p-2 rounded mb-4" rows="3"
                        placeholder="Enter rejection reason (optional)"></textarea>
                    <div class="flex justify-end gap-3">
                        <button id="cancelDeclineConfirm"
                            class="bg-gray-300 text-gray-800 px-4 py-2 rounded-md hover:bg-gray-400 transition">
                            Cancel
                        </button>
                        <button id="confirmDecline"
                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">
                            Reject Proposal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modals -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Bid Submission Modal
            const proposalModal = document.getElementById("proposalModal");
            const openProposalModal = document.getElementById("openProposalModal");
            const closeProposalModal = document.getElementById("closeProposalModal");
            const cancelBtn = document.getElementById("cancelBtn");
            const bidForm = document.getElementById("bidForm");
            const submitBtn = document.getElementById("submitBtn");
            const submitText = document.getElementById("submitText");
            const loadingSpinner = document.getElementById("loadingSpinner");

            // View Modal
            const viewModal = document.getElementById("viewModal");
            const closeViewModal = document.getElementById("closeViewModal");
            const cancelView = document.getElementById("cancelView");
            const approveProposal = document.getElementById("approveProposal");
            const declineProposal = document.getElementById("declineProposal");
            const proposalDetails = document.getElementById("proposalDetails").querySelector("tbody");
            const statusIndicator = document.getElementById("statusIndicator");

            // Approve Confirmation Modal
            const approveConfirmModal = document.getElementById("approveConfirmModal");
            const closeApproveConfirm = document.getElementById("closeApproveConfirm");
            const cancelApproveConfirm = document.getElementById("cancelApproveConfirm");
            const confirmApprove = document.getElementById("confirmApprove");

            // Decline Confirmation Modal
            const declineConfirmModal = document.getElementById("declineConfirmModal");
            const closeDeclineConfirm = document.getElementById("closeDeclineConfirm");
            const cancelDeclineConfirm = document.getElementById("cancelDeclineConfirm");
            const confirmDecline = document.getElementById("confirmDecline");

            let currentProposalId = null;

            // Bid Modal Functions
            function openBidModal() {
                proposalModal.classList.remove("opacity-0", "pointer-events-none");
                proposalModal.classList.add("opacity-100", "pointer-events-auto");
            }

            function closeBidModal() {
                proposalModal.classList.remove("opacity-100", "pointer-events-auto");
                proposalModal.classList.add("opacity-0", "pointer-events-none");
                bidForm.reset();
            }

            openProposalModal.addEventListener("click", openBidModal);
            closeProposalModal.addEventListener("click", closeBidModal);
            cancelBtn.addEventListener("click", closeBidModal);

            bidForm.addEventListener("submit", function(event) {
                event.preventDefault();
                submitBtn.disabled = true;
                submitText.textContent = "Submitting...";
                loadingSpinner.classList.remove("hidden");

                const formData = new FormData(bidForm);

                fetch(bidForm.action, {
                        method: "POST",
                        body: formData,
                        headers: {
                            "Accept": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                ?.content || "{{ csrf_token() }}"
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error(response.statusText);
                        return response.json();
                    })
                    .then(data => {
                        if (data.message) {
                            alert(data.message);
                            closeBidModal();
                            location.reload();
                        } else {
                            throw new Error("Unexpected response format");
                        }
                    })
                    .catch(error => {
                        console.error("Submission error:", error);
                        alert("Failed to submit bid: " + error.message);
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitText.textContent = "Submit Bid";
                        loadingSpinner.classList.add("hidden");
                    });
            });

            proposalModal.addEventListener("click", function(event) {
                if (event.target === proposalModal) {
                    closeBidModal();
                }
            });

            // View Modal Functions
            document.querySelectorAll(".viewProposal").forEach(button => {
                button.addEventListener("click", function() {
                    currentProposalId = this.getAttribute("data-id");
                    const proposals = @json($proposals->items());
                    const proposal = proposals.find(p => p.id == parseInt(currentProposalId));

                    if (proposal) {
                        proposalDetails.innerHTML = `
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Vendor Name</td>
                                <td class="py-2 px-4">${proposal.vendor_name || proposal.user.name}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Email</td>
                                <td class="py-2 px-4">${proposal.email || proposal.user.email}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Title</td>
                                <td class="py-2 px-4">${proposal.proposal_title}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Type</td>
                                <td class="py-2 px-4">${proposal.product_service_type || 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Pricing</td>
                                <td class="py-2 px-4">${proposal.pricing || 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Delivery Timeline</td>
                                <td class="py-2 px-4">${proposal.delivery_timeline || 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Valid Until</td>
                                <td class="py-2 px-4">${proposal.valid_until ? new Date(proposal.valid_until).toLocaleDateString() : 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">AI Score</td>
                                <td class="py-2 px-4">${proposal.ai_score || 'N/A'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Admin Status</td>
                                <td class="py-2 px-4">${proposal.admin_status ? proposal.admin_status.charAt(0).toUpperCase() + proposal.admin_status.slice(1) : 'Pending'}</td>
                            </tr>
                            <tr class="border-b border-gray-200">
                                <td class="py-2 px-4 font-semibold">Notes</td>
                                <td class="py-2 px-4">${proposal.fraud_notes || 'N/A'}</td>
                            </tr>
                        `;

                        const adminStatus = proposal.admin_status ? proposal.admin_status
                            .toLowerCase() : 'pending';
                        if (adminStatus === 'approved') {
                            approveProposal.classList.add("hidden");
                            declineProposal.classList.add("hidden");
                            statusIndicator.classList.remove("hidden");
                            statusIndicator.textContent = "Proposal Approved";
                            statusIndicator.classList.remove("text-red-600");
                            statusIndicator.classList.add("text-green-600");
                        } else if (adminStatus === 'rejected') {
                            approveProposal.classList.add("hidden");
                            declineProposal.classList.add("hidden");
                            statusIndicator.classList.remove("hidden");
                            statusIndicator.textContent = "Proposal Rejected";
                            statusIndicator.classList.remove("text-green-600");
                            statusIndicator.classList.add("text-red-600");
                        } else {
                            approveProposal.classList.remove("hidden");
                            declineProposal.classList.remove("hidden");
                            statusIndicator.classList.add("hidden");
                        }

                        viewModal.classList.remove("hidden");
                    } else {
                        console.error("Proposal not found for ID:", currentProposalId);
                    }
                });
            });

            function closeViewModalFn() {
                viewModal.classList.add("hidden");
                currentProposalId = null;
            }

            closeViewModal.addEventListener("click", closeViewModalFn);
            cancelView.addEventListener("click", closeViewModalFn);
            viewModal.addEventListener("click", (e) => {
                if (e.target === viewModal) {
                    closeViewModalFn();
                }
            });

            // Approve/Decline Modal Functions
            function closeApproveConfirmFn() {
                approveConfirmModal.classList.add("hidden");
                document.getElementById("approveNotes").value = "";
            }

            function closeDeclineConfirmFn() {
                declineConfirmModal.classList.add("hidden");
                document.getElementById("declineNotes").value = "";
            }

            closeApproveConfirm.addEventListener("click", closeApproveConfirmFn);
            cancelApproveConfirm.addEventListener("click", closeApproveConfirmFn);
            approveConfirmModal.addEventListener("click", (e) => {
                if (e.target === approveConfirmModal) {
                    closeApproveConfirmFn();
                }
            });

            closeDeclineConfirm.addEventListener("click", closeDeclineConfirmFn);
            cancelDeclineConfirm.addEventListener("click", closeDeclineConfirmFn);
            declineConfirmModal.addEventListener("click", (e) => {
                if (e.target === declineConfirmModal) {}
            });

            approveProposal.addEventListener("click", () => {
                approveConfirmModal.classList.remove("hidden");
            });

            declineProposal.addEventListener("click", () => {
                declineConfirmModal.classList.remove("hidden");
            });

            confirmApprove.addEventListener("click", () => {
                if (currentProposalId) {
                    const notes = document.getElementById("approveNotes").value;
                    fetch(`/proposals/${currentProposalId}/approve`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                notes: notes
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeApproveConfirmFn();
                                closeViewModalFn();
                                location.reload();
                            }
                        })
                        .catch(error => console.error("Approve error:", error));
                }
            });

            confirmDecline.addEventListener("click", () => {
                if (currentProposalId) {
                    const notes = document.getElementById("declineNotes").value;
                    fetch(`/proposals/${currentProposalId}/decline`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                notes: notes
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                closeDeclineConfirmFn();
                                closeViewModalFn();
                                location.reload();
                            }
                        })
                        .catch(error => console.error("Decline error:", error));
                }
            });
        });
    </script>
</x-app-layout>
