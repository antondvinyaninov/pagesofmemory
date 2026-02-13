import sqlite3 from 'sqlite3';
/**
 * Класс для работы с SQLite базой данных
 */
export declare class Database {
    private db;
    constructor();
    /**
     * Получить экземпляр базы данных
     */
    getDatabase(): sqlite3.Database;
    /**
     * Инициализация таблиц
     */
    private initializeTables;
    /**
     * Создание таблицы пользователей
     */
    private createUsersTable;
    /**
     * Создание таблицы мемориалов
     */
    private createMemorialsTable;
    /**
     * Миграция для добавления новых полей профиля пользователя
     */
    private migrateUserProfileFields;
    /**
     * Закрытие соединения с базой данных
     */
    close(): void;
}
export declare const database: Database;
