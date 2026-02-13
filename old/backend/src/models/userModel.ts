import type { Database } from 'sqlite3';
import bcrypt from 'bcryptjs';
import { database } from './database';
import type { User, RegisterUserRequest, UpdateUserProfileRequest } from '../types/user';

/**
 * Модель для работы с пользователями
 */
export class UserModel {
  private db: Database;

  constructor() {
    this.db = database.getDatabase();
  }

  /**
   * Создание нового пользователя
   */
  public async createUser(userData: RegisterUserRequest): Promise<User> {
    return new Promise((resolve, reject) => {
      const { email, password, name } = userData;

      // Хешируем пароль
      bcrypt.hash(password, 10, (err, hashedPassword) => {
        if (err) {
          reject(new Error('Ошибка хеширования пароля'));
          return;
        }

        const query = `
          INSERT INTO users (email, password, name)
          VALUES (?, ?, ?)
        `;

        this.db.run(query, [email, hashedPassword, name], function(err) {
          if (err) {
            if (err.message.includes('UNIQUE constraint failed')) {
              reject(new Error('Пользователь с таким email уже существует'));
            } else {
              reject(new Error('Ошибка создания пользователя'));
            }
            return;
          }

          // Получаем созданного пользователя
          const getUserQuery = `
            SELECT id, email, password, name, avatar_url as avatar, bio, location, role,
                   created_at as createdAt, 
                   updated_at as updatedAt
            FROM users WHERE id = ?
          `;

          database.getDatabase().get(getUserQuery, [this.lastID], (err, row) => {
            if (err) {
              reject(new Error('Ошибка получения созданного пользователя'));
              return;
            }

            resolve(row as User);
          });
        });
      });
    });
  }

  /**
   * Поиск пользователя по email
   */
  public async findUserByEmail(email: string): Promise<User | null> {
    return new Promise((resolve, reject) => {
      const query = `
        SELECT id, email, password, name, avatar_url as avatar, bio, location, role,
               created_at as createdAt, 
               updated_at as updatedAt
        FROM users WHERE email = ?
      `;

      this.db.get(query, [email], (err, row) => {
        if (err) {
          reject(new Error('Ошибка поиска пользователя'));
          return;
        }

        resolve(row as User || null);
      });
    });
  }

  /**
   * Поиск пользователя по ID
   */
  public async findUserById(id: number): Promise<User | null> {
    return new Promise((resolve, reject) => {
      const query = `
        SELECT id, email, password, name, avatar_url as avatar, bio, location, role,
               created_at as createdAt, 
               updated_at as updatedAt
        FROM users WHERE id = ?
      `;

      this.db.get(query, [id], (err, row) => {
        if (err) {
          reject(new Error('Ошибка поиска пользователя'));
          return;
        }

        resolve(row as User || null);
      });
    });
  }

  /**
   * Проверка пароля
   */
  public async verifyPassword(plainPassword: string, hashedPassword: string): Promise<boolean> {
    return bcrypt.compare(plainPassword, hashedPassword);
  }

  /**
   * Обновление профиля пользователя
   */
  public async updateUserProfile(userId: number, updateData: UpdateUserProfileRequest): Promise<User | null> {
    return new Promise((resolve, reject) => {
      // Подготавливаем поля для обновления
      const fields: string[] = [];
      const values: any[] = [];

      if (updateData.name !== undefined) {
        fields.push('name = ?');
        values.push(updateData.name);
      }

      if (updateData.email !== undefined) {
        fields.push('email = ?');
        values.push(updateData.email);
      }

      if (updateData.avatar !== undefined) {
        fields.push('avatar_url = ?');
        values.push(updateData.avatar);
      }

      if (updateData.bio !== undefined) {
        fields.push('bio = ?');
        values.push(updateData.bio);
      }

      if (updateData.location !== undefined) {
        fields.push('location = ?');
        values.push(updateData.location);
      }

      // Добавляем поле updated_at
      fields.push('updated_at = CURRENT_TIMESTAMP');

      if (fields.length === 1) { // Только updated_at, значит нечего обновлять
        // Просто возвращаем текущего пользователя
        this.findUserById(userId).then(resolve).catch(reject);
        return;
      }

      values.push(userId);

      const query = `
        UPDATE users 
        SET ${fields.join(', ')}
        WHERE id = ?
      `;

      this.db.run(query, values, function(err) {
        if (err) {
          if (err.message.includes('UNIQUE constraint failed')) {
            reject(new Error('Пользователь с таким email уже существует'));
          } else {
            reject(new Error('Ошибка обновления профиля пользователя'));
          }
          return;
        }

        if (this.changes === 0) {
          reject(new Error('Пользователь не найден'));
          return;
        }

        // Получаем обновленного пользователя
        const getUserQuery = `
          SELECT id, email, password, name, avatar_url as avatar, bio, location, role,
                 created_at as createdAt, 
                 updated_at as updatedAt
          FROM users WHERE id = ?
        `;

        database.getDatabase().get(getUserQuery, [userId], (err, row) => {
          if (err) {
            reject(new Error('Ошибка получения обновленного пользователя'));
            return;
          }

          resolve(row as User || null);
        });
      });
    });
  }

  /**
   * Получение всех пользователей
   */
  public async getAllUsers(): Promise<User[]> {
    return new Promise((resolve, reject) => {
      const query = `
        SELECT id, email, password, name, avatar_url as avatar, bio, location, role,
               created_at as createdAt, 
               updated_at as updatedAt
        FROM users 
        ORDER BY created_at DESC
      `;

      this.db.all(query, [], (err, rows) => {
        if (err) {
          reject(new Error('Ошибка получения списка пользователей'));
          return;
        }

        resolve(rows as User[] || []);
      });
    });
  }

  /**
   * Обновление роли пользователя
   */
  public async updateUserRole(userId: number, role: string): Promise<User | null> {
    return new Promise((resolve, reject) => {
      const query = `
        UPDATE users 
        SET role = ?, updated_at = CURRENT_TIMESTAMP
        WHERE id = ?
      `;

      this.db.run(query, [role, userId], function(err) {
        if (err) {
          reject(new Error('Ошибка обновления роли пользователя'));
          return;
        }

        if (this.changes === 0) {
          reject(new Error('Пользователь не найден'));
          return;
        }

        // Получаем обновленного пользователя
        const getUserQuery = `
          SELECT id, email, password, name, avatar_url as avatar, bio, location, role,
                 created_at as createdAt, 
                 updated_at as updatedAt
          FROM users WHERE id = ?
        `;

        database.getDatabase().get(getUserQuery, [userId], (err, row) => {
          if (err) {
            reject(new Error('Ошибка получения обновленного пользователя'));
            return;
          }

          resolve(row as User || null);
        });
      });
    });
  }
}

export const userModel = new UserModel();
