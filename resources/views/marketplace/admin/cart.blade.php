<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200 rounded-lg">
                    {{ session('success') }}
                </div>
            @elseif (session('info'))
                <div class="mb-6 p-4 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 rounded-lg">
                    {{ session('info') }}
                </div>
            @elseif (session('error'))
                <div class="mb-6 p-4 bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Your Cart</h1>
                <div class="mt-4 sm:mt-0 flex items-center gap-4">
                    <a href="{{ route('marketplace.admin.store') }}"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition duration-200">
                        <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                    </a>
                    @if ($cartItems->isNotEmpty())
                        <label class="flex items-center text-gray-700 dark:text-gray-300">
                            <input type="checkbox" id="select-all-global"
                                class="mr-2 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                onchange="toggleSelectAllGlobal()">
                            Select All
                        </label>
                    @endif
                </div>
            </div>

            @if ($cartItems->isNotEmpty())
                <form action="{{ route('marketplace.admin.cart.checkout') }}" method="POST" id="cart-form">
                    @csrf
                    <!-- Cart Items Grouped by Vendor -->
                    @foreach ($cartItems->groupBy('product.vendor_id') as $vendorId => $items)
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center">
                                    <i class="fas fa-store mr-2"></i> {{ $items->first()->product->vendor->user->name }}
                                </h2>
                                <label class="flex items-center text-gray-700 dark:text-gray-300">
                                    <input type="checkbox" class="select-all-vendor"
                                        data-vendor-id="{{ $vendorId }}"
                                        class="mr-2 h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                        onchange="toggleSelectAllVendor('{{ $vendorId }}')">
                                    Select All
                                </label>
                            </div>
                            <div
                                class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-md">
                                <table class="w-full text-sm text-left divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th
                                                class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300 w-12">
                                                <i class="fas fa-check-square"></i>
                                            </th>
                                            <th
                                                class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Product</th>
                                            <th
                                                class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Price</th>
                                            <th
                                                class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Quantity</th>
                                            <th
                                                class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Total</th>
                                            <th
                                                class="px-4 py-3 sm:px-6 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">
                                                Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($items as $item)
                                            <tr
                                                class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 {{ request()->query('buy_product_id') == $item->product_id ? 'bg-green-50 dark:bg-green-800' : '' }}">
                                                <td class="px-4 py-4 sm:px-6 w-12">
                                                    <input type="checkbox" name="selected_items[]"
                                                        value="{{ $item->id }}"
                                                        class="item-checkbox vendor-{{ $vendorId }} h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                                        onchange="updateSelectAll('{{ $vendorId }}')">
                                                </td>
                                                <td
                                                    class="px-4 py-4 sm:px-6 text-gray-900 dark:text-gray-100 truncate max-w-xs">
                                                    {{ $item->product->name }}</td>
                                                <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                                    ₱{{ number_format($item->product->price, 2) }}</td>
                                                <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                                    <div class="flex items-center gap-2">
                                                        <button type="button"
                                                            onclick="updateQuantity('{{ $item->id }}', -1)"
                                                            class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500 disabled:opacity-50"
                                                            {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                                        <span class="font-medium">{{ $item->quantity }}</span>
                                                        <button type="button"
                                                            onclick="updateQuantity('{{ $item->id }}', 1)"
                                                            class="px-2 py-1 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500"
                                                            {{ $item->product->type === 'items' && $item->quantity >= $item->product->stock ? 'disabled' : '' }}>+</button>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 sm:px-6 text-gray-700 dark:text-gray-300">
                                                    ₱{{ number_format($item->product->price * $item->quantity, 2) }}
                                                </td>
                                                <td class="px-4 py-4 sm:px-6">
                                                    <form action="{{ route('marketplace.admin.cart.remove') }}"
                                                        method="POST" class="inline"
                                                        onsubmit="return confirm('Remove {{ $item->product->name }} from your cart?');">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $item->product_id }}">
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div
                                class="mt-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex justify-between items-center">
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Vendor Total:
                                    ₱{{ number_format($items->sum(fn($item) => $item->product->price * $item->quantity), 2) }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <!-- Overall Cart Summary and Checkout -->
                    <div
                        class="mt-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md flex flex-col sm:flex-row justify-between items-center gap-4">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            Grand Total:
                            ₱{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 2) }}
                        </p>
                        <div class="flex gap-4">
                            <button type="button" onclick="removeSelected()"
                                class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 dark:bg-red-500 dark:hover:bg-red-600">
                                Remove Selected
                            </button>
                            <button type="submit"
                                class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 dark:bg-green-500 dark:hover:bg-green-600">
                                Checkout Selected
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <!-- Empty Cart State -->
                <div class="text-center py-12">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-lg text-gray-700 dark:text-gray-300">Your cart is empty.</p>
                    <a href="{{ route('marketplace.admin.store') }}"
                        class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for Quantity Updates and Checkbox Handling -->
    <script>
        async function updateQuantity(cartItemId, change) {
            const response = await fetch('{{ route('marketplace.admin.cart.update') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    cart_item_id: cartItemId,
                    change: change
                }),
            });

            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to update quantity. Please try again.');
            }
        }

        function toggleSelectAllVendor(vendorId) {
            const vendorCheckbox = document.querySelector(`.select-all-vendor[data-vendor-id="${vendorId}"]`);
            const itemCheckboxes = document.querySelectorAll(`.vendor-${vendorId}`);
            itemCheckboxes.forEach(checkbox => checkbox.checked = vendorCheckbox.checked);
            updateSelectAllGlobal();
        }

        function toggleSelectAllGlobal() {
            const globalCheckbox = document.getElementById('select-all-global');
            const allVendorCheckboxes = document.querySelectorAll('.select-all-vendor');
            const allItemCheckboxes = document.querySelectorAll('.item-checkbox');
            allVendorCheckboxes.forEach(checkbox => checkbox.checked = globalCheckbox.checked);
            allItemCheckboxes.forEach(checkbox => checkbox.checked = globalCheckbox.checked);
        }

        function updateSelectAll(vendorId) {
            const vendorCheckbox = document.querySelector(`.select-all-vendor[data-vendor-id="${vendorId}"]`);
            const itemCheckboxes = document.querySelectorAll(`.vendor-${vendorId}`);
            const allChecked = Array.from(itemCheckboxes).every(checkbox => checkbox.checked);
            vendorCheckbox.checked = allChecked;
            updateSelectAllGlobal();
        }

        function updateSelectAllGlobal() {
            const globalCheckbox = document.getElementById('select-all-global');
            const allItemCheckboxes = document.querySelectorAll('.item-checkbox');
            globalCheckbox.checked = Array.from(allItemCheckboxes).every(checkbox => checkbox.checked);
        }

        async function removeSelected() {
            const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked')).map(checkbox =>
                checkbox.value);
            if (selectedItems.length === 0) {
                alert('Please select items to remove.');
                return;
            }

            if (!confirm(`Remove ${selectedItems.length} selected item(s) from your cart?`)) return;

            const response = await fetch('{{ route('marketplace.admin.cart.remove-selected') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    selected_items: selectedItems
                }),
            });

            if (response.ok) {
                location.reload();
            } else {
                alert('Failed to remove selected items. Please try again.');
            }
        }
    </script>

    <!-- Include Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</x-app-layout>
