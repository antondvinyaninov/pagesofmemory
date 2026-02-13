'use client';

import React, { useState, useRef } from 'react';
import Link from 'next/link';
import { useAuth } from '@/contexts/AuthContext';
import { authService } from '@/services/api';
import {
  UserIcon,
  PhotoIcon,
  KeyIcon,
  BellIcon,
  EyeSlashIcon,
  TrashIcon,
  ArrowLeftIcon,
  CheckIcon,
  XMarkIcon,
  ExclamationTriangleIcon
} from '@heroicons/react/24/outline';

/**
 * Страница настроек профиля пользователя
 */
export default function ProfileSettingsPage() {
  const { user, isAuthenticated, updateUser, loading } = useAuth();
  const fileInputRef = useRef<HTMLInputElement>(null);

  // Состояния для форм
  const [activeSection, setActiveSection] = useState('profile'); // profile, password, notifications, privacy, danger
  const [isLoading, setIsLoading] = useState(false);
  const [successMessage, setSuccessMessage] = useState('');
  const [errorMessage, setErrorMessage] = useState('');

  // Профиль
  const [profileData, setProfileData] = useState({
    name: '',
    email: '',
    bio: '',
    location: '',
    avatar: ''
  });

  // Синхронизация данных формы с данными пользователя
  React.useEffect(() => {
    if (user) {
      setProfileData({
        name: user.name || '',
        email: user.email || '',
        bio: user.bio || '',
        location: user.location || '',
        avatar: user.avatar || ''
      });
    }
  }, [user]);

  // Пароль
  const [passwordData, setPasswordData] = useState({
    currentPassword: '',
    newPassword: '',
    confirmPassword: ''
  });

  // Уведомления
  const [notificationSettings, setNotificationSettings] = useState({
    emailMemories: true,
    emailComments: true,
    emailLikes: false,
    emailWeeklyDigest: true,
    pushMemories: true,
    pushComments: false,
    pushLikes: false
  });

  // Приватность
  const [privacySettings, setPrivacySettings] = useState({
    profileVisible: true,
    showEmail: false,
    showLocation: true,
    allowMemorialCreation: true,
    allowDirectMessages: true
  });

  // Функции для обработки форм
  const handleProfileSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setErrorMessage('');
    setSuccessMessage('');

    try {
      // API запрос для обновления профиля
      const response = await authService.updateProfile({
        name: profileData.name,
        email: profileData.email,
        avatar: profileData.avatar,
        bio: profileData.bio,
        location: profileData.location
      });
      
      // Обновляем пользователя в контексте с данными от сервера
      updateUser(response.user);
      
      setSuccessMessage(response.message);
    } catch (error: any) {
      const errorMsg = error.response?.data?.error || 'Ошибка при обновлении профиля';
      setErrorMessage(errorMsg);
    } finally {
      setIsLoading(false);
    }
  };

  const handlePasswordSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setErrorMessage('');
    setSuccessMessage('');

    if (passwordData.newPassword !== passwordData.confirmPassword) {
      setErrorMessage('Новые пароли не совпадают');
      setIsLoading(false);
      return;
    }

    try {
      // TODO: API запрос для изменения пароля
      await new Promise(resolve => setTimeout(resolve, 1000)); // Имитация API
      setSuccessMessage('Пароль успешно изменен');
      setPasswordData({ currentPassword: '', newPassword: '', confirmPassword: '' });
    } catch (error) {
      setErrorMessage('Ошибка при изменении пароля');
    } finally {
      setIsLoading(false);
    }
  };

  const handleAvatarUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      // Проверка типа файла
      if (!file.type.startsWith('image/')) {
        setErrorMessage('Пожалуйста, выберите изображение');
        return;
      }

      // Проверка размера файла (максимум 5MB)
      if (file.size > 5 * 1024 * 1024) {
        setErrorMessage('Размер файла не должен превышать 5MB');
        return;
      }

      // Предварительный просмотр
      const reader = new FileReader();
      reader.onload = (e) => {
        const newAvatar = e.target?.result as string;
        
        // Обновляем локальное состояние
        setProfileData(prev => ({
          ...prev,
          avatar: newAvatar
        }));
        
        // Сразу обновляем в контексте для мгновенного отображения
        updateUser({ avatar: newAvatar });
      };
      reader.readAsDataURL(file);

      setSuccessMessage('Аватар обновлен и сохранен.');
      setErrorMessage('');
    }
  };

  const handleDeleteAccount = async () => {
    if (confirm('Вы уверены, что хотите удалить аккаунт? Это действие необратимо.')) {
      if (confirm('Это действие удалит все ваши данные, включая созданные мемориалы и воспоминания. Продолжить?')) {
        setIsLoading(true);
        try {
          // TODO: API запрос для удаления аккаунта
          await new Promise(resolve => setTimeout(resolve, 2000));
          alert('Аккаунт успешно удален');
          // TODO: Выход из системы и редирект
        } catch (error) {
          setErrorMessage('Ошибка при удалении аккаунта');
        } finally {
          setIsLoading(false);
        }
      }
    }
  };

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
          <p className="text-gray-500 mb-6">Для доступа к настройкам необходимо войти в систему</p>
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
      {/* Заголовок */}
      <div className="bg-white border-b border-gray-200">
        <div className="container mx-auto px-4 py-4">
          <div className="flex items-center gap-4">
            <Link 
              href="/profile"
              className="p-2 text-gray-400 hover:text-gray-600 transition-colors"
            >
              <ArrowLeftIcon className="w-5 h-5" />
            </Link>
            <h1 className="text-2xl font-bold text-slate-700">Настройки профиля</h1>
          </div>
        </div>
      </div>

      <div className="container mx-auto px-4 py-8">
        <div className="grid lg:grid-cols-4 gap-8">
          {/* Боковое меню */}
          <div className="lg:col-span-1">
            <div className="bg-white rounded-xl shadow-md overflow-hidden">
              <nav className="p-6 space-y-2">
                <button
                  onClick={() => setActiveSection('profile')}
                  className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors ${
                    activeSection === 'profile'
                      ? 'bg-red-50 text-red-600'
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <UserIcon className="w-5 h-5" />
                  Основные данные
                </button>
                <button
                  onClick={() => setActiveSection('password')}
                  className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors ${
                    activeSection === 'password'
                      ? 'bg-red-50 text-red-600'
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <KeyIcon className="w-5 h-5" />
                  Безопасность
                </button>
                <button
                  onClick={() => setActiveSection('notifications')}
                  className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors ${
                    activeSection === 'notifications'
                      ? 'bg-red-50 text-red-600'
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <BellIcon className="w-5 h-5" />
                  Уведомления
                </button>
                <button
                  onClick={() => setActiveSection('privacy')}
                  className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors ${
                    activeSection === 'privacy'
                      ? 'bg-red-50 text-red-600'
                      : 'text-gray-700 hover:bg-gray-50'
                  }`}
                >
                  <EyeSlashIcon className="w-5 h-5" />
                  Приватность
                </button>
                <hr className="my-4 border-gray-200" />
                <button
                  onClick={() => setActiveSection('danger')}
                  className={`w-full flex items-center gap-3 px-3 py-2 rounded-lg text-left transition-colors ${
                    activeSection === 'danger'
                      ? 'bg-red-50 text-red-600'
                      : 'text-red-600 hover:bg-red-50'
                  }`}
                >
                  <ExclamationTriangleIcon className="w-5 h-5" />
                  Опасная зона
                </button>
              </nav>
            </div>
          </div>

          {/* Основной контент */}
          <div className="lg:col-span-3">
            {/* Уведомления об успехе/ошибке */}
            {successMessage && (
              <div className="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
                <CheckIcon className="w-5 h-5 text-green-600" />
                <p className="text-green-700">{successMessage}</p>
                <button 
                  onClick={() => setSuccessMessage('')}
                  className="ml-auto text-green-600 hover:text-green-800"
                >
                  <XMarkIcon className="w-4 h-4" />
                </button>
              </div>
            )}

            {errorMessage && (
              <div className="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
                <ExclamationTriangleIcon className="w-5 h-5 text-red-600" />
                <p className="text-red-700">{errorMessage}</p>
                <button 
                  onClick={() => setErrorMessage('')}
                  className="ml-auto text-red-600 hover:text-red-800"
                >
                  <XMarkIcon className="w-4 h-4" />
                </button>
              </div>
            )}

            {/* Основные данные */}
            {activeSection === 'profile' && (
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h2 className="text-lg font-semibold text-slate-700">Основные данные</h2>
                </div>
                <form onSubmit={handleProfileSubmit} className="p-6 space-y-6">
                  {/* Аватар */}
                  <div className="flex items-center gap-6">
                  <div className="flex-shrink-0">
                    <Avatar name={profileData.name || 'Пользователь'} src={profileData.avatar} size={96} />
                  </div>
                    <div>
                      <h3 className="font-medium text-slate-700 mb-2">Фото профиля</h3>
                      <p className="text-sm text-gray-500 mb-3">Рекомендуемый размер: 400x400px, максимум 5MB</p>
                      <input
                        type="file"
                        ref={fileInputRef}
                        onChange={handleAvatarUpload}
                        accept="image/*"
                        className="hidden"
                      />
                      <button
                        type="button"
                        onClick={() => fileInputRef.current?.click()}
                        className="flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors text-sm"
                      >
                        <PhotoIcon className="w-4 h-4" />
                        Изменить фото
                      </button>
                    </div>
                  </div>

                  {/* Имя */}
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Имя *
                    </label>
                    <input
                      type="text"
                      value={profileData.name}
                      onChange={(e) => setProfileData(prev => ({ ...prev, name: e.target.value }))}
                      required
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  </div>

                  {/* Email */}
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Email *
                    </label>
                    <input
                      type="email"
                      value={profileData.email}
                      onChange={(e) => setProfileData(prev => ({ ...prev, email: e.target.value }))}
                      required
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  </div>

                  {/* Биография */}
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      О себе
                    </label>
                    <textarea
                      value={profileData.bio}
                      onChange={(e) => setProfileData(prev => ({ ...prev, bio: e.target.value }))}
                      rows={4}
                      placeholder="Расскажите немного о себе..."
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 resize-none"
                    />
                  </div>

                  {/* Местоположение */}
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Местоположение
                    </label>
                    <input
                      type="text"
                      value={profileData.location}
                      onChange={(e) => setProfileData(prev => ({ ...prev, location: e.target.value }))}
                      placeholder="Город, страна"
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  </div>


                  <div className="flex justify-end">
                    <button
                      type="submit"
                      disabled={isLoading}
                      className="bg-red-500 hover:bg-red-600 disabled:bg-gray-300 text-white px-6 py-2 rounded-lg transition-colors"
                    >
                      {isLoading ? 'Сохранение...' : 'Сохранить изменения'}
                    </button>
                  </div>
                </form>
              </div>
            )}

            {/* Безопасность */}
            {activeSection === 'password' && (
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h2 className="text-lg font-semibold text-slate-700">Изменение пароля</h2>
                </div>
                <form onSubmit={handlePasswordSubmit} className="p-6 space-y-6">
                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Текущий пароль *
                    </label>
                    <input
                      type="password"
                      value={passwordData.currentPassword}
                      onChange={(e) => setPasswordData(prev => ({ ...prev, currentPassword: e.target.value }))}
                      required
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Новый пароль *
                    </label>
                    <input
                      type="password"
                      value={passwordData.newPassword}
                      onChange={(e) => setPasswordData(prev => ({ ...prev, newPassword: e.target.value }))}
                      required
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  </div>

                  <div>
                    <label className="block text-sm font-medium text-slate-700 mb-2">
                      Подтвердите новый пароль *
                    </label>
                    <input
                      type="password"
                      value={passwordData.confirmPassword}
                      onChange={(e) => setPasswordData(prev => ({ ...prev, confirmPassword: e.target.value }))}
                      required
                      className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  </div>

                  <div className="flex justify-end">
                    <button
                      type="submit"
                      disabled={isLoading}
                      className="bg-red-500 hover:bg-red-600 disabled:bg-gray-300 text-white px-6 py-2 rounded-lg transition-colors"
                    >
                      {isLoading ? 'Изменение...' : 'Изменить пароль'}
                    </button>
                  </div>
                </form>
              </div>
            )}

            {/* Уведомления */}
            {activeSection === 'notifications' && (
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h2 className="text-lg font-semibold text-slate-700">Настройки уведомлений</h2>
                </div>
                <div className="p-6 space-y-8">
                  {/* Email уведомления */}
                  <div>
                    <h3 className="font-medium text-slate-700 mb-4">Email уведомления</h3>
                    <div className="space-y-4">
                      {[
                        { key: 'emailMemories', label: 'Новые воспоминания в ваших мемориалах' },
                        { key: 'emailComments', label: 'Комментарии к вашим воспоминаниям' },
                        { key: 'emailLikes', label: 'Лайки ваших воспоминаний' },
                        { key: 'emailWeeklyDigest', label: 'Еженедельная сводка активности' }
                      ].map(({ key, label }) => (
                        <label key={key} className="flex items-center gap-3">
                          <input
                            type="checkbox"
                            checked={notificationSettings[key as keyof typeof notificationSettings]}
                            onChange={(e) => setNotificationSettings(prev => ({
                              ...prev,
                              [key]: e.target.checked
                            }))}
                            className="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                          />
                          <span className="text-slate-700">{label}</span>
                        </label>
                      ))}
                    </div>
                  </div>

                  {/* Push уведомления */}
                  <div>
                    <h3 className="font-medium text-slate-700 mb-4">Push уведомления</h3>
                    <div className="space-y-4">
                      {[
                        { key: 'pushMemories', label: 'Новые воспоминания' },
                        { key: 'pushComments', label: 'Комментарии' },
                        { key: 'pushLikes', label: 'Лайки' }
                      ].map(({ key, label }) => (
                        <label key={key} className="flex items-center gap-3">
                          <input
                            type="checkbox"
                            checked={notificationSettings[key as keyof typeof notificationSettings]}
                            onChange={(e) => setNotificationSettings(prev => ({
                              ...prev,
                              [key]: e.target.checked
                            }))}
                            className="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500"
                          />
                          <span className="text-slate-700">{label}</span>
                        </label>
                      ))}
                    </div>
                  </div>

                  <div className="flex justify-end">
                    <button
                      onClick={() => setSuccessMessage('Настройки уведомлений сохранены')}
                      className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
                    >
                      Сохранить настройки
                    </button>
                  </div>
                </div>
              </div>
            )}

            {/* Приватность */}
            {activeSection === 'privacy' && (
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h2 className="text-lg font-semibold text-slate-700">Настройки приватности</h2>
                </div>
                <div className="p-6 space-y-6">
                  {[
                    { 
                      key: 'profileVisible', 
                      label: 'Профиль виден другим пользователям',
                      description: 'Если отключено, ваш профиль будет скрыт от других пользователей'
                    },
                    { 
                      key: 'showEmail', 
                      label: 'Показывать email в профиле',
                      description: 'Ваш email будет виден на странице профиля'
                    },
                    { 
                      key: 'showLocation', 
                      label: 'Показывать местоположение',
                      description: 'Ваше местоположение будет отображаться в профиле'
                    },
                    { 
                      key: 'allowMemorialCreation', 
                      label: 'Разрешить создание мемориалов',
                      description: 'Другие пользователи могут создавать мемориалы в вашу память'
                    },
                    { 
                      key: 'allowDirectMessages', 
                      label: 'Разрешить личные сообщения',
                      description: 'Другие пользователи могут отправлять вам сообщения'
                    }
                  ].map(({ key, label, description }) => (
                    <div key={key} className="flex items-start gap-3">
                      <input
                        type="checkbox"
                        checked={privacySettings[key as keyof typeof privacySettings]}
                        onChange={(e) => setPrivacySettings(prev => ({
                          ...prev,
                          [key]: e.target.checked
                        }))}
                        className="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500 mt-1"
                      />
                      <div>
                        <label className="font-medium text-slate-700">{label}</label>
                        <p className="text-sm text-gray-500 mt-1">{description}</p>
                      </div>
                    </div>
                  ))}

                  <div className="flex justify-end">
                    <button
                      onClick={() => setSuccessMessage('Настройки приватности сохранены')}
                      className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors"
                    >
                      Сохранить настройки
                    </button>
                  </div>
                </div>
              </div>
            )}

            {/* Опасная зона */}
            {activeSection === 'danger' && (
              <div className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h2 className="text-lg font-semibold text-red-600">Опасная зона</h2>
                </div>
                <div className="p-6">
                  <div className="border border-red-200 rounded-lg p-6 bg-red-50">
                    <div className="flex items-start gap-4">
                      <TrashIcon className="w-8 h-8 text-red-600 flex-shrink-0 mt-1" />
                      <div className="flex-1">
                        <h3 className="font-semibold text-red-700 mb-2">Удаление аккаунта</h3>
                        <p className="text-red-600 text-sm mb-4">
                          После удаления аккаунта все ваши данные будут безвозвратно утеряны, включая:
                        </p>
                        <ul className="text-red-600 text-sm mb-6 list-disc list-inside space-y-1">
                          <li>Профиль и личные данные</li>
                          <li>Созданные мемориалы</li>
                          <li>Оставленные воспоминания и комментарии</li>
                          <li>История активности</li>
                        </ul>
                        <button
                          onClick={handleDeleteAccount}
                          disabled={isLoading}
                          className="bg-red-600 hover:bg-red-700 disabled:bg-gray-300 text-white px-6 py-2 rounded-lg transition-colors font-medium"
                        >
                          {isLoading ? 'Удаление...' : 'Удалить аккаунт'}
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
