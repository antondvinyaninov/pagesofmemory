import type { User, UserRole } from '@/types/auth';

/**
 * Проверка, является ли пользователь администратором
 */
export const isAdmin = (user: User | null): boolean => {
  return user?.role === 'admin' || user?.role === 'super_admin';
};

/**
 * Проверка, является ли пользователь модератором или выше
 */
export const isModerator = (user: User | null): boolean => {
  return user?.role === 'moderator' || user?.role === 'admin' || user?.role === 'super_admin';
};

/**
 * Проверка роли пользователя
 */
export const hasRole = (user: User | null, role: UserRole): boolean => {
  if (!user) return false;
  
  const roleHierarchy: Record<UserRole, number> = {
    user: 0,
    moderator: 1,
    admin: 2,
    super_admin: 3
  };
  
  const userLevel = roleHierarchy[user.role] || 0;
  const requiredLevel = roleHierarchy[role] || 0;
  
  return userLevel >= requiredLevel;
};

/**
 * Проверка минимальной роли пользователя
 */
export const hasMinRole = (user: User | null, minRole: UserRole): boolean => {
  return hasRole(user, minRole);
};



