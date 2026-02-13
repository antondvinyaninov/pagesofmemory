'use client';

import React from 'react';

/**
 * Компонент подвала сайта в стиле PHP проекта
 */
export const Footer: React.FC = () => {
  return (
    <footer className="bg-slate-700 text-white py-4 mt-16">
      <div className="container mx-auto px-4">
        <div className="flex flex-col md:flex-row justify-between items-center gap-2">
          <p className="text-sm text-center md:text-left">
            © 2025 Memory Platform. Создано с ❤️ для сохранения важных моментов.
          </p>
          <p className="text-sm text-white/70">
            Сохраните память о близких для будущих поколений
          </p>
        </div>
      </div>
    </footer>
  );
};
