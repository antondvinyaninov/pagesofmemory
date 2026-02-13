import { z } from 'zod';

/**
 * Схема для регистрации пользователя
 */
export const registerUserSchema = z.object({
  email: z.string().email('Неверный формат email'),
  password: z.string().min(1, 'Пароль обязателен'),
  name: z.string().min(2, 'Имя должно содержать минимум 2 символа')
});

/**
 * Схема для авторизации пользователя
 */
export const loginUserSchema = z.object({
  email: z.string().email('Неверный формат email'),
  password: z.string().min(1, 'Пароль обязателен')
});

/**
 * Схема для обновления профиля пользователя
 */
export const updateUserProfileSchema = z.object({
  name: z.string().min(2, 'Имя должно содержать минимум 2 символа').optional(),
  email: z.string().email('Неверный формат email').optional(),
  avatar: z.string().optional(),
  bio: z.string().optional(),
  location: z.string().optional()
});

/**
 * Перечисление ролей пользователей
 */
export const userRoles = ['user', 'moderator', 'admin', 'super_admin'] as const;
export type UserRole = typeof userRoles[number];

/**
 * Схема пользователя в базе данных
 */
export const userSchema = z.object({
  id: z.number(),
  email: z.string().email(),
  password: z.string(),
  name: z.string(),
  avatar: z.string().optional(),
  bio: z.string().optional(),
  location: z.string().optional(),
  role: z.enum(userRoles).default('user'),
  createdAt: z.string(),
  updatedAt: z.string()
});

/**
 * Схема пользователя для ответа (без пароля)
 */
export const userResponseSchema = userSchema.omit({ password: true });

/**
 * Типы, выведенные из Zod схем
 */
export type RegisterUserRequest = z.infer<typeof registerUserSchema>;
export type LoginUserRequest = z.infer<typeof loginUserSchema>;
export type UpdateUserProfileRequest = z.infer<typeof updateUserProfileSchema>;
export type User = z.infer<typeof userSchema>;
export type UserResponse = z.infer<typeof userResponseSchema>;

/**
 * Тип для JWT payload
 */
export interface JwtPayload {
  userId: number;
  email: string;
}
