'use client';

import React from 'react';
import { 
  Icon,
  HomeIcon,
  UserIcon,
  SearchIcon,
  LoginIcon,
  LogoutIcon,
  BrainIcon,
  type IconSize 
} from '@/components/icons';

/**
 * Компонент с примерами использования иконок
 * Может быть использован для тестирования или как справочник
 */
export const IconExamples: React.FC = () => {
  const sizes: IconSize[] = ['xs', 'sm', 'md', 'lg', 'xl'];

  return (
    <div className="p-8 space-y-8">
      <h1 className="text-2xl font-bold text-gray-900">Примеры использования иконок</h1>

      {/* Размеры иконок */}
      <section>
        <h2 className="text-xl font-semibold text-gray-900 mb-4">Размеры иконок</h2>
        <div className="flex items-center gap-4">
          {sizes.map((size) => (
            <div key={size} className="text-center">
              <Icon icon={HomeIcon} size={size} color="text-blue-600" />
              <p className="text-sm text-gray-600 mt-1">{size}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Цвета иконок */}
      <section>
        <h2 className="text-xl font-semibold text-gray-900 mb-4">Цвета иконок</h2>
        <div className="flex items-center gap-4">
          <Icon icon={UserIcon} color="text-red-600" />
          <Icon icon={UserIcon} color="text-green-600" />
          <Icon icon={UserIcon} color="text-blue-600" />
          <Icon icon={UserIcon} color="text-purple-600" />
          <Icon icon={UserIcon} color="text-gray-600" />
        </div>
      </section>

      {/* Интерактивные иконки */}
      <section>
        <h2 className="text-xl font-semibold text-gray-900 mb-4">Интерактивные иконки</h2>
        <div className="flex items-center gap-4">
          <Icon 
            icon={SearchIcon} 
            size="lg" 
            color="text-blue-600"
            onClick={() => alert('Поиск!')}
            aria-label="Выполнить поиск"
          />
          <Icon 
            icon={LoginIcon} 
            size="lg" 
            color="text-green-600"
            onClick={() => alert('Войти!')}
            aria-label="Войти в систему"
          />
          <Icon 
            icon={LogoutIcon} 
            size="lg" 
            color="text-red-600"
            onClick={() => alert('Выйти!')}
            aria-label="Выйти из системы"
          />
        </div>
      </section>

      {/* Функциональные иконки */}
      <section>
        <h2 className="text-xl font-semibold text-gray-900 mb-4">Функциональные иконки</h2>
        <div className="grid grid-cols-4 gap-4">
          <div className="text-center">
            <Icon icon={BrainIcon} size="lg" color="text-blue-600" />
            <p className="text-sm text-gray-600 mt-1">Умное хранение</p>
          </div>
          <div className="text-center">
            <Icon icon={UserIcon} size="lg" color="text-green-600" />
            <p className="text-sm text-gray-600 mt-1">Пользователи</p>
          </div>
          <div className="text-center">
            <Icon icon={SearchIcon} size="lg" color="text-purple-600" />
            <p className="text-sm text-gray-600 mt-1">Поиск</p>
          </div>
          <div className="text-center">
            <Icon icon={HomeIcon} size="lg" color="text-orange-600" />
            <p className="text-sm text-gray-600 mt-1">Главная</p>
          </div>
        </div>
      </section>

      {/* Примеры кода */}
      <section>
        <h2 className="text-xl font-semibold text-gray-900 mb-4">Примеры кода</h2>
        <div className="bg-gray-100 rounded-lg p-4 font-mono text-sm">
          <div className="space-y-2">
            <p className="text-gray-700">// Базовое использование</p>
            <p className="text-blue-600">&lt;Icon icon={`{HomeIcon}`} /&gt;</p>
            
            <p className="text-gray-700 mt-4">// С настройками</p>
            <p className="text-blue-600">&lt;Icon icon={`{UserIcon}`} size="lg" color="text-blue-600" /&gt;</p>
            
            <p className="text-gray-700 mt-4">// Интерактивная иконка</p>
            <p className="text-blue-600">&lt;Icon icon={`{SearchIcon}`} onClick={`{handleSearch}`} aria-label="Поиск" /&gt;</p>
          </div>
        </div>
      </section>
    </div>
  );
};
