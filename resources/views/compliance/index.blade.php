<x-app-layout>
    <div class="flex-1 container mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-gray-50 min-h-screen" id="main_container">
        <!-- Header -->
        @include('navigation.header')

        <!-- Content Box -->
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg mt-12 sm:mt-16">
            <div class="flex justify-between items-center px-4 sm:px-6 py-4 border-b border-gray-200">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Compliance and Training</h1>
                    <p class="text-sm text-gray-600 mt-1">Upload all necessary files to process your proposal!</p>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 sm:px-6 py-6">
                <!-- Upload Section (wider) -->
                <div class="md:col-span-2">
                    <form action="/upload_file" method="post" enctype="multipart/form-data" id="uploadForm">
                        <label for="file-upload" id="drop-area"
                            class="flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-white hover:bg-gray-50 transition-colors">
                            <div id="drop-text" class="text-center">
                                <p class="text-sm text-gray-600"><strong>Click to upload</strong> or drag and drop</p>
                                <p class="text-xs text-gray-500 mt-2">PDF (Max. 10 pages)</p>
                            </div>
                            <input id="file-upload" type="file" name="file" class="hidden" accept=".pdf" />
                        </label>
                    </form>
                </div>

                <!-- Summary and Audio Section -->
                <div class="space-y-4">
                    <div onclick="openCardModal('cardModal1')"
                        class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md cursor-pointer hover:shadow-xl hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-800">Submission ID#001</h3>
                            </div>
                        </div>
                    </div>

                    <div onclick="openCardModal('cardModal1')"
                        class="flex items-center justify-between bg-white p-4 rounded-lg shadow-md cursor-pointer hover:shadow-xl hover:scale-105 transition-transform duration-300">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" />
                            </svg>
                            <div>
                                <h3 class="font-medium text-gray-800">Submission ID#002</h3>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span
                                class="bg-red-100 text-red-800 px-2 py-1 rounded-md text-xs font-medium">Expiring</span>
                            <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                <p class="text-xs sm:text-sm text-gray-500">
                    Compliance Checklist: Safety inspections, driver qualifications, etc.
                </p>
            </div>
        </div>

        <!-- Card Modal -->
        <div id="cardModal1" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl transform transition-all duration-300 scale-100"
                    id="modal-content">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Task Details</h3>
                    <p class="mb-2 text-sm sm:text-base text-gray-700"><strong>Vehicle:</strong> Truck #1234</p>
                    <p class="mb-2 text-sm sm:text-base text-gray-700"><strong>Task:</strong> Oil Change</p>
                    <p class="mb-4 text-sm sm:text-base text-gray-700"><strong>Date:</strong> 2024-03-25</p>
                    <div class="flex justify-end">
                        <button onclick="closeCardModal('cardModal1')"
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 focus:outline-none transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dropArea = document.getElementById('drop-area');
            const fileInput = document.getElementById('file-upload');
            const dropText = document.getElementById('drop-text');

            // Drag and drop events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.add('border-blue-500', 'bg-blue-50');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, () => {
                    dropArea.classList.remove('border-blue-500', 'bg-blue-50');
                }, false);
            });

            dropArea.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;
                updateDropText(files);
            });

            fileInput.addEventListener('change', () => {
                updateDropText(fileInput.files);
            });

            function updateDropText(files) {
                if (files.length > 0) {
                    dropText.innerHTML = `<p class="text-sm text-gray-600">Selected: ${files[0].name}</p>`;
                }
            }
        });

        function openCardModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = modal.querySelector('#modal-content');
            modal.classList.remove('hidden');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function closeCardModal(modalId) {
            const modal = document.getElementById(modalId);
            const modalContent = modal.querySelector('#modal-content');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                // Reset scale to 100 for next open
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 300); // Match transition duration
        }
    </script>
</x-app-layout>
