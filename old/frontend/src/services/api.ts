import axios from 'axios';
import type { AuthResponse, LoginFormData, RegisterFormData, User } from '@/types/auth';

/**
 * Базовый URL для API
 */
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:5000';

/**
 * Создание экземпляра axios с базовыми настройками
 */
const apiClient = axios.create({
  baseURL: `${API_BASE_URL}/api`,
  headers: {
    'Content-Type': 'application/json',
  },
});

/**
 * Интерцептор для добавления токена к запросам
 */
apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

/**
 * Интерцептор для обработки ответов
 */
apiClient.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      // Удаляем токен при ошибке 401
      localStorage.removeItem('authToken');
      localStorage.removeItem('user');
    }
    return Promise.reject(error);
  }
);

/**
 * Сервис для аутентификации
 */
export const authService = {
  /**
   * Регистрация пользователя
   */
  register: async (data: Omit<RegisterFormData, 'confirmPassword'>): Promise<AuthResponse> => {
    const response = await apiClient.post<AuthResponse>('/auth/register', data);
    return response.data;
  },

  /**
   * Авторизация пользователя
   */
  login: async (data: LoginFormData): Promise<AuthResponse> => {
    const response = await apiClient.post<AuthResponse>('/auth/login', data);
    return response.data;
  },

  /**
   * Получение профиля пользователя
   */
  getProfile: async (): Promise<{ user: User }> => {
    const response = await apiClient.get<{ user: User }>('/auth/profile');
    return response.data;
  },

  /**
   * Обновление профиля пользователя
   */
  updateProfile: async (data: Partial<Pick<User, 'name' | 'email' | 'avatar' | 'bio' | 'location'>>): Promise<{ message: string; user: User }> => {
    const response = await apiClient.put<{ message: string; user: User }>('/auth/profile', data);
    return response.data;
  },

  /**
   * Выход из системы (очистка локального хранилища)
   */
  logout: (): void => {
    localStorage.removeItem('authToken');
    localStorage.removeItem('user');
  }
};

/**
 * Сервис для администрирования
 */
export const adminService = {
  /**
   * Получение списка всех пользователей
   */
  getAllUsers: async (): Promise<{ users: User[]; total: number }> => {
    const response = await apiClient.get<{ users: User[]; total: number }>('/auth/users');
    return response.data;
  },

  /**
   * Обновление роли пользователя
   */
  updateUserRole: async (userId: number, role: string): Promise<{ message: string; user: User }> => {
    const response = await apiClient.put<{ message: string; user: User }>(`/auth/users/${userId}/role`, { role });
    return response.data;
  },

  /**
   * Получение списка всех мемориалов
   */
  getAllMemorials: async (): Promise<{ memorials: any[]; stats: any; total: number }> => {
    const response = await apiClient.get<{ memorials: any[]; stats: any; total: number }>('/auth/memorials');
    return response.data;
  },

  /**
   * Обновление статуса мемориала
   */
  updateMemorialStatus: async (memorialId: number, status: string): Promise<{ message: string; memorial: any }> => {
    const response = await apiClient.put<{ message: string; memorial: any }>(`/auth/memorials/${memorialId}/status`, { status });
    return response.data;
  },

  /**
   * Удаление мемориала
   */
  deleteMemorial: async (memorialId: number): Promise<{ message: string }> => {
    const response = await apiClient.delete<{ message: string }>(`/auth/memorials/${memorialId}`);
    return response.data;
  }
};

export const uploadService = {
  uploadImage: async (file: File): Promise<{ url: string }> => {
    const form = new FormData();
    form.append('file', file);
    const res = await apiClient.post<{ url: string }>('/uploads', form, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });
    // Преобразуем относительный путь /uploads/... в абсолютный URL, чтобы корректно отображалось на фронтенде
    const relative = res.data.url;
    const absoluteUrl = relative.startsWith('http') ? relative : `${API_BASE_URL}${relative}`;
    return { url: absoluteUrl };
  }
};

export default apiClient;
