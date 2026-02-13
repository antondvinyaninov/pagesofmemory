'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import {
  UsersIcon,
  BuildingLibraryIcon,
  ExclamationTriangleIcon,
  EyeIcon,
  PlusIcon,
  ArrowTrendingUpIcon,
  ClockIcon,
  CalendarDaysIcon,
  CogIcon
} from '@heroicons/react/24/outline';

interface DashboardStats {
  users: {
    total: number;
    new_today: number;
    new_this_week: number;
    online: number;
  };
  memorials: {
    total: number;
    new_today: number;
    pending_moderation: number;
    total_views: number;
  };
  reports: {
    total: number;
    pending: number;
    resolved_today: number;
  };
  activity: {
    total_memories: number;
    total_comments: number;
    total_likes: number;
  };
}

interface RecentActivity {
  id: number;
  type: 'user_registered' | 'memorial_created' | 'memory_added' | 'report_submitted';
  description: string;
  user_name: string;
  timestamp: string;
}

/**
 * Главная страница админ-панели (Dashboard)
 */
export default function AdminDashboard() {
  const [stats, setStats] = useState<DashboardStats | null>(null);
  const [recentActivity, setRecentActivity] = useState<RecentActivity[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    // Загружаем статистику (пока mock данные)
    const loadDashboardData = async () => {
      try {
        // TODO: Заменить на реальные API запросы
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        setStats({
          users: {
            total: 156,
            new_today: 3,
            new_this_week: 12,
            online: 8
          },
          memorials: {
            total: 89,
            new_today: 1,
            pending_moderation: 4,
            total_views: 12453
          },
          reports: {
            total: 23,
            pending: 2,
            resolved_today: 1
          },
          activity: {
            total_memories: 342,
            total_comments: 567,
            total_likes: 1234
          }
        });

        setRecentActivity([
          {
            id: 1,
            type: 'user_registered',
            description: 'Новый пользователь зарегистрировался',
            user_name: 'Анна Смирнова',
            timestamp: '2024-01-15T10:30:00Z'
          },
          {
            id: 2,
            type: 'memorial_created',
            description: 'Создан новый мемориал',
            user_name: 'Иван Петров',
            timestamp: '2024-01-15T09:15:00Z'
          },
          {
            id: 3,
            type: 'memory_added',
            description: 'Добавлено новое воспоминание',
            user_name: 'Мария Иванова',
            timestamp: '2024-01-15T08:45:00Z'
          },
          {
            id: 4,
            type: 'report_submitted',
            description: 'Подана жалоба на контент',
            user_name: 'Система',
            timestamp: '2024-01-15T07:20:00Z'
          }
        ]);
      } catch (error) {
        console.error('Ошибка загрузки данных:', error);
      } finally {
        setLoading(false);
      }
    };

    loadDashboardData();
  }, []);

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleString('ru-RU', {
      day: 'numeric',
      month: 'short',
      hour: '2-digit',
      minute: '2-digit'
    });
  };

  const getActivityIcon = (type: RecentActivity['type']) => {
    switch (type) {
      case 'user_registered':
        return <UsersIcon className="w-4 h-4 text-green-500" />;
      case 'memorial_created':
        return <BuildingLibraryIcon className="w-4 h-4 text-blue-500" />;
      case 'memory_added':
        return <PlusIcon className="w-4 h-4 text-purple-500" />;
      case 'report_submitted':
        return <ExclamationTriangleIcon className="w-4 h-4 text-red-500" />;
      default:
        return <ClockIcon className="w-4 h-4 text-gray-500" />;
    }
  };

  if (loading) {
    return (
      <div className="flex items-center justify-center h-64">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-red-500"></div>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Приветствие */}
      <div>
        <h1 className="text-2xl font-bold text-slate-700">Добро пожаловать в админ-панель!</h1>
        <p className="text-gray-500 mt-1">Обзор текущего состояния системы</p>
      </div>

      {/* Основная статистика */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        {/* Пользователи */}
        <div className="bg-white rounded-xl shadow-md p-6">
          <div className="flex items-center justify-between mb-4">
            <div className="p-2 bg-blue-100 rounded-lg">
              <UsersIcon className="w-6 h-6 text-blue-600" />
            </div>
            <Link 
              href="/admin/users"
              className="text-sm text-blue-600 hover:text-blue-800 font-medium"
            >
              Управление →
            </Link>
          </div>
          <div className="space-y-2">
            <h3 className="text-2xl font-bold text-slate-700">{stats?.users.total}</h3>
            <p className="text-sm text-gray-600">Всего пользователей</p>
            <div className="flex items-center gap-4 text-xs text-gray-500">
              <span className="flex items-center gap-1">
                <ArrowTrendingUpIcon className="w-3 h-3 text-green-500" />
                +{stats?.users.new_today} сегодня
              </span>
              <span>🟢 {stats?.users.online} онлайн</span>
            </div>
          </div>
        </div>

        {/* Мемориалы */}
        <div className="bg-white rounded-xl shadow-md p-6">
          <div className="flex items-center justify-between mb-4">
            <div className="p-2 bg-purple-100 rounded-lg">
              <BuildingLibraryIcon className="w-6 h-6 text-purple-600" />
            </div>
            <Link 
              href="/admin/memorials"
              className="text-sm text-purple-600 hover:text-purple-800 font-medium"
            >
              Управление →
            </Link>
          </div>
          <div className="space-y-2">
            <h3 className="text-2xl font-bold text-slate-700">{stats?.memorials.total}</h3>
            <p className="text-sm text-gray-600">Всего мемориалов</p>
            <div className="flex items-center gap-4 text-xs text-gray-500">
              <span className="flex items-center gap-1">
                <PlusIcon className="w-3 h-3 text-green-500" />
                +{stats?.memorials.new_today} сегодня
              </span>
              <span className="text-orange-600">
                ⏳ {stats?.memorials.pending_moderation} на модерации
              </span>
            </div>
          </div>
        </div>

        {/* Жалобы */}
        <div className="bg-white rounded-xl shadow-md p-6">
          <div className="flex items-center justify-between mb-4">
            <div className="p-2 bg-red-100 rounded-lg">
              <ExclamationTriangleIcon className="w-6 h-6 text-red-600" />
            </div>
            <Link 
              href="/admin/reports"
              className="text-sm text-red-600 hover:text-red-800 font-medium"
            >
              Модерация →
            </Link>
          </div>
          <div className="space-y-2">
            <h3 className="text-2xl font-bold text-slate-700">{stats?.reports.pending}</h3>
            <p className="text-sm text-gray-600">Ожидают рассмотрения</p>
            <div className="flex items-center gap-4 text-xs text-gray-500">
              <span>{stats?.reports.total} всего</span>
              <span className="text-green-600">
                ✅ {stats?.reports.resolved_today} решено сегодня
              </span>
            </div>
          </div>
        </div>

        {/* Просмотры */}
        <div className="bg-white rounded-xl shadow-md p-6">
          <div className="flex items-center justify-between mb-4">
            <div className="p-2 bg-green-100 rounded-lg">
              <EyeIcon className="w-6 h-6 text-green-600" />
            </div>
            <Link 
              href="/admin/analytics"
              className="text-sm text-green-600 hover:text-green-800 font-medium"
            >
              Аналитика →
            </Link>
          </div>
          <div className="space-y-2">
            <h3 className="text-2xl font-bold text-slate-700">{stats?.memorials.total_views.toLocaleString()}</h3>
            <p className="text-sm text-gray-600">Всего просмотров</p>
            <div className="text-xs text-gray-500">
              Мемориалы и воспоминания
            </div>
          </div>
        </div>
      </div>

      {/* Дополнительная статистика */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div className="bg-white rounded-xl shadow-md p-6">
          <h3 className="font-semibold text-slate-700 mb-4">Активность пользователей</h3>
          <div className="space-y-4">
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Воспоминания</span>
              <span className="font-medium">{stats?.activity.total_memories}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Комментарии</span>
              <span className="font-medium">{stats?.activity.total_comments}</span>
            </div>
            <div className="flex justify-between items-center">
              <span className="text-gray-600">Лайки</span>
              <span className="font-medium">{stats?.activity.total_likes}</span>
            </div>
          </div>
        </div>

        <div className="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
          <h3 className="font-semibold text-slate-700 mb-4">Последняя активность</h3>
          <div className="space-y-4">
            {recentActivity.map((activity) => (
              <div key={activity.id} className="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                <div className="mt-1">
                  {getActivityIcon(activity.type)}
                </div>
                <div className="flex-1 min-w-0">
                  <p className="text-sm text-slate-700">
                    <span className="font-medium">{activity.user_name}</span> - {activity.description}
                  </p>
                  <p className="text-xs text-gray-500 mt-1 flex items-center gap-1">
                    <CalendarDaysIcon className="w-3 h-3" />
                    {formatDate(activity.timestamp)}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Быстрые действия */}
      <div className="bg-white rounded-xl shadow-md p-6">
        <h3 className="font-semibold text-slate-700 mb-4">Быстрые действия</h3>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <Link
            href="/admin/users"
            className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <UsersIcon className="w-5 h-5 text-blue-500" />
            <span className="font-medium text-slate-700">Управление пользователями</span>
          </Link>
          
          <Link
            href="/admin/memorials"
            className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <BuildingLibraryIcon className="w-5 h-5 text-purple-500" />
            <span className="font-medium text-slate-700">Модерация мемориалов</span>
          </Link>
          
          <Link
            href="/admin/reports"
            className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <ExclamationTriangleIcon className="w-5 h-5 text-red-500" />
            <span className="font-medium text-slate-700">Рассмотреть жалобы</span>
          </Link>
          
          <Link
            href="/admin/settings"
            className="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
          >
            <CogIcon className="w-5 h-5 text-gray-500" />
            <span className="font-medium text-slate-700">Настройки системы</span>
          </Link>
        </div>
      </div>
    </div>
  );
}
