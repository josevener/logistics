<x-app-layout>
    <main class="flex-1 p-6 md:p-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                    <h4 class="text-xl md:text-2xl font-bold text-gray-900">Vehicle Inventory</h4>
                    <!-- Tabs -->
                    <div class="flex gap-3">
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-semibold text-gray-700 bg-white shadow-sm hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 {{ request('filter', 'all') === 'all' ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white' : '' }}"
                            data-filter="all">
                            All Vehicles ({{ $vehicles->total() }})
                        </button>
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-semibold text-gray-700 bg-white shadow-sm hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 {{ request('filter') === 'ready' ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white' : '' }}"
                            data-filter="ready">
                            Ready to Use ({{ $vehicles->where('status', 'ready')->count() }})
                        </button>
                        <button
                            class="tab-button px-4 py-2 rounded-full text-sm font-semibold text-gray-700 bg-white shadow-sm hover:bg-blue-100 hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 {{ request('filter') === 'maintenance' ? 'bg-blue-600 text-white hover:bg-blue-700 hover:text-white' : '' }}"
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
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition shadow-sm">Add
                        New Vehicle</button>
                </div>
            </div>

            <!-- Vehicle Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($vehicles as $vehicle)
                    <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-xl hover:scale-105 transition-all duration-300 cursor-pointer shipment-card"
                        data-modal-target="default-modal" data-id="{{ $vehicle->id }}"
                        data-from="{{ $vehicle->route_from }}" data-to="{{ $vehicle->route_to }}"
                        data-datetime="{{ $vehicle->last_updated }}" data-status="{{ $vehicle->status }}">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 mb-1">{{ $vehicle->route_from }} —
                                    {{ $vehicle->route_to }}</h2>
                                <p class="text-sm text-gray-500">{{ $vehicle->last_updated }}</p>
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
                                    <p class="text-sm text-gray-500 mb-1">Vehicle Type</p>
                                    <p class="font-medium text-gray-800">{{ $vehicle->truck_type }}</p>
                                </div>
                            </div>
                            <div class="relative">
                                <img src="{{ $vehicle->image ? Storage::url($vehicle->image) : asset('assets/images/truck1.png') }}"
                                    alt="Vehicle image" class="w-full h-auto object-cover rounded-md" />
                            </div>
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
                <form id="vehicleForm" method="POST" action="{{ route('vehicles.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="flex flex-col">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                            <h3 id="modalTitle" class="text-xl sm:text-2xl font-semibold text-gray-900">Add New Vehicle
                            </h3>
                            <button type="button" id="closeVehicleModal"
                                class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-full text-sm w-8 h-8 inline-flex justify-center items-center transition-colors"
                                aria-label="Close modal">
                                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-4 sm:p-6 space-y-6">
                            <!-- Vehicle Number and Type -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="vehicle_number"
                                        class="block text-sm font-medium text-gray-700 mb-1">Vehicle Number</label>
                                    <input type="text" name="vehicle_number" id="vehicle_number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                </div>
                                <div>
                                    <label for="truck_type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle
                                        Type</label>
                                    <input type="text" name="truck_type" id="truck_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                </div>
                            </div>
                            <!-- Image Upload -->
                            <div>
                                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Vehicle
                                    Image</label>
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                <div id="image-preview" class="mt-2 hidden">
                                    <img src="#" alt="Image Preview" class="w-32 h-auto rounded-md"
                                        id="preview-img">
                                    <button type="button" id="remove-image"
                                        class="mt-2 text-red-600 hover:text-red-800 text-sm font-medium">Remove
                                        Image</button>
                                </div>
                            </div>
                            <!-- Route From and To -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="route_from" class="block text-sm font-medium text-gray-700 mb-1">Route
                                        From</label>
                                    <input type="text" name="route_from" id="route_from"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                </div>
                                <div>
                                    <label for="route_to" class="block text-sm font-medium text-gray-700 mb-1">Route
                                        To</label>
                                    <input type="text" name="route_to" id="route_to"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                </div>
                            </div>
                            <!-- Capacities -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="total_capacity"
                                        class="block text-sm font-medium text-gray-700 mb-1">Total Capacity
                                        (kg)</label>
                                    <input type="number" name="total_capacity" id="total_capacity"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                </div>
                                <div>
                                    <label for="available_capacity"
                                        class="block text-sm font-medium text-gray-700 mb-1">Available Capacity
                                        (kg)</label>
                                    <input type="number" name="available_capacity" id="available_capacity"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                        required>
                                </div>
                            </div>
                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                                    <option value="ready" selected>Ready</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                            <!-- Additional Fields -->
                            {{-- <div>
                                <label for="available_parts"
                                    class="block text-sm font-medium text-gray-700 mb-1">Available Parts</label>
                                <textarea name="available_parts" id="available_parts"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    rows="3"></textarea>
                            </div>
                            <div>
                                <label for="maintenance_record"
                                    class="block text-sm font-medium text-gray-700 mb-1">Maintenance Record</label>
                                <textarea name="maintenance_record" id="maintenance_record"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    rows="3"></textarea>
                            </div>
                            <div>
                                <label for="fuel_consumption"
                                    class="block text-sm font-medium text-gray-700 mb-1">Fuel Consumption</label>
                                <input type="text" name="fuel_consumption" id="fuel_consumption"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div> --}}
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50 gap-3">
                            <button type="submit"
                                class="bg-blue-600 text-white px-5 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors shadow-sm">
                                Save Vehicle
                            </button>
                            <button type="button" id="cancelVehicleModal"
                                class="text-gray-600 px-5 py-2 rounded-md hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-colors">
                                Cancel
                            </button>
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
                        {{-- <section>
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
                                        Record</h5>
                                    <p id="modal_maintenance"
                                        class="text-gray-600 text-sm sm:text-base leading-relaxed"></p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
                                    <h5 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Fuel Consumption
                                    </h5>
                                    <p id="modal_fuel" class="text-gray-600 text-sm sm:text-base leading-relaxed"></p>
                                </div>
                            </div>
                        </section> --}}
                    </div>
                    <!-- Modal footer with Edit and Delete buttons -->
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50 gap-2">
                        <button id="editVehicleBtn"
                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 transition">Edit</button>
                        <button id="deleteVehicleBtn"
                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Delete</button>
                        <button data-modal-hide="default-modal" type="button"
                            class="text-white bg-blue-600 hover:bg-blue-700 font-medium rounded-lg text-sm sm:text-base px-4 sm:px-6 py-2 sm:py-2.5 transition-colors shadow-sm"
                            id="modalCloseBtn">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-confirmation-modal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 w-full h-full bg-black bg-opacity-60">
        <div class="flex justify-center items-center min-h-screen p-4 sm:p-6">
            <div class="relative w-full max-w-md mx-auto my-4 rounded-xl shadow-xl bg-white">
                <div class="flex flex-col">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-xl sm:text-2xl font-semibold text-gray-900">Confirm Deletion</h3>
                        <button type="button" id="closeDeleteModal"
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
                    <div class="p-4 sm:p-6">
                        <p class="text-gray-700 text-sm sm:text-base">Are you sure you want to delete this vehicle?
                            This action cannot be undone.</p>
                    </div>
                    <!-- Modal footer -->
                    <div class="flex items-center justify-end p-4 sm:p-6 border-t border-gray-200 bg-gray-50 gap-2">
                        <form id="deleteVehicleForm" method="POST" action="">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition">Yes,
                                Delete</button>
                        </form>
                        <button type="button" id="cancelDeleteBtn"
                            class="text-gray-600 px-4 py-2 rounded-md hover:bg-gray-200 transition">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const removeImageBtn = document.getElementById('remove-image');

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.classList.add('hidden');
                previewImg.src = '#';
            });
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('image-preview');
        const previewImg = document.getElementById('preview-img');
        const removeImageBtn = document.getElementById('remove-image');
        const vehicleForm = document.getElementById('vehicleForm');

        // Image preview on file selection
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.classList.remove('hidden');
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // Remove image preview
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                imageInput.value = '';
                imagePreview.classList.add('hidden');
                previewImg.src = '#';
            });
        }

        // Populate form for editing
        const editVehicleBtn = document.getElementById('editVehicleBtn');
        if (editVehicleBtn) {
            editVehicleBtn.addEventListener('click', function() {
                const vehicleId = document.querySelector('.shipment-card:hover')?.dataset.id;
                if (vehicleId) {
                    fetch(`/vehicles/${vehicleId}/edit`)
                        .then(response => response.json())
                        .then(data => {
                            vehicleForm.action = `/vehicles/${vehicleId}`;
                            vehicleForm.method = 'POST';
                            if (!vehicleForm.querySelector('input[name="_method"]')) {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = '_method';
                                input.value = 'PUT';
                                vehicleForm.appendChild(input);
                            }
                            document.getElementById('modalTitle').textContent = 'Edit Vehicle';
                            document.getElementById('vehicle_number').value = data.vehicle_number ||
                                '';
                            document.getElementById('truck_type').value = data.truck_type || '';
                            document.getElementById('route_from').value = data.route_from || '';
                            document.getElementById('route_to').value = data.route_to || '';
                            document.getElementById('total_capacity').value = data.total_capacity ||
                                '';
                            document.getElementById('available_capacity').value = data
                                .available_capacity || '';
                            document.getElementById('status').value = data.status || 'ready';
                            document.getElementById('available_parts').value = data
                                .available_parts || '';
                            document.getElementById('maintenance_record').value = data
                                .maintenance_record || '';
                            document.getElementById('fuel_consumption').value = data
                                .fuel_consumption || '';
                            if (data.image) {
                                previewImg.src = '{{ env('APP_URL') }}/storage/' + data.image;
                                imagePreview.classList.remove('hidden');
                            } else {
                                imagePreview.classList.add('hidden');
                            }
                            document.getElementById('vehicle-modal').classList.remove('hidden');
                            document.getElementById('default-modal').classList.add('hidden');
                        })
                        .catch(error => console.error('Error fetching vehicle:', error));
                }
            });
        }
    });
</script>
