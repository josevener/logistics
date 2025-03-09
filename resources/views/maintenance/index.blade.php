<x-app-layout>
    <main class="flex-1 p-4 sm:p-6 md:p-8 lg:p-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-6 sm:gap-8">
            <!-- Center Column: Upcoming Maintenance -->
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 sm:mb-8 gap-4">
                    <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900">Upcoming Maintenance</h2>
                    <!-- Schedule Task Button with Modal Trigger -->
                    <button onclick="openModal()"
                        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition text-sm sm:text-base font-semibold">
                        <span class="text-lg sm:text-xl">+</span> Schedule Task
                    </button>
                </div>

                <!-- Maintenance List -->
                <div class="space-y-4 sm:space-y-6">
                    <!-- Card 1 -->
                    <div onclick="openCardModal('cardModal1')"
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-xl hover:scale-[1.02] transition-transform duration-300 cursor-pointer">
                        <div class="flex items-center gap-4 sm:gap-6">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 sm:h-7 sm:w-7 text-gray-400 flex-shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Truck #1234</h3>
                                <p class="text-gray-500 text-sm sm:text-base">Oil Change</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 mt-3 sm:mt-0">
                            <span
                                class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-md text-xs sm:text-sm font-medium">2024-03-25</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div onclick="openCardModal('cardModal2')"
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-xl hover:scale-[1.02] transition-transform duration-300 cursor-pointer">
                        <div class="flex items-center gap-4 sm:gap-6">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 sm:h-7 sm:w-7 text-gray-400 flex-shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Van #5678</h3>
                                <p class="text-gray-500 text-sm sm:text-base">Brake Inspection</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 mt-3 sm:mt-0">
                            <span
                                class="bg-blue-100 text-blue-800 px-2 sm:px-3 py-1 rounded-md text-xs sm:text-sm font-medium">2024-03-26</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div onclick="openCardModal('cardModal3')"
                        class="flex flex-col sm:flex-row items-start sm:items-center justify-between bg-white p-4 sm:p-6 rounded-lg shadow-md hover:shadow-xl hover:scale-[1.02] transition-transform duration-300 cursor-pointer">
                        <div class="flex items-center gap-4 sm:gap-6">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6 sm:h-7 sm:w-7 text-gray-400 flex-shrink-0" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900">Truck #9012</h3>
                                <p class="text-gray-500 text-sm sm:text-base">Tire Rotation</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-3 mt-3 sm:mt-0">
                            <span
                                class="bg-red-100 text-red-800 px-2 sm:px-3 py-1 rounded-md text-xs sm:text-sm font-medium">2024-03-27</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar: Maintenance Alerts & Quick Stats -->
            <div class="w-full lg:w-80 space-y-6 sm:space-y-8">
                <!-- Maintenance Alerts -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 sm:h-7 sm:w-7 text-red-500 flex-shrink-0"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        Maintenance Alerts
                    </h2>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                            <h3 class="text-red-800 font-semibold text-sm sm:text-base">Overdue: Oil Change</h3>
                            <p class="text-red-600 text-xs sm:text-sm">Truck #9012 - 3 days overdue</p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                            <h3 class="text-yellow-800 font-semibold text-sm sm:text-base">Due Soon: Brake Check
                            </h3>
                            <p class="text-yellow-600 text-xs sm:text-sm">Van #5678 - Due in 2 days</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div>
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 mb-4 sm:mb-6">Quick Stats</h2>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm sm:text-base">Pending Tasks</span>
                            <span class="font-semibold text-gray-900 text-sm sm:text-base">5</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm sm:text-base">Completed This Month</span>
                            <span class="font-semibold text-gray-900 text-sm sm:text-base">12</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 text-sm sm:text-base">Active Vehicles</span>
                            <span class="font-semibold text-gray-900 text-sm sm:text-base">8</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Schedule Task Modal -->
    <div id="scheduleModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-md mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Schedule Task</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closeModal()">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                        <form id="scheduleModalForm">
                            <div class="space-y-4 sm:space-y-6">
                                <div>
                                    <label for="vehicle-id"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-1 sm:mb-2">Vehicle</label>
                                    <input type="text" id="vehicle-id" name="vehicle-id"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                                        placeholder="Vehicle ID" required>
                                </div>
                                <div>
                                    <label for="task-desc"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-1 sm:mb-2">Task</label>
                                    <input type="text" id="task-desc" name="task-desc"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                                        placeholder="Task Description" required>
                                </div>
                                <div>
                                    <label for="task-date"
                                        class="block text-sm sm:text-base font-medium text-gray-700 mb-1 sm:mb-2">Date</label>
                                    <input type="date" id="task-date" name="task-date"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base"
                                        required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div
                        class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b space-x-2 sm:space-x-3">
                        <button type="button"
                            class="text-gray-700 bg-gray-200 hover:bg-gray-300 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeModal()">Cancel</button>
                        <button type="submit" form="scheduleModalForm"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Modal 1 -->
    <div id="cardModal1" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-md mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Task Details</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closeCardModal('cardModal1')">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <div class="space-y-2 sm:space-y-3">
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Vehicle:</strong> Truck #1234</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Task:</strong> Oil Change</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Date:</strong> 2024-03-25</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeCardModal('cardModal1')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Modal 2 -->
    <div id="cardModal2" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-md mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Task Details</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closeCardModal('cardModal2')">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <div class="space-y-2 sm:space-y-3">
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Vehicle:</strong> Van #5678</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Task:</strong> Brake Inspection
                            </p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Date:</strong> 2024-03-26</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeCardModal('cardModal2')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Modal 3 -->
    <div id="cardModal3" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-50">
        <div class="flex justify-center items-center min-h-screen p-4">
            <div class="relative w-full max-w-md mx-4">
                <div class="relative bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 rounded-t">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Task Details</h3>
                        <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                            onclick="closeCardModal('cardModal3')">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                        <div class="space-y-2 sm:space-y-3">
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Vehicle:</strong> Truck #9012</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Task:</strong> Tire Rotation</p>
                            <p class="text-gray-700 text-sm sm:text-base"><strong>Date:</strong> 2024-03-27</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 rounded-b">
                        <button type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 transition"
                            onclick="closeCardModal('cardModal3')">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        function openCardModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeCardModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            const modals = ['scheduleModal', 'cardModal1', 'cardModal2', 'cardModal3'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    const modalContent = modal.querySelector('.relative');
                    if (e.target === modal && !modalContent.contains(e.target)) {
                        modal.classList.add('hidden');
                    }
                }
            });
        });

        // Prevent form submission for demo (remove in production)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Form submitted (demo mode)');
                closeModal();
            });
        });
    </script>

    <!-- Scripts -->
    <script>
        // Sidebar toggle (if sidebar exists)
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
                if (toggleIcon) toggleIcon.classList.toggle('rotate-180');
            }
        }

        // Modal controls
        function openModal() {
            const modal = document.getElementById('scheduleModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeModal() {
            const modal = document.getElementById('scheduleModal');
            if (modal) modal.classList.add('hidden');
        }

        function openCardModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.remove('hidden');
        }

        function closeCardModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        }

        // Close modals when clicking outside
        window.addEventListener('click', (e) => {
            const modals = ['scheduleModal', 'cardModal1', 'cardModal2', 'cardModal3'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    const modalContent = modal.querySelector('.relative');
                    if (e.target === modal && !modalContent.contains(e.target)) {
                        modal.classList.add('hidden');
                    }
                }
            });
        });

        // Prevent form submission for demo (remove in production)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Form submitted (demo mode)');
                closeModal();
            });
        });
    </script>
</x-app-layout>
