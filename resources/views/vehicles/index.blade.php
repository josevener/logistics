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
                            class="tab-button px-4 py-2 rounded-full text-sm font-medium text-gray-600 bg-transparent hover:bg-gray-100 transition {{ request('filter', 'all') === 'all' ? 'bg-blue-600 text-white' : '' }}"
                            data-filter="all">
                            All Vehicles ({{ $vehicles->total() }})
                        </button>
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-medium text-gray-600 bg-transparent hover:bg-gray-100 transition {{ request('filter') === 'ready' ? 'bg-blue-600 text-white' : '' }}"
                            data-filter="ready">
                            Ready to Use ({{ $vehicles->where('status', 'ready')->count() }})
                        </button>
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-medium text-gray-600 bg-transparent hover:bg-gray-100 transition {{ request('filter') === 'maintenance' ? 'bg-blue-600 text-white' : '' }}"
                            data-filter="maintenance">
                            Under Maintenance ({{ $vehicles->where('status', 'maintenance')->count() }})
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Sort Dropdown -->
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="font-medium">Sort by:</span>
                        <select id="sortSelect" name="sort"
                            class="px-3 py-1 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="created_at-desc"
                                {{ request('sort', 'created_at-desc') === 'created_at-desc' ? 'selected' : '' }}>Time
                                (Newest)</option>
                            <option value="created_at-asc" {{ request('sort') === 'created_at-asc' ? 'selected' : '' }}>
                                Time (Oldest)</option>
                            <option value="capacity-desc" {{ request('sort') === 'capacity-desc' ? 'selected' : '' }}>
                                Capacity (High to Low)</option>
                            <option value="capacity-asc" {{ request('sort') === 'capacity-asc' ? 'selected' : '' }}>
                                Capacity (Low to High)</option>
                        </select>
                    </div>
                    <!-- Create Vehicle Button -->
                    <button id="createVehicleBtn"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Add New
                        Vehicle</button>
                </div>
            </div>

            <!-- Vehicle Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($vehicles as $vehicle)
                    <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer shipment-card"
                        data-modal-target="default-modal" data-id="{{ $vehicle->id }}"
                        data-from="{{ $vehicle->route_from }}" data-to="{{ $vehicle->route_to }}"
                        data-datetime="{{ $vehicle->last_updated->format('F d, Y H:i') }}"
                        data-status="{{ $vehicle->status }}">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $vehicle->route_from }} —
                                    {{ $vehicle->route_to }}</h2>
                                <p class="text-sm text-gray-500">{{ $vehicle->last_updated->format('F d, Y H:i') }}</p>
                            </div>
                            <span
                                class="text-{{ $vehicle->status === 'ready' ? 'green' : 'amber' }}-500 text-xl font-semibold">
                                {{ round(($vehicle->available_capacity / $vehicle->total_capacity) * 100) }}%
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Available (kg)</p>
                                    <p class="font-medium text-gray-800">{{ $vehicle->available_capacity }}<span
                                            class="text-gray-400">/{{ $vehicle->total_capacity }}</span></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Vehicle Number</p>
                                    <p class="font-medium text-gray-800">{{ $vehicle->vehicle_number }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 mb-1">Truck</p>
                                    <p class="font-medium text-gray-800">{{ $vehicle->truck_type }}</p>
                                </div>
                            </div>
                            <div class="relative">
                                <img src="{{ asset('assets/images/truck1.png') }}" alt="Truck illustration"
                                    class="w-full h-auto object-cover rounded-md" />
                            </div>
                        </div>
                        <!-- CRUD Buttons -->
                        <div class="mt-4 flex justify-end gap-2">
                            <button
                                class="edit-vehicle bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600 transition"
                                data-id="{{ $vehicle->id }}">Edit</button>
                            <form action="{{ route('vehicles.destroy', $vehicle->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="delete-vehicle bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600 transition"
                                    onclick="return confirm('Are you sure you want to delete this vehicle?')">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-6">
                        No vehicles found.
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $vehicles->links() }}
            </div>
        </div>
    </main>

    <!-- Create/Edit Modal -->
    <div id="vehicle-modal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-60">
        <div class="flex justify-center items-center min-h-screen p-4 sm:p-6">
            <div
                class="relative w-full max-w-2xl mx-auto my-4 max-h-[90vh] overflow-y-auto rounded-xl shadow-xl bg-white">
                <form id="vehicleForm" method="POST" action="{{ route('vehicles.store') }}">
                    @csrf
                    <div class="flex flex-col">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                            <h3 id="modalTitle" class="text-xl sm:text-2xl font-semibold text-gray-900">Add New Vehicle
                            </h3>
                            <button type="button" id="closeVehicleModal"
                                class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                                aria-label="Close modal">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 sm:p-6 space-y-4">
                            <div>
                                <label for="vehicle_number" class="block text-sm font-medium text-gray-700 mb-1">Vehicle
                                    Number</label>
                                <input type="text" name="vehicle_number" id="vehicle_number"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                            <div>
                                <label for="truck_type" class="block text-sm font-medium text-gray-700 mb-1">Truck
                                    Type</label>
                                <input type="text" name="truck_type" id="truck_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="route_from" class="block text-sm font-medium text-gray-700 mb-1">Route
                                        From</label>
                                    <input type="text" name="route_from" id="route_from"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label for="route_to" class="block text-sm font-medium text-gray-700 mb-1">Route
                                        To</label>
                                    <input type="text" name="route_to" id="route_to"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="total_capacity"
                                        class="block text-sm font-medium text-gray-700 mb-1">Total Capacity
                                        (kg)</label>
                                    <input type="number" name="total_capacity" id="total_capacity"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                                <div>
                                    <label for="available_capacity"
                                        class="block text-sm font-medium text-gray-700 mb-1">Available Capacity
                                        (kg)</label>
                                    <input type="number" name="available_capacity" id="available_capacity"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                </div>
                            </div>
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="ready">Ready</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                        <!-- Modal footer -->
                        <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50">
                            <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">Save
                                Vehicle</button>
                            <button type="button" id="cancelVehicleModal"
                                class="ml-2 text-gray-600 px-4 py-2 rounded-md hover:bg-gray-200 transition">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Vehicle Details Modal -->
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
                                    <p id="modal_parts" class="text-gray-600 text-sm sm:text-base leading-relaxed">
                                    </p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                    <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Maintenance
                                        Record
                                    </h5>
                                    <p id="modal_maintenance"
                                        class="text-gray-600 text-sm sm:text-base leading-relaxed"></p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                    <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Fuel Consumption
                                    </h5>
                                    <p id="modal_fuel" class="text-gray-600 text-sm sm:text-base leading-relaxed"></p>
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
        document.addEventListener('DOMContentLoaded', () => {
            // Tab functionality
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    const sort = document.getElementById('sortSelect')?.value || 'created_at-desc';
                    window.location.href =
                        `{{ route('vehicles.index') }}?filter=${filter}&sort=${sort}`;
                });
            });

            // Sort functionality
            const sortSelect = document.getElementById('sortSelect');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const filter = document.querySelector('.tab-button.bg-blue-600')?.getAttribute(
                        'data-filter') || 'all';
                    window.location.href =
                        `{{ route('vehicles.index') }}?filter=${filter}&sort=${this.value}`;
                });
            }

            // Vehicle details modal functionality
            const shipmentCards = document.querySelectorAll('.shipment-card');
            const modal = document.getElementById('default-modal');
            const modalRoute = document.getElementById('modal_route');
            const modalDatetime = document.getElementById('modal_datetime');
            const modalParts = document.getElementById('modal_parts');
            const modalMaintenance = document.getElementById('modal_maintenance');
            const modalFuel = document.getElementById('modal_fuel');
            const closeModalBtn = document.getElementById('closeModal');
            const modalCloseBtn = document.getElementById('modalCloseBtn');

            if (shipmentCards && modal) {
                shipmentCards.forEach(card => {
                    card.addEventListener('click', (e) => {
                        if (e.target.classList.contains('edit-vehicle') || e.target.classList
                            .contains('delete-vehicle')) return;
                        const from = card.getAttribute('data-from');
                        const to = card.getAttribute('data-to');
                        const datetime = card.getAttribute('data-datetime');
                        if (modalRoute) modalRoute.value = `${from} — ${to}`;
                        if (modalDatetime) modalDatetime.value = datetime;

                        const vehicleId = card.getAttribute('data-id');
                        fetch(`{{ route('vehicles.show', '') }}/${vehicleId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (modalParts) modalParts.textContent = data.available_parts ||
                                    'No data available';
                                if (modalMaintenance) modalMaintenance.textContent = data
                                    .maintenance_record || 'No data available';
                                if (modalFuel) modalFuel.textContent = data.fuel_consumption ||
                                    'No data available';
                            })
                            .catch(error => {
                                console.error('Error fetching vehicle details:', error);
                                if (modalParts) modalParts.textContent = 'Error loading data';
                                if (modalMaintenance) modalMaintenance.textContent =
                                    'Error loading data';
                                if (modalFuel) modalFuel.textContent = 'Error loading data';
                            });

                        modal.classList.remove('hidden');
                    });
                });
            }

            function closeModal() {
                if (modal) modal.classList.add('hidden');
            }

            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeModal);
            window.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });

            // Create/Edit vehicle modal functionality
            const createVehicleBtn = document.getElementById('createVehicleBtn');
            const vehicleModal = document.getElementById('vehicle-modal');
            const closeVehicleModalBtn = document.getElementById('closeVehicleModal');
            const cancelVehicleModalBtn = document.getElementById('cancelVehicleModal');
            const vehicleForm = document.getElementById('vehicleForm');

            if (createVehicleBtn && vehicleModal && vehicleForm) {
                createVehicleBtn.addEventListener('click', () => {
                    vehicleForm.action = '{{ route('vehicles.store') }}';
                    vehicleForm.method = 'POST';
                    const methodInput = vehicleForm.querySelector('input[name="_method"]');
                    if (methodInput) methodInput.remove();
                    document.getElementById('modalTitle').textContent = 'Add New Vehicle';
                    vehicleForm.reset();
                    vehicleModal.classList.remove('hidden');
                });
            }

            function closeVehicleModal() {
                if (vehicleModal) vehicleModal.classList.add('hidden');
            }

            if (closeVehicleModalBtn) closeVehicleModalBtn.addEventListener('click', closeVehicleModal);
            if (cancelVehicleModalBtn) cancelVehicleModalBtn.addEventListener('click', closeVehicleModal);
            window.addEventListener('click', (e) => {
                if (e.target === vehicleModal) closeVehicleModal();
            });

            // Edit vehicle functionality
            const editButtons = document.querySelectorAll('.edit-vehicle');
            if (editButtons && vehicleForm && vehicleModal) {
                editButtons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        const vehicleId = btn.getAttribute('data-id');
                        fetch(`/vehicles/${vehicleId}/edit`, {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) throw new Error(
                                    'Failed to fetch vehicle data');
                                return response.json();
                            })
                            .then(data => {
                                vehicleForm.action =
                                    `{{ route('vehicles.update', '') }}/${vehicleId}`;
                                vehicleForm.method = 'POST';
                                if (!vehicleForm.querySelector('input[name="_method"]')) {
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = '_method';
                                    input.value = 'PUT';
                                    vehicleForm.appendChild(input);
                                }
                                document.getElementById('modalTitle').textContent =
                                    'Edit Vehicle';
                                document.getElementById('vehicle_number').value = data
                                    .vehicle_number || '';
                                document.getElementById('truck_type').value = data.truck_type ||
                                    '';
                                document.getElementById('route_from').value = data.route_from ||
                                    '';
                                document.getElementById('route_to').value = data.route_to || '';
                                document.getElementById('total_capacity').value = data
                                    .total_capacity || '';
                                document.getElementById('available_capacity').value = data
                                    .available_capacity || '';
                                document.getElementById('status').value = data.status ||
                                    'ready';
                                vehicleModal.classList.remove('hidden');
                            })
                            .catch(error => console.error('Error fetching vehicle:', error));
                    });
                });
            }

            // Form submission with AJAX
            if (vehicleForm) {
                vehicleForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const isUpdate = vehicleForm.querySelector('input[name="_method"]')?.value === 'PUT';
                    fetch(vehicleForm.action, {
                            method: 'POST', // Laravel handles PUT via POST with _method
                            body: new FormData(vehicleForm),
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content,
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                if (response.status === 403) {
                                    alert('You do not have permission to perform this action.');
                                } else if (response.status === 405) {
                                    throw new Error('Method Not Allowed: Check route configuration');
                                }
                                return response.json().then(err => {
                                    throw new Error(err.message || 'Request failed');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log(data.message);
                            window.location.href = '{{ route('vehicles.index') }}';
                        })
                        .catch(error => {
                            console.error('Error:', error.message);
                            alert('An error occurred: ' + error.message);
                        });
                });
            }
        });
    </script>
</x-app-layout>
