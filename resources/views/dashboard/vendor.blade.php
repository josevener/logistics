<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Title -->
            <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800 mb-6 dark:text-gray-100">
                Vendor Dashboard
            </h2>

            <!-- Overview Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-white p-4 rounded-xl shadow-md dark:bg-gray-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-300">On-Time Delivery</h3>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $onTimeDelivery }}%</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-md dark:bg-gray-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-300">Pending Maintenance Tasks</h3>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">{{ $pendingTasksCount }}</p>
                </div>
            </div>

            <!-- Main Content: Split into Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Purchase Orders Table and Bid Opportunities -->
                <div class="lg:col-span-2">
                    <!-- Purchase Orders Table -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800 mb-6">
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase dark:text-gray-300">
                                            Description</th>
                                        <th
                                            class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase dark:text-gray-300">
                                            ID</th>
                                        <th
                                            class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase dark:text-gray-300">
                                            Date</th>
                                        <th
                                            class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase dark:text-gray-300">
                                            Status</th>
                                        <th
                                            class="px-4 py-3 text-left text-sm font-medium text-gray-500 uppercase dark:text-gray-300">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @forelse ($purchaseOrders as $po)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $po->description }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $po->po_number }}</td>
                                            <td class="px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $po->created_at->format('Y-m-d') }}</td>
                                            <td class="px-4 py-4 text-sm">
                                                <span
                                                    class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                                    @if ($po->status === 'Approved') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif ($po->status === 'Pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-200
                                                    @elseif ($po->status === 'Completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                    {{ $po->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-sm">
                                                <button data-id="{{ $po->id }}"
                                                    class="view-po bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700">View</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5"
                                                class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                                No purchase orders found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4">
                            {{ $purchaseOrders->links() }}
                        </div>
                    </div>

                    <!-- Bid Opportunities -->
                    <div class="bg-white rounded-xl shadow-md p-4 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 dark:text-gray-100">Bid Opportunities</h3>
                        <ul class="space-y-2">
                            <li class="text-sm font-semibold text-gray-700 dark:text-gray-300">Open Opportunities:</li>
                            @forelse ($bidOpportunities as $proposal)
                                <li class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $proposal->proposal_title }} - Due:
                                    {{ $proposal->valid_until?->format('Y-m-d') ?? 'N/A' }}
                                </li>
                            @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400">No open bid opportunities.</li>
                            @endforelse
                            <li class="text-sm font-semibold text-gray-700 dark:text-gray-300 mt-2">Submitted Proposals:
                            </li>
                            @forelse ($submittedProposals as $proposal)
                                <li class="text-sm text-gray-700 dark:text-gray-300">
                                    {{ $proposal->proposal_title }} - Submitted:
                                    {{ $proposal->created_at->format('Y-m-d') }}
                                    (Status: {{ $proposal->admin_status ?? 'Pending' }})
                                </li>
                            @empty
                                <li class="text-sm text-gray-500 dark:text-gray-400">No submitted proposals.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Right Column: Quick Actions -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-md p-4 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 dark:text-gray-100">Quick Actions</h3>
                        <a href="{{ route('proposals.index') }}"
                            class="w-full block bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 mb-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700">Submit
                            Bid</a>
                        <button
                            class="w-full bg-gray-200 text-gray-700 py-2 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">Contact
                            Support</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- View Modal -->
    <div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Purchase Order Details</h2>
                <button id="closeModal"
                    class="text-gray-600 hover:text-gray-900 text-xl dark:text-gray-300 dark:hover:text-gray-100">×</button>
            </div>
            <div id="poDetails" class="text-sm text-gray-700 dark:text-gray-300 space-y-2">
                <p><span class="font-semibold">Description:</span> <span id="poDescription"></span></p>
                <p><span class="font-semibold">PO Number:</span> <span id="poNumber"></span></p>
                <p><span class="font-semibold">Date:</span> <span id="poDate"></span></p>
                <p><span class="font-semibold">Status:</span> <span id="poStatus"></span></p>
                <p><span class="font-semibold">Amount:</span> <span id="poAmount"></span></p>
            </div>
            <div class="flex justify-end mt-4">
                <button id="closeModalBtn"
                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">Close</button>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const viewModal = document.getElementById("viewModal");
            const closeModal = document.getElementById("closeModal");
            const closeModalBtn = document.getElementById("closeModalBtn");
            const poDescription = document.getElementById("poDescription");
            const poNumber = document.getElementById("poNumber");
            const poDate = document.getElementById("poDate");
            const poStatus = document.getElementById("poStatus");
            const poAmount = document.getElementById("poAmount");

            document.querySelectorAll(".view-po").forEach(button => {
                button.addEventListener("click", function() {
                    const poId = this.getAttribute("data-id");
                    fetch(`/marketplace/vendor/purchase-order/${poId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(
                                `Failed to fetch purchase order: ${response.status} ${response.statusText}`
                            );
                            return response.json();
                        })
                        .then(po => {
                            poDescription.textContent = po.description || 'N/A';
                            poNumber.textContent = po.po_number || 'N/A';
                            poDate.textContent = po.created_at ? new Date(po.created_at)
                                .toLocaleDateString() : 'N/A';
                            poStatus.textContent = po.status || 'N/A';
                            poAmount.textContent = po.amount ? '₱' + Number(po.amount)
                                .toLocaleString() : 'N/A';
                            viewModal.classList.remove("hidden");
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            poDescription.textContent = `Error: ${error.message}`;
                            poNumber.textContent = '';
                            poDate.textContent = '';
                            poStatus.textContent = '';
                            poAmount.textContent = '';
                            viewModal.classList.remove("hidden");
                        });
                });
            });

            function hideModal() {
                viewModal.classList.add("hidden");
            }

            closeModal.addEventListener("click", hideModal);
            closeModalBtn.addEventListener("click", hideModal);
            viewModal.addEventListener("click", (e) => {
                if (e.target === viewModal) hideModal();
            });
        });
    </script>
</x-app-layout>
