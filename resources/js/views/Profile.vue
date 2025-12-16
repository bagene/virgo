<script setup lang="ts">

import {useAuth} from "@/composables/useAuth.ts";
import {User} from "@/types/auth.ts";
import {useOrder} from "@/composables/useOrder.ts";
import {onMounted, reactive, watch} from "vue";
import {useEcho} from "@laravel/echo-vue";

const { user } = useAuth();
const { orders, fetchOrders, symbols, listenForOrderUpdates } = useOrder();

const params = reactive<{symbol: string|null, status: string|null}>({
    symbol: null,
    status: null,
});

const selectSymbol = (symbol: string) => {
    if (params.symbol === symbol) {
        params.symbol = null;

        return;
    }

    params.symbol = symbol;
};

const selectStatus = (status: string) => {
    if (params.status === status) {
        params.status = null;

        return;
    }

    params.status = status;
};

onMounted(async () => {
    await fetchOrders();
});

watch(params, async () => {
    await fetchOrders(params);
});

listenForOrderUpdates();
</script>
<template>
    <div class="px-10 space-y-6 text-gray-600">
        <div class="space-y-2">
            <h1 class="text-xl font-semibold">Balance</h1>
            <h3>${{ (user as User).balance.toFixed() }}</h3>
        </div>

        <div>
            <h1 class="text-xl font-semibold">Assets</h1>

            <div
              v-for="asset in user?.assets || []"
              :key="asset.id"
              class="border border-gray-300 rounded p-4 mb-4"
            >
                <p>Type: {{ asset.symbol }}</p>
                <p>Value: {{ asset.amount }}</p>
            </div>
        </div>

        <div class="px-4 py-4 space-y-2 rounded border-1 border-gray-300">
            <h1 class="text-xl font-semibold">Orders</h1>

            <div class="space-y-2">
                <h3 class="font-medium">Symbols: </h3>
                <div class="flex space-x-2 mt-2">
                    <button
                      v-for="symbol in symbols"
                      :key="symbol"
                      @click="selectSymbol(symbol)"
                      class="cursor-pointer px-3 py-1 rounded-full text-sm"
                      :class="{
                          'bg-blue-500 text-white': params.symbol === symbol,
                          'bg-gray-200 hover:bg-gray-300': params.symbol !== symbol
                      }"
                    >
                        {{ symbol }}
                    </button>
                </div>

                <h3 class="font-medium">Status: </h3>
                <div class="flex space-x-2 mt-2">
                    <button
                        v-for="status in ['open', 'filled', 'canceled']"
                        :key="status"
                        @click="selectStatus(status)"
                        class="cursor-pointer px-3 py-1 rounded-full text-sm"
                        :class="{
                            'bg-blue-500 text-white': params.status === status,
                            'bg-gray-200 hover:bg-gray-300': params.status !== status
                        }"
                    >
                        {{ status }}
                    </button>
                </div>
            </div>

            <table class="table-auto w-full">
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
</template>


