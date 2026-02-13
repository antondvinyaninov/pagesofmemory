import { z } from 'zod';
/**
 * Схема для создания мемориала
 */
export declare const createMemorialSchema: z.ZodObject<{
    firstName: z.ZodString;
    lastName: z.ZodString;
    middleName: z.ZodOptional<z.ZodString>;
    birthDate: z.ZodOptional<z.ZodString>;
    deathDate: z.ZodOptional<z.ZodString>;
    biography: z.ZodOptional<z.ZodString>;
    photoUrl: z.ZodOptional<z.ZodString>;
}, "strip", z.ZodTypeAny, {
    firstName: string;
    lastName: string;
    middleName?: string | undefined;
    birthDate?: string | undefined;
    deathDate?: string | undefined;
    biography?: string | undefined;
    photoUrl?: string | undefined;
}, {
    firstName: string;
    lastName: string;
    middleName?: string | undefined;
    birthDate?: string | undefined;
    deathDate?: string | undefined;
    biography?: string | undefined;
    photoUrl?: string | undefined;
}>;
/**
 * Схема для обновления мемориала
 */
export declare const updateMemorialSchema: z.ZodObject<{
    firstName: z.ZodOptional<z.ZodString>;
    lastName: z.ZodOptional<z.ZodString>;
    middleName: z.ZodOptional<z.ZodOptional<z.ZodString>>;
    birthDate: z.ZodOptional<z.ZodOptional<z.ZodString>>;
    deathDate: z.ZodOptional<z.ZodOptional<z.ZodString>>;
    biography: z.ZodOptional<z.ZodOptional<z.ZodString>>;
    photoUrl: z.ZodOptional<z.ZodOptional<z.ZodString>>;
}, "strip", z.ZodTypeAny, {
    firstName?: string | undefined;
    lastName?: string | undefined;
    middleName?: string | undefined;
    birthDate?: string | undefined;
    deathDate?: string | undefined;
    biography?: string | undefined;
    photoUrl?: string | undefined;
}, {
    firstName?: string | undefined;
    lastName?: string | undefined;
    middleName?: string | undefined;
    birthDate?: string | undefined;
    deathDate?: string | undefined;
    biography?: string | undefined;
    photoUrl?: string | undefined;
}>;
/**
 * Схема мемориала в базе данных
 */
export declare const memorialSchema: z.ZodObject<{
    id: z.ZodNumber;
    firstName: z.ZodString;
    lastName: z.ZodString;
    middleName: z.ZodNullable<z.ZodString>;
    birthDate: z.ZodNullable<z.ZodString>;
    deathDate: z.ZodNullable<z.ZodString>;
    biography: z.ZodNullable<z.ZodString>;
    photoUrl: z.ZodNullable<z.ZodString>;
    createdBy: z.ZodNumber;
    createdAt: z.ZodString;
    updatedAt: z.ZodString;
}, "strip", z.ZodTypeAny, {
    id: number;
    createdAt: string;
    updatedAt: string;
    firstName: string;
    lastName: string;
    middleName: string | null;
    birthDate: string | null;
    deathDate: string | null;
    biography: string | null;
    photoUrl: string | null;
    createdBy: number;
}, {
    id: number;
    createdAt: string;
    updatedAt: string;
    firstName: string;
    lastName: string;
    middleName: string | null;
    birthDate: string | null;
    deathDate: string | null;
    biography: string | null;
    photoUrl: string | null;
    createdBy: number;
}>;
/**
 * Схема мемориала с информацией о создателе
 */
export declare const memorialWithCreatorSchema: z.ZodObject<{
    id: z.ZodNumber;
    firstName: z.ZodString;
    lastName: z.ZodString;
    middleName: z.ZodNullable<z.ZodString>;
    birthDate: z.ZodNullable<z.ZodString>;
    deathDate: z.ZodNullable<z.ZodString>;
    biography: z.ZodNullable<z.ZodString>;
    photoUrl: z.ZodNullable<z.ZodString>;
    createdBy: z.ZodNumber;
    createdAt: z.ZodString;
    updatedAt: z.ZodString;
} & {
    creator: z.ZodObject<{
        id: z.ZodNumber;
        name: z.ZodString;
        email: z.ZodString;
    }, "strip", z.ZodTypeAny, {
        email: string;
        name: string;
        id: number;
    }, {
        email: string;
        name: string;
        id: number;
    }>;
}, "strip", z.ZodTypeAny, {
    id: number;
    createdAt: string;
    updatedAt: string;
    firstName: string;
    lastName: string;
    middleName: string | null;
    birthDate: string | null;
    deathDate: string | null;
    biography: string | null;
    photoUrl: string | null;
    createdBy: number;
    creator: {
        email: string;
        name: string;
        id: number;
    };
}, {
    id: number;
    createdAt: string;
    updatedAt: string;
    firstName: string;
    lastName: string;
    middleName: string | null;
    birthDate: string | null;
    deathDate: string | null;
    biography: string | null;
    photoUrl: string | null;
    createdBy: number;
    creator: {
        email: string;
        name: string;
        id: number;
    };
}>;
/**
 * Типы, выведенные из Zod схем
 */
export type CreateMemorialRequest = z.infer<typeof createMemorialSchema>;
export type UpdateMemorialRequest = z.infer<typeof updateMemorialSchema>;
export type Memorial = z.infer<typeof memorialSchema>;
export type MemorialWithCreator = z.infer<typeof memorialWithCreatorSchema>;
