/**
 * Коллекция часто используемых Hero Icons для проекта Memory
 * Экспортируем самые популярные иконки для удобного импорта
 */

// Outline иконки (тонкие линии)
export {
  // Навигация и UI
  HomeIcon,
  UserIcon,
  Cog6ToothIcon as SettingsIcon,
  BellIcon as NotificationIcon,
  MagnifyingGlassIcon as SearchIcon,
  
  // Аутентификация
  ArrowRightOnRectangleIcon as LoginIcon,
  ArrowLeftOnRectangleIcon as LogoutIcon,
  UserPlusIcon,
  EyeIcon,
  EyeSlashIcon,
  
  // Контент и данные
  DocumentIcon,
  FolderIcon,
  PhotoIcon,
  FilmIcon,
  MusicalNoteIcon,
  LinkIcon,
  TagIcon,
  
  // Действия
  PlusIcon,
  MinusIcon,
  PencilIcon as EditIcon,
  TrashIcon as DeleteIcon,
  ShareIcon,
  BookmarkIcon,
  HeartIcon,
  StarIcon,
  
  // Состояния и индикаторы
  CheckIcon,
  XMarkIcon as CloseIcon,
  ExclamationTriangleIcon as WarningIcon,
  InformationCircleIcon as InfoIcon,
  
  // Стрелки и направления
  ArrowUpIcon,
  ArrowDownIcon,
  ArrowLeftIcon,
  ArrowRightIcon,
  ChevronUpIcon,
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  
  // Функциональные
  CpuChipIcon as BrainIcon,
  UsersIcon,
  ShieldCheckIcon,
  BoltIcon,
  CalendarIcon,
  ClockIcon,
  MapPinIcon,
  
  // Коммуникация
  ChatBubbleLeftRightIcon as ChatIcon,
  EnvelopeIcon as EmailIcon,
  PhoneIcon,
  
  // Медиа и файлы
  ArrowUpTrayIcon as UploadIcon,
  ArrowDownTrayIcon as DownloadIcon,
  PrinterIcon,
  
} from '@heroicons/react/24/outline';

// Solid иконки (заполненные)
export {
  HomeIcon as HomeIconSolid,
  UserIcon as UserIconSolid,
  HeartIcon as HeartIconSolid,
  StarIcon as StarIconSolid,
  BookmarkIcon as BookmarkIconSolid,
  BellIcon as NotificationIconSolid,
  CheckCircleIcon as CheckIconSolid,
  XCircleIcon as ErrorIconSolid,
  ExclamationCircleIcon as WarningIconSolid,
  InformationCircleIcon as InfoIconSolid,
} from '@heroicons/react/24/solid';

/**
 * Типы для размеров иконок
 */
export type IconSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl';

/**
 * Маппинг размеров иконок в Tailwind классы
 */
export const iconSizes: Record<IconSize, string> = {
  xs: 'h-3 w-3',    // 12px
  sm: 'h-4 w-4',    // 16px  
  md: 'h-5 w-5',    // 20px
  lg: 'h-6 w-6',    // 24px
  xl: 'h-8 w-8',    // 32px
};

/**
 * Утилита для получения размера иконки
 */
export const getIconSize = (size: IconSize = 'md'): string => {
  return iconSizes[size];
};
