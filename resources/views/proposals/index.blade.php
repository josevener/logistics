<x-app-layout>
    <!-- Header -->
    <main class="flex-1 p-6 md:p-12 bg-gray-50 min-h-screen">
        @include('navigation.header')

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200 rounded-lg shadow">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200 rounded-lg shadow">
                {{ session('error') }}
            </div>
        @endif

        <!-- Main Content -->
        <div class="flex-1 flex flex-col space-y-8 overflow-hidden">
            <!-- Create Bid Card -->
            <div
                class="w-full bg-white dark:bg-gray-800 rounded-xl p-6 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Submit a New Bid</h2>
                    <button id="openProposalModal"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 focus:outline-none transition-colors duration-300 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Add Bid
                    </button>
                </div>
                <p class="text-gray-600 dark:text-gray-300">Submit a competitive bid for a service or product.</p>
            </div>

            <!-- Submitted Bids Section -->
            <div
                class="w-full bg-white dark:bg-gray-800 rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow duration-300 flex-1 flex flex-col">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Submitted Vendor Bids</h2>
                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Title</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Vendor</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">Type
                                </th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Pricing</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Timeline</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Valid Until</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Score</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold border-b dark:border-gray-600">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($proposals as $proposal)
                                <tr class="border-b dark:border-gray-700 text-gray-600 dark:text-gray-300">
                                    <td class="px-4 py-3 text-sm">{{ $proposal->proposal_title }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $proposal->vendor_name ?? $proposal->user->name }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ ucfirst($proposal->product_service_type ?? 'N/A') }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $proposal->pricing ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $proposal->delivery_timeline ? \Carbon\Carbon::parse($proposal->delivery_timeline)->format('F d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $proposal->valid_until ? \Carbon\Carbon::parse($proposal->valid_until)->format('F d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $scoreClasses = match (true) {
                                                $proposal->proposal_evaluation_score >= 80
                                                    => 'bg-green-200 text-green-800',
                                                $proposal->proposal_evaluation_score >= 50
                                                    => 'bg-yellow-200 text-yellow-800',
                                                default => 'bg-red-200 text-red-800',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $scoreClasses }}">
                                            {{ number_format($proposal->proposal_evaluation_score ?? 0, 2) }}/100
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        @php
                                            $adminStatusClasses = match ($proposal->admin_status ?? 'pending') {
                                                'approved' => 'bg-green-200 text-green-800',
                                                'rejected' => 'bg-red-200 text-red-800',
                                                'flagged' => 'bg-orange-200 text-orange-800',
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
                                            class="viewProposal bg-blue-500 text-white px-3 py-1 rounded-md text-xs hover:bg-blue-600 transition-colors">
                                            View
                                        </button>
                                        @if (Auth::user()->role === 'Admin' || $proposal->user_id === Auth::id())
                                            <button onclick="openDeleteModal({{ $proposal->id }})"
                                                class="ml-2 bg-red-500 text-white px-3 py-1 rounded-md text-xs hover:bg-red-600 transition-colors">
                                                Delete
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9"
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
                                class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">Previous</a>
                        @endif
                        @foreach ($proposals->links()->elements[0] as $page => $url)
                            @if ($page == $proposals->currentPage())
                                <span class="px-3 py-1 bg-blue-600 text-white rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if ($proposals->hasMorePages())
                            <a href="{{ $proposals->nextPageUrl() }}"
                                class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition-colors">Next</a>
                        @else
                            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bid Submission Modal -->
        <div id="proposalModal"
            class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden" role="dialog"
            aria-labelledby="modalTitle" aria-hidden="true">
            <div
                class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-2xl overflow-hidden flex flex-col transform scale-95 transition-transform duration-200 ease-out">
                <header
                    class="px-4 py-3 flex justify-between items-center border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <h2 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Submit New Bid</h2>
                    <button id="closeProposalModal"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-150">
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
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="vendor_name"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Vendor Name
                                    </label>
                                    <input type="text" name="vendor_name" id="vendor_name"
                                        value="{{ old('vendor_name') }}"
                                        class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                                        placeholder="e.g., Acme Transport">
                                    @error('vendor_name')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="email"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Email
                                    </label>
                                    <input type="email" name="email" id="email"
                                        value="{{ old('email', auth()->user()->email) }}" readonly
                                        class="mt-1 block w-full rounded-md border py-2 px-3 bg-gray-200 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
                                    @error('email')
                                        <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Bid Details -->
                        <div class="space-y-3">
                            <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                Bid
                                Details</h3>
                            <div class="space-y-4">
                                <!-- Row 1 -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <div>
                                        <label for="proposal_title"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Bid Title <span class="text-red-500 text-xs">*</span>
                                        </label>
                                        <input type="text" name="proposal_title" id="proposal_title"
                                            value="{{ old('proposal_title') }}"
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                                            placeholder="e.g., Bus Transport Bid" required>
                                        @error('proposal_title')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="product_service_type"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Type <span class="text-red-500 text-xs">*</span>
                                        </label>
                                        <select name="product_service_type" id="product_service_type" required
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150">
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
                                            Pricing (₱) <span class="text-red-500 text-xs">*</span>
                                        </label>
                                        <div class="relative mt-1">
                                            <span
                                                class="absolute inset-y-0 left-0 pl-2 flex items-center text-gray-500 dark:text-gray-400 text-sm">₱</span>
                                            <input type="text" name="pricing" id="pricing"
                                                value="{{ old('pricing') }}"
                                                class="mt-1 block w-full pl-6 rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                                                placeholder="e.g., 50000" required
                                                pattern="^\d+(,\d{3})*(\.\d{1,2})?$" min="10000" max="10000000">
                                        </div>
                                        @error('pricing')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Row 2 -->
                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div>
                                        <label for="delivery_timeline"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Delivery Timeline <span class="text-red-500 text-xs">*</span>
                                        </label>
                                        <input type="date" name="delivery_timeline" id="delivery_timeline"
                                            value="{{ old('delivery_timeline') }}" required
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
                                        @error('delivery_timeline')
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="valid_until"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Valid Until <span class="text-red-500 text-xs">*</span>
                                        </label>
                                        <input type="date" name="valid_until" id="valid_until"
                                            value="{{ old('valid_until') }}" required
                                            class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
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
                                        class="mt-1 block w-full rounded-md border py-2 px-3 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150 resize-y"
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
                            class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:ring-2 focus:ring-blue-500 transition-colors duration-150">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-colors duration-150 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="submitText">Submit Bid</span>
                        </button>
                    </footer>
                </form>
            </div>
        </div>

        <!-- View Proposal Modal -->
        <div id="viewProposalModal"
            class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 hidden" role="dialog"
            aria-labelledby="viewModalTitle" aria-hidden="true">
            <div
                class="bg-white dark:bg-gray-900 rounded-xl shadow-xl w-full max-w-3xl overflow-hidden flex flex-col transform scale-95 transition-transform duration-200 ease-out">
                <header
                    class="px-4 py-3 flex justify-between items-center border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                    <h2 id="viewModalTitle" class="text-xl font-semibold text-gray-900 dark:text-white">Proposal
                        Details</h2>
                    <button id="closeViewModal"
                        class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-full w-8 h-8 flex items-center justify-center transition-colors duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <title>Close</title>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </header>
                <div class="p-4 flex-1 overflow-y-auto max-h-[70vh]" id="proposalDetails">
                    <!-- Dynamic content injected via JavaScript -->
                </div>
                <footer
                    class="px-4 py-3 bg-gray-50 dark:bg-gray-800 flex justify-end border-t border-gray-200 dark:border-gray-700">
                    <button id="closeViewFooterBtn"
                        class="px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-600 focus:ring-2 focus:ring-blue-500 transition-colors duration-150">
                        Close
                    </button>
                </footer>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Confirm Deletion</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">Are you sure you want to delete this bid?</p>
                <div class="flex justify-end gap-2">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                        Cancel
                    </button>
                    <form id="delete-form" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        </div>

        <!-- Scripts -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const proposalModal = document.getElementById('proposalModal');
                const viewProposalModal = document.getElementById('viewProposalModal');
                const openModalBtn = document.getElementById('openProposalModal');
                const closeModalBtn = document.getElementById('closeProposalModal');
                const closeViewModalBtn = document.getElementById('closeViewModal');
                const closeViewFooterBtn = document.getElementById('closeViewFooterBtn');
                const cancelBtn = document.getElementById('cancelBtn');
                const form = document.getElementById('bidForm');
                const submitBtn = document.getElementById('submitBtn');
                const submitText = document.getElementById('submitText');
                const proposalDetails = document.getElementById('proposalDetails');
                const viewButtons = document.querySelectorAll('.viewProposal');

                // Open Submission Modal
                openModalBtn.addEventListener('click', () => {
                    proposalModal.classList.remove('hidden');
                    proposalModal.classList.remove('opacity-0', 'pointer-events-none');
                    proposalModal.classList.add('opacity-100', 'pointer-events-auto');
                });

                // Close Submission Modal
                function closeProposalModal() {
                    proposalModal.classList.remove('opacity-100', 'pointer-events-auto');
                    proposalModal.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => proposalModal.classList.add('hidden'), 300);
                }
                closeModalBtn.addEventListener('click', closeProposalModal);
                cancelBtn.addEventListener('click', closeProposalModal);
                proposalModal.addEventListener('click', (e) => {
                    if (e.target === proposalModal) closeProposalModal();
                });

                // Form Submission (Traditional POST)
                form.addEventListener('submit', function(e) {
                    submitBtn.disabled = true;
                    submitText.textContent = 'Submitting...';
                });

                // Date Validation (Client-Side)
                const deliveryTimelineInput = document.getElementById('delivery_timeline');
                const validUntilInput = document.getElementById('valid_until');
                const today = new Date().toISOString().split('T')[0];
                deliveryTimelineInput.setAttribute('min', today);
                validUntilInput.setAttribute('min', today);

                deliveryTimelineInput.addEventListener('change', function() {
                    if (this.value < today) {
                        this.value = today;
                        alert('Delivery timeline cannot be in the past.');
                    }
                    validUntilInput.setAttribute('min', this.value || today);
                });

                validUntilInput.addEventListener('change', function() {
                    if (this.value < (deliveryTimelineInput.value || today)) {
                        this.value = deliveryTimelineInput.value || today;
                        alert('Valid until date must be on or after the delivery timeline.');
                    }
                });

                // View Proposal Modal
                let currentProposalId = null;
                viewButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        currentProposalId = this.getAttribute('data-id');
                        const proposals = @json($proposals->items());
                        const proposal = proposals.find(p => p.id == parseInt(currentProposalId));

                        if (proposal) {
                            const notes = proposal.notes ? JSON.parse(proposal.notes) : {};
                            const scoreInterpretation = notes.score_interpretation || 'N/A';

                            proposalDetails.innerHTML = `
                                <table class="w-full text-sm text-gray-700 dark:text-gray-300">
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Vendor Name</td>
                                        <td class="py-2 px-4">${proposal.vendor_name || proposal.user.name}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Email</td>
                                        <td class="py-2 px-4">${proposal.email || proposal.user.email}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Title</td>
                                        <td class="py-2 px-4">${proposal.proposal_title}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Type</td>
                                        <td class="py-2 px-4">${proposal.product_service_type ? proposal.product_service_type.charAt(0).toUpperCase() + proposal.product_service_type.slice(1) : 'N/A'}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Pricing</td>
                                        <td class="py-2 px-4">${proposal.pricing || 'N/A'}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Delivery Timeline</td>
                                        <td class="py-2 px-4">${proposal.delivery_timeline ? new Date(proposal.delivery_timeline).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'N/A'}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Valid Until</td>
                                        <td class="py-2 px-4">${proposal.valid_until ? new Date(proposal.valid_until).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : 'N/A'}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Evaluation Score</td>
                                        <td class="py-2 px-4">${proposal.proposal_evaluation_score ? proposal.proposal_evaluation_score.toFixed(2) : 'N/A'}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Score Interpretation</td>
                                        <td class="py-2 px-4">${scoreInterpretation}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Admin Status</td>
                                        <td class="py-2 px-4">${proposal.admin_status ? proposal.admin_status.charAt(0).toUpperCase() + proposal.admin_status.slice(1) : 'Pending'}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Notes</td>
                                        <td class="py-2 px-4">${notes.scoring?.join(', ') || 'N/A'}</td>
                                    </tr>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <td class="py-2 px-4 font-semibold">Fraud Analysis</td>
                                        <td class="py-2 px-4">${notes.fraud?.join(', ') || 'N/A'}</td>
                                    </tr>
                                    ${proposal.description ? `
                                                        <tr class="border-b border-gray-200 dark:border-gray-700">
                                                            <td class="py-2 px-4 font-semibold">Description</td>
                                                            <td class="py-2 px-4">${proposal.description}</td>
                                                        </tr>
                                                    ` : ''}
                                </table>
                            `;

                            viewProposalModal.classList.remove('hidden');
                            viewProposalModal.classList.remove('opacity-0', 'pointer-events-none');
                            viewProposalModal.classList.add('opacity-100', 'pointer-events-auto');
                        }
                    });
                });

                // Close View Modal
                function closeViewModal() {
                    viewProposalModal.classList.remove('opacity-100', 'pointer-events-auto');
                    viewProposalModal.classList.add('opacity-0', 'pointer-events-none');
                    setTimeout(() => viewProposalModal.classList.add('hidden'), 300);
                }
                closeViewModalBtn.addEventListener('click', closeViewModal);
                closeViewFooterBtn.addEventListener('click', closeViewModal);
                viewProposalModal.addEventListener('click', (e) => {
                    if (e.target === viewProposalModal) closeViewModal();
                });

                // Delete Modal Functions
                window.openDeleteModal = function(id) {
                    const modal = document.getElementById('delete-modal');
                    const form = document.getElementById('delete-form');
                    form.action = `{{ url('proposals') }}/${id}`;
                    modal.classList.remove('hidden');
                };

                window.closeDeleteModal = function() {
                    document.getElementById('delete-modal').classList.add('hidden');
                };
            });
        </script>

        <!-- Custom Styles -->
        <style>
            tbody {
                max-height: 50vh;
                overflow-y: auto;
                display: block;
            }

            thead,
            tbody tr {
                display: table;
                width: 100%;
                table-layout: fixed;
            }

            tbody::-webkit-scrollbar {
                width: 8px;
            }

            tbody::-webkit-scrollbar-thumb {
                background-color: #6b7280;
                border-radius: 4px;
            }

            tbody::-webkit-scrollbar-track {
                background-color: #e5e7eb;
            }

            .dark tbody::-webkit-scrollbar-thumb {
                background-color: #9ca3af;
            }

            .dark tbody::-webkit-scrollbar-track {
                background-color: #374151;
            }
        </style>
    </main>
</x-app-layout>
