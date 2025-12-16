import {CreateOrderData, FetchOrderParams, Order} from "@/types/order.ts";
import {computed, ref} from "vue";
import axios from "axios";
import {useEcho} from "@laravel/echo-vue";
import {useAuth} from "@/composables/useAuth.ts";
import {useToast} from "@/composables/useToast.ts";

const orders = ref<Order[]>([]);
const symbols = computed<string[]>((): string[] => {
    if (symbols.value?.length > 0) {
        return symbols.value;
    }

    const uniqueSymbols = new Set<string>();
    orders.value.forEach(order => uniqueSymbols.add(order.symbol));
    return Array.from(uniqueSymbols);
});
const lastUsedParams = ref<FetchOrderParams>({});

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
        } finally {
            lastUsedParams.value = params;
        }
    }

    const createOrder = async (orderData: CreateOrderData) => {
        try {
            const response = await axios.post('/api/orders', orderData);
            orders.value.push(response.data);
        } catch (error) {
            console.error('Error creating order:', error);
        }
    }

    const listenForOrderUpdates = () => {
        const { user } = useAuth();

        if (!user) return;

        useEcho(
            `user.${user.value?.id}`,
            'OrderMatched',
            async () => {
                await fetchOrders(lastUsedParams.value);
                useToast().add({
                    title: 'Order Matched',
                    message: 'One of your orders has been matched successfully.',
                    type: 'success',
                })
            },
        )
    };

    return {
        orders,
        fetchOrders,
        symbols,
        createOrder,
        listenForOrderUpdates,
    };
}


