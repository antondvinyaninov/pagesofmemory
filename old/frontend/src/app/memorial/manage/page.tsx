'use client';

import React, { useEffect, useMemo, useRef, useState } from 'react';
import { useSearchParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import { 
  ArrowLeftIcon,
  CheckIcon,
  XMarkIcon,
  PhotoIcon,
  CalendarDaysIcon,
  MapPinIcon,
  UserIcon,
  BuildingLibraryIcon,
  BookOpenIcon
} from '@heroicons/react/24/outline';
import { uploadService } from '@/services/api';

type FormState = {
  first_name: string;
  middle_name: string;
  last_name: string;
  birth_date: string;
  death_date: string;
  birth_place: string;
  religion: string;
  biography: string;
  full_biography: string;
  burial_place: string;
  burial_address: string;
  burial_location: string;
  burial_coordinates: string;
  photo_url: string;
};

export default function ManageMemorialPage() {
  const searchParams = useSearchParams();
  const router = useRouter();
  const memorialId = searchParams.get('id');
  const isEdit = Boolean(memorialId);
  const draftKey = React.useMemo(() => `memorial_draft_${memorialId || 'new'}`,[memorialId]);

  const [form, setForm] = useState<FormState>({
    first_name: '',
    middle_name: '',
    last_name: '',
    birth_date: '',
    death_date: '',
    birth_place: '',
    religion: 'none',
    biography: '',
    full_biography: '',
    burial_place: '',
    burial_address: '',
    burial_location: '',
    burial_coordinates: '',
    photo_url: ''
  });
  const [saving, setSaving] = useState(false);
  const [message, setMessage] = useState<string>('');
  const photoInputRef = useRef<HTMLInputElement | null>(null);
  const [originalPhotoUrl, setOriginalPhotoUrl] = useState<string>('');

  // Заглушка загрузки существующего мемориала (для демо)
  useEffect(() => {
    if (!isEdit) return;
    // TODO: заменить на реальный вызов API
    setTimeout(() => {
      setForm(prev => ({
        ...prev,
        first_name: 'Иван',
        middle_name: 'Иванович',
        last_name: 'Иванов',
        birth_date: '1945-03-15',
        death_date: '2023-01-10',
        birth_place: 'Москва, Россия',
        religion: 'orthodox',
        biography: 'Любящий муж, отец и дедушка...',
        full_biography: 'Длинная биография...',
        burial_place: 'Новодевичье кладбище',
        burial_address: 'Москва, Лужнецкий проезд, 2',
        burial_location: 'Участок 2, ряд 15, место 7',
        burial_coordinates: '55.726944, 37.553889'
      }));
    }, 300);
  }, [isEdit]);

  // Загрузка черновика из localStorage
  useEffect(() => {
    try {
      const raw = localStorage.getItem(draftKey);
      if (raw) {
        const saved = JSON.parse(raw) as FormState;
        setForm(prev => ({ ...prev, ...saved }));
        if (saved.photo_url) setOriginalPhotoUrl(saved.photo_url);
      }
    } catch {}
  }, [draftKey]);

  // Сохранение черновика в localStorage при изменении формы
  useEffect(() => {
    try {
      localStorage.setItem(draftKey, JSON.stringify(form));
    } catch {}
  }, [draftKey, form]);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setForm(prev => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    setMessage('');
    try {
      // TODO: подключить реальный API: POST (create) или PUT (update)
      await new Promise(r => setTimeout(r, 600));
      setMessage(isEdit ? 'Мемориал обновлён' : 'Мемориал создан');
      try { localStorage.removeItem(draftKey); } catch {}
      // редирект на страницу мемориала
      router.push(isEdit ? `/memorial/${memorialId}` : '/memorial/1');
    } catch (err) {
      setMessage('Ошибка сохранения');
    } finally {
      setSaving(false);
    }
  };

  const handlePhotoUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) {
      setMessage('Пожалуйста, выберите изображение');
      return;
    }
    if (file.size > 10 * 1024 * 1024) {
      setMessage('Размер файла не должен превышать 10MB');
      return;
    }
    // upload to backend
    (async () => {
      try {
        const { url } = await uploadService.uploadImage(file);
        // cache-busting для мгновенного отображения без кэша
        const viewUrl = `${url}?t=${Date.now()}`;
        setOriginalPhotoUrl(url);
        setForm(prev => ({ ...prev, photo_url: viewUrl }));
        setMessage('Фото загружено на сервер');
      } catch (err) {
        console.error(err);
        setMessage('Ошибка загрузки фото');
      }
    })();
  };

  const applyPhotoFilter = async (mode: 'grayscale' | 'sepia' | 'reset') => {
    const base = originalPhotoUrl || form.photo_url;
    if (!base) {
      setMessage('Сначала загрузите фото');
      return;
    }

    if (mode === 'reset') {
      setForm(prev => ({ ...prev, photo_url: originalPhotoUrl || form.photo_url }));
      setMessage('Фото сброшено');
      return;
    }

    const img = new Image();
    img.crossOrigin = 'anonymous';
    img.onload = () => {
      const canvas = document.createElement('canvas');
      canvas.width = img.naturalWidth;
      canvas.height = img.naturalHeight;
      const ctx = canvas.getContext('2d');
      if (!ctx) return;
      ctx.filter = mode === 'grayscale' ? 'grayscale(1)' : 'sepia(1)';
      ctx.drawImage(img, 0, 0);
      const processed = canvas.toDataURL('image/jpeg', 0.92);
      setForm(prev => ({ ...prev, photo_url: processed }));
      setMessage(mode === 'grayscale' ? 'Применён фильтр Ч/Б' : 'Применён фильтр Сепия');
    };
    img.src = base;
  };

  return (
    <div className="min-h-screen bg-gray-200">
      <div className="container mx-auto px-4 py-6">
        {/* Заголовок */}
        <div className="flex items-center gap-3 mb-4">
          <Link href={isEdit ? `/memorial/${memorialId}` : '/'} className="p-2 text-gray-500 hover:text-gray-700">
            <ArrowLeftIcon className="w-5 h-5" />
          </Link>
          <h1 className="text-2xl font-bold text-slate-700">
            {isEdit ? 'Редактирование мемориала' : 'Создание мемориала'}
          </h1>
        </div>

        {message && (
          <div className="mb-4 bg-green-50 border border-green-200 rounded-lg p-3 text-green-700 flex items-center gap-2">
            <CheckIcon className="w-5 h-5" />
            {message}
            <button onClick={() => setMessage('')} className="ml-auto text-green-600 hover:text-green-800">
              <XMarkIcon className="w-4 h-4" />
            </button>
          </div>
        )}
        <div className="grid lg:grid-cols-[1fr_380px] gap-6">
          {/* Левая колонка: форма по блокам */}
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* 1. Основные данные */}
            <section className="bg-white rounded-card shadow-md overflow-hidden">
              <div className="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <UserIcon className="w-5 h-5 text-red-500" />
                <h2 className="text-lg font-semibold text-slate-700">Основные данные</h2>
              </div>
              <div className="p-6">
                <div className="grid md:grid-cols-2 gap-6">
                  {/* Левая часть: Фото */}
                  <div className="space-y-3">
                    <div className="flex items-center md:items-start gap-4">
                      <div className="w-32 h-32 md:w-40 md:h-40 bg-gray-100 rounded-card overflow-hidden flex items-center justify-center mx-auto md:mx-0">
                        {form.photo_url ? (
                          // eslint-disable-next-line @next/next/no-img-element
                          <img src={form.photo_url} alt="Фото" className="w-full h-full object-cover" onError={(e) => { (e.target as HTMLImageElement).src = '/api/placeholder/300/300'; }} />
                        ) : (
                          <PhotoIcon className="w-10 h-10 text-gray-400" />
                        )}
                      </div>
                      <div className="flex flex-col gap-2">
                        <button type="button" onClick={() => applyPhotoFilter('grayscale')} className="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                          Ч/Б
                        </button>
                        <button type="button" onClick={() => applyPhotoFilter('sepia')} className="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                          Сепия
                        </button>
                      </div>
                    </div>
                    {/* Кнопки загрузки/сброса/удаления под фото (слева) */}
                    <div className="flex items-center gap-2 justify-center md:justify-start">
                      <input ref={photoInputRef} type="file" accept="image/*" className="hidden" onChange={handlePhotoUpload} />
                      <button type="button" onClick={() => photoInputRef.current?.click()} className="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm">
                        Загрузить фото
                      </button>
                      {form.photo_url && (
                        <>
                          <button type="button" onClick={() => applyPhotoFilter('reset')} className="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                            Сбросить
                          </button>
                          <button type="button" onClick={() => { setForm(prev => ({ ...prev, photo_url: '' })); setOriginalPhotoUrl(''); }} className="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-50">
                            Удалить
                          </button>
                        </>
                      )}
                    </div>
                    <p className="text-xs text-gray-500 text-center md:text-left">Рекомендуемый размер 600x600px. Загрузка реализуется позже.</p>
                  </div>

                  {/* Правая часть: ФИО (вверху) и Даты жизни (внизу), явно разделены */}
                  <div className="space-y-6">
                    <div className="border border-gray-200 rounded-lg p-4 bg-white">
                      <h3 className="text-sm font-semibold text-slate-700 mb-3">Фамилия Имя Отчество</h3>
                      <div className="grid md:grid-cols-3 gap-3">
                        <input name="last_name" value={form.last_name} onChange={handleChange} placeholder="Фамилия" className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" required />
                        <input name="first_name" value={form.first_name} onChange={handleChange} placeholder="Имя" className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" required />
                        <input name="middle_name" value={form.middle_name} onChange={handleChange} placeholder="Отчество" className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
                      </div>
                    </div>
                    <div className="border border-gray-200 rounded-lg p-4 bg-white">
                      <h3 className="text-sm font-semibold text-slate-700 mb-3">Даты жизни</h3>
                      <div className="grid md:grid-cols-2 gap-3">
                        <div>
                          <label className="block text-xs text-gray-600 mb-1">Дата рождения</label>
                          <div className="relative">
                            <CalendarDaysIcon className="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                            <input type="date" name="birth_date" value={form.birth_date} onChange={handleChange} className="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
                          </div>
                        </div>
                        <div>
                          <label className="block text-xs text-gray-600 mb-1">Дата смерти</label>
                          <div className="relative">
                            <CalendarDaysIcon className="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                            <input type="date" name="death_date" value={form.death_date} onChange={handleChange} className="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            {/* 2. Даты и место рождения */}
            <section className="bg-white rounded-card shadow-md overflow-hidden">
              <div className="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <CalendarDaysIcon className="w-5 h-5 text-red-500" />
                <h2 className="text-lg font-semibold text-slate-700">Место рождения</h2>
              </div>
              <div className="p-6 space-y-3">
                <div>
                  <label className="block text-sm text-gray-600 mb-1">Город рождения</label>
                  <div className="relative">
                    <MapPinIcon className="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" />
                    <input name="birth_place" value={form.birth_place} onChange={handleChange} placeholder="Например: Москва" className="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
                  </div>
                </div>
              </div>
            </section>

            {/* 3. (перенесено в правую колонку) */}

            {/* 4. Биография */}
            <section className="bg-white rounded-card shadow-md overflow-hidden">
              <div className="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <BookOpenIcon className="w-5 h-5 text-red-500" />
                <h2 className="text-lg font-semibold text-slate-700">Биография</h2>
              </div>
              <div className="p-6 space-y-4">
                <div>
                  <label className="block text-sm text-gray-600 mb-1">Краткая биография</label>
                  <textarea name="biography" value={form.biography} onChange={handleChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 min-h-[80px]" />
                </div>
                <div>
                  <label className="block text-sm text-gray-600 mb-1">Полная биография</label>
                  <textarea name="full_biography" value={form.full_biography} onChange={handleChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 min-h-[140px]" />
                </div>
              </div>
            </section>

            {/* 5. Место захоронения */}
            <section className="bg_white rounded-card shadow-md overflow-hidden">
              <div className="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                <MapPinIcon className="w-5 h-5 text-red-500" />
                <h2 className="text-lg font-semibold text-slate-700">Место захоронения</h2>
              </div>
              <div className="p-6 grid md:grid-cols-2 gap-4">
                <input name="burial_place" value={form.burial_place} onChange={handleChange} placeholder="Место" className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
                <input name="burial_address" value={form.burial_address} onChange={handleChange} placeholder="Адрес" className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
                <input name="burial_location" value={form.burial_location} onChange={handleChange} placeholder="Секция / ряд / место" className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
                <input name="burial_coordinates" value={form.burial_coordinates} onChange={handleChange} placeholder="Координаты (lat, lng)" className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500" />
              </div>
            </section>

            {/* Действия */}
            <div className="flex items-center justify-end gap-3 pt-2">
              <Link href={isEdit ? `/memorial/${memorialId}` : '/'} className="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-50">
                Отмена
              </Link>
              <button type="submit" disabled={saving} className="px-5 py-2 rounded bg-red-500 hover:bg-red-600 text-white font-medium disabled:bg-gray-400">
                {saving ? 'Сохранение...' : isEdit ? 'Сохранить' : 'Создать'}
              </button>
            </div>
          </form>

          {/* Правая колонка: превью */}
          <aside className="h-fit lg:sticky lg:top-6 space-y-4">
            <div className="bg-white rounded-card shadow-md overflow-hidden">
              <div className="px-4 py-3 border-b border-gray-100">
                <h3 className="text-sm font-semibold text-slate-700">Превью карточки</h3>
              </div>
              <div className="p-4 space-y-3">
                <div className="w-28 h-28 bg-gray-100 rounded-card overflow-hidden mx-auto">
                  {form.photo_url ? (
                    // eslint-disable-next-line @next/next/no-img-element
                    <img src={form.photo_url} alt="Фото" className="w-full h-full object-cover" onError={(e) => { (e.target as HTMLImageElement).src = '/api/placeholder/300/300'; }} />
                  ) : (
                    <PhotoIcon className="w-8 h-8 text-gray-400 mx-auto mt-9" />
                  )}
                </div>
                <div className="text-center">
                  <div className="font-semibold text-slate-700">
                    {[form.first_name, form.middle_name, form.last_name].filter(Boolean).join(' ') || 'Имя Фамилия'}
                  </div>
                  <div className="text-sm text-gray-500">
                    {(form.birth_date || '????-??-??') + ' — ' + (form.death_date || '????-??-??')}
                  </div>
                  {form.birth_place && (
                    <div className="mt-1 inline-flex items-center gap-1 text-xs text-gray-500">
                      <MapPinIcon className="w-3.5 h-3.5" />
                      {form.birth_place}
                    </div>
                  )}
                </div>
              </div>
            </div>

            {/* Вероисповедание (перенесено) */}
            <div className="bg-white rounded-card shadow-md overflow-hidden">
              <div className="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
                <BuildingLibraryIcon className="w-5 h-5 text-red-500" />
                <h3 className="text-sm font-semibold text-slate-700">Вероисповедание</h3>
              </div>
              <div className="p-4">
                <select name="religion" value={form.religion} onChange={handleChange} className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                  <option value="none">Не указано</option>
                  <option value="orthodox">Православие</option>
                  <option value="catholic">Католицизм</option>
                  <option value="islam">Ислам</option>
                  <option value="judaism">Иудаизм</option>
                  <option value="buddhism">Буддизм</option>
                  <option value="hinduism">Индуизм</option>
                  <option value="other">Другое</option>
                </select>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  );
}


