import type { Request, Response, NextFunction } from 'express';
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
export declare const authenticateToken: (req: AuthenticatedRequest, res: Response, next: NextFunction) => Promise<void>;
