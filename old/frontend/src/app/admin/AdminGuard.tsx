'use client';

import React from 'react';
import Link from 'next/link';
import { useAuth } from '@/contexts/AuthContext';
import { hasMinRole } from '@/middleware/adminAuth';
import type { UserRole } from '@/types/auth';
import {
  ShieldExclamationIcon,
  UserIcon,
  ArrowLeftIcon
} from '@heroicons/react/24/outline';

interface AdminGuardProps {
  children: React.ReactNode;
  minRole?: UserRole;
  fallbackComponent?: React.ReactNode;
}

/**
 * Компонент для защиты админских страниц
 */
const AdminGuard: React.FC<AdminGuardProps> = ({ 
  children, 
  minRole = 'moderator',
  fallbackComponent 
}) => {
  const { user, isAuthenticated, loading } = useAuth();

  // Показываем загрузку
  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-red-500 mx-auto mb-4"></div>
          <p className="text-gray-500">Проверка прав доступа...</p>
        </div>
      </div>
    );
  }

  // Пользователь не авторизован
  if (!isAuthenticated) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center max-w-md mx-auto p-6">
          <UserIcon className="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h2 className="text-2xl font-bold text-slate-700 mb-2">Требуется авторизация</h2>
          <p className="text-gray-500 mb-6">
            Для доступа к административной панели необходимо войти в систему
          </p>
          <div className="space-x-4">
            <Link 
              href="/auth/login"
              className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
            >
              Войти
            </Link>
            <Link 
              href="/"
              className="border border-red-500 text-red-500 hover:bg-red-50 px-6 py-2 rounded-lg transition-colors"
            >
              На главную
            </Link>
          </div>
        </div>
      </div>
    );
  }

  // Пользователь не имеет достаточных прав
  if (!hasMinRole(user, minRole)) {
    return fallbackComponent || (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center max-w-md mx-auto p-6">
          <ShieldExclamationIcon className="w-16 h-16 text-red-400 mx-auto mb-4" />
          <h2 className="text-2xl font-bold text-slate-700 mb-2">Доступ запрещен</h2>
          <p className="text-gray-500 mb-2">
            У вас недостаточно прав для доступа к этой странице
          </p>
          <p className="text-sm text-gray-400 mb-6">
            Требуется роль: <span className="font-medium">{minRole}</span> или выше<br />
            Ваша роль: <span className="font-medium">{user?.role || 'user'}</span>
          </p>
          <div className="space-x-4">
            <Link 
              href="/profile"
              className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors inline-flex items-center gap-2"
            >
              <ArrowLeftIcon className="w-4 h-4" />
              В профиль
            </Link>
            <Link 
              href="/"
              className="border border-red-500 text-red-500 hover:bg-red-50 px-6 py-2 rounded-lg transition-colors"
            >
              На главную
            </Link>
          </div>
        </div>
      </div>
    );
  }

  // Пользователь имеет достаточные права
  return <>{children}</>;
};

export default AdminGuard;
