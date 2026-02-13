import type { Request, Response } from 'express';
import { registerUserSchema, loginUserSchema, updateUserProfileSchema } from '../types/user';
import { userModel } from '../models/userModel';
import { generateToken } from '../utils/jwt';
import { omit } from 'lodash';

/**
 * Контроллер для регистрации пользователя
 */
export const registerUser = async (req: Request, res: Response): Promise<void> => {
  try {
    // Валидация входных данных
    const validatedData = registerUserSchema.parse(req.body);

    // Проверяем, не существует ли пользователь с таким email
    const existingUser = await userModel.findUserByEmail(validatedData.email);
    if (existingUser) {
      res.status(400).json({ error: 'Пользователь с таким email уже существует' });
      return;
    }

    // Создаем нового пользователя
    const newUser = await userModel.createUser(validatedData);

    // Генерируем JWT токен
    const token = generateToken({
      userId: newUser.id,
      email: newUser.email
    });

    // Возвращаем данные пользователя без пароля
    const userResponse = omit(newUser, ['password']);

    res.status(201).json({
      message: 'Пользователь успешно зарегистрирован',
      user: userResponse,
      token
    });
  } catch (error) {
    if (error instanceof Error) {
      // Ошибки валидации Zod
      if (error.name === 'ZodError') {
        res.status(400).json({ error: 'Неверные данные', details: error.message });
        return;
      }

      res.status(400).json({ error: error.message });
    } else {
      res.status(500).json({ error: 'Внутренняя ошибка сервера' });
    }
  }
};

/**
 * Контроллер для авторизации пользователя
 */
export const loginUser = async (req: Request, res: Response): Promise<void> => {
  try {
    // Валидация входных данных
    const validatedData = loginUserSchema.parse(req.body);

    // Ищем пользователя по email
    const user = await userModel.findUserByEmail(validatedData.email);
    if (!user) {
      res.status(401).json({ error: 'Неверный email или пароль' });
      return;
    }

    // Проверяем пароль
    const isPasswordValid = await userModel.verifyPassword(validatedData.password, user.password);
    if (!isPasswordValid) {
      res.status(401).json({ error: 'Неверный email или пароль' });
      return;
    }

    // Генерируем JWT токен
    const token = generateToken({
      userId: user.id,
      email: user.email
    });

    // Возвращаем данные пользователя без пароля
    const userResponse = omit(user, ['password']);

    res.status(200).json({
      message: 'Авторизация успешна',
      user: userResponse,
      token
    });
  } catch (error) {
    if (error instanceof Error) {
      // Ошибки валидации Zod
      if (error.name === 'ZodError') {
        res.status(400).json({ error: 'Неверные данные', details: error.message });
        return;
      }

      res.status(400).json({ error: error.message });
    } else {
      res.status(500).json({ error: 'Внутренняя ошибка сервера' });
    }
  }
};

/**
 * Контроллер для получения профиля пользователя
 */
export const getUserProfile = async (req: Request, res: Response): Promise<void> => {
  try {
    // user добавляется в middleware аутентификации
    const userId = (req as any).user?.id;

    if (!userId) {
      res.status(401).json({ error: 'Пользователь не аутентифицирован' });
      return;
    }

    const user = await userModel.findUserById(userId);
    if (!user) {
      res.status(404).json({ error: 'Пользователь не найден' });
      return;
    }

    // Возвращаем данные пользователя без пароля
    const userResponse = omit(user, ['password']);

    res.status(200).json({
      user: userResponse
    });
  } catch (error) {
    const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
    res.status(500).json({ error: message });
  }
};

/**
 * Контроллер для обновления профиля пользователя
 */
export const updateUserProfile = async (req: Request, res: Response): Promise<void> => {
  try {
    // user добавляется в middleware аутентификации
    const userId = (req as any).user?.id;

    if (!userId) {
      res.status(401).json({ error: 'Пользователь не аутентифицирован' });
      return;
    }

    // Валидация входных данных
    const validatedData = updateUserProfileSchema.parse(req.body);

    // Проверяем, не занят ли email другим пользователем
    if (validatedData.email) {
      const existingUser = await userModel.findUserByEmail(validatedData.email);
      if (existingUser && existingUser.id !== userId) {
        res.status(400).json({ error: 'Пользователь с таким email уже существует' });
        return;
      }
    }

    // Обновляем профиль пользователя
    const updatedUser = await userModel.updateUserProfile(userId, validatedData);

    if (!updatedUser) {
      res.status(404).json({ error: 'Пользователь не найден' });
      return;
    }

    // Возвращаем обновленные данные пользователя без пароля
    const userResponse = omit(updatedUser, ['password']);

    res.status(200).json({
      message: 'Профиль успешно обновлен',
      user: userResponse
    });
  } catch (error) {
    if (error instanceof Error) {
      // Ошибки валидации Zod
      if (error.name === 'ZodError') {
        res.status(400).json({ error: 'Неверные данные', details: error.message });
        return;
      }

      res.status(400).json({ error: error.message });
    } else {
      res.status(500).json({ error: 'Внутренняя ошибка сервера' });
    }
  }
};
