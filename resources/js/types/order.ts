export interface Order {
    id: number;
    user_id: number;
    side_label: 'Buy' | 'Sell';
    symbol: string;
    amount: number;
    price: number;
    status_label: 'Open' | 'Filled' | 'Cancelled';
    created_at: string;
    updated_at: string;
}

export interface FetchOrderParams {
    status?: 'Open' | 'Filled' | 'Cancelled' | null;
    symbol?: string | null;
}

export interface CreateOrderData {
    side: 'Buy' | 'Sell';
    symbol: string;
    amount: number;
    price: number;
}
