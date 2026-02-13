"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.extractTokenFromHeader = exports.verifyToken = exports.generateToken = void 0;
const jsonwebtoken_1 = __importDefault(require("jsonwebtoken"));
const config_1 = require("../config");
/**
 * Генерация JWT токена
 */
const generateToken = (payload) => {
    return jsonwebtoken_1.default.sign(payload, config_1.config.jwtSecret, {
        expiresIn: config_1.config.jwtExpiresIn
    });
};
exports.generateToken = generateToken;
/**
 * Проверка JWT токена
 */
const verifyToken = (token) => {
    try {
        return jsonwebtoken_1.default.verify(token, config_1.config.jwtSecret);
    }
    catch (error) {
        throw new Error('Неверный токен');
    }
};
exports.verifyToken = verifyToken;
/**
 * Извлечение токена из заголовка Authorization
 */
const extractTokenFromHeader = (authHeader) => {
    if (!authHeader) {
        throw new Error('Отсутствует заголовок авторизации');
    }
    const parts = authHeader.split(' ');
    if (parts.length !== 2 || parts[0] !== 'Bearer') {
        throw new Error('Неверный формат заголовка авторизации');
    }
    return parts[1];
};
exports.extractTokenFromHeader = extractTokenFromHeader;
//# sourceMappingURL=jwt.js.map