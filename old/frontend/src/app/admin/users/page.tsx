'use client';

import React, { useState, useEffect } from 'react';
import { useAuth } from '@/contexts/AuthContext';
import { adminService } from '@/services/api';
import { AdminGuard } from '@/components/admin/AdminGuard';
import type { User, UserRole } from '@/types/auth';
import {
  MagnifyingGlassIcon,
  FunnelIcon,
  UserIcon,
  PencilIcon,
  CheckIcon,
  XMarkIcon,
  ShieldCheckIcon,
  UserCircleIcon,
  CalendarDaysIcon,
  EnvelopeIcon,
  MapPinIcon
} from '@heroicons/react/24/outline';
import Avatar from '@/components/layout/Avatar';

/**
 * Страница управления пользователями
 */
export default function AdminUsersPage() {
  const { user: currentUser } = useAuth();
  const [users, setUsers] = useState<User[]>([]);
  const [filteredUsers, setFilteredUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [roleFilter, setRoleFilter] = useState<string>('all');
  const [editingUserId, setEditingUserId] = useState<number | null>(null);
  const [newRole, setNewRole] = useState<UserRole>('user');
  const [successMessage, setSuccessMessage] = useState('');
  const [errorMessage, setErrorMessage] = useState('');

  // Загрузка пользователей
  useEffect(() => {
    const loadUsers = async () => {
      try {
        const response = await adminService.getAllUsers();
        setUsers(response.users);
        setFilteredUsers(response.users);
      } catch (error: any) {
        setErrorMessage(error.response?.data?.error || 'Ошибка загрузки пользователей');
      } finally {
        setLoading(false);
      }
    };

    loadUsers();
  }, []);

  // Фильтрация пользователей
  useEffect(() => {
    let filtered = users;

    // Поиск по имени и email
    if (searchTerm) {
      filtered = filtered.filter(user =>
        user.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        user.email.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Фильтр по роли
    if (roleFilter !== 'all') {
      filtered = filtered.filter(user => user.role === roleFilter);
    }

    setFilteredUsers(filtered);
  }, [users, searchTerm, roleFilter]);

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    });
  };

  const getRoleColor = (role: UserRole) => {
    switch (role) {
      case 'super_admin':
        return 'bg-purple-100 text-purple-800';
      case 'admin':
        return 'bg-red-100 text-red-800';
      case 'moderator':
        return 'bg-blue-100 text-blue-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getRoleLabel = (role: UserRole) => {
    switch (role) {
      case 'super_admin':
        return 'Супер-админ';
      case 'admin':
        return 'Администратор';
      case 'moderator':
        return 'Модератор';
      default:
        return 'Пользователь';
    }
  };

  const handleEditRole = (userId: number, currentRole: UserRole) => {
    setEditingUserId(userId);
    setNewRole(currentRole);
  };

  const handleSaveRole = async (userId: number) => {
    try {
      const response = await adminService.updateUserRole(userId, newRole);
      
      // Обновляем пользователя в списке
      setUsers(prev => prev.map(user => 
        user.id === userId ? { ...user, role: newRole } : user
      ));
      
      setEditingUserId(null);
      setSuccessMessage(response.message);
      setTimeout(() => setSuccessMessage(''), 3000);
    } catch (error: any) {
      setErrorMessage(error.response?.data?.error || 'Ошибка обновления роли');
      setTimeout(() => setErrorMessage(''), 3000);
    }
  };

  const handleCancelEdit = () => {
    setEditingUserId(null);
    setNewRole('user');
  };

  if (loading) {
    return (
      <AdminGuard minRole="admin">
        <div className="flex items-center justify-center h-64 bg-gray-200">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-red-500"></div>
        </div>
      </AdminGuard>
    );
  }

  return (
    <AdminGuard minRole="admin">
      <div className="space-y-6">
        {/* Заголовок */}
        <div>
          <h1 className="text-2xl font-bold text-slate-700">Управление пользователями</h1>
          <p className="text-gray-500 mt-1">Просмотр и редактирование пользователей системы</p>
        </div>

        {/* Уведомления */}
        {successMessage && (
          <div className="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
            <CheckIcon className="w-5 h-5 text-green-600" />
            <p className="text-green-700">{successMessage}</p>
          </div>
        )}

        {errorMessage && (
          <div className="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
            <XMarkIcon className="w-5 h-5 text-red-600" />
            <p className="text-red-700">{errorMessage}</p>
          </div>
        )}

        {/* Статистика */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div className="bg-white rounded-xl shadow-md p-6">
            <div className="flex items-center gap-3">
              <UserIcon className="w-8 h-8 text-blue-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">{users.length}</p>
                <p className="text-sm text-gray-600">Всего пользователей</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-xl shadow-md p-6">
            <div className="flex items-center gap-3">
              <ShieldCheckIcon className="w-8 h-8 text-red-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">
                  {users.filter(u => u.role === 'admin' || u.role === 'super_admin').length}
                </p>
                <p className="text-sm text-gray-600">Администраторов</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-xl shadow-md p-6">
            <div className="flex items-center gap-3">
              <UserCircleIcon className="w-8 h-8 text-blue-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">
                  {users.filter(u => u.role === 'moderator').length}
                </p>
                <p className="text-sm text-gray-600">Модераторов</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-xl shadow-md p-6">
            <div className="flex items-center gap-3">
              <UserIcon className="w-8 h-8 text-gray-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">
                  {users.filter(u => u.role === 'user').length}
                </p>
                <p className="text-sm text-gray-600">Обычных пользователей</p>
              </div>
            </div>
          </div>
        </div>

        {/* Фильтры и поиск */}
        <div className="bg-white rounded-xl shadow-md p-6">
          <div className="flex flex-col md:flex-row gap-4">
            {/* Поиск */}
            <div className="flex-1 relative">
              <MagnifyingGlassIcon className="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <input
                type="text"
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                placeholder="Поиск по имени или email..."
                className="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
              />
            </div>

            {/* Фильтр по роли */}
            <div className="relative">
              <FunnelIcon className="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <select
                value={roleFilter}
                onChange={(e) => setRoleFilter(e.target.value)}
                className="pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
              >
                <option value="all">Все роли</option>
                <option value="user">Пользователи</option>
                <option value="moderator">Модераторы</option>
                <option value="admin">Администраторы</option>
                <option value="super_admin">Супер-админы</option>
              </select>
            </div>
          </div>
        </div>

        {/* Список пользователей */}
        <div className="bg-white rounded-xl shadow-md overflow-hidden">
          <div className="px-6 py-4 border-b border-gray-200">
            <h3 className="text-lg font-semibold text-slate-700">
              Пользователи ({filteredUsers.length})
            </h3>
          </div>
          
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Пользователь
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Контакты
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Роль
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Дата регистрации
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Действия
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {filteredUsers.map((user) => (
                  <tr key={user.id} className="hover:bg-gray-50">
                    {/* Пользователь */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center gap-3">
                        <Avatar name={user.name} src={user.avatar} size={40} />
                        <div>
                          <p className="font-medium text-slate-700">{user.name}</p>
                          <p className="text-sm text-gray-500">ID: {user.id}</p>
                        </div>
                      </div>
                    </td>

                    {/* Контакты */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-sm text-gray-600">
                          <EnvelopeIcon className="w-4 h-4" />
                          {user.email}
                        </div>
                        {user.location && (
                          <div className="flex items-center gap-2 text-sm text-gray-500">
                            <MapPinIcon className="w-4 h-4" />
                            {user.location}
                          </div>
                        )}
                      </div>
                    </td>

                    {/* Роль */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      {editingUserId === user.id ? (
                        <div className="flex items-center gap-2">
                          <select
                            value={newRole}
                            onChange={(e) => setNewRole(e.target.value as UserRole)}
                            className="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-red-500"
                          >
                            <option value="user">Пользователь</option>
                            <option value="moderator">Модератор</option>
                            <option value="admin">Администратор</option>
                            {currentUser?.role === 'super_admin' && (
                              <option value="super_admin">Супер-админ</option>
                            )}
                          </select>
                          <button
                            onClick={() => handleSaveRole(user.id)}
                            className="p-1 text-green-600 hover:text-green-800"
                          >
                            <CheckIcon className="w-4 h-4" />
                          </button>
                          <button
                            onClick={handleCancelEdit}
                            className="p-1 text-red-600 hover:text-red-800"
                          >
                            <XMarkIcon className="w-4 h-4" />
                          </button>
                        </div>
                      ) : (
                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getRoleColor(user.role)}`}>
                          {getRoleLabel(user.role)}
                        </span>
                      )}
                    </td>

                    {/* Дата регистрации */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center gap-2 text-sm text-gray-600">
                        <CalendarDaysIcon className="w-4 h-4" />
                        {formatDate(user.createdAt)}
                      </div>
                    </td>

                    {/* Действия */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      {user.id !== currentUser?.id && editingUserId !== user.id && (
                        <button
                          onClick={() => handleEditRole(user.id, user.role)}
                          className="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                          title="Изменить роль"
                        >
                          <PencilIcon className="w-4 h-4" />
                        </button>
                      )}
                      {user.id === currentUser?.id && (
                        <span className="text-xs text-gray-500 italic">Это вы</span>
                      )}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {filteredUsers.length === 0 && (
            <div className="text-center py-12">
              <UserIcon className="w-12 h-12 text-gray-300 mx-auto mb-3" />
              <p className="text-gray-500">
                {searchTerm || roleFilter !== 'all' 
                  ? 'Пользователи не найдены по заданным критериям'
                  : 'Пользователи не найдены'
                }
              </p>
            </div>
          )}
        </div>
      </div>
    </AdminGuard>
  );
}


