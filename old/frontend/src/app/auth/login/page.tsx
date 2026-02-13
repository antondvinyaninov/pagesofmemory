'use client';

import React, { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { LoginForm } from '@/components/auth/LoginForm';
import { useAuth } from '@/contexts/AuthContext';

/**
 * Страница авторизации
 */
export default function LoginPage() {
  const { isAuthenticated, loading } = useAuth();
  const router = useRouter();

  /**
   * Редирект авторизованных пользователей
   */
  useEffect(() => {
    if (!loading && isAuthenticated) {
      router.push('/');
    }
  }, [isAuthenticated, loading, router]);

  // Показываем лоадер во время проверки аутентификации
  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-gray-50">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  // Если пользователь уже авторизован, не показываем форму
  if (isAuthenticated) {
    return null;
  }

  return (
    <div className="bg-gray-50 py-16 px-4">
      <div className="max-w-md w-full mx-auto space-y-8">
        <LoginForm />
        
        {/* Ссылка на регистрацию */}
        <div className="text-center">
          <p className="text-gray-500">
            Нет аккаунта?{' '}
            <Link
              href="/auth/register"
              className="text-red-500 hover:text-red-600 font-medium transition-colors"
              aria-label="Перейти к регистрации"
            >
              Зарегистрируйтесь
            </Link>
          </p>
        </div>
      </div>
    </div>
  );
}
