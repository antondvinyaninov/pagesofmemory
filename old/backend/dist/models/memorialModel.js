"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.memorialModel = exports.MemorialModel = void 0;
const database_1 = require("./database");
/**
 * Модель для работы с мемориалами
 */
class MemorialModel {
    constructor() {
        this.db = database_1.database.getDatabase();
    }
    /**
     * Получение всех мемориалов с информацией о создателях
     */
    async getAllMemorials() {
        return new Promise((resolve, reject) => {
            const query = `
        SELECT 
          m.id, m.first_name, m.last_name, m.middle_name,
          m.birth_date, m.death_date, m.biography, m.photo_url,
          m.created_by, m.status, m.created_at, m.updated_at,
          u.name as creator_name, u.email as creator_email
        FROM memorials m
        LEFT JOIN users u ON m.created_by = u.id
        ORDER BY m.created_at DESC
      `;
            this.db.all(query, [], (err, rows) => {
                if (err) {
                    reject(new Error('Ошибка получения списка мемориалов'));
                    return;
                }
                resolve(rows || []);
            });
        });
    }
    /**
     * Получение мемориала по ID
     */
    async getMemorialById(id) {
        return new Promise((resolve, reject) => {
            const query = `
        SELECT 
          m.id, m.first_name, m.last_name, m.middle_name,
          m.birth_date, m.death_date, m.biography, m.photo_url,
          m.created_by, m.status, m.created_at, m.updated_at,
          u.name as creator_name, u.email as creator_email
        FROM memorials m
        LEFT JOIN users u ON m.created_by = u.id
        WHERE m.id = ?
      `;
            this.db.get(query, [id], (err, row) => {
                if (err) {
                    reject(new Error('Ошибка получения мемориала'));
                    return;
                }
                resolve(row || null);
            });
        });
    }
    /**
     * Обновление статуса мемориала
     */
    async updateMemorialStatus(id, status) {
        return new Promise((resolve, reject) => {
            const query = `
        UPDATE memorials 
        SET status = ?, updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
      `;
            this.db.run(query, [status, id], function (err) {
                if (err) {
                    reject(new Error('Ошибка обновления статуса мемориала'));
                    return;
                }
                if (this.changes === 0) {
                    reject(new Error('Мемориал не найден'));
                    return;
                }
                // Получаем обновленный мемориал
                const getMemorialQuery = `
          SELECT 
            m.id, m.first_name, m.last_name, m.middle_name,
            m.birth_date, m.death_date, m.biography, m.photo_url,
            m.created_by, m.status, m.created_at, m.updated_at,
            u.name as creator_name, u.email as creator_email
          FROM memorials m
          LEFT JOIN users u ON m.created_by = u.id
          WHERE m.id = ?
        `;
                database_1.database.getDatabase().get(getMemorialQuery, [id], (err, row) => {
                    if (err) {
                        reject(new Error('Ошибка получения обновленного мемориала'));
                        return;
                    }
                    resolve(row || null);
                });
            });
        });
    }
    /**
     * Удаление мемориала
     */
    async deleteMemorial(id) {
        return new Promise((resolve, reject) => {
            const query = `DELETE FROM memorials WHERE id = ?`;
            this.db.run(query, [id], function (err) {
                if (err) {
                    reject(new Error('Ошибка удаления мемориала'));
                    return;
                }
                resolve(this.changes > 0);
            });
        });
    }
    /**
     * Получение статистики мемориалов
     */
    async getMemorialStats() {
        return new Promise((resolve, reject) => {
            const query = `
        SELECT 
          COUNT(*) as total,
          SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
          SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
          SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
        FROM memorials
      `;
            this.db.get(query, [], (err, row) => {
                if (err) {
                    reject(new Error('Ошибка получения статистики мемориалов'));
                    return;
                }
                resolve({
                    total: row.total || 0,
                    pending: row.pending || 0,
                    approved: row.approved || 0,
                    rejected: row.rejected || 0
                });
            });
        });
    }
}
exports.MemorialModel = MemorialModel;
exports.memorialModel = new MemorialModel();
//# sourceMappingURL=memorialModel.js.map