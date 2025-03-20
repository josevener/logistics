<x-app-layout>
    <div class="flex-1 p-4 sm:p-6 md:p-8 lg:p-10 bg-gray-100 min-h-screen dark:bg-gray-900">
        <div class="max-w-7xl mx-auto">
            <h1 class="text-2xl font-semibold mb-6 dark:text-white">Your Cart</h1>

            @if (session('cart') && count(session('cart')) > 0)
                <table class="w-full text-sm text-left border border-gray-200 dark:border-gray-700 rounded-lg">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">Product
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">Price
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">Quantity
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">Total
                            </th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase dark:text-gray-300">Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach (session('cart') as $id => $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-4 py-4 text-gray-900 dark:text-gray-100">{{ $item['name'] }}</td>
                                <td class="px-4 py-4 text-gray-700 dark:text-gray-300">
                                    ₱{{ number_format($item['price'], 2) }}</td>
                                <td class="px-4 py-4 text-gray-700 dark:text-gray-300">{{ $item['quantity'] }}</td>
                                <td class="px-4 py-4 text-gray-700 dark:text-gray-300">
                                    ₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                <td class="px-4 py-4">
                                    <form action="{{ route('marketplace.admin.cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $id }}">
                                        <button type="submit" class="text-red-500 hover:text-red-700">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-6 flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Total:
                        ₱{{ number_format(array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], session('cart'))), 2) }}
                    </p>
                    <form action="{{ route('marketplace.admin.cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Checkout</button>
                    </form>
                </div>
            @else
                <p class="text-gray-700 dark:text-gray-300">Your cart is empty.</p>
            @endif

            <a href="{{ route('marketplace.admin.store') }}"
                class="mt-4 inline-block text-blue-500 hover:text-blue-700 dark:text-blue-400">Continue Shopping</a>
        </div>
    </div>
</x-app-layout>
