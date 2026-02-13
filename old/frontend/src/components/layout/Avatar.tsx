'use client';

import React, { useState } from 'react';

type AvatarProps = {
  name: string;
  src?: string | null;
  size?: number; // px
  className?: string;
  rounded?: boolean; // default true
};

const getInitials = (name: string): string => {
  if (!name) return '';
  const parts = name.trim().split(/\s+/).slice(0, 2);
  const initials = parts.map(p => p[0]?.toUpperCase() || '').join('');
  return initials || '';
};

export const Avatar: React.FC<AvatarProps> = ({ name, src, size = 40, className = '', rounded = true }) => {
  const [failed, setFailed] = useState<boolean>(false);
  const initials = getInitials(name);
  const fontSize = Math.max(10, Math.floor(size * 0.42));
  const style: React.CSSProperties = { width: size, height: size, fontSize, lineHeight: 1 };

  const baseClasses = `${rounded ? 'rounded-full' : 'rounded-card'} ${className}`;

  if (src && !failed) {
    return (
      <div className={`relative overflow-hidden ${baseClasses}`} style={style} aria-label={name} title={name}>
        {/* Фон с инициалами за картинкой на случай задержки */}
        <div className="absolute inset-0 bg-slate-700 text-white flex items-center justify-center font-semibold">
          {initials}
        </div>
        {/* eslint-disable-next-line @next/next/no-img-element */}
        <img
          src={src}
          alt={name}
          className="absolute inset-0 w-full h-full object-cover"
          onError={() => setFailed(true)}
        />
      </div>
    );
  }

  return (
    <div
      className={`${baseClasses} bg-slate-700 text-white flex items-center justify-center font-semibold`}
      style={style}
      aria-label={name}
      title={name}
    >
      {initials}
    </div>
  );
};

export default Avatar;


