import { z } from 'zod';
/**
 * Схема для регистрации пользователя
 */
export declare const registerUserSchema: z.ZodObject<{
    email: z.ZodString;
    password: z.ZodString;
    name: z.ZodString;
}, "strip", z.ZodTypeAny, {
    email: string;
    password: string;
    name: string;
}, {
    email: string;
    password: string;
    name: string;
}>;
/**
 * Схема для авторизации пользователя
 */
export declare const loginUserSchema: z.ZodObject<{
    email: z.ZodString;
    password: z.ZodString;
}, "strip", z.ZodTypeAny, {
    email: string;
    password: string;
}, {
    email: string;
    password: string;
}>;
/**
 * Схема для обновления профиля пользователя
 */
export declare const updateUserProfileSchema: z.ZodObject<{
    name: z.ZodOptional<z.ZodString>;
    email: z.ZodOptional<z.ZodString>;
    avatar: z.ZodOptional<z.ZodString>;
    bio: z.ZodOptional<z.ZodString>;
    location: z.ZodOptional<z.ZodString>;
}, "strip", z.ZodTypeAny, {
    email?: string | undefined;
    name?: string | undefined;
    avatar?: string | undefined;
    bio?: string | undefined;
    location?: string | undefined;
}, {
    email?: string | undefined;
    name?: string | undefined;
    avatar?: string | undefined;
    bio?: string | undefined;
    location?: string | undefined;
}>;
/**
 * Перечисление ролей пользователей
 */
export declare const userRoles: readonly ["user", "moderator", "admin", "super_admin"];
export type UserRole = typeof userRoles[number];
/**
 * Схема пользователя в базе данных
 */
export declare const userSchema: z.ZodObject<{
    id: z.ZodNumber;
    email: z.ZodString;
    password: z.ZodString;
    name: z.ZodString;
    avatar: z.ZodOptional<z.ZodString>;
    bio: z.ZodOptional<z.ZodString>;
    location: z.ZodOptional<z.ZodString>;
    role: z.ZodDefault<z.ZodEnum<["user", "moderator", "admin", "super_admin"]>>;
    createdAt: z.ZodString;
    updatedAt: z.ZodString;
}, "strip", z.ZodTypeAny, {
    email: string;
    password: string;
    name: string;
    id: number;
    role: "user" | "moderator" | "admin" | "super_admin";
    createdAt: string;
    updatedAt: string;
    avatar?: string | undefined;
    bio?: string | undefined;
    location?: string | undefined;
}, {
    email: string;
    password: string;
    name: string;
    id: number;
    createdAt: string;
    updatedAt: string;
    avatar?: string | undefined;
    bio?: string | undefined;
    location?: string | undefined;
    role?: "user" | "moderator" | "admin" | "super_admin" | undefined;
}>;
/**
 * Схема пользователя для ответа (без пароля)
 */
export declare const userResponseSchema: z.ZodObject<Omit<{
    id: z.ZodNumber;
    email: z.ZodString;
    password: z.ZodString;
    name: z.ZodString;
    avatar: z.ZodOptional<z.ZodString>;
    bio: z.ZodOptional<z.ZodString>;
    location: z.ZodOptional<z.ZodString>;
    role: z.ZodDefault<z.ZodEnum<["user", "moderator", "admin", "super_admin"]>>;
    createdAt: z.ZodString;
    updatedAt: z.ZodString;
}, "password">, "strip", z.ZodTypeAny, {
    email: string;
    name: string;
    id: number;
    role: "user" | "moderator" | "admin" | "super_admin";
    createdAt: string;
    updatedAt: string;
    avatar?: string | undefined;
    bio?: string | undefined;
    location?: string | undefined;
}, {
    email: string;
    name: string;
    id: number;
    createdAt: string;
    updatedAt: string;
    avatar?: string | undefined;
    bio?: string | undefined;
    location?: string | undefined;
    role?: "user" | "moderator" | "admin" | "super_admin" | undefined;
}>;
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
