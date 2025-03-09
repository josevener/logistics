<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            @include('navigation.header')

            <!-- Title -->
            <h2
                class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-semibold text-gray-800 mb-6 sm:mb-8 dark:text-gray-100">
                Vendor Dashboard
            </h2>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800">
                <div class="overflow-x-auto">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                    Contract
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                    ID
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                    Date
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                    Status
                                </th>
                                <th
                                    class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            @forelse ($contracts as $contract)
                                <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-gray-700">
                                    <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                                        {{ $contract->purpose ?? 'Unnamed Contract' }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                        {{ 'CON-' . str_pad($contract->id, 3, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                        {{ $contract->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                        @php
                                            switch ($contract->admin_status) {
                                                case 'approved':
                                                    $statusClasses =
                                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                                                    break;
                                                case 'flagged':
                                                    $statusClasses =
                                                        'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200';
                                                    break;
                                                case 'rejected':
                                                    $statusClasses =
                                                        'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                                                    break;
                                                default:
                                                    $statusClasses =
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-200';
                                                    break;
                                            }
                                            $statusText = ucfirst($contract->admin_status ?? 'pending');
                                        @endphp
                                        <span
                                            class="inline-block px-2 sm:px-3 py-1 {{ $statusClasses }} rounded-full text-xs sm:text-sm font-medium">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                        <button data-id="{{ $contract->id }}"
                                            class="viewContract bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-600 dark:hover:bg-blue-700">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-4 sm:px-6 py-12 text-center text-sm sm:text-base text-gray-500 dark:text-gray-400">
                                        No contracts found. Start by adding a new contract!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($contracts->hasPages())
                    <div
                        class="mt-6 flex justify-between items-center px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Showing {{ $contracts->firstItem() }} to {{ $contracts->lastItem() }} of
                            {{ $contracts->total() }} contracts
                        </div>
                        <div class="flex gap-2">
                            @if ($contracts->onFirstPage())
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-600 dark:text-gray-400">
                                    Previous
                                </span>
                            @else
                                <a href="{{ $contracts->previousPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">
                                    Previous
                                </a>
                            @endif

                            @foreach ($contracts->links()->elements[0] as $page => $url)
                                @if ($page == $contracts->currentPage())
                                    <span class="px-3 py-1 bg-blue-600 text-white rounded-md dark:bg-blue-700">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                        class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            @if ($contracts->hasMorePages())
                                <a href="{{ $contracts->nextPageUrl() }}"
                                    class="px-3 py-1 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition dark:bg-blue-600 dark:hover:bg-blue-700">
                                    Next
                                </a>
                            @else
                                <span
                                    class="px-3 py-1 bg-gray-200 text-gray-500 rounded-md cursor-not-allowed dark:bg-gray-600 dark:text-gray-400">
                                    Next
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- View Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-xl relative dark:bg-gray-800">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Contract Details</h2>
                    <button id="closeViewModal"
                        class="text-gray-600 hover:text-gray-900 text-3xl dark:text-gray-300 dark:hover:text-gray-100">×</button>
                </div>
                <div id="contractDetails" class="text-sm text-gray-700 dark:text-gray-300">
                    <table class="w-full border-collapse">
                        <tbody>
                            <!-- Populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button id="cancelView"
                        class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const viewModal = document.getElementById("viewModal");
            const closeViewModal = document.getElementById("closeViewModal");
            const cancelView = document.getElementById("cancelView");
            const contractDetails = document.getElementById("contractDetails");

            document.querySelectorAll(".viewContract").forEach(button => {
                button.addEventListener("click", function() {
                    const contractId = this.getAttribute("data-id");
                    console.log("Selected Contract ID:", contractId); // Debug log
                    const contracts = @json($contracts->items());
                    const contract = contracts.find(c => c.id == parseInt(contractId));

                    if (contract) {
                        contractDetails.querySelector("tbody").innerHTML = `
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="py-2 px-4 font-semibold">Contract</td>
                                <td class="py-2 px-4">${contract.purpose || 'Unnamed Contract'}</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="py-2 px-4 font-semibold">ID</td>
                                <td class="py-2 px-4">CON-${String(contract.id).padStart(3, '0')}</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="py-2 px-4 font-semibold">Date Created</td>
                                <td class="py-2 px-4">${new Date(contract.created_at).toLocaleDateString()}</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="py-2 px-4 font-semibold">Status</td>
                                <td class="py-2 px-4">${contract.admin_status ? contract.admin_status.charAt(0).toUpperCase() + contract.admin_status.slice(1) : 'Pending'}</td>
                            </tr>
                            <tr class="border-b border-gray-200 dark:border-gray-600">
                                <td class="py-2 px-4 font-semibold">View Submitted File</td>
                                <td class="py-2 px-4">
                                    <button class="view-file-button bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700 transition dark:bg-blue-700 dark:hover:bg-blue-800" data-id="${contract.id}">
                                        View File
                                    </button>
                                </td>
                            </tr>
                        `;
                        viewModal.classList.remove("hidden");
                    } else {
                        console.error("Contract not found for ID:", contractId);
                    }
                });
            });

            // Event delegation for "View File" buttons
            contractDetails.addEventListener("click", function(e) {
                const viewFileButton = e.target.closest(".view-file-button");
                if (viewFileButton) {
                    const contractId = viewFileButton.getAttribute("data-id");
                    if (contractId) {
                        const fileUrl = "{{ route('contracts.preview', ':id') }}".replace(':id',
                            contractId);
                        console.log("Opening file URL:", fileUrl); // Debug log
                        window.open(fileUrl, '_blank');
                    } else {
                        console.error("No contract ID available for preview.");
                    }
                }
            });

            function closeViewModalFn() {
                viewModal.classList.add("hidden");
            }

            closeViewModal.addEventListener("click", closeViewModalFn);
            cancelView.addEventListener("click", closeViewModalFn);
            viewModal.addEventListener("click", (e) => {
                if (e.target === viewModal) {
                    closeViewModalFn();
                }
            });
        });
    </script>
</x-app-layout>
