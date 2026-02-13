"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.memorialWithCreatorSchema = exports.memorialSchema = exports.updateMemorialSchema = exports.createMemorialSchema = void 0;
const zod_1 = require("zod");
/**
 * Схема для создания мемориала
 */
exports.createMemorialSchema = zod_1.z.object({
    firstName: zod_1.z.string().min(2, 'Имя должно содержать минимум 2 символа'),
    lastName: zod_1.z.string().min(2, 'Фамилия должна содержать минимум 2 символа'),
    middleName: zod_1.z.string().optional(),
    birthDate: zod_1.z.string().regex(/^\d{4}-\d{2}-\d{2}$/, 'Неверный формат даты (YYYY-MM-DD)').optional(),
    deathDate: zod_1.z.string().regex(/^\d{4}-\d{2}-\d{2}$/, 'Неверный формат даты (YYYY-MM-DD)').optional(),
    biography: zod_1.z.string().optional(),
    photoUrl: zod_1.z.string().url('Неверный формат URL').optional()
});
/**
 * Схема для обновления мемориала
 */
exports.updateMemorialSchema = exports.createMemorialSchema.partial();
/**
 * Схема мемориала в базе данных
 */
exports.memorialSchema = zod_1.z.object({
    id: zod_1.z.number(),
    firstName: zod_1.z.string(),
    lastName: zod_1.z.string(),
    middleName: zod_1.z.string().nullable(),
    birthDate: zod_1.z.string().nullable(),
    deathDate: zod_1.z.string().nullable(),
    biography: zod_1.z.string().nullable(),
    photoUrl: zod_1.z.string().nullable(),
    createdBy: zod_1.z.number(),
    createdAt: zod_1.z.string(),
    updatedAt: zod_1.z.string()
});
/**
 * Схема мемориала с информацией о создателе
 */
exports.memorialWithCreatorSchema = exports.memorialSchema.extend({
    creator: zod_1.z.object({
        id: zod_1.z.number(),
        name: zod_1.z.string(),
        email: zod_1.z.string()
    })
});
//# sourceMappingURL=memorial.js.map