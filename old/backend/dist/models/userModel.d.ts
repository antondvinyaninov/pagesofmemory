import type { User, RegisterUserRequest, UpdateUserProfileRequest } from '../types/user';
/**
 * Модель для работы с пользователями
 */
export declare class UserModel {
    private db;
    constructor();
    /**
     * Создание нового пользователя
     */
    createUser(userData: RegisterUserRequest): Promise<User>;
    /**
     * Поиск пользователя по email
     */
    findUserByEmail(email: string): Promise<User | null>;
    /**
     * Поиск пользователя по ID
     */
    findUserById(id: number): Promise<User | null>;
    /**
     * Проверка пароля
     */
    verifyPassword(plainPassword: string, hashedPassword: string): Promise<boolean>;
    /**
     * Обновление профиля пользователя
     */
    updateUserProfile(userId: number, updateData: UpdateUserProfileRequest): Promise<User | null>;
    /**
     * Получение всех пользователей
     */
    getAllUsers(): Promise<User[]>;
    /**
     * Обновление роли пользователя
     */
    updateUserRole(userId: number, role: string): Promise<User | null>;
}
export declare const userModel: UserModel;
