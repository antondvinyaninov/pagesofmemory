'use client';

import React, { useState } from 'react';
import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { useAuth } from '@/contexts/AuthContext';
import { AdminGuard } from '@/components/admin/AdminGuard';
import {
  HomeIcon,
  UsersIcon,
  BuildingLibraryIcon,
  ChartBarIcon,
  ExclamationTriangleIcon,
  CogIcon,
  ArrowLeftOnRectangleIcon,
  Bars3Icon,
  XMarkIcon,
  ArrowLeftIcon
} from '@heroicons/react/24/outline';
import Avatar from '@/components/layout/Avatar';

/**
 * Элементы навигации админ-панели
 */
const navigationItems = [
  {
    name: 'Dashboard',
    href: '/admin',
    icon: HomeIcon,
    description: 'Общая статистика'
  },
  {
    name: 'Пользователи',
    href: '/admin/users',
    icon: UsersIcon,
    description: 'Управление пользователями'
  },
  {
    name: 'Мемориалы',
    href: '/admin/memorials',
    icon: BuildingLibraryIcon,
    description: 'Управление мемориалами'
  },
  {
    name: 'Жалобы',
    href: '/admin/reports',
    icon: ExclamationTriangleIcon,
    description: 'Модерация контента'
  },
  {
    name: 'Аналитика',
    href: '/admin/analytics',
    icon: ChartBarIcon,
    description: 'Статистика и отчеты'
  },
  {
    name: 'Настройки',
    href: '/admin/settings',
    icon: CogIcon,
    description: 'Настройки системы'
  }
];

/**
 * Лейаут для админ-панели
 */
export default function AdminLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const { user, logout } = useAuth();
  const pathname = usePathname();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const handleLogout = () => {
    logout();
    window.location.href = '/';
  };

  return (
    <AdminGuard minRole="moderator">
      <div className="min-h-screen bg-gray-200 flex">
        {/* Мобильное меню overlay */}
        {sidebarOpen && (
          <div 
            className="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            onClick={() => setSidebarOpen(false)}
          />
        )}

        {/* Сайдбар */}
        <div className={`
          fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0
          ${sidebarOpen ? 'translate-x-0' : '-translate-x-full'}
        `}>
          {/* Заголовок сайдбара */}
          <div className="flex items-center justify-between h-16 px-4 border-b border-gray-200">
            <div className="flex items-center gap-3">
              <div className="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                <span className="text-white font-bold text-sm">A</span>
              </div>
              <span className="font-semibold text-slate-700">Админ-панель</span>
            </div>
            <button
              onClick={() => setSidebarOpen(false)}
              className="lg:hidden p-1 rounded-md text-gray-400 hover:text-gray-600"
            >
              <XMarkIcon className="w-5 h-5" />
            </button>
          </div>

          {/* Информация о пользователе */}
          <div className="p-4 border-b border-gray-200">
            <div className="flex items-center gap-3">
              <Avatar name={user?.name || 'Пользователь'} src={user?.avatar} size={40} />
              <div className="flex-1 min-w-0">
                <p className="text-sm font-medium text-slate-700 truncate">{user?.name}</p>
                <p className="text-xs text-gray-500 capitalize">{user?.role}</p>
              </div>
            </div>
          </div>

          {/* Навигация */}
          <nav className="flex-1 p-4 space-y-2">
            {navigationItems.map((item) => {
              const isActive = pathname === item.href || (item.href !== '/admin' && pathname.startsWith(item.href));
              return (
                <Link
                  key={item.name}
                  href={item.href}
                  onClick={() => setSidebarOpen(false)}
                  className={`
                    group flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors
                    ${isActive
                      ? 'bg-red-50 text-red-600 border-r-2 border-red-500'
                      : 'text-gray-700 hover:bg-gray-50 hover:text-gray-900'
                    }
                  `}
                >
                  <item.icon 
                    className={`mr-3 h-5 w-5 transition-colors ${
                      isActive ? 'text-red-500' : 'text-gray-400 group-hover:text-gray-500'
                    }`} 
                  />
                  <div className="flex-1">
                    <div>{item.name}</div>
                    <div className="text-xs text-gray-500 mt-1">{item.description}</div>
                  </div>
                </Link>
              );
            })}
          </nav>

          {/* Дополнительные действия */}
          <div className="p-4 border-t border-gray-200 space-y-2">
            <Link
              href="/profile"
              className="flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
            >
              <ArrowLeftIcon className="mr-3 h-5 w-5 text-gray-400" />
              Вернуться в профиль
            </Link>
            <button
              onClick={handleLogout}
              className="w-full flex items-center px-3 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50 transition-colors"
            >
              <ArrowLeftOnRectangleIcon className="mr-3 h-5 w-5" />
              Выйти
            </button>
          </div>
        </div>

        {/* Основной контент */}
        <div className="flex-1 flex flex-col min-w-0">
          {/* Верхняя панель */}
          <header className="bg-white shadow-sm border-b border-gray-200">
            <div className="flex items-center justify-between h-16 px-4 lg:px-6">
              <div className="flex items-center gap-4">
                <button
                  onClick={() => setSidebarOpen(!sidebarOpen)}
                  className="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                >
                  <Bars3Icon className="w-5 h-5" />
                </button>
                
                <div>
                  <h1 className="text-xl font-semibold text-slate-700">
                    {navigationItems.find(item => pathname === item.href || (item.href !== '/admin' && pathname.startsWith(item.href)))?.name || 'Dashboard'}
                  </h1>
                  <p className="text-sm text-gray-500">
                    {navigationItems.find(item => pathname === item.href || (item.href !== '/admin' && pathname.startsWith(item.href)))?.description || 'Административная панель управления'}
                  </p>
                </div>
              </div>

              <div className="flex items-center gap-4">
                <Link
                  href="/"
                  className="hidden sm:flex items-center gap-2 px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition-colors"
                >
                  <ArrowLeftIcon className="w-4 h-4" />
                  На сайт
                </Link>
              </div>
            </div>
          </header>

          {/* Контент страницы */}
          <main className="flex-1 p-4 lg:p-6 overflow-auto">
            {children}
          </main>
        </div>
      </div>
    </AdminGuard>
  );
}


