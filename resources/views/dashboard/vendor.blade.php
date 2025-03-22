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

            <!-- Overview Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-xl shadow-md dark:bg-gray-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-300">Active Contracts</h3>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">5</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-md dark:bg-gray-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-300">On-Time Delivery</h3>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">92%</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-md dark:bg-gray-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-300">Pending Tasks</h3>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">3</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-md dark:bg-gray-800">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-300">AI Alerts</h3>
                    <p class="text-2xl font-semibold text-gray-800 dark:text-gray-100">2</p>
                </div>
            </div>

            <!-- Main Content: Split into Two Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Contracts Table and Procurement -->
                <div class="lg:col-span-2">
                    <!-- Contracts Table -->
                    <div class="bg-white rounded-xl shadow-md overflow-hidden dark:bg-gray-800 mb-6">
                        <div class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Contract</th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            ID</th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Date</th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Status</th>
                                        <th
                                            class="px-4 sm:px-6 py-3 text-left text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wider dark:text-gray-300">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <!-- Static Example Rows -->
                                    <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-gray-700">
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                                            Bus Parts Supply</td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            CON-001</td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            2025-03-01</td>
                                        <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                            <span
                                                class="inline-block px-2 sm:px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full text-xs sm:text-sm font-medium">Approved</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                            <button data-id="1"
                                                class="viewContract bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-600 dark:hover:bg-blue-700">View</button>
                                        </td>
                                    </tr>
                                    <tr class="hover:bg-gray-50 transition-colors dark:hover:bg-gray-700">
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-900 dark:text-gray-100">
                                            Fuel Delivery</td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            CON-002</td>
                                        <td
                                            class="px-4 sm:px-6 py-4 text-xs sm:text-sm text-gray-700 dark:text-gray-300">
                                            2025-03-15</td>
                                        <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                            <span
                                                class="inline-block px-2 sm:px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-200 rounded-full text-xs sm:text-sm font-medium">Pending</span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-xs sm:text-sm">
                                            <button data-id="2"
                                                class="viewContract bg-blue-500 text-white px-3 py-1 rounded-md hover:bg-blue-600 transition focus:outline-none focus:ring-2 focus:ring-blue-400 dark:bg-blue-600 dark:hover:bg-blue-700">View</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Procurement: Bid Opportunities -->
                    <div class="bg-white rounded-xl shadow-md p-4 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 dark:text-gray-100">Bid Opportunities</h3>
                        <ul class="space-y-2">
                            <li class="text-sm text-gray-700 dark:text-gray-300">Tender: Bus Tires - Due: 2025-03-30
                            </li>
                            <li class="text-sm text-gray-700 dark:text-gray-300">Tender: Engine Oil - Due: 2025-04-05
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Right Column: Transit and AI Insights -->
                <div class="space-y-6">
                    <!-- Transit Tracking -->
                    <div class="bg-white rounded-xl shadow-md p-4 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 dark:text-gray-100">Transit Tracking</h3>
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            <p>Shipment: Bus Parts</p>
                            <p>Location: En Route - Chicago</p>
                            <p>ETA: 2025-03-25</p>
                            <p class="text-green-600 dark:text-green-400">On Schedule</p>
                        </div>
                    </div>

                    <!-- AI-Driven Insights -->
                    <div class="bg-white rounded-xl shadow-md p-4 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 dark:text-gray-100">AI Insights</h3>
                        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                            <li>Predicted Delay: Fuel shipment may arrive late due to traffic.</li>
                            <li>Route Suggestion: Optimize Route A to save 10% on fuel.</li>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-md p-4 dark:bg-gray-800">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 dark:text-gray-100">Quick Actions</h3>
                        <button
                            class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition mb-2 dark:bg-blue-600 dark:hover:bg-blue-700">Submit
                            Bid</button>
                        <button
                            class="w-full bg-gray-200 text-gray-700 py-2 rounded-md hover:bg-gray-300 transition dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">Contact
                            Support</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- View Modal (Unchanged) -->
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
                        <tbody></tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-4 mt-8">
                    <button id="cancelView"
                        class="bg-gray-200 text-gray-700 px-5 py-2 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal (Updated with Static Data) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const viewModal = document.getElementById("viewModal");
            const closeViewModal = document.getElementById("closeViewModal");
            const cancelView = document.getElementById("cancelView");
            const contractDetails = document.getElementById("contractDetails");

            const staticContracts = [{
                    id: 1,
                    purpose: "Bus Parts Supply",
                    created_at: "2025-03-01",
                    admin_status: "approved"
                },
                {
                    id: 2,
                    purpose: "Fuel Delivery",
                    created_at: "2025-03-15",
                    admin_status: "pending"
                }
            ];

            document.querySelectorAll(".viewContract").forEach(button => {
                button.addEventListener("click", function() {
                    const contractId = this.getAttribute("data-id");
                    const contract = staticContracts.find(c => c.id == parseInt(contractId));

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
                    }
                });
            });

            contractDetails.addEventListener("click", function(e) {
                const viewFileButton = e.target.closest(".view-file-button");
                if (viewFileButton) {
                    const contractId = viewFileButton.getAttribute("data-id");
                    alert(`Static file preview for Contract ID: ${contractId}`);
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
