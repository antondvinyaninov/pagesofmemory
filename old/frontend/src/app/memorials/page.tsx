'use client';

import React, { useEffect, useMemo, useState } from 'react';
import Link from 'next/link';
import { useSearchParams, useRouter } from 'next/navigation';
import { 
  MagnifyingGlassIcon, 
  XMarkIcon, 
  MapPinIcon, 
  CalendarDaysIcon, 
  EyeIcon
} from '@heroicons/react/24/outline';

type MemorialItem = {
  id: number;
  name: string;
  years?: string;
  city?: string;
  href: string;
  photoUrl?: string;
};

export default function MemorialsSearchPage() {
  const searchParams = useSearchParams();
  const router = useRouter();
  const initialQ = (searchParams.get('q') || '').trim();
  const [query, setQuery] = useState<string>(initialQ);
  

  useEffect(() => {
    setQuery(initialQ);
  }, [initialQ]);

  const data: MemorialItem[] = useMemo(() => ([
    { id: 1, name: 'Иван Петрович Смирнов', years: '1945–2023', city: 'Москва', href: '/memorial/1', photoUrl: '' },
    { id: 2, name: 'Анна Сергеевна Иванова', years: '1938–2022', city: 'Санкт-Петербург', href: '/memorial/2', photoUrl: '' },
    { id: 3, name: 'Елена Павловна Соколова', years: '1942–2023', city: 'Екатеринбург', href: '/memorial/3', photoUrl: '' },
  ]), []);

  const filtered = useMemo(() => {
    const q = query.toLowerCase();
    return data.filter(item => {
      const matchesQ = !q || item.name.toLowerCase().includes(q) || item.city?.toLowerCase().includes(q) || item.years?.toLowerCase().includes(q);
      return matchesQ;
    });
  }, [data, query]);

  const highlight = (text?: string) => {
    if (!text) return null;
    if (!query.trim()) return <>{text}</>;
    const parts = text.split(new RegExp(`(${query})`, 'ig'));
    return (
      <>
        {parts.map((part, i) =>
          part.toLowerCase() === query.toLowerCase() ? (
            <mark key={i} className="bg-yellow-100 text-slate-800 rounded px-0.5">{part}</mark>
          ) : (
            <span key={i}>{part}</span>
          )
        )}
      </>
    );
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    const params = new URLSearchParams();
    if (query.trim()) params.set('q', query.trim());
    if (status !== 'all') params.set('status', status);
    router.push(params.toString() ? `/memorials?${params.toString()}` : '/memorials');
  };

  const handleClear = (): void => {
    setQuery('');
    router.push('/memorials');
  };

  return (
    <div className="bg-gray-100">
      <div className="container mx-auto px-4 pt-8 pb-16">
        <div className="mb-6">
          <h1 className="text-3xl font-bold text-slate-700">Расширенный поиск</h1>
          <p className="text-gray-500 mt-2">Найдите страницу памяти по ФИО, годам жизни или городу</p>
        </div>

        {/* Поле поиска */}
        <form onSubmit={handleSubmit} className="bg-white rounded-card shadow-md p-4 mb-4">
          <div className="flex flex-col md:flex-row md:items-center gap-3">
            <div className="relative md:flex-1">
              <MagnifyingGlassIcon className="pointer-events-none w-5 h-5 text-red-500 absolute left-3 top-1/2 -translate-y-1/2" />
              <input
                type="text"
                value={query}
                onChange={(e) => setQuery(e.target.value)}
                placeholder="ФИО, годы жизни или город"
                aria-label="Поиск страниц памяти по ФИО, годам жизни или городу"
                className="w-full pl-10 pr-9 py-2 rounded-card bg-white text-slate-800 placeholder:text-gray-400 border border-slate-300 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
              />
              {query && (
                <button
                  type="button"
                  onClick={handleClear}
                  className="absolute right-2 top-1/2 -translate-y-1/2 p-1 rounded hover:bg-slate-100"
                  aria-label="Очистить запрос"
                >
                  <XMarkIcon className="w-4 h-4 text-gray-400" />
                </button>
              )}
            </div>

            {/* Место для будущих фильтров (город, годы) */}

            <div className="md:self-stretch">
              <button type="submit" className="w-full md:w-auto px-4 py-2 rounded bg-red-500 hover:bg-red-600 text-white font-medium transition-colors">
                Найти
              </button>
            </div>
          </div>
        </form>

        {/* Результаты */}
        <div className="bg-white rounded-card shadow-md">
          <div className="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <div className="text-slate-700 font-semibold">Результаты поиска</div>
            <div className="text-sm text-gray-500">Найдено: {filtered.length}</div>
          </div>

          {filtered.length === 0 ? (
            <div className="px-6 py-12 text-center text-gray-500">Ничего не найдено. Попробуйте изменить запрос.</div>
          ) : (
            <div className="p-4 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
              {filtered.map(item => (
                <Link key={item.id} href={item.href} className="group block bg-white rounded-card border border-gray-200 hover:border-red-200 hover:shadow-lg transition-all overflow-hidden">
                  <div className="relative aspect-square bg-gray-100">
                    {item.photoUrl ? (
                      // eslint-disable-next-line @next/next/no-img-element
                      <img src={item.photoUrl} alt={item.name} className="w-full h-full object-cover" />
                    ) : (
                      <div className="w-full h-full flex items-center justify-center">
                        <CalendarDaysIcon className="w-10 h-10 text-gray-400" />
                      </div>
                    )}
                    {/* Годы */}
                    {item.years && (
                      <div className="absolute bottom-2 left-2 bg-black/60 text-white text-xs px-2 py-0.5 rounded-card">
                        {highlight(item.years)}
                      </div>
                    )}
                  </div>
                  <div className="p-3">
                    <div className="text-slate-700 font-semibold leading-5 line-clamp-2 group-hover:text-red-600 transition-colors">{highlight(item.name)}</div>
                    <div className="mt-2 flex items-center justify-between">
                      {item.city ? (
                        <span className="inline-flex items-center gap-1 text-xs text-gray-600 bg-gray-100 rounded-full px-2 py-0.5">
                          <MapPinIcon className="w-3.5 h-3.5 text-gray-500" />
                          {highlight(item.city)}
                        </span>
                      ) : <span />}
                      <span className="inline-flex items-center gap-1 text-xs text-red-600">
                        <EyeIcon className="w-4 h-4" />
                        Открыть
                      </span>
                    </div>
                  </div>
                </Link>
              ))}
            </div>
          )}
        </div>
      </div>
    </div>
  );
}


