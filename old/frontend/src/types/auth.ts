import { z } from 'zod';

/**
 * Схема для регистрации пользователя
 */
export const registerSchema = z.object({
  name: z.string().min(2, 'Имя должно содержать минимум 2 символа'),
  email: z.string().email('Неверный формат email'),
  password: z.string().min(1, 'Пароль обязателен'),
  confirmPassword: z.string().min(1, 'Подтвердите пароль')
}).refine((data) => data.password === data.confirmPassword, {
  message: "Пароли не совпадают",
  path: ["confirmPassword"]
});

/**
 * Схема для авторизации пользователя
 */
export const loginSchema = z.object({
  email: z.string().email('Неверный формат email'),
  password: z.string().min(1, 'Пароль обязателен')
});

/**
 * Роли пользователей
 */
export const userRoles = ['user', 'moderator', 'admin', 'super_admin'] as const;
export type UserRole = typeof userRoles[number];

/**
 * Схема пользователя
 */
export const userSchema = z.object({
  id: z.number(),
  email: z.string().email(),
  name: z.string(),
  avatar: z.string().optional(),
  bio: z.string().optional(),
  location: z.string().optional(),
  role: z.enum(userRoles).default('user'),
  createdAt: z.string(),
  updatedAt: z.string()
});

/**
 * Типы, выведенные из схем
 */
export type RegisterFormData = z.infer<typeof registerSchema>;
export type LoginFormData = z.infer<typeof loginSchema>;
export type User = z.infer<typeof userSchema>;

/**
 * Тип ответа от API при аутентификации
 */
export interface AuthResponse {
  message: string;
  user: User;
  token: string;
}

/**
 * Тип ошибки от API
 */
export interface ApiError {
  error: string;
  details?: string;
}
