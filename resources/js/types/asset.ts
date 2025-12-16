import {User} from "@/types/auth.ts";

export interface Asset {
    id: number;
    user_id: number;
    amount: number;
    locked_amount: number;
    symbol: string;
    created_at: string;
    updated_at: string;

    user: User;
}
