// ============================================================
// API Client - Axios instance with JWT auth
// ============================================================
import axios, { AxiosInstance } from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const BASE_URL = process.env.EXPO_PUBLIC_BACKEND_URL || 'http://127.0.0.1:8000';

export const api: AxiosInstance = axios.create({
  baseURL: `${BASE_URL}/api`,
  headers: { 'Content-Type': 'application/json' },
  timeout: 30000,
});

// Inject token from AsyncStorage
api.interceptors.request.use(async (config) => {
  const token = await AsyncStorage.getItem('peso_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Generic error normaliser
export function getApiError(err: any): string {
  if (err?.response?.data?.errors) {
    const firstError = Object.values(err.response.data.errors)[0] as string[];
    return firstError[0];
  }
  if (err?.response?.data?.message) return err.response.data.message;
  if (err?.response?.data?.error) return err.response.data.error;
  if (err?.message) return err.message;
  return 'Network error. Please try again.';
}

export const TOKEN_KEY = 'peso_token';
export const USER_KEY = 'peso_user';
