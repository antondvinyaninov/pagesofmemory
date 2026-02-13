"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.database = exports.Database = void 0;
const sqlite3_1 = __importDefault(require("sqlite3"));
const config_1 = require("../config");
/**
 * Класс для работы с SQLite базой данных
 */
class Database {
    constructor() {
        this.db = new sqlite3_1.default.Database(config_1.config.dbPath, (err) => {
            if (err) {
                console.error('Ошибка подключения к базе данных:', err.message);
            }
            else {
                console.log('Подключение к SQLite базе данных установлено');
                this.initializeTables();
            }
        });
    }
    /**
     * Получить экземпляр базы данных
     */
    getDatabase() {
        return this.db;
    }
    /**
     * Инициализация таблиц
     */
    initializeTables() {
        this.createUsersTable();
        this.createMemorialsTable();
        this.migrateUserProfileFields();
    }
    /**
     * Создание таблицы пользователей
     */
    createUsersTable() {
        const createUsersTable = `
      CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        name TEXT NOT NULL,
        avatar_url TEXT DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
      )
    `;
        this.db.run(createUsersTable, (err) => {
            if (err) {
                console.error('Ошибка создания таблицы users:', err.message);
            }
            else {
                console.log('Таблица users создана или уже существует');
            }
        });
    }
    /**
     * Создание таблицы мемориалов
     */
    createMemorialsTable() {
        const createMemorialsTable = `
      CREATE TABLE IF NOT EXISTS memorials (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        first_name TEXT NOT NULL,
        last_name TEXT NOT NULL,
        middle_name TEXT,
        birth_date DATE,
        death_date DATE,
        biography TEXT,
        photo_url TEXT,
        created_by INTEGER NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id)
      )
    `;
        this.db.run(createMemorialsTable, (err) => {
            if (err) {
                console.error('Ошибка создания таблицы memorials:', err.message);
            }
            else {
                console.log('Таблица memorials создана или уже существует');
            }
        });
    }
    /**
     * Миграция для добавления новых полей профиля пользователя
     */
    migrateUserProfileFields() {
        // Получаем все колонки таблицы users
        this.db.all("PRAGMA table_info(users)", (err, columns) => {
            if (err) {
                console.error('Ошибка получения колонок таблицы users:', err.message);
                return;
            }
            const columnNames = columns.map(col => col.name);
            // Добавляем недостающие колонки
            if (!columnNames.includes('bio')) {
                this.db.run("ALTER TABLE users ADD COLUMN bio TEXT DEFAULT NULL", (err) => {
                    if (err && !err.message.includes('duplicate column')) {
                        console.error('Ошибка добавления колонки bio:', err.message);
                    }
                    else {
                        console.log('Колонка bio добавлена в таблицу users');
                    }
                });
            }
            if (!columnNames.includes('location')) {
                this.db.run("ALTER TABLE users ADD COLUMN location TEXT DEFAULT NULL", (err) => {
                    if (err && !err.message.includes('duplicate column')) {
                        console.error('Ошибка добавления колонки location:', err.message);
                    }
                    else {
                        console.log('Колонка location добавлена в таблицу users');
                    }
                });
            }
        });
    }
    /**
     * Закрытие соединения с базой данных
     */
    close() {
        this.db.close((err) => {
            if (err) {
                console.error('Ошибка закрытия базы данных:', err.message);
            }
            else {
                console.log('Соединение с базой данных закрыто');
            }
        });
    }
}
exports.Database = Database;
exports.database = new Database();
//# sourceMappingURL=database.js.map