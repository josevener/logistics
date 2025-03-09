<x-app-layout>
    <main class="flex-1 p-6 md:p-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <h4 class="text-xl md:text-xl font-bold text-gray-900">Vehicle Inventory</h4>
                    <!-- Tabs -->
                    <div class="flex gap-2">
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-medium text-gray-600 bg-transparent hover:bg-gray-100 transition"
                            data-filter="all">All Vehicles</button>
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-medium text-gray-600 bg-transparent hover:bg-gray-100 transition"
                            data-filter="ready">Ready to Use (5)</button>
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-medium text-gray-600 bg-transparent hover:bg-gray-100 transition"
                            data-filter="maintenance">Under Maintenance (36)</button>
                    </div>
                </div>
                <!-- Sort Dropdown -->
                <div class="flex items-center gap-2 text-sm text-gray-600">
                    <span class="font-medium">Sort by:</span>
                    <button class="flex items-center gap-1 px-3 py-1 rounded-md hover:bg-gray-100 transition">
                        Time
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Vehicle Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1: Ready -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer shipment-card"
                    data-modal-target="default-modal" data-from="Sample Text" data-to="Sample Text"
                    data-datetime="Sample Date & Time" data-status="ready">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-1">Sample Text — Sample Text</h2>
                            <p class="text-sm text-gray-500">Sample Date & Time</p>
                        </div>
                        <span class="text-red-500 text-xl font-semibold">90%</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Available (kg)</p>
                                <p class="font-medium text-gray-800">20<span class="text-gray-400">/200</span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Shipment Number</p>
                                <p class="font-medium text-gray-800">#000001</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Truck</p>
                                <p class="font-medium text-gray-800">Sample Truck</p>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="{{ asset('assets/images/truck1.png') }}" alt="Truck illustration"
                                class="w-full h-auto object-cover rounded-md" />
                        </div>
                    </div>
                </div>
                <!-- Card 2: Maintenance -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer shipment-card"
                    data-modal-target="default-modal" data-from="Sample Text" data-to="Sample Text"
                    data-datetime="Sample Date & Time" data-status="maintenance">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-1">Sample Text — Sample Text</h2>
                            <p class="text-sm text-gray-500">Sample Date & Time</p>
                        </div>
                        <span class="text-amber-500 text-xl font-semibold">75%</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Available (kg)</p>
                                <p class="font-medium text-gray-800">200<span class="text-gray-400">/400</span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Shipment Number</p>
                                <p class="font-medium text-gray-800">#000002</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Truck</p>
                                <p class="font-medium text-gray-800">Sample Truck</p>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="{{ asset('assets/images/truck1.png') }}" alt="Truck illustration"
                                class="w-full h-auto object-cover rounded-md" />
                        </div>
                    </div>
                </div>
                <!-- Card 3: Ready -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer shipment-card"
                    data-modal-target="default-modal" data-from="Sample Text" data-to="Sample Text"
                    data-datetime="Sample Date & Time" data-status="ready">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-1">Sample Text — Sample Text</h2>
                            <p class="text-sm text-gray-500">Sample Date & Time</p>
                        </div>
                        <span class="text-green-500 text-xl font-semibold">25%</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Available (kg)</p>
                                <p class="font-medium text-gray-800">225<span class="text-gray-400">/300</span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Shipment Number</p>
                                <p class="font-medium text-gray-800">#000003</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Truck</p>
                                <p class="font-medium text-gray-800">Sample Truck</p>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="{{ asset('assets/images/truck1.png') }}" alt="Truck illustration"
                                class="w-full h-auto object-cover rounded-md" />
                        </div>
                    </div>
                </div>
                <!-- Card 4: Maintenance -->
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer shipment-card"
                    data-modal-target="default-modal" data-from="Sample Text" data-to="Sample Text"
                    data-datetime="Sample Date & Time" data-status="maintenance">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-1">Sample Text — Sample Text</h2>
                            <p class="text-sm text-gray-500">Sample Date & Time</p>
                        </div>
                        <span class="text-green-500 text-xl font-semibold">20%</span>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Available (kg)</p>
                                <p class="font-medium text-gray-800">160<span class="text-gray-400">/200</span></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Shipment Number</p>
                                <p class="font-medium text-gray-800">#000004</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Truck</p>
                                <p class="font-medium text-gray-800">Sample Truck</p>
                            </div>
                        </div>
                        <div class="relative">
                            <img src="{{ asset('assets/images/truck1.png') }}" alt="Truck illustration"
                                class="w-full h-auto object-cover rounded-md" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal (Landscape) -->
    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-60">
        <div class="flex justify-center items-center min-h-screen p-4 sm:p-6">
            <div
                class="relative w-full max-w-4xl mx-auto my-4 max-h-[90vh] overflow-y-auto rounded-xl shadow-xl bg-white">
                <!-- Modal content -->
                <div class="flex flex-col">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-xl sm:text-2xl md:text-3xl font-semibold text-gray-900">Vehicle Details</h3>
                        <button type="button"
                            class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 sm:w-10 sm:h-10 inline-flex justify-center items-center transition-colors"
                            id="closeModal" aria-label="Close modal">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 sm:p-6 md:p-8 space-y-8 sm:space-y-10">
                        <!-- Trip Information Section -->
                        <section>
                            <h4 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6">Trip Information
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                <div>
                                    <label for="modal_route"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Route</label>
                                    <input type="text" id="modal_route" name="modal_route"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-md bg-gray-50 text-gray-700 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
                                        readonly />
                                </div>
                                <div>
                                    <label for="modal_datetime"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-2">Date &
                                        Time</label>
                                    <input type="text" id="modal_datetime" name="modal_datetime"
                                        class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-md bg-gray-50 text-gray-700 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm"
                                        readonly />
                                </div>
                            </div>
                        </section>
                        <!-- Vehicle Profiles Section -->
                        <section>
                            <h4 class="text-lg sm:text-xl md:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Vehicle
                                Profiles</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 md:gap-8">
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                    <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Available Parts
                                    </h5>
                                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vitae ligula
                                        non magna finibus mollis.
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                    <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Maintenance
                                        Record</h5>
                                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                                        Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
                                        minim veniam.
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                    <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Fuel Consumption
                                    </h5>
                                    <p class="text-gray-600 text-sm sm:text-base leading-relaxed">
                                        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                                        fugiat nulla pariatur.
                                    </p>
                                </div>
                            </div>
                        </section>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50">
                        <button data-modal-hide="default-modal" type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm sm:text-base px-4 sm:px-6 py-2 sm:py-2.5 transition-colors shadow-sm"
                            id="modalCloseBtn">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                // Reset all buttons to inactive state
                tabButtons.forEach(b => {
                    b.classList.remove('bg-blue-600', 'text-white');
                    b.classList.add('text-gray-600', 'bg-transparent');
                });

                // Set active state on clicked button
                this.classList.remove('text-gray-600', 'bg-transparent');
                this.classList.add('bg-blue-600', 'text-white');

                // Filter vehicle cards
                const cards = document.querySelectorAll('.shipment-card');
                cards.forEach(card => {
                    const status = card.getAttribute('data-status');
                    card.classList.toggle('hidden', filter !== 'all' && status !== filter);
                });
            });
        });

        // Modal functionality
        const shipmentCards = document.querySelectorAll('.shipment-card');
        const modal = document.getElementById('default-modal');
        const modalRoute = document.getElementById('modal_route');
        const modalDatetime = document.getElementById('modal_datetime');
        const closeModalBtn = document.getElementById('closeModal');
        const modalCloseBtn = document.getElementById('modalCloseBtn');

        shipmentCards.forEach(card => {
            card.addEventListener('click', () => {
                const from = card.getAttribute('data-from');
                const to = card.getAttribute('data-to');
                const datetime = card.getAttribute('data-datetime');
                modalRoute.value = `${from} — ${to}`;
                modalDatetime.value = datetime;
                modal.classList.remove('hidden');
            });
        });

        function closeModal() {
            modal.classList.add('hidden');
        }

        closeModalBtn.addEventListener('click', closeModal);
        modalCloseBtn.addEventListener('click', closeModal);

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });
    </script>
</x-app-layout>

<script>
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');

            // Reset all buttons to inactive state
            tabButtons.forEach(b => {
                b.classList.remove('bg-blue-600', 'text-white');
                b.classList.add('text-gray-600', 'bg-transparent');
            });

            // Set active state on clicked button
            this.classList.remove('text-gray-600', 'bg-transparent');
            this.classList.add('bg-blue-600', 'text-white');

            // Filter vehicle cards
            const cards = document.querySelectorAll('.shipment-card');
            cards.forEach(card => {
                if (filter === "all" || card.getAttribute('data-status') === filter) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });

    // Modal functionality
    const shipmentCards = document.querySelectorAll('.shipment-card');
    const modal = document.getElementById('default-modal');
    const modalRoute = document.getElementById('modal_route');
    const modalDatetime = document.getElementById('modal_datetime');
    const closeModalBtn = document.getElementById('closeModal');
    const modalCloseBtn = document.getElementById('modalCloseBtn');

    // Open modal and populate fields when a card is clicked
    shipmentCards.forEach(card => {
        card.addEventListener('click', () => {
            const from = card.getAttribute('data-from');
            const to = card.getAttribute('data-to');
            const datetime = card.getAttribute('data-datetime');
            modalRoute.value = from + " — " + to;
            modalDatetime.value = datetime;
            modal.classList.remove('hidden');
        });
    });

    // Close modal function
    function closeModal() {
        modal.classList.add('hidden');
    }
    closeModalBtn.addEventListener('click', closeModal);
    modalCloseBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside modal content
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Dummy function for sidebar toggle (if needed)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('hidden');
    }
</script>
