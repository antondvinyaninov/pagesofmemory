"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.authenticateToken = void 0;
const jwt_1 = require("../utils/jwt");
const userModel_1 = require("../models/userModel");
/**
 * Middleware для проверки аутентификации
 */
const authenticateToken = async (req, res, next) => {
    try {
        const token = (0, jwt_1.extractTokenFromHeader)(req.headers.authorization);
        const payload = (0, jwt_1.verifyToken)(token);
        // Проверяем существование пользователя в базе данных
        const user = await userModel_1.userModel.findUserById(payload.userId);
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
    }
    catch (error) {
        const message = error instanceof Error ? error.message : 'Ошибка аутентификации';
        res.status(401).json({ error: message });
    }
};
exports.authenticateToken = authenticateToken;
//# sourceMappingURL=auth.js.map