<x-app-layout>


    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            @include('navigation.header')

            <!-- Main Grid Content -->
            <div class="grid grid-cols-1 gap-4 sm:gap-6">


                <!-- Main Card -->
                <div class="max-w-6xl mx-auto pt-24 sm:pt-28 md:pt-32 px-4 sm:px-6 lg:px-8">
                    <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row gap-6 sm:gap-8 p-6 sm:p-8">
                        <!-- Left Content: Account Settings Form -->
                        <div class="flex-1 w-full">
                            <!-- Tabs -->
                            <div class="border-b flex flex-wrap gap-4 sm:gap-6 lg:gap-8 mb-4 sm:mb-6">
                                <button
                                    class="tab-button active px-3 py-2 text-sm sm:text-base font-medium focus:outline-none"
                                    data-tab="account">
                                    Account Settings
                                </button>
                                <button
                                    class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-500 focus:outline-none"
                                    data-tab="company">
                                    Company Settings
                                </button>
                                <button
                                    class="tab-button px-3 py-2 text-sm sm:text-base font-medium text-gray-500 focus:outline-none"
                                    data-tab="documents">
                                    Documents
                                </button>
                            </div>

                            <!-- Profile Tab Contents -->
                            <div class="tab-content active bg-white p-1 shadow rounded-lg" id="account">


                                <!-- Profile Information (Default View) -->
                                <div id="profile-info">
                                    <div class="space-y-4">
                                        <div class="flex flex-col">
                                            <label class="text-sm font-medium text-gray-600">Full Name:</label>
                                            <p class="text-lg text-gray-900 font-semibold">
                                                {{ Auth::user()->vendor->fname }}
                                                {{ Auth::user()->vendor->mname }}
                                                {{ Auth::user()->vendor->lname }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col">
                                            <label class="text-sm font-medium text-gray-600">Address:</label>
                                            <p class="text-lg text-gray-900 font-semibold">
                                                {{ Auth::user()->vendor->address }}
                                            </p>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div class="flex flex-col">
                                                <label class="text-sm font-medium text-gray-600">Email:</label>
                                                <p class="text-lg text-gray-900 font-semibold">
                                                    {{ Auth::user()->email }}
                                                </p>
                                            </div>
                                            <div class="flex flex-col">
                                                <label class="text-sm font-medium text-gray-600">Contact Number:</label>
                                                <p class="text-lg text-gray-900 font-semibold">
                                                    {{ Auth::user()->vendor->contact_info }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile Update Form (Hidden by Default) -->
                                <div id="profile-update-form" class="hidden mt-0">
                                    @include('profile.partials.update-profile-information-form', [
                                        'user' => Auth::user(),
                                    ])

                                </div>
                            </div>


                        </div>
                        <!-- Right Content: Profile Card -->
                        <div class="w-full md:w-64 lg:w-72 flex flex-col md:border-l md:pl-6 lg:pl-8">
                            <div class="profile-card flex flex-col items-center h-full">
                                <div class="relative flex justify-center">
                                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-DdqTN0X4rJ55yMBHnX70tuun4EN5TY.png"
                                        alt="Profile" class="w-24 h-24 rounded-full object-cover shadow-md" />
                                </div>
                                <h2 class="mt-4 text-xl font-semibold text-gray-800 text-center">Sample User</h2>
                                <p class="text-sm text-gray-500 text-center">Bus Terminal Owner</p>

                                <!-- Push Delete Account Section to Bottom -->
                                <div class="flex-grow"></div>

                                <!-- Delete Account Section -->
                                <div class="w-full px-4">
                                    @include('profile.partials.delete-user-form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal (Hidden by Default) -->
    <div id="cancel-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <h2 class="text-lg font-semibold text-gray-800">Cancel Update?</h2>
            <p class="text-sm text-gray-600 mt-2">Any unsaved changes will be lost. Are you sure you want to cancel?</p>

            <div class="mt-4 flex justify-end gap-3">
                <button id="keep-editing" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    No, Keep Editing
                </button>
                <button id="confirm-cancel" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    Yes, Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.querySelectorAll('.tab-button').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const tabId = tab.getAttribute('data-tab');
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>

    <script>
        document.getElementById('toggle-profile-update').addEventListener('click', function() {
            let profileInfo = document.getElementById('profile-info');
            let profileForm = document.getElementById('profile-update-form');
            let updateButton = document.getElementById('toggle-profile-update');
            let cancelModal = document.getElementById('cancel-modal');

            if (!profileForm.classList.contains('hidden')) {
                // Show modal instead of instant cancel
                cancelModal.classList.remove('hidden');
            } else {
                profileForm.classList.remove('hidden');
                profileInfo.classList.add('hidden');
                updateButton.textContent = 'Cancel Update';
                updateButton.classList.remove('bg-blue-500', 'text-white');
                updateButton.classList.add('border', 'border-red-500', 'text-red-500', 'bg-transparent',
                    'hover:bg-red-500', 'hover:text-white');
            }
        });

        document.getElementById('keep-editing').addEventListener('click', function() {
            document.getElementById('cancel-modal').classList.add('hidden');
        });

        document.getElementById('confirm-cancel').addEventListener('click', function() {
            let profileInfo = document.getElementById('profile-info');
            let profileForm = document.getElementById('profile-update-form');
            let updateButton = document.getElementById('toggle-profile-update');

            profileForm.classList.add('hidden');
            profileInfo.classList.remove('hidden');
            updateButton.textContent = 'Update Profile';
            updateButton.classList.remove('border', 'border-red-500', 'text-red-500', 'bg-transparent',
                'hover:bg-red-500', 'hover:text-white');
            updateButton.classList.add('bg-blue-500', 'text-white');

            document.getElementById('cancel-modal').classList.add('hidden');
        });
    </script>



</x-app-layout>
