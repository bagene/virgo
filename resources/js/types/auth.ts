import {Asset} from "@/types/asset.ts";
import {Order} from "@/types/order.ts";

export interface User {
    id: number;
    name: string;
    email: string;
    balance: number;
    created_at: string;
    updated_at: string;

    assets: Asset[];
    orders: Order[];
}

export interface LoginCredentials {
    email: string;
    password: string;
}

export interface LoginResponse {
    access_token: string;
    token_type: string;
}

