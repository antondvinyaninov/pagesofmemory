import type { Request, Response } from 'express';
/**
 * Контроллер для регистрации пользователя
 */
export declare const registerUser: (req: Request, res: Response) => Promise<void>;
/**
 * Контроллер для авторизации пользователя
 */
export declare const loginUser: (req: Request, res: Response) => Promise<void>;
/**
 * Контроллер для получения профиля пользователя
 */
export declare const getUserProfile: (req: Request, res: Response) => Promise<void>;
/**
 * Контроллер для обновления профиля пользователя
 */
export declare const updateUserProfile: (req: Request, res: Response) => Promise<void>;
