"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.deleteMemorial = exports.updateMemorialStatus = exports.getAllMemorials = exports.updateUserRole = exports.getAllUsers = void 0;
const user_1 = require("../types/user");
const userModel_1 = require("../models/userModel");
const memorialModel_1 = require("../models/memorialModel");
const lodash_1 = require("lodash");
/**
 * Проверка прав администратора
 */
const checkAdminRights = (userRole) => {
    return userRole === 'admin' || userRole === 'super_admin';
};
/**
 * Контроллер для получения списка всех пользователей (только для админов)
 */
const getAllUsers = async (req, res) => {
    try {
        const userId = req.user?.id;
        const userRole = req.user?.role;
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
        const users = await userModel_1.userModel.getAllUsers();
        // Возвращаем данные пользователей без паролей
        const usersResponse = users.map(user => (0, lodash_1.omit)(user, ['password']));
        res.status(200).json({
            users: usersResponse,
            total: usersResponse.length
        });
    }
    catch (error) {
        const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
        res.status(500).json({ error: message });
    }
};
exports.getAllUsers = getAllUsers;
/**
 * Контроллер для обновления роли пользователя (только для админов)
 */
const updateUserRole = async (req, res) => {
    try {
        const userId = req.user?.id;
        const userRole = req.user?.role;
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
        if (!user_1.userRoles.includes(role)) {
            res.status(400).json({ error: 'Некорректная роль пользователя' });
            return;
        }
        // Запрещаем менять роль самому себе
        if (userId === targetUserId) {
            res.status(400).json({ error: 'Нельзя изменить свою собственную роль' });
            return;
        }
        // Обновляем роль пользователя
        const updatedUser = await userModel_1.userModel.updateUserRole(targetUserId, role);
        if (!updatedUser) {
            res.status(404).json({ error: 'Пользователь не найден' });
            return;
        }
        // Возвращаем обновленные данные пользователя без пароля
        const userResponse = (0, lodash_1.omit)(updatedUser, ['password']);
        res.status(200).json({
            message: 'Роль пользователя успешно обновлена',
            user: userResponse
        });
    }
    catch (error) {
        const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
        res.status(500).json({ error: message });
    }
};
exports.updateUserRole = updateUserRole;
/**
 * Контроллер для получения списка всех мемориалов (только для модераторов и выше)
 */
const getAllMemorials = async (req, res) => {
    try {
        const userId = req.user?.id;
        const userRole = req.user?.role;
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
        const memorials = await memorialModel_1.memorialModel.getAllMemorials();
        const stats = await memorialModel_1.memorialModel.getMemorialStats();
        res.status(200).json({
            memorials,
            stats,
            total: memorials.length
        });
    }
    catch (error) {
        const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
        res.status(500).json({ error: message });
    }
};
exports.getAllMemorials = getAllMemorials;
/**
 * Контроллер для обновления статуса мемориала (только для модераторов и выше)
 */
const updateMemorialStatus = async (req, res) => {
    try {
        const userId = req.user?.id;
        const userRole = req.user?.role;
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
        const updatedMemorial = await memorialModel_1.memorialModel.updateMemorialStatus(memorialId, status);
        if (!updatedMemorial) {
            res.status(404).json({ error: 'Мемориал не найден' });
            return;
        }
        res.status(200).json({
            message: 'Статус мемориала успешно обновлен',
            memorial: updatedMemorial
        });
    }
    catch (error) {
        const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
        res.status(500).json({ error: message });
    }
};
exports.updateMemorialStatus = updateMemorialStatus;
/**
 * Контроллер для удаления мемориала (только для админов)
 */
const deleteMemorial = async (req, res) => {
    try {
        const userId = req.user?.id;
        const userRole = req.user?.role;
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
        const deleted = await memorialModel_1.memorialModel.deleteMemorial(memorialId);
        if (!deleted) {
            res.status(404).json({ error: 'Мемориал не найден' });
            return;
        }
        res.status(200).json({
            message: 'Мемориал успешно удален'
        });
    }
    catch (error) {
        const message = error instanceof Error ? error.message : 'Внутренняя ошибка сервера';
        res.status(500).json({ error: message });
    }
};
exports.deleteMemorial = deleteMemorial;
//# sourceMappingURL=adminController.js.map