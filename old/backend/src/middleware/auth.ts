import type { Request, Response, NextFunction } from 'express';
import { verifyToken, extractTokenFromHeader } from '../utils/jwt';
import { userModel } from '../models/userModel';
import type { JwtPayload } from '../types/user';

/**
 * Расширение интерфейса Request для добавления информации о пользователе
 */
export interface AuthenticatedRequest extends Request {
  user?: {
    id: number;
    email: string;
    role: string;
  };
}

/**
 * Middleware для проверки аутентификации
 */
export const authenticateToken = async (
  req: AuthenticatedRequest,
  res: Response,
  next: NextFunction
): Promise<void> => {
  try {
    const token = extractTokenFromHeader(req.headers.authorization);
    const payload: JwtPayload = verifyToken(token);

    // Проверяем существование пользователя в базе данных
    const user = await userModel.findUserById(payload.userId);
    if (!user) {
      res.status(401).json({ error: 'Пользователь не найден' });
      return;
    }

    // Добавляем информацию о пользователе в запрос
    req.user = {
      id: user.id,
      email: user.email,
      role: user.role || 'user'
    };

    next();
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Ошибка аутентификации';
    res.status(401).json({ error: message });
  }
};
