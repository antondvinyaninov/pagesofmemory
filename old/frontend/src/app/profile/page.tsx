'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useAuth } from '@/contexts/AuthContext';
import {
  UserIcon,
  CogIcon,
  HeartIcon,
  BookOpenIcon,
  PlusIcon,
  PhotoIcon,
  MapPinIcon,
  CalendarIcon,
  EyeIcon,
  ChatBubbleLeftIcon,
  PencilIcon
} from '@heroicons/react/24/outline';
import Avatar from '@/components/layout/Avatar';

/**
 * Страница профиля пользователя
 */
export default function ProfilePage() {
  const { user, isAuthenticated, loading } = useAuth();
  const [activeTab, setActiveTab] = useState('overview'); // overview, memories, created-memorials, settings
  const [userMemories, setUserMemories] = useState<any[]>([]);
  const [createdMemorials, setCreatedMemorials] = useState<any[]>([]);
  const [userStats, setUserStats] = useState<any>({});

  // Функция для форматирования даты на русском языке
  const formatRussianDate = (dateString: string) => {
    const date = new Date(dateString);
    const months = [
      'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
      'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
    ];
    
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    
    return `${day} ${month} ${year}`;
  };

  useEffect(() => {
    if (isAuthenticated && user) {
      // Загружаем данные пользователя
      setTimeout(() => {
        // Воспоминания пользователя
        setUserMemories([
          {
            id: 1,
            memorial_name: 'Иван Иванович Иванов',
            memorial_id: 1,
            content: 'Помню, как мы вместе проводили время. Это были прекрасные моменты.',
            created_at: '2024-01-10T12:30:00Z',
            likes: 8,
            comments: 3,
          },
          {
            id: 2,
            memorial_name: 'Мария Петровна Сидорова',
            memorial_id: 2,
            content: 'Светлая память удивительному человеку. Всегда будем помнить её доброту.',
            created_at: '2024-01-05T14:20:00Z',
            likes: 12,
            comments: 5,
          },
        ]);

        // Созданные мемориалы
        setCreatedMemorials([
          {
            id: 1,
            name: 'Иван Иванович Иванов',
            birth_date: '1945-03-15',
            death_date: '2023-01-10',
            photo_url: '/api/placeholder/150/150',
            views: 1425,
            memories_count: 24,
            created_at: '2023-01-12T10:00:00Z',
          },
        ]);

        // Статистика пользователя
        setUserStats({
          total_memories: 15,
          total_likes_received: 89,
          total_memorials_created: 1,
          total_views: 1425,
          member_since: '2023-01-01T00:00:00Z',
        });
      }, 1000);
    }
  }, [isAuthenticated, user]);

  // Показываем загрузку пока проверяется авторизация
  if (loading) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-red-500 mx-auto mb-4"></div>
          <p className="text-gray-500">Загрузка...</p>
        </div>
      </div>
    );
  }

  if (!isAuthenticated) {
    return (
      <div className="min-h-screen bg-gray-50 flex items-center justify-center">
        <div className="text-center">
          <UserIcon className="w-16 h-16 text-gray-300 mx-auto mb-4" />
          <h2 className="text-2xl font-bold text-slate-700 mb-2">Требуется авторизация</h2>
          <p className="text-gray-500 mb-6">Для просмотра профиля необходимо войти в систему</p>
          <div className="space-x-4">
            <Link 
              href="/auth/login"
              className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
            >
              Войти
            </Link>
            <Link 
              href="/auth/register"
              className="border border-red-500 text-red-500 hover:bg-red-50 px-6 py-2 rounded-lg transition-colors"
            >
              Регистрация
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-200">
      {/* Hero секция профиля */}
      <div className="bg-white border-b border-gray-200">
        <div className="container mx-auto px-4 py-8">
          <div className="flex flex-col md:flex-row items-start gap-6">
            {/* Аватар */}
            <div className="flex-shrink-0 shadow-lg rounded-full overflow-hidden">
              <Avatar name={user?.name || 'Пользователь'} src={user?.avatar} size={128} />
            </div>

            {/* Информация о пользователе */}
            <div className="flex-1">
              <div className="flex flex-col md:flex-row md:items-center justify-between mb-4">
                <div>
                  <h1 className="text-3xl font-bold text-slate-700 mb-2">{user?.name}</h1>
                  <p className="text-gray-500 mb-2">{user?.email}</p>
                  {userStats.member_since && (
                    <p className="text-sm text-gray-400 flex items-center gap-2">
                      <CalendarIcon className="w-4 h-4" />
                      Участник с {formatRussianDate(userStats.member_since)}
                    </p>
                  )}
                </div>
                
                <Link 
                  href="/profile/settings"
                  className="flex items-center gap-2 bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg transition-colors text-sm"
                >
                  <CogIcon className="w-4 h-4" />
                  Настройки
                </Link>
              </div>

              {/* Статистика */}
              <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div className="bg-gray-50 rounded-lg p-4 text-center">
                  <div className="text-2xl font-bold text-red-500">{userStats.total_memories || 0}</div>
                  <div className="text-sm text-gray-500">Воспоминаний</div>
                </div>
                <div className="bg-gray-50 rounded-lg p-4 text-center">
                  <div className="text-2xl font-bold text-red-500">{userStats.total_likes_received || 0}</div>
                  <div className="text-sm text-gray-500">Лайков получено</div>
                </div>
                <div className="bg-gray-50 rounded-lg p-4 text-center">
                  <div className="text-2xl font-bold text-red-500">{userStats.total_memorials_created || 0}</div>
                  <div className="text-sm text-gray-500">Мемориалов создано</div>
                </div>
                <div className="bg-gray-50 rounded-lg p-4 text-center">
                  <div className="text-2xl font-bold text-red-500">{userStats.total_views || 0}</div>
                  <div className="text-sm text-gray-500">Просмотров</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Навигация по табам */}
      <div className="bg-white border-b border-gray-200">
        <div className="container mx-auto px-4">
          <nav className="flex space-x-8">
            <button
              onClick={() => setActiveTab('overview')}
              className={`py-4 px-2 border-b-2 font-medium text-sm transition-colors ${
                activeTab === 'overview'
                  ? 'border-red-500 text-red-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              Обзор
            </button>
            <button
              onClick={() => setActiveTab('memories')}
              className={`py-4 px-2 border-b-2 font-medium text-sm transition-colors ${
                activeTab === 'memories'
                  ? 'border-red-500 text-red-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              Мои воспоминания
            </button>
            <button
              onClick={() => setActiveTab('created-memorials')}
              className={`py-4 px-2 border-b-2 font-medium text-sm transition-colors ${
                activeTab === 'created-memorials'
                  ? 'border-red-500 text-red-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
              }`}
            >
              Созданные мемориалы
            </button>
          </nav>
        </div>
      </div>

      {/* Контент */}
      <div className="container mx-auto px-4 py-8">
        {/* Обзор */}
        {activeTab === 'overview' && (
          <div className="grid lg:grid-cols-3 gap-8">
            {/* Последние воспоминания */}
            <div className="lg:col-span-2">
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                  <h3 className="text-lg font-semibold text-slate-700">Последние воспоминания</h3>
                  <Link 
                    href="#"
                    onClick={() => setActiveTab('memories')}
                    className="text-red-500 hover:text-red-600 text-sm"
                  >
                    Посмотреть все
                  </Link>
                </div>
                <div className="p-6">
                  {userMemories.slice(0, 3).map((memory) => (
                    <div key={memory.id} className="mb-6 last:mb-0 pb-6 last:pb-0 border-b last:border-b-0 border-gray-100">
                      <div className="flex items-start justify-between mb-3">
                        <div>
                          <Link 
                            href={`/memorial/${memory.memorial_id}`}
                            className="font-medium text-slate-700 hover:text-red-600 transition-colors"
                          >
                            {memory.memorial_name}
                          </Link>
                          <p className="text-sm text-gray-500">{formatRussianDate(memory.created_at)}</p>
                        </div>
                        <div className="flex items-center gap-4 text-sm text-gray-500">
                          <span className="flex items-center gap-1">
                            <HeartIcon className="w-4 h-4" />
                            {memory.likes}
                          </span>
                          <span className="flex items-center gap-1">
                            <ChatBubbleLeftIcon className="w-4 h-4" />
                            {memory.comments}
                          </span>
                        </div>
                      </div>
                      <p className="text-slate-700 leading-relaxed">{memory.content}</p>
                    </div>
                  ))}
                  
                  {userMemories.length === 0 && (
                    <div className="text-center py-8">
                      <BookOpenIcon className="w-12 h-12 text-gray-300 mx-auto mb-3" />
                      <p className="text-gray-500">Вы еще не оставили ни одного воспоминания</p>
                    </div>
                  )}
                </div>
              </div>
            </div>

            {/* Боковая панель */}
            <div className="space-y-6">
              {/* Быстрые действия */}
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-slate-700">Быстрые действия</h3>
                </div>
                <div className="p-6 space-y-3">
                  <Link 
                    href="/memorial/create"
                    className="w-full flex items-center gap-3 p-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                  >
                    <PlusIcon className="w-5 h-5" />
                    Создать мемориал
                  </Link>
                  <Link 
                    href="/memorials"
                    className="w-full flex items-center gap-3 p-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors"
                  >
                    <BookOpenIcon className="w-5 h-5" />
                    Найти мемориалы
                  </Link>
                  <Link 
                    href="/profile/settings"
                    className="w-full flex items-center gap-3 p-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors"
                  >
                    <CogIcon className="w-5 h-5" />
                    Настройки профиля
                  </Link>
                </div>
              </div>

              {/* Созданные мемориалы */}
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                  <h3 className="text-lg font-semibold text-slate-700">Созданные мемориалы</h3>
                  {createdMemorials.length > 1 && (
                    <Link 
                      href="#"
                      onClick={() => setActiveTab('created-memorials')}
                      className="text-red-500 hover:text-red-600 text-sm"
                    >
                      Все
                    </Link>
                  )}
                </div>
                <div className="p-6">
                  {createdMemorials.slice(0, 2).map((memorial) => (
                    <div key={memorial.id} className="mb-4 last:mb-0">
                      <Link 
                        href={`/memorial/${memorial.id}`}
                        className="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors"
                      >
                        <img 
                          src={memorial.photo_url} 
                          alt={memorial.name}
                          className="w-12 h-12 rounded-full object-cover"
                        />
                        <div className="flex-1 min-w-0">
                          <h4 className="font-medium text-slate-700 truncate">{memorial.name}</h4>
                          <div className="flex items-center gap-4 text-xs text-gray-500">
                            <span className="flex items-center gap-1">
                              <EyeIcon className="w-3 h-3" />
                              {memorial.views}
                            </span>
                            <span className="flex items-center gap-1">
                              <BookOpenIcon className="w-3 h-3" />
                              {memorial.memories_count}
                            </span>
                          </div>
                        </div>
                      </Link>
                    </div>
                  ))}
                  
                  {createdMemorials.length === 0 && (
                    <div className="text-center py-4">
                      <UserIcon className="w-8 h-8 text-gray-300 mx-auto mb-2" />
                      <p className="text-sm text-gray-500">Мемориалов пока нет</p>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Мои воспоминания */}
        {activeTab === 'memories' && (
          <div className="bg-white rounded-xl shadow-md overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-100">
              <h3 className="text-lg font-semibold text-slate-700">Мои воспоминания</h3>
            </div>
            <div className="p-6">
              {userMemories.map((memory) => (
                <div key={memory.id} className="mb-6 last:mb-0 pb-6 last:pb-0 border-b last:border-b-0 border-gray-100">
                  <div className="flex items-start justify-between mb-3">
                    <div>
                      <Link 
                        href={`/memorial/${memory.memorial_id}`}
                        className="font-medium text-slate-700 hover:text-red-600 transition-colors"
                      >
                        {memory.memorial_name}
                      </Link>
                      <p className="text-sm text-gray-500">{formatRussianDate(memory.created_at)}</p>
                    </div>
                    <div className="flex items-center gap-4">
                      <div className="flex items-center gap-4 text-sm text-gray-500">
                        <span className="flex items-center gap-1">
                          <HeartIcon className="w-4 h-4" />
                          {memory.likes}
                        </span>
                        <span className="flex items-center gap-1">
                          <ChatBubbleLeftIcon className="w-4 h-4" />
                          {memory.comments}
                        </span>
                      </div>
                      <button className="p-1 text-gray-400 hover:text-gray-600">
                        <PencilIcon className="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                  <p className="text-slate-700 leading-relaxed">{memory.content}</p>
                </div>
              ))}
              
              {userMemories.length === 0 && (
                <div className="text-center py-12">
                  <BookOpenIcon className="w-16 h-16 text-gray-300 mx-auto mb-4" />
                  <h4 className="text-lg font-medium text-slate-700 mb-2">Нет воспоминаний</h4>
                  <p className="text-gray-500 mb-6">Вы еще не оставили ни одного воспоминания</p>
                  <Link 
                    href="/memorials"
                    className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
                  >
                    Найти мемориалы
                  </Link>
                </div>
              )}
            </div>
          </div>
        )}

        {/* Созданные мемориалы */}
        {activeTab === 'created-memorials' && (
          <div className="bg-white rounded-xl shadow-md overflow-hidden">
            <div className="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
              <h3 className="text-lg font-semibold text-slate-700">Созданные мемориалы</h3>
              <Link 
                href="/memorial/create"
                className="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors text-sm flex items-center gap-2"
              >
                <PlusIcon className="w-4 h-4" />
                Создать мемориал
              </Link>
            </div>
            <div className="p-6">
              {createdMemorials.map((memorial) => (
                <div key={memorial.id} className="mb-6 last:mb-0">
                  <div className="flex items-center gap-4 p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow">
                    <img 
                      src={memorial.photo_url} 
                      alt={memorial.name}
                      className="w-20 h-20 rounded-full object-cover"
                    />
                    <div className="flex-1 min-w-0">
                      <Link 
                        href={`/memorial/${memorial.id}`}
                        className="font-medium text-slate-700 hover:text-red-600 transition-colors"
                      >
                        <h4 className="text-lg">{memorial.name}</h4>
                      </Link>
                      <p className="text-sm text-gray-500 mb-2">
                        {formatRussianDate(memorial.birth_date)} — {formatRussianDate(memorial.death_date)}
                      </p>
                      <div className="flex items-center gap-6 text-sm text-gray-500">
                        <span className="flex items-center gap-1">
                          <EyeIcon className="w-4 h-4" />
                          {memorial.views} просмотров
                        </span>
                        <span className="flex items-center gap-1">
                          <BookOpenIcon className="w-4 h-4" />
                          {memorial.memories_count} воспоминаний
                        </span>
                        <span className="flex items-center gap-1">
                          <CalendarIcon className="w-4 h-4" />
                          Создан {formatRussianDate(memorial.created_at)}
                        </span>
                      </div>
                    </div>
                    <Link 
                      href={`/memorial/${memorial.id}/edit`}
                      className="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <PencilIcon className="w-5 h-5" />
                    </Link>
                  </div>
                </div>
              ))}
              
              {createdMemorials.length === 0 && (
                <div className="text-center py-12">
                  <UserIcon className="w-16 h-16 text-gray-300 mx-auto mb-4" />
                  <h4 className="text-lg font-medium text-slate-700 mb-2">Нет созданных мемориалов</h4>
                  <p className="text-gray-500 mb-6">Создайте первый мемориал в память о близком человеке</p>
                  <Link 
                    href="/memorial/create"
                    className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
                  >
                    Создать мемориал
                  </Link>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}
