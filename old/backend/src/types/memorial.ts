import { z } from 'zod';

/**
 * Схема для создания мемориала
 */
export const createMemorialSchema = z.object({
  firstName: z.string().min(2, 'Имя должно содержать минимум 2 символа'),
  lastName: z.string().min(2, 'Фамилия должна содержать минимум 2 символа'),
  middleName: z.string().optional(),
  birthDate: z.string().regex(/^\d{4}-\d{2}-\d{2}$/, 'Неверный формат даты (YYYY-MM-DD)').optional(),
  deathDate: z.string().regex(/^\d{4}-\d{2}-\d{2}$/, 'Неверный формат даты (YYYY-MM-DD)').optional(),
  biography: z.string().optional(),
  photoUrl: z.string().url('Неверный формат URL').optional()
});

/**
 * Схема для обновления мемориала
 */
export const updateMemorialSchema = createMemorialSchema.partial();

/**
 * Схема мемориала в базе данных
 */
export const memorialSchema = z.object({
  id: z.number(),
  firstName: z.string(),
  lastName: z.string(),
  middleName: z.string().nullable(),
  birthDate: z.string().nullable(),
  deathDate: z.string().nullable(),
  biography: z.string().nullable(),
  photoUrl: z.string().nullable(),
  createdBy: z.number(),
  createdAt: z.string(),
  updatedAt: z.string()
});

/**
 * Схема мемориала с информацией о создателе
 */
export const memorialWithCreatorSchema = memorialSchema.extend({
  creator: z.object({
    id: z.number(),
    name: z.string(),
    email: z.string()
  })
});

/**
 * Типы, выведенные из Zod схем
 */
export type CreateMemorialRequest = z.infer<typeof createMemorialSchema>;
export type UpdateMemorialRequest = z.infer<typeof updateMemorialSchema>;
export type Memorial = z.infer<typeof memorialSchema>;
export type MemorialWithCreator = z.infer<typeof memorialWithCreatorSchema>;
