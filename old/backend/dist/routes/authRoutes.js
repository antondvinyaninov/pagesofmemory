"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = require("express");
const authController_1 = require("../controllers/authController");
const adminController_1 = require("../controllers/adminController");
const auth_1 = require("../middleware/auth");
/**
 * Маршруты для аутентификации
 */
const router = (0, express_1.Router)();
/**
 * POST /api/auth/register - Регистрация пользователя
 */
router.post('/register', authController_1.registerUser);
/**
 * POST /api/auth/login - Авторизация пользователя
 */
router.post('/login', authController_1.loginUser);
/**
 * GET /api/auth/profile - Получение профиля пользователя (требует аутентификации)
 */
router.get('/profile', auth_1.authenticateToken, authController_1.getUserProfile);
/**
 * PUT /api/auth/profile - Обновление профиля пользователя (требует аутентификации)
 */
router.put('/profile', auth_1.authenticateToken, authController_1.updateUserProfile);
/**
 * GET /api/auth/users - Получение списка всех пользователей (только для админов)
 */
router.get('/users', auth_1.authenticateToken, adminController_1.getAllUsers);
/**
 * PUT /api/auth/users/:id/role - Обновление роли пользователя (только для админов)
 */
router.put('/users/:id/role', auth_1.authenticateToken, adminController_1.updateUserRole);
/**
 * GET /api/auth/memorials - Получение списка всех мемориалов (только для модераторов и выше)
 */
router.get('/memorials', auth_1.authenticateToken, adminController_1.getAllMemorials);
/**
 * PUT /api/auth/memorials/:id/status - Обновление статуса мемориала (только для модераторов и выше)
 */
router.put('/memorials/:id/status', auth_1.authenticateToken, adminController_1.updateMemorialStatus);
/**
 * DELETE /api/auth/memorials/:id - Удаление мемориала (только для админов)
 */
router.delete('/memorials/:id', auth_1.authenticateToken, adminController_1.deleteMemorial);
exports.default = router;
//# sourceMappingURL=authRoutes.js.map