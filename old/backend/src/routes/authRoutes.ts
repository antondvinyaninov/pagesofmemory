import { Router } from 'express';
import { registerUser, loginUser, getUserProfile, updateUserProfile } from '../controllers/authController';
import { getAllUsers, updateUserRole, getAllMemorials, updateMemorialStatus, deleteMemorial } from '../controllers/adminController';
import { authenticateToken } from '../middleware/auth';

/**
 * Маршруты для аутентификации
 */
const router = Router();

/**
 * POST /api/auth/register - Регистрация пользователя
 */
router.post('/register', registerUser);

/**
 * POST /api/auth/login - Авторизация пользователя
 */
router.post('/login', loginUser);

/**
 * GET /api/auth/profile - Получение профиля пользователя (требует аутентификации)
 */
router.get('/profile', authenticateToken, getUserProfile);

/**
 * PUT /api/auth/profile - Обновление профиля пользователя (требует аутентификации)
 */
router.put('/profile', authenticateToken, updateUserProfile);

/**
 * GET /api/auth/users - Получение списка всех пользователей (только для админов)
 */
router.get('/users', authenticateToken, getAllUsers);

/**
 * PUT /api/auth/users/:id/role - Обновление роли пользователя (только для админов)
 */
router.put('/users/:id/role', authenticateToken, updateUserRole);

/**
 * GET /api/auth/memorials - Получение списка всех мемориалов (только для модераторов и выше)
 */
router.get('/memorials', authenticateToken, getAllMemorials);

/**
 * PUT /api/auth/memorials/:id/status - Обновление статуса мемориала (только для модераторов и выше)
 */
router.put('/memorials/:id/status', authenticateToken, updateMemorialStatus);

/**
 * DELETE /api/auth/memorials/:id - Удаление мемориала (только для админов)
 */
router.delete('/memorials/:id', authenticateToken, deleteMemorial);

export default router;
