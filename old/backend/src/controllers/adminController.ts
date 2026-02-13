import type { Request, Response } from 'express';
import { userRoles } from '../types/user';
import { userModel } from '../models/userModel';
import { memorialModel } from '../models/memorialModel';
import { omit } from 'lodash';

/**
 * Проверка прав администратора
 */
const checkAdminRights = (userRole: string): boolean => {
  return userRole === 'admin' || userRole === 'super_admin';
};

/**
 * Контроллер для получения списка всех пользователей (только для админов)
 */
export const getAllUsers = async (req: Request, res: Response): Promise<void> => {
  try {
    const userId = (req as any).user?.id;
    const userRole = (req as any).user?.role;

    if (!userId) {
      res.status(401).json({ error: 'Пользователь не аутентифицирован' });
      return;
    }

    // Проверяем права администратора
    if (!checkAdminRights(userRole)) {
      res.status(403).json({ error: 'Недостаточно прав для выполнения операции' });
      return;
    }

    // Получаем всех пользователей
    const users = await userModel.getAllUsers();

    // Возвращаем данные пользователей без паролей
    const usersResponse = users.map(user => omit(user, ['password']));

    res.status(200).json({
      users: usersResponse,
      total: usersResponse.length
    });
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
    res.status(500).json({ error: message });
  }
};

/**
 * Контроллер для обновления роли пользователя (только для админов)
 */
export const updateUserRole = async (req: Request, res: Response): Promise<void> => {
  try {
    const userId = (req as any).user?.id;
    const userRole = (req as any).user?.role;
    const targetUserId = parseInt(req.params.id);
    const { role } = req.body;

    if (!userId) {
      res.status(401).json({ error: 'Пользователь не аутентифицирован' });
      return;
    }

    // Проверяем права администратора
    if (!checkAdminRights(userRole)) {
      res.status(403).json({ error: 'Недостаточно прав для выполнения операции' });
      return;
    }

    // Валидация роли
    if (!userRoles.includes(role)) {
      res.status(400).json({ error: 'Некорректная роль пользователя' });
      return;
    }

    // Запрещаем менять роль самому себе
    if (userId === targetUserId) {
      res.status(400).json({ error: 'Нельзя изменить свою собственную роль' });
      return;
    }

    // Обновляем роль пользователя
    const updatedUser = await userModel.updateUserRole(targetUserId, role);

    if (!updatedUser) {
      res.status(404).json({ error: 'Пользователь не найден' });
      return;
    }

    // Возвращаем обновленные данные пользователя без пароля
    const userResponse = omit(updatedUser, ['password']);

    res.status(200).json({
      message: 'Роль пользователя успешно обновлена',
      user: userResponse
    });
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
    res.status(500).json({ error: message });
  }
};

/**
 * Контроллер для получения списка всех мемориалов (только для модераторов и выше)
 */
export const getAllMemorials = async (req: Request, res: Response): Promise<void> => {
  try {
    const userId = (req as any).user?.id;
    const userRole = (req as any).user?.role;

    if (!userId) {
      res.status(401).json({ error: 'Пользователь не аутентифицирован' });
      return;
    }

    // Проверяем права модератора и выше
    if (userRole !== 'moderator' && userRole !== 'admin' && userRole !== 'super_admin') {
      res.status(403).json({ error: 'Недостаточно прав для выполнения операции' });
      return;
    }

    // Получаем все мемориалы
    const memorials = await memorialModel.getAllMemorials();
    const stats = await memorialModel.getMemorialStats();

    res.status(200).json({
      memorials,
      stats,
      total: memorials.length
    });
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
    res.status(500).json({ error: message });
  }
};

/**
 * Контроллер для обновления статуса мемориала (только для модераторов и выше)
 */
export const updateMemorialStatus = async (req: Request, res: Response): Promise<void> => {
  try {
    const userId = (req as any).user?.id;
    const userRole = (req as any).user?.role;
    const memorialId = parseInt(req.params.id);
    const { status } = req.body;

    if (!userId) {
      res.status(401).json({ error: 'Пользователь не аутентифицирован' });
      return;
    }

    // Проверяем права модератора и выше
    if (userRole !== 'moderator' && userRole !== 'admin' && userRole !== 'super_admin') {
      res.status(403).json({ error: 'Недостаточно прав для выполнения операции' });
      return;
    }

    // Валидация статуса
    if (!['pending', 'approved', 'rejected'].includes(status)) {
      res.status(400).json({ error: 'Некорректный статус мемориала' });
      return;
    }

    // Обновляем статус мемориала
    const updatedMemorial = await memorialModel.updateMemorialStatus(memorialId, status);

    if (!updatedMemorial) {
      res.status(404).json({ error: 'Мемориал не найден' });
      return;
    }

    res.status(200).json({
      message: 'Статус мемориала успешно обновлен',
      memorial: updatedMemorial
    });
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
    res.status(500).json({ error: message });
  }
};

/**
 * Контроллер для удаления мемориала (только для админов)
 */
export const deleteMemorial = async (req: Request, res: Response): Promise<void> => {
  try {
    const userId = (req as any).user?.id;
    const userRole = (req as any).user?.role;
    const memorialId = parseInt(req.params.id);

    if (!userId) {
      res.status(401).json({ error: 'Пользователь не аутентифицирован' });
      return;
    }

    // Проверяем права администратора
    if (!checkAdminRights(userRole)) {
      res.status(403).json({ error: 'Недостаточно прав для выполнения операции' });
      return;
    }

    // Удаляем мемориал
    const deleted = await memorialModel.deleteMemorial(memorialId);

    if (!deleted) {
      res.status(404).json({ error: 'Мемориал не найден' });
      return;
    }

    res.status(200).json({
      message: 'Мемориал успешно удален'
    });
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
    res.status(500).json({ error: message });
  }
};
