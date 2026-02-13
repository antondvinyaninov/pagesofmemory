/**
 * Интерфейс мемориала
 */
export interface Memorial {
    id: number;
    first_name: string;
    last_name: string;
    middle_name?: string;
    birth_date?: string;
    death_date?: string;
    biography?: string;
    photo_url?: string;
    created_by: number;
    status: 'pending' | 'approved' | 'rejected';
    created_at: string;
    updated_at: string;
    creator_name?: string;
    creator_email?: string;
}
/**
 * Модель для работы с мемориалами
 */
export declare class MemorialModel {
    private db;
    constructor();
    /**
     * Получение всех мемориалов с информацией о создателях
     */
    getAllMemorials(): Promise<Memorial[]>;
    /**
     * Получение мемориала по ID
     */
    getMemorialById(id: number): Promise<Memorial | null>;
    /**
     * Обновление статуса мемориала
     */
    updateMemorialStatus(id: number, status: 'pending' | 'approved' | 'rejected'): Promise<Memorial | null>;
    /**
     * Удаление мемориала
     */
    deleteMemorial(id: number): Promise<boolean>;
    /**
     * Получение статистики мемориалов
     */
    getMemorialStats(): Promise<{
        total: number;
        pending: number;
        approved: number;
        rejected: number;
    }>;
}
export declare const memorialModel: MemorialModel;
