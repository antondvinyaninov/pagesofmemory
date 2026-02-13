import type { JwtPayload } from '../types/user';
/**
 * Генерация JWT токена
 */
export declare const generateToken: (payload: JwtPayload) => string;
/**
 * Проверка JWT токена
 */
export declare const verifyToken: (token: string) => JwtPayload;
/**
 * Извлечение токена из заголовка Authorization
 */
export declare const extractTokenFromHeader: (authHeader: string | undefined) => string;
