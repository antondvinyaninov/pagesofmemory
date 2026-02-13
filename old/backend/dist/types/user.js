"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.userResponseSchema = exports.userSchema = exports.userRoles = exports.updateUserProfileSchema = exports.loginUserSchema = exports.registerUserSchema = void 0;
const zod_1 = require("zod");
/**
 * Схема для регистрации пользователя
 */
exports.registerUserSchema = zod_1.z.object({
    email: zod_1.z.string().email('Неверный формат email'),
    password: zod_1.z.string().min(1, 'Пароль обязателен'),
    name: zod_1.z.string().min(2, 'Имя должно содержать минимум 2 символа')
});
/**
 * Схема для авторизации пользователя
 */
exports.loginUserSchema = zod_1.z.object({
    email: zod_1.z.string().email('Неверный формат email'),
    password: zod_1.z.string().min(1, 'Пароль обязателен')
});
/**
 * Схема для обновления профиля пользователя
 */
exports.updateUserProfileSchema = zod_1.z.object({
    name: zod_1.z.string().min(2, 'Имя должно содержать минимум 2 символа').optional(),
    email: zod_1.z.string().email('Неверный формат email').optional(),
    avatar: zod_1.z.string().optional(),
    bio: zod_1.z.string().optional(),
    location: zod_1.z.string().optional()
});
/**
 * Перечисление ролей пользователей
 */
exports.userRoles = ['user', 'moderator', 'admin', 'super_admin'];
/**
 * Схема пользователя в базе данных
 */
exports.userSchema = zod_1.z.object({
    id: zod_1.z.number(),
    email: zod_1.z.string().email(),
    password: zod_1.z.string(),
    name: zod_1.z.string(),
    avatar: zod_1.z.string().optional(),
    bio: zod_1.z.string().optional(),
    location: zod_1.z.string().optional(),
    role: zod_1.z.enum(exports.userRoles).default('user'),
    createdAt: zod_1.z.string(),
    updatedAt: zod_1.z.string()
});
/**
 * Схема пользователя для ответа (без пароля)
 */
exports.userResponseSchema = exports.userSchema.omit({ password: true });
//# sourceMappingURL=user.js.map