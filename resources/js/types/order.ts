export interface Order {
    id: number;
    user_id: number;
    side_label: 'buy' | 'sell';
    symbol: string;
    amount: number;
    price: number;
    status_label: 'open' | 'filled' | 'cancelled';
    created_at: string;
    updated_at: string;
}
