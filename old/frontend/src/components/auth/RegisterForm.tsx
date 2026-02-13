'use client';

import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { 
  EyeIcon, 
  EyeSlashIcon, 
  UserPlusIcon 
} from '@heroicons/react/24/outline';
import { useAuth } from '@/contexts/AuthContext';
import { registerSchema, type RegisterFormData } from '@/types/auth';

/**
 * Компонент формы регистрации
 */
export const RegisterForm: React.FC = () => {
  const { register: registerUser } = useAuth();
  const [showPassword, setShowPassword] = useState<boolean>(false);
  const [showConfirmPassword, setShowConfirmPassword] = useState<boolean>(false);
  const [isLoading, setIsLoading] = useState<boolean>(false);
  const [apiError, setApiError] = useState<string>('');

  const {
    register,
    handleSubmit,
    formState: { errors }
  } = useForm<RegisterFormData>({
    resolver: zodResolver(registerSchema)
  });

  /**
   * Обработчик отправки формы
   */
  const handleFormSubmit = async (data: RegisterFormData): Promise<void> => {
    try {
      setIsLoading(true);
      setApiError('');
      
      // Исключаем confirmPassword из данных для отправки
      const { confirmPassword, ...registrationData } = data;
      await registerUser(registrationData);
    } catch (error: any) {
      const errorMessage = error.response?.data?.error || 'Произошла ошибка при регистрации';
      setApiError(errorMessage);
    } finally {
      setIsLoading(false);
    }
  };

  /**
   * Переключение видимости пароля
   */
  const handleTogglePassword = (): void => {
    setShowPassword(prev => !prev);
  };

  /**
   * Переключение видимости подтверждения пароля
   */
  const handleToggleConfirmPassword = (): void => {
    setShowConfirmPassword(prev => !prev);
  };

  return (
    <div className="w-full max-w-md mx-auto bg-white rounded-card shadow-lg p-8">
      <div className="text-center mb-6">
        <h1 className="text-2xl font-bold text-slate-700 mb-2">Регистрация</h1>
        <p className="text-gray-500">Создайте новый аккаунт</p>
      </div>

      <form onSubmit={handleSubmit(handleFormSubmit)} className="space-y-4">
        {/* Имя */}
        <div>
          <label htmlFor="name" className="block text-sm font-medium text-gray-800 mb-2">
            Имя
          </label>
          <input
            {...register('name')}
            type="text"
            id="name"
            className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
            placeholder="Введите ваше имя"
          />
          {errors.name && (
            <p className="text-red-500 text-sm mt-1">{errors.name.message}</p>
          )}
        </div>

        {/* Email */}
        <div>
          <label htmlFor="email" className="block text-sm font-medium text-gray-800 mb-2">
            Email
          </label>
          <input
            {...register('email')}
            type="email"
            id="email"
            className="w-full px-4 py-3 border border-gray-300 rounded-md focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors shadow-sm"
            placeholder="example@email.com"
          />
          {errors.email && (
            <p className="text-red-500 text-sm mt-1">{errors.email.message}</p>
          )}
        </div>

        {/* Пароль */}
        <div>
          <label htmlFor="password" className="block text-sm font-medium text-gray-800 mb-2">
            Пароль
          </label>
          <div className="relative">
            <input
              {...register('password')}
              type={showPassword ? 'text' : 'password'}
              id="password"
              className="w-full px-4 py-3 pr-12 border-2 border-gray-border rounded-md focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-colors"
              placeholder="Введите пароль"
            />
            <button
              type="button"
              onClick={handleTogglePassword}
              className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-800 transition-colors"
              tabIndex={0}
              aria-label={showPassword ? 'Скрыть пароль' : 'Показать пароль'}
            >
              {showPassword ? <EyeSlashIcon className="h-5 w-5" /> : <EyeIcon className="h-5 w-5" />}
            </button>
          </div>
          {errors.password && (
            <p className="text-red-500 text-sm mt-1">{errors.password.message}</p>
          )}
        </div>

        {/* Подтверждение пароля */}
        <div>
          <label htmlFor="confirmPassword" className="block text-sm font-medium text-gray-800 mb-2">
            Подтвердите пароль
          </label>
          <div className="relative">
            <input
              {...register('confirmPassword')}
              type={showConfirmPassword ? 'text' : 'password'}
              id="confirmPassword"
              className="w-full px-4 py-3 pr-12 border-2 border-gray-border rounded-md focus:outline-none focus:border-accent focus:ring-2 focus:ring-accent/20 transition-colors"
              placeholder="Подтвердите пароль"
            />
            <button
              type="button"
              onClick={handleToggleConfirmPassword}
              className="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-800 transition-colors"
              tabIndex={0}
              aria-label={showConfirmPassword ? 'Скрыть пароль' : 'Показать пароль'}
            >
              {showConfirmPassword ? <EyeSlashIcon className="h-5 w-5" /> : <EyeIcon className="h-5 w-5" />}
            </button>
          </div>
          {errors.confirmPassword && (
            <p className="text-red-500 text-sm mt-1">{errors.confirmPassword.message}</p>
          )}
        </div>

        {/* Ошибка API */}
        {apiError && (
          <div className="bg-red-50 border border-red-100 rounded-md p-4">
            <p className="text-red-500 text-sm">{apiError}</p>
          </div>
        )}

        {/* Кнопка отправки */}
        <button
          type="submit"
          disabled={isLoading}
          className="w-full bg-red-500 hover:bg-red-600 disabled:bg-red-500/50 text-white font-medium py-3 px-4 rounded-md transition-all duration-300 hover:-translate-y-0.5 hover:shadow-card-hover flex items-center justify-center gap-2"
          aria-label="Зарегистрироваться"
        >
          {isLoading ? (
            <div className="animate-spin rounded-full h-5 w-5 border-b-2 border-white"></div>
          ) : (
            <>
              <UserPlusIcon className="h-5 w-5" />
              Зарегистрироваться
            </>
          )}
        </button>
      </form>
    </div>
  );
};
