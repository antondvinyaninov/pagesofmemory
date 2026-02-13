'use client';

import React from 'react';
import Link from 'next/link';
import { useAuth } from '@/contexts/AuthContext';
import { 
  PhotoIcon, 
  BookOpenIcon, 
  UsersIcon, 
  HeartIcon,
  EyeIcon,
  UserPlusIcon,
  ArrowRightOnRectangleIcon
} from '@heroicons/react/24/outline';

/**
 * Главная страница приложения Memory в стиле PHP проекта
 */
export default function Home() {
  const { isAuthenticated, user } = useAuth();

  return (
    <div className="bg-gray-200">
      <div className="container mx-auto px-4 pt-6 pb-16">
        {/* Приветствие */}
        <section className="mb-16">
          <div className="bg-white rounded-card shadow-lg text-center p-8 animate-fade-in">
            <HeartIcon className="w-12 h-12 text-red-500 mx-auto mb-6" />
            <h1 className="text-5xl font-bold text-slate-700 mb-4">Память о близких</h1>
                <p className="text-xl text-gray-500 mb-8 max-w-2xl mx-auto leading-relaxed">
              Сохраните драгоценные воспоминания о ваших близких для будущих поколений
            </p>
            
            {isAuthenticated ? (
              <div className="bg-gray-50 rounded-card shadow-md p-6 max-w-md mx-auto">
                <h2 className="text-2xl font-semibold text-slate-700 mb-4">
                  С возвращением, {user?.name}! 👋
                </h2>
                <p className="text-gray-500 mb-6">
                  Готовы продолжить работу с вашими воспоминаниями?
                </p>
                <Link
                  href="/profile"
                  className="inline-block bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-6 rounded transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover"
                  aria-label="Перейти в профиль"
                >
                  Открыть профиль
                </Link>
              </div>
            ) : (
              <>
                <div className="flex justify-center gap-4 mb-4 max-w-lg mx-auto">
                  <Link
                    href="/auth/register"
                        className="flex-1 max-w-48 bg-red-500 hover:bg-red-600 text-white font-medium py-3 px-4 rounded transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover flex items-center justify-center gap-2"
                    aria-label="Зарегистрироваться"
                  >
                    <UserPlusIcon className="w-5 h-5" />
                    Регистрация
                  </Link>
                  <Link
                    href="/auth/login"
                        className="flex-1 max-w-48 border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white font-medium py-3 px-4 rounded transition-all duration-300 hover:-translate-y-1 hover:shadow-card-hover flex items-center justify-center gap-2"
                    aria-label="Войти в систему"
                  >
                    <ArrowRightOnRectangleIcon className="w-5 h-5" />
                    Войти
                  </Link>
                </div>
                    <p className="text-gray-500 text-sm">
                  Зарегистрируйтесь, чтобы создать страницу памяти
                </p>
              </>
            )}
          </div>
        </section>

        {/* Статистика */}
        <section className="mb-16">
          <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div className="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in">
              <PhotoIcon className="w-8 h-8 text-red-500 mx-auto mb-4" />
              <div className="text-gray-500 text-sm mb-2">Фотографий</div>
              <div className="text-2xl font-semibold text-slate-700 leading-none">15,832</div>
            </div>
            <div className="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in-delay-1">
              <BookOpenIcon className="w-8 h-8 text-red-500 mx-auto mb-4" />
              <div className="text-gray-500 text-sm mb-2">Историй</div>
              <div className="text-2xl font-semibold text-slate-700 leading-none">2,431</div>
            </div>
            <div className="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in-delay-2">
              <UsersIcon className="w-8 h-8 text-red-500 mx-auto mb-4" />
              <div className="text-gray-500 text-sm mb-2">Пользователей</div>
              <div className="text-2xl font-semibold text-slate-700 leading-none">8,521</div>
            </div>
            <div className="bg-white rounded-card shadow-md hover:shadow-lg text-center p-6 hover:-translate-y-1 transition-all duration-300 animate-fade-in-delay-3">
              <HeartIcon className="w-8 h-8 text-red-500 mx-auto mb-4" />
              <div className="text-gray-500 text-sm mb-2">Воспоминаний</div>
              <div className="text-2xl font-semibold text-slate-700 leading-none">42,981</div>
            </div>
          </div>
        </section>

        {/* Как это работает */}
        <section className="mb-16">
          <div className="text-center mb-12">
            <h2 className="text-3xl font-bold text-slate-700 mb-4">Как это работает</h2>
            <p className="text-xl text-gray-500 max-w-2xl mx-auto">
              Простые шаги для создания страницы памяти о ваших близких
            </p>
          </div>
          
          <div className="grid md:grid-cols-3 gap-8">
            {/* Шаг 1 */}
            <div className="text-center group">
              <div className="bg-white rounded-card shadow-md hover:shadow-lg p-6 transition-all duration-300 group-hover:-translate-y-1">
                <div className="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-100 transition-colors">
                  <UserPlusIcon className="w-8 h-8 text-red-500" />
                </div>
                <h3 className="text-xl font-semibold text-slate-700 mb-3">1. Регистрация</h3>
                <p className="text-gray-500 text-sm leading-relaxed">
                  Создайте аккаунт и получите доступ к созданию страниц памяти
                </p>
              </div>
            </div>

            {/* Шаг 2 */}
            <div className="text-center group">
              <div className="bg-white rounded-card shadow-md hover:shadow-lg p-6 transition-all duration-300 group-hover:-translate-y-1">
                <div className="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-100 transition-colors">
                  <PhotoIcon className="w-8 h-8 text-red-500" />
                </div>
                <h3 className="text-xl font-semibold text-slate-700 mb-3">2. Загрузка</h3>
                <p className="text-gray-500 text-sm leading-relaxed">
                  Добавьте фотографии, видео и напишите историю жизни близкого человека
                </p>
              </div>
            </div>

            {/* Шаг 3 */}
            <div className="text-center group">
              <div className="bg-white rounded-card shadow-md hover:shadow-lg p-6 transition-all duration-300 group-hover:-translate-y-1">
                <div className="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-100 transition-colors">
                  <UsersIcon className="w-8 h-8 text-red-500" />
                </div>
                <h3 className="text-xl font-semibold text-slate-700 mb-3">3. Поделиться</h3>
                <p className="text-gray-500 text-sm leading-relaxed">
                  Пригласите родных и друзей делиться воспоминаниями и фотографиями
                </p>
              </div>
            </div>
          </div>

          {/* Дополнительная информация */}
          <div className="mt-12 bg-slate-50 rounded-card p-6 text-center">
            <div className="max-w-3xl mx-auto">
              <h3 className="text-xl font-semibold text-slate-700 mb-3">
                Сохраните память навсегда
              </h3>
              <p className="text-gray-500 mb-6">
                Все данные надежно защищены и сохранены для будущих поколений. 
                Создайте цифровое наследие, которое останется с вашей семьей навсегда.
              </p>
              <div className="flex justify-center gap-4 flex-wrap">
                <div className="flex items-center gap-2 text-sm text-gray-500">
                  <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                  Безопасное хранение
                </div>
                <div className="flex items-center gap-2 text-sm text-gray-500">
                  <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                  Неограниченные фото
                </div>
                <div className="flex items-center gap-2 text-sm text-gray-500">
                  <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                  Доступ всей семье
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Поиск страниц памяти перенесён в Header */}

        {/* Последние страницы памяти */}
        <section>
          <div className="flex justify-between items-center mb-2">
            <h2 className="text-2xl font-bold text-slate-700">Последние страницы памяти</h2>
            <Link
              href="/memorials"
              className="border-2 border-red-500 text-red-500 hover:bg-red-500 hover:text-white font-medium py-2 px-4 rounded text-sm transition-all duration-300 flex items-center gap-2"
              aria-label="Показать все мемориалы"
            >
              <EyeIcon className="w-4 h-4" />
              Показать все
            </Link>
          </div>
          <p className="text-gray-500 mb-6">Здесь отображаются недавно созданные или обновлённые страницы памяти. Используйте поиск выше, чтобы быстро найти нужного человека.</p>
          
          <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            {/* Карточка мемориала 1 */}
            <div className="bg-white rounded-card shadow-md hover:shadow-lg overflow-hidden hover:-translate-y-1 transition-all duration-300">
              <div className="aspect-square bg-gray-50 flex items-center justify-center">
                <PhotoIcon className="w-12 h-12 text-slate-700" />
              </div>
              <div className="p-4">
                <h4 className="text-lg font-semibold text-slate-700 mb-1">
                  Иван Петрович Смирнов
                </h4>
                <p className="text-gray-500 text-sm mb-3">1945 - 2023</p>
                <div className="flex justify-between items-center">
                  <span className="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    32 воспоминания
                  </span>
                  <Link
                    href="/memorial/1"
                    className="border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-1 px-3 rounded text-sm transition-all duration-300 flex items-center gap-1"
                    aria-label="Посмотреть мемориал"
                  >
                    <EyeIcon className="w-4 h-4" />
                    Смотреть
                  </Link>
                </div>
              </div>
            </div>

            {/* Карточка мемориала 2 */}
            <div className="bg-white rounded-card shadow-md hover:shadow-lg overflow-hidden hover:-translate-y-1 transition-all duration-300">
              <div className="aspect-square bg-gray-50 flex items-center justify-center">
                <PhotoIcon className="w-12 h-12 text-slate-700" />
              </div>
              <div className="p-4">
                <h4 className="text-lg font-semibold text-slate-700 mb-1">
                  Анна Сергеевна Иванова
                </h4>
                <p className="text-gray-500 text-sm mb-3">1938 - 2022</p>
                <div className="flex justify-between items-center">
                  <span className="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    18 воспоминаний
                  </span>
                  <Link
                    href="/memorial/2"
                    className="border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-1 px-3 rounded text-sm transition-all duration-300 flex items-center gap-1"
                    aria-label="Посмотреть мемориал"
                  >
                    <EyeIcon className="w-4 h-4" />
                    Смотреть
                  </Link>
                </div>
              </div>
            </div>

            {/* Карточка мемориала 3 */}
            <div className="bg-white rounded-card shadow-md hover:shadow-lg overflow-hidden hover:-translate-y-1 transition-all duration-300">
              <div className="aspect-square bg-gray-50 flex items-center justify-center">
                <PhotoIcon className="w-12 h-12 text-slate-700" />
              </div>
              <div className="p-4">
                <h4 className="text-lg font-semibold text-slate-700 mb-1">
                  Елена Павловна Соколова
                </h4>
                <p className="text-gray-500 text-sm mb-3">1942 - 2023</p>
                <div className="flex justify-between items-center">
                  <span className="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    24 воспоминания
                  </span>
                  <Link
                    href="/memorial/3"
                    className="border border-red-500 text-red-500 hover:bg-red-500 hover:text-white py-1 px-3 rounded text-sm transition-all duration-300 flex items-center gap-1"
                    aria-label="Посмотреть мемориал"
                  >
                    <EyeIcon className="w-4 h-4" />
                    Смотреть
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  );
}
