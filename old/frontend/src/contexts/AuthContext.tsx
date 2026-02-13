'use client';

import React, { createContext, useContext, useState, useEffect, type ReactNode } from 'react';
import { authService } from '@/services/api';
import type { User, LoginFormData, RegisterFormData } from '@/types/auth';

/**
 * Интерфейс контекста аутентификации
 */
interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (data: LoginFormData) => Promise<void>;
  register: (data: Omit<RegisterFormData, 'confirmPassword'>) => Promise<void>;
  logout: () => void;
  updateUser: (userData: Partial<User>) => void;
  isAuthenticated: boolean;
}

/**
 * Создание контекста аутентификации
 */
const AuthContext = createContext<AuthContextType | undefined>(undefined);

/**
 * Провайдер контекста аутентификации
 */
export const AuthProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState<boolean>(true);

  /**
   * Проверка аутентификации при загрузке приложения
   */
  useEffect(() => {
    const initAuth = async (): Promise<void> => {
      try {
        const token = localStorage.getItem('authToken');
        const savedUser = localStorage.getItem('user');
        
        if (token && savedUser) {
          // Сначала загружаем сохраненные данные пользователя
          setUser(JSON.parse(savedUser));
          
          // Затем пытаемся обновить данные с сервера
          try {
            const response = await authService.getProfile();
            setUser(response.user);
            localStorage.setItem('user', JSON.stringify(response.user));
          } catch (error) {
            // Если сервер недоступен, используем сохраненные данные
            console.warn('Не удалось обновить профиль с сервера, используем локальные данные');
          }
        }
      } catch (error) {
        console.error('Ошибка проверки аутентификации:', error);
        // Очищаем невалидный токен
        authService.logout();
      } finally {
        setLoading(false);
      }
    };

    initAuth();
  }, []);

  /**
   * Функция авторизации
   */
  const login = async (data: LoginFormData): Promise<void> => {
    try {
      const response = await authService.login(data);
      setUser(response.user);
      localStorage.setItem('authToken', response.token);
      localStorage.setItem('user', JSON.stringify(response.user));
    } catch (error) {
      throw error;
    }
  };

  /**
   * Функция регистрации
   */
  const register = async (data: Omit<RegisterFormData, 'confirmPassword'>): Promise<void> => {
    try {
      const response = await authService.register(data);
      setUser(response.user);
      localStorage.setItem('authToken', response.token);
      localStorage.setItem('user', JSON.stringify(response.user));
    } catch (error) {
      throw error;
    }
  };

  /**
   * Функция выхода из системы
   */
  const logout = (): void => {
    authService.logout();
    setUser(null);
  };

  /**
   * Функция обновления данных пользователя
   */
  const updateUser = (userData: Partial<User>): void => {
    if (user) {
      const updatedUser = { ...user, ...userData };
      setUser(updatedUser);
      localStorage.setItem('user', JSON.stringify(updatedUser));
    }
  };

  const isAuthenticated = !!user;

  const value: AuthContextType = {
    user,
    loading,
    login,
    register,
    logout,
    updateUser,
    isAuthenticated
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
};

/**
 * Хук для использования контекста аутентификации
 */
export const useAuth = (): AuthContextType => {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth должен использоваться внутри AuthProvider');
  }
  return context;
};
