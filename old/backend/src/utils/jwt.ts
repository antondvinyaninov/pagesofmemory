import jwt from 'jsonwebtoken';
import { config } from '../config';
import type { JwtPayload } from '../types/user';

/**
 * Генерация JWT токена
 */
export const generateToken = (payload: JwtPayload): string => {
  return jwt.sign(payload, config.jwtSecret, {
    expiresIn: config.jwtExpiresIn
  } as jwt.SignOptions);
};

/**
 * Проверка JWT токена
 */
export const verifyToken = (token: string): JwtPayload => {
  try {
    return jwt.verify(token, config.jwtSecret) as JwtPayload;
  } catch (error) {
    throw new Error('Неверный токен');
  }
};

/**
 * Извлечение токена из заголовка Authorization
 */
export const extractTokenFromHeader = (authHeader: string | undefined): string => {
  if (!authHeader) {
    throw new Error('Отсутствует заголовок авторизации');
  }

  const parts = authHeader.split(' ');
  if (parts.length !== 2 || parts[0] !== 'Bearer') {
    throw new Error('Неверный формат заголовка авторизации');
  }

  return parts[1];
};
