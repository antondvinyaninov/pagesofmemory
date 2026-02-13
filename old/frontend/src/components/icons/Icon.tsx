import React from 'react';
import { type IconSize, getIconSize } from './Icons';

/**
 * Интерфейс для компонента Icon
 */
interface IconProps {
  /** Hero Icon компонент */
  icon: React.ComponentType<React.SVGProps<SVGSVGElement>>;
  /** Размер иконки */
  size?: IconSize;
  /** Дополнительные CSS классы */
  className?: string;
  /** Цвет иконки (Tailwind класс) */
  color?: string;
  /** Обработчик клика */
  onClick?: () => void;
  /** Aria label для доступности */
  'aria-label'?: string;
}

/**
 * Универсальный компонент для отображения Hero Icons
 * 
 * @example
 * ```tsx
 * <Icon icon={HomeIcon} size="lg" color="text-blue-600" />
 * <Icon icon={UserIcon} size="sm" onClick={handleUserClick} aria-label="Профиль пользователя" />
 * ```
 */
export const Icon: React.FC<IconProps> = ({
  icon: IconComponent,
  size = 'md',
  className = '',
  color = 'text-current',
  onClick,
  'aria-label': ariaLabel,
  ...props
}) => {
  const sizeClass = getIconSize(size);
  const baseClasses = `${sizeClass} ${color}`;
  const clickableClasses = onClick ? 'cursor-pointer hover:opacity-75 transition-opacity' : '';
  const finalClassName = `${baseClasses} ${clickableClasses} ${className}`.trim();

  return (
    <IconComponent
      className={finalClassName}
      onClick={onClick}
      aria-label={ariaLabel}
      role={onClick ? 'button' : undefined}
      tabIndex={onClick ? 0 : undefined}
      onKeyDown={onClick ? (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          onClick();
        }
      } : undefined}
      {...props}
    />
  );
};
