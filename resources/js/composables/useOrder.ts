import {Order} from "@/types/order.ts";
import {computed, ref} from "vue";
import axios from "axios";

interface FetchOrderParams {
    status?: 'open' | 'filled' | 'cancelled' | null;
    symbol?: string | null;
}

const orders = ref<Order[]>([]);
const symbols = computed(() => {
    if (symbols.value?.length > 0) {
        return symbols.value;
    }

    const uniqueSymbols = new Set<string>();
    orders.value.forEach(order => uniqueSymbols.add(order.symbol));
    return Array.from(uniqueSymbols);
});

export function useOrder() {
    const fetchOrders = async (params: FetchOrderParams = {}) => {
        try {
            const response = await axios.get(
                '/api/orders',
                {
                    params,
                }
            );

            orders.value = response.data;
        } catch (error) {
            console.error('Error fetching orders:', error);
        }
    }

    return {
        orders,
        fetchOrders,
        symbols
    };
}


