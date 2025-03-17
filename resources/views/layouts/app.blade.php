<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bus Travels - Inventory Management</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "stat-orange": "#FF6B6B",
                        "stat-green": "#4CAF50",
                        "stat-blue": "#2196F3",
                        "stat-pink": "#E91E63",
                    },
                },
            },
        };
    </script>
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('sidebar.sidebar')
        <!-- End Sidebar -->

        <!-- Main Content -->
        <div class="flex-1 md:ml-64 lg:ml-80">
            {{ $slot }}
        </div>
        <!-- End Main Content Area -->
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const toggleIcon = document.getElementById('toggleIcon');

            sidebar.classList.toggle('-translate-x-full');
            toggleIcon.classList.toggle('rotate-180');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', (e) => {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = sidebar.querySelector('button');

            if (window.innerWidth < 768 &&
                !sidebar.contains(e.target) &&
                !toggleButton.contains(e.target) &&
                !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
                document.getElementById('toggleIcon').classList.remove('rotate-180');
            }
        });

        // Adjust sidebar on window resize
        window.addEventListener('resize', () => {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('-translate-x-full');
            }
        });
    </script>
    <!-- Scripts -->
    <script>
        // Basic search filter (client-side demo)
        const searchInput = document.querySelector('input[type="text"]');
        const tableRows = document.querySelectorAll('tbody tr:not(#empty-state)');
        const emptyState = document.getElementById('empty-state');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            let hasVisibleRows = false;

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(query);
                row.classList.toggle('hidden', !isVisible);
                if (isVisible) hasVisibleRows = true;
            });

            emptyState.classList.toggle('hidden', hasVisibleRows);
        });

        // Dropdown functionality with outside click to close
        const dropdowns = {
            notifications: {
                btn: 'notificationsBtn',
                dropdown: 'notificationsDropdown'
            },
            messages: {
                btn: 'messagesBtn',
                dropdown: 'messagesDropdown'
            },
            profile: {
                btn: 'profileBtn',
                dropdown: 'profileDropdown'
            }
        };

        Object.values(dropdowns).forEach(({
            btn,
            dropdown
        }) => {
            const btnElement = document.getElementById(btn);
            const dropdownElement = document.getElementById(dropdown);

            // Toggle dropdown on button click
            btnElement.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent click from bubbling to document
                const isOpen = !dropdownElement.classList.contains('hidden');
                // Close all other dropdowns
                Object.values(dropdowns).forEach(d => {
                    if (d.dropdown !== dropdown) {
                        document.getElementById(d.dropdown).classList.add('hidden');
                    }
                });
                // Toggle current dropdown
                dropdownElement.classList.toggle('hidden', isOpen);
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!dropdownElement.classList.contains('hidden')) {
                    const isClickInsideDropdown = dropdownElement.contains(e.target);
                    const isClickOnButton = btnElement.contains(e.target);
                    if (!isClickInsideDropdown && !isClickOnButton) {
                        dropdownElement.classList.add('hidden');
                    }
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</body>

</html>
