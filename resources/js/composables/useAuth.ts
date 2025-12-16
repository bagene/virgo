import { ref, computed } from 'vue';
import { useStorage } from '@vueuse/core';
import axios from 'axios';
import type { User, LoginCredentials, LoginResponse } from '../types/auth';

const token = useStorage<string | null>('auth_token', null);
const user = ref<User | null>(null);
const isLoading = ref(false);
const error = ref<string | null>(null);

// Configure axios to use the token
if (token.value) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
}

export function useAuth() {
    const isAuthenticated = computed(() => !!token.value && !!user.value);

    const login = async (credentials: LoginCredentials): Promise<boolean> => {
        isLoading.value = true;
        error.value = null;

        try {
            const response = await axios.post<LoginResponse>('/api/login', credentials);

            if (response.data.access_token) {
                token.value = response.data.access_token;
                axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;

                // Fetch user profile after successful login
                await fetchUser();
                return true;
            }

            return false;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Login failed';
            token.value = null;
            delete axios.defaults.headers.common['Authorization'];
            return false;
        } finally {
            isLoading.value = false;
        }
    };

    const logout = async (): Promise<void> => {
        isLoading.value = true;
        error.value = null;

        try {
            await axios.post('/api/logout');
        } catch (err: any) {
            console.error('Logout error:', err);
        } finally {
            token.value = null;
            user.value = null;
            delete axios.defaults.headers.common['Authorization'];
            isLoading.value = false;
            window.location.reload();
        }
    };

    const fetchUser = async (): Promise<User | null> => {
        if (!token.value) {
            user.value = null;
            return null;
        }

        isLoading.value = true;
        error.value = null;

        try {
            const response = await axios.get<User>('/api/profile');
            user.value = response.data;
            return response.data;
        } catch (err: any) {
            error.value = err.response?.data?.message || 'Failed to fetch user';

            // If unauthorized, clear token
            if (err.response?.status === 401) {
                token.value = null;
                user.value = null;
                delete axios.defaults.headers.common['Authorization'];
            }

            return null;
        } finally {
            isLoading.value = false;
        }
    };

    // Initialize: fetch user if token exists
    if (token.value && !user.value) {
        fetchUser();
    }

    return {
        // State
        user: computed(() => user.value),
        token: computed(() => token.value),
        isAuthenticated,
        isLoading: computed(() => isLoading.value),
        error: computed(() => error.value),

        // Methods
        login,
        logout,
        fetchUser,
    };
}
