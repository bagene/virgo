
<script setup lang="ts">
import {onMounted, reactive} from "vue";
import {useOrder} from "@/composables/useOrder.ts";
import {CreateOrderData} from "@/types/order.ts";

const { orders, fetchOrders, createOrder, listenForOrderUpdates } = useOrder();

const form = reactive<CreateOrderData>({
    side: 'Buy',
    symbol: 'BTC',
    amount: 0,
    price: 0,
});

onMounted(async () => {
    await fetchOrders();
});

listenForOrderUpdates();
</script>

<template>
    <div class="px-4 py-6 sm:px-0">
        <div class="border-4 border-gray-200 rounded-lg p-8 space-y-4">
            <h1 class="text-3xl font-bold text-gray-900">Limit Order</h1>

            <form @submit.prevent="createOrder(form)">
                <div class="mb-4">
                    <label for="side" class="block text-gray-700 mb-2">Side</label>
                    <select
                        v-model="form.side"
                        id="side"
                        class="w-full px-3 py-2 border rounded"
                    >
                        <option value="Buy">Buy</option>
                        <option value="Sell">Sell</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="symbol" class="block text-gray-700 mb-2">Symbol</label>
                    <select
                        v-model="form.symbol"
                        id="symbol"
                        class="w-full px-3 py-2 border rounded"
                    >
                        <option value="BTC">BTC</option>
                        <option value="ETH">ETH</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-gray-700 mb-2">Price</label>
                    <input
                        v-model="form.price"
                        type="text"
                        id="price"
                        class="w-full px-3 py-2 border rounded"
                    />
                </div>

                <div class="mb-6">
                    <label for="amount" class="block text-gray-700 mb-2">Amount</label>
                    <input
                        v-model="form.amount"
                        type="text"
                        id="amount"
                        class="w-full px-3 py-2 border rounded"
                    />
                </div>

                <button
                    type="submit"
                    class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition duration-200"
                >
                    Place Order
                </button>
            </form>

            <div class="border-1 border-gray-300 rounded" >
                <table class="table table-auto w-full">
                    <thead>
                        <tr class="[&>th]:text-start [&>th]:p-2">
                            <th>Symbol</th>
                            <th>Side</th>
                            <th>Amount</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-if="orders.length > 0"
                            v-for="order in orders"
                            :key="order.id"
                            class="[&>td]:p-2 border-t border-gray-200"
                        >
                            <td>{{ order.symbol }}</td>
                            <td>{{ order.side_label }}</td>
                            <td>{{ order.amount }}</td>
                            <td>{{ order.price }}</td>
                            <td>{{ order.status_label }}</td>
                        </tr>
                        <tr v-else>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No orders found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
