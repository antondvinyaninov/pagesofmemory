'use client';

import React, { useEffect, useRef, useState } from 'react';
import Link from 'next/link';
import { 
  ArrowLeftOnRectangleIcon as LogOutIcon,
  UserIcon,
  HeartIcon,
  CogIcon,
  MagnifyingGlassIcon,
  XMarkIcon
} from '@heroicons/react/24/outline';
import { MapPinIcon } from '@heroicons/react/24/solid';
import { useAuth } from '@/contexts/AuthContext';
import { hasMinRole } from '@/middleware/adminAuth';

/**
 * Компонент шапки сайта в стиле PHP проекта
 */
export const Header: React.FC = () => {
  const { user, logout, isAuthenticated } = useAuth();
  const [query, setQuery] = useState<string>('');
  const [open, setOpen] = useState<boolean>(false);
  const [loading, setLoading] = useState<boolean>(false);
  const [suggestions, setSuggestions] = useState<Array<{ id: number; name: string; years?: string; city?: string; href: string; photoUrl?: string }>>([]);
  const containerRef = useRef<HTMLDivElement | null>(null);
  const inputRef = useRef<HTMLInputElement | null>(null);

  useEffect(() => {
    const handleClickOutside = (e: MouseEvent) => {
      if (containerRef.current && !containerRef.current.contains(e.target as Node)) {
        setOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClickOutside);
    return () => document.removeEventListener('mousedown', handleClickOutside);
  }, []);

  useEffect(() => {
    if (query.trim().length < 2) {
      setSuggestions([]);
      setLoading(false);
      return;
    }
    setLoading(true);
    const id = setTimeout(() => {
      // Временные локальные подсказки (заменим на API позже)
      const pool = [
        { id: 1, name: 'Иван Петрович Смирнов', years: '1945–2023', city: 'Москва', href: '/memorial/1', photoUrl: '' },
        { id: 2, name: 'Анна Сергеевна Иванова', years: '1938–2022', city: 'Санкт-Петербург', href: '/memorial/2', photoUrl: '' },
        { id: 3, name: 'Елена Павловна Соколова', years: '1942–2023', city: 'Екатеринбург', href: '/memorial/3', photoUrl: '' }
      ];
      const q = query.toLowerCase();
      const filtered = pool.filter(i => i.name.toLowerCase().includes(q)).slice(0, 5);
      setSuggestions(filtered);
      setLoading(false);
    }, 300);
    return () => clearTimeout(id);
  }, [query]);

  const handleClear = (): void => {
    setQuery('');
    inputRef.current?.focus();
  };

  /**
   * Обработчик выхода из системы
   */
  const handleLogout = (): void => {
    logout();
  };

  return (
    <header className="bg-slate-700 shadow-lg mb-0">
      <div className="container mx-auto px-4">
        <div className="flex justify-between items-center h-16 gap-1">
          {/* Логотип */}
          <div className="flex items-center">
            <Link 
              href="/" 
              className="flex items-center gap-2 text-xl font-bold text-white hover:text-blue-300 transition-colors"
              aria-label="Перейти на главную страницу"
            >
                <HeartIcon className="h-7 w-7 text-red-500" />
              Страницы памяти
              <span
                className="ml-2 inline-flex items-center rounded-full border border-red-200 bg-red-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-red-600"
                title="Бета-версия"
                aria-label="Бета-версия"
              >
                beta
              </span>
            </Link>
          </div>

          {/* Поиск */}
          <div ref={containerRef} className="relative block self-center w-[520px] sm:w-[420px] md:w-[520px]">
            <label htmlFor="header-search" className="sr-only">Поиск страниц памяти</label>
            <div className="relative">
              <MagnifyingGlassIcon className="pointer-events-none w-3.5 h-3.5 text-red-500 absolute left-3 top-1/2 -translate-y-1/2" />
              <input
                ref={inputRef}
                id="header-search"
                type="text"
                value={query}
                onChange={(e) => { setQuery(e.target.value); setOpen(true); }}
                onFocus={() => setOpen(true)}
                placeholder="Найдите страницу памяти: ФИО, годы жизни или город"
                aria-label="Поиск страниц памяти по ФИО, годам жизни или городу"
                className="w-full pl-9 pr-8 py-1.5 rounded-card bg-white/95 text-slate-800 placeholder:text-gray-400 border border-slate-300 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 shadow-sm"
              />
              {query && (
                <button
                  type="button"
                  onClick={handleClear}
                  className="absolute right-2 top-1/2 -translate-y-1/2 p-1 rounded hover:bg-slate-100"
                  aria-label="Очистить поиск"
                >
                  <XMarkIcon className="w-4 h-4 text-gray-400" />
                </button>
              )}
            </div>

            {/* Выпадающее меню подсказок */}
            {open && (query.trim().length >= 2 || loading) && (
              <div role="listbox" aria-label="Подсказки поиска" className="absolute z-50 mt-2 w-full bg-white rounded-card shadow-lg border border-slate-200 overflow-hidden">
                {loading && (
                  <div className="px-4 py-3 text-sm text-gray-500">Идёт поиск…</div>
                )}
                {!loading && suggestions.length > 0 && (
                  <ul className="max-h-72 overflow-auto">
                    {suggestions.map(item => (
                      <li key={item.id} role="option">
                        <Link
                          href={item.href}
                          className="block px-4 py-3 hover:bg-slate-50 focus:bg-slate-50 focus:outline-none"
                          onClick={() => setOpen(false)}
                          aria-label={`Открыть мемориал ${item.name}`}
                        >
                          <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-card overflow-hidden bg-gray-100 flex items-center justify-center flex-shrink-0">
                              {item.photoUrl ? (
                                // eslint-disable-next-line @next/next/no-img-element
                                <img src={item.photoUrl} alt={item.name} className="w-full h-full object-cover" />
                              ) : (
                                <span className="inline-flex items-center justify-center w-full h-full text-gray-400">
                                  <UserIcon className="w-5 h-5" />
                                </span>
                              )}
                            </div>
                            <div>
                              <div className="text-slate-700 font-medium leading-5">{item.name}</div>
                              <div className="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
                                {item.years && <span>{item.years}</span>}
                                {item.city && (
                                  <span className="inline-flex items-center gap-1">
                                    <MapPinIcon className="w-3.5 h-3.5 text-gray-400" />
                                    {item.city}
                                  </span>
                                )}
                              </div>
                            </div>
                          </div>
                        </Link>
                      </li>
                    ))}
                  </ul>
                )}
                {!loading && suggestions.length === 0 && (
                  <div className="px-4 py-3 text-sm text-gray-500">Ничего не найдено</div>
                )}
                <div className="border-t border-slate-200">
                  <Link
                    href={query ? `/memorials?q=${encodeURIComponent(query)}` : '/memorials'}
                    className="block px-4 py-3 text-sm text-red-600 hover:bg-red-50"
                    onClick={() => setOpen(false)}
                  >
                    Перейти к расширенному поиску
                  </Link>
                </div>
              </div>
            )}
          </div>

          {/* Навигация */}
          <nav className="flex items-center gap-6">
            {isAuthenticated ? (
              <>
                {/* Информация о пользователе */}
                <div className="flex items-center gap-2 text-white">
                  <div className="w-7 h-7 bg-white/10 rounded-full overflow-hidden">
                    {/* Аватар с инициалами */}
                    <span className="w-7 h-7 inline-flex items-center justify-center text-xs font-semibold">
                      {user?.name?.split(' ').slice(0,2).map(p=>p[0]).join('').toUpperCase()}
                    </span>
                  </div>
                  <span className="hidden sm:inline">Привет, {user?.name}!</span>
                </div>

                {/* Ссылка на профиль */}
                <Link
                  href="/profile"
                  className="text-white hover:text-blue-300 font-medium transition-colors"
                  aria-label="Перейти в профиль"
                >
                  Профиль
                </Link>

                {/* Ссылка на админ-панель (только для модераторов и выше) */}
                {hasMinRole(user, 'moderator') && (
                  <Link
                    href="/admin"
                    className="flex items-center gap-2 px-3 py-2 text-white hover:text-blue-300 hover:bg-slate-600 rounded transition-colors"
                    aria-label="Перейти в админ-панель"
                  >
                    <CogIcon className="h-5 w-5" />
                    <span className="hidden sm:inline">Админка</span>
                  </Link>
                )}

                {/* Кнопка выхода */}
                <button
                  onClick={handleLogout}
                      className="flex items-center gap-2 px-3 py-2 text-white hover:text-red-500 hover:bg-slate-600 rounded transition-colors"
                  aria-label="Выйти из системы"
                >
                  <LogOutIcon className="h-5 w-5" />
                  <span className="hidden sm:inline">Выйти</span>
                </button>
              </>
            ) : (
              <>
                {/* Ссылки для неавторизованных пользователей */}
                <Link
                  href="/auth/login"
                  className="text-white hover:text-blue-300 font-medium transition-colors"
                  aria-label="Войти в систему"
                >
                  Войти
                </Link>
                    <Link
                      href="/auth/register"
                      className="px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded transition-colors"
                      aria-label="Зарегистрироваться"
                    >
                      Регистрация
                    </Link>
              </>
            )}
          </nav>
        </div>
      </div>
    </header>
  );
};
