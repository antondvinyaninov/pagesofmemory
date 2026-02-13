import type { Request, Response } from 'express';
/**
 * Контроллер для получения списка всех пользователей (только для админов)
 */
export declare const getAllUsers: (req: Request, res: Response) => Promise<void>;
/**
 * Контроллер для обновления роли пользователя (только для админов)
 */
export declare const updateUserRole: (req: Request, res: Response) => Promise<void>;
/**
 * Контроллер для получения списка всех мемориалов (только для модераторов и выше)
 */
export declare const getAllMemorials: (req: Request, res: Response) => Promise<void>;
/**
 * Контроллер для обновления статуса мемориала (только для модераторов и выше)
 */
export declare const updateMemorialStatus: (req: Request, res: Response) => Promise<void>;
/**
 * Контроллер для удаления мемориала (только для админов)
 */
export declare const deleteMemorial: (req: Request, res: Response) => Promise<void>;
