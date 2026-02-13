"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.updateUserProfile = exports.getUserProfile = exports.loginUser = exports.registerUser = void 0;
const user_1 = require("../types/user");
const userModel_1 = require("../models/userModel");
const jwt_1 = require("../utils/jwt");
const lodash_1 = require("lodash");
/**
 * Контроллер для регистрации пользователя
 */
const registerUser = async (req, res) => {
    try {
        // Валидация входных данных
        const validatedData = user_1.registerUserSchema.parse(req.body);
        // Проверяем, не существует ли пользователь с таким email
        const existingUser = await userModel_1.userModel.findUserByEmail(validatedData.email);
        if (existingUser) {
            res.status(400).json({ error: 'Пользователь с таким email уже существует' });
            return;
        }
        // Создаем нового пользователя
        const newUser = await userModel_1.userModel.createUser(validatedData);
        // Генерируем JWT токен
        const token = (0, jwt_1.generateToken)({
            userId: newUser.id,
            email: newUser.email
        });
        // Возвращаем данные пользователя без пароля
        const userResponse = (0, lodash_1.omit)(newUser, ['password']);
        res.status(201).json({
            message: 'Пользователь успешно зарегистрирован',
            user: userResponse,
            token
        });
    }
    catch (error) {
        if (error instanceof Error) {
            // Ошибки валидации Zod
            if (error.name === 'ZodError') {
                res.status(400).json({ error: 'Неверные данные', details: error.message });
                return;
            }
            res.status(400).json({ error: error.message });
        }
        else {
            res.status(500).json({ error: 'Внутренняя ошибка сервера' });
        }
    }
};
exports.registerUser = registerUser;
/**
 * Контроллер для авторизации пользователя
 */
const loginUser = async (req, res) => {
    try {
        // Валидация входных данных
        const validatedData = user_1.loginUserSchema.parse(req.body);
        // Ищем пользователя по email
        const user = await userModel_1.userModel.findUserByEmail(validatedData.email);
        if (!user) {
            res.status(401).json({ error: 'Неверный email или пароль' });
            return;
        }
        // Проверяем пароль
        const isPasswordValid = await userModel_1.userModel.verifyPassword(validatedData.password, user.password);
        if (!isPasswordValid) {
            res.status(401).json({ error: 'Неверный email или пароль' });
            return;
        }
        // Генерируем JWT токен
        const token = (0, jwt_1.generateToken)({
            userId: user.id,
            email: user.email
        });
        // Возвращаем данные пользователя без пароля
        const userResponse = (0, lodash_1.omit)(user, ['password']);
        res.status(200).json({
            message: 'Авторизация успешна',
            user: userResponse,
            token
        });
    }
    catch (error) {
        if (error instanceof Error) {
            // Ошибки валидации Zod
            if (error.name === 'ZodError') {
                res.status(400).json({ error: 'Неверные данные', details: error.message });
                return;
            }
            res.status(400).json({ error: error.message });
        }
        else {
            res.status(500).json({ error: 'Внутренняя ошибка сервера' });
        }
    }
};
exports.loginUser = loginUser;
/**
 * Контроллер для получения профиля пользователя
 */
const getUserProfile = async (req, res) => {
    try {
        // user добавляется в middleware аутентификации
        const userId = req.user?.id;
        if (!userId) {
            res.status(401).json({ error: 'Пользователь не аутентифицирован' });
            return;
        }
        const user = await userModel_1.userModel.findUserById(userId);
        if (!user) {
            res.status(404).json({ error: 'Пользователь не найден' });
            return;
        }
        // Возвращаем данные пользователя без пароля
        const userResponse = (0, lodash_1.omit)(user, ['password']);
        res.status(200).json({
            user: userResponse
        });
    }
    catch (error) {
        const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
        res.status(500).json({ error: message });
    }
};
exports.getUserProfile = getUserProfile;
/**
 * Контроллер для обновления профиля пользователя
 */
const updateUserProfile = async (req, res) => {
    try {
        // user добавляется в middleware аутентификации
        const userId = req.user?.id;
        if (!userId) {
            res.status(401).json({ error: 'Пользователь не аутентифицирован' });
            return;
        }
        // Валидация входных данных
        const validatedData = user_1.updateUserProfileSchema.parse(req.body);
        // Проверяем, не занят ли email другим пользователем
        if (validatedData.email) {
            const existingUser = await userModel_1.userModel.findUserByEmail(validatedData.email);
            if (existingUser && existingUser.id !== userId) {
                res.status(400).json({ error: 'Пользователь с таким email уже существует' });
                return;
            }
        }
        // Обновляем профиль пользователя
        const updatedUser = await userModel_1.userModel.updateUserProfile(userId, validatedData);
        if (!updatedUser) {
            res.status(404).json({ error: 'Пользователь не найден' });
            return;
        }
        // Возвращаем обновленные данные пользователя без пароля
        const userResponse = (0, lodash_1.omit)(updatedUser, ['password']);
        res.status(200).json({
            message: 'Профиль успешно обновлен',
            user: userResponse
        });
    }
    catch (error) {
        if (error instanceof Error) {
            // Ошибки валидации Zod
            if (error.name === 'ZodError') {
                res.status(400).json({ error: 'Неверные данные', details: error.message });
                return;
            }
            res.status(400).json({ error: error.message });
        }
        else {
            res.status(500).json({ error: 'Внутренняя ошибка сервера' });
        }
    }
};
exports.updateUserProfile = updateUserProfile;
//# sourceMappingURL=authController.js.map