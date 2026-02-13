'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import { useAuth } from '@/contexts/AuthContext';
import { adminService } from '@/services/api';
import { AdminGuard } from '@/components/admin/AdminGuard';
import {
  MagnifyingGlassIcon,
  FunnelIcon,
  BuildingLibraryIcon,
  CheckIcon,
  XMarkIcon,
  ClockIcon,
  EyeIcon,
  PencilIcon,
  TrashIcon,
  UserIcon,
  CalendarDaysIcon,
  PhotoIcon,
  ExclamationTriangleIcon
} from '@heroicons/react/24/outline';

interface Memorial {
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

interface MemorialStats {
  total: number;
  pending: number;
  approved: number;
  rejected: number;
}

/**
 * Страница управления мемориалами
 */
export default function AdminMemorialsPage() {
  const { user: currentUser } = useAuth();
  const [memorials, setMemorials] = useState<Memorial[]>([]);
  const [filteredMemorials, setFilteredMemorials] = useState<Memorial[]>([]);
  const [stats, setStats] = useState<MemorialStats>({ total: 0, pending: 0, approved: 0, rejected: 0 });
  const [loading, setLoading] = useState(true);
  const [searchTerm, setSearchTerm] = useState('');
  const [statusFilter, setStatusFilter] = useState<string>('all');
  const [successMessage, setSuccessMessage] = useState('');
  const [errorMessage, setErrorMessage] = useState('');

  // Загрузка мемориалов
  useEffect(() => {
    const loadMemorials = async () => {
      try {
        const response = await adminService.getAllMemorials();
        setMemorials(response.memorials);
        setFilteredMemorials(response.memorials);
        setStats(response.stats);
      } catch (error: any) {
        setErrorMessage(error.response?.data?.error || 'Ошибка загрузки мемориалов');
      } finally {
        setLoading(false);
      }
    };

    loadMemorials();
  }, []);

  // Фильтрация мемориалов
  useEffect(() => {
    let filtered = memorials;

    // Поиск по имени
    if (searchTerm) {
      filtered = filtered.filter(memorial =>
        `${memorial.first_name} ${memorial.last_name} ${memorial.middle_name || ''}`.toLowerCase().includes(searchTerm.toLowerCase()) ||
        memorial.creator_name?.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Фильтр по статусу
    if (statusFilter !== 'all') {
      filtered = filtered.filter(memorial => memorial.status === statusFilter);
    }

    setFilteredMemorials(filtered);
  }, [memorials, searchTerm, statusFilter]);

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('ru-RU', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    });
  };

  const getStatusColor = (status: Memorial['status']) => {
    switch (status) {
      case 'approved':
        return 'bg-green-100 text-green-800';
      case 'pending':
        return 'bg-yellow-100 text-yellow-800';
      case 'rejected':
        return 'bg-red-100 text-red-800';
      default:
        return 'bg-gray-100 text-gray-800';
    }
  };

  const getStatusLabel = (status: Memorial['status']) => {
    switch (status) {
      case 'approved':
        return 'Одобрен';
      case 'pending':
        return 'На модерации';
      case 'rejected':
        return 'Отклонен';
      default:
        return 'Неизвестно';
    }
  };

  const handleUpdateStatus = async (memorialId: number, status: Memorial['status']) => {
    try {
      const response = await adminService.updateMemorialStatus(memorialId, status);
      
      // Обновляем мемориал в списке
      setMemorials(prev => prev.map(memorial => 
        memorial.id === memorialId ? { ...memorial, status } : memorial
      ));
      
      // Обновляем статистику
      setStats(prev => {
        const newStats = { ...prev };
        // Находим старый статус
        const oldMemorial = memorials.find(m => m.id === memorialId);
        if (oldMemorial) {
          newStats[oldMemorial.status as keyof MemorialStats]--;
          newStats[status as keyof MemorialStats]++;
        }
        return newStats;
      });
      
      setSuccessMessage(response.message);
      setTimeout(() => setSuccessMessage(''), 3000);
    } catch (error: any) {
      setErrorMessage(error.response?.data?.error || 'Ошибка обновления статуса');
      setTimeout(() => setErrorMessage(''), 3000);
    }
  };

  const handleDelete = async (memorialId: number) => {
    if (!confirm('Вы уверены, что хотите удалить этот мемориал? Это действие нельзя отменить.')) {
      return;
    }

    try {
      const response = await adminService.deleteMemorial(memorialId);
      
      // Удаляем мемориал из списка
      setMemorials(prev => prev.filter(memorial => memorial.id !== memorialId));
      
      // Обновляем статистику
      setStats(prev => ({
        ...prev,
        total: prev.total - 1
      }));
      
      setSuccessMessage(response.message);
      setTimeout(() => setSuccessMessage(''), 3000);
    } catch (error: any) {
      setErrorMessage(error.response?.data?.error || 'Ошибка удаления мемориала');
      setTimeout(() => setErrorMessage(''), 3000);
    }
  };

  const getFullName = (memorial: Memorial) => {
    return `${memorial.first_name} ${memorial.middle_name ? memorial.middle_name + ' ' : ''}${memorial.last_name}`;
  };

  const getLifeYears = (memorial: Memorial) => {
    if (!memorial.birth_date && !memorial.death_date) return '';
    const birth = memorial.birth_date ? new Date(memorial.birth_date).getFullYear() : '?';
    const death = memorial.death_date ? new Date(memorial.death_date).getFullYear() : '?';
    return `${birth} - ${death}`;
  };

  if (loading) {
    return (
      <AdminGuard minRole="moderator">
        <div className="flex items-center justify-center h-64">
          <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-red-500"></div>
        </div>
      </AdminGuard>
    );
  }

  return (
    <AdminGuard minRole="moderator">
      <div className="space-y-6">
        {/* Заголовок */}
        <div>
          <h1 className="text-2xl font-bold text-slate-700">Управление мемориалами</h1>
          <p className="text-gray-500 mt-1">Модерация и управление мемориалами в системе</p>
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
              <BuildingLibraryIcon className="w-8 h-8 text-purple-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">{stats.total}</p>
                <p className="text-sm text-gray-600">Всего мемориалов</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-xl shadow-md p-6">
            <div className="flex items-center gap-3">
              <ClockIcon className="w-8 h-8 text-yellow-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">{stats.pending}</p>
                <p className="text-sm text-gray-600">На модерации</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-xl shadow-md p-6">
            <div className="flex items-center gap-3">
              <CheckIcon className="w-8 h-8 text-green-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">{stats.approved}</p>
                <p className="text-sm text-gray-600">Одобрено</p>
              </div>
            </div>
          </div>
          
          <div className="bg-white rounded-xl shadow-md p-6">
            <div className="flex items-center gap-3">
              <XMarkIcon className="w-8 h-8 text-red-500" />
              <div>
                <p className="text-2xl font-bold text-slate-700">{stats.rejected}</p>
                <p className="text-sm text-gray-600">Отклонено</p>
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
                placeholder="Поиск по имени мемориала или создателю..."
                className="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
              />
            </div>

            {/* Фильтр по статусу */}
            <div className="relative">
              <FunnelIcon className="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" />
              <select
                value={statusFilter}
                onChange={(e) => setStatusFilter(e.target.value)}
                className="pl-10 pr-8 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
              >
                <option value="all">Все статусы</option>
                <option value="pending">На модерации</option>
                <option value="approved">Одобрено</option>
                <option value="rejected">Отклонено</option>
              </select>
            </div>
          </div>
        </div>

        {/* Список мемориалов */}
        <div className="bg-white rounded-xl shadow-md overflow-hidden">
          <div className="px-6 py-4 border-b border-gray-200">
            <h3 className="text-lg font-semibold text-slate-700">
              Мемориалы ({filteredMemorials.length})
            </h3>
          </div>
          
          <div className="overflow-x-auto">
            <table className="w-full">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Мемориал
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Создатель
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Статус
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Дата создания
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Действия
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {filteredMemorials.map((memorial) => (
                  <tr key={memorial.id} className="hover:bg-gray-50">
                    {/* Мемориал */}
                    <td className="px-6 py-4">
                      <div className="flex items-center gap-3">
                        <div className="w-12 h-12 rounded-full overflow-hidden bg-gray-200 flex-shrink-0">
                          {memorial.photo_url ? (
                            <img 
                              src={memorial.photo_url} 
                              alt={getFullName(memorial)}
                              className="w-full h-full object-cover"
                            />
                          ) : (
                            <div className="w-full h-full flex items-center justify-center">
                              <PhotoIcon className="w-6 h-6 text-gray-400" />
                            </div>
                          )}
                        </div>
                        <div>
                          <p className="font-medium text-slate-700">{getFullName(memorial)}</p>
                          <p className="text-sm text-gray-500">{getLifeYears(memorial)}</p>
                          <p className="text-xs text-gray-400">ID: {memorial.id}</p>
                        </div>
                      </div>
                    </td>

                    {/* Создатель */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="space-y-1">
                        <div className="flex items-center gap-2 text-sm text-gray-600">
                          <UserIcon className="w-4 h-4" />
                          {memorial.creator_name || 'Неизвестно'}
                        </div>
                        {memorial.creator_email && (
                          <div className="text-xs text-gray-500">
                            {memorial.creator_email}
                          </div>
                        )}
                      </div>
                    </td>

                    {/* Статус */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(memorial.status)}`}>
                        {getStatusLabel(memorial.status)}
                      </span>
                    </td>

                    {/* Дата создания */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center gap-2 text-sm text-gray-600">
                        <CalendarDaysIcon className="w-4 h-4" />
                        {formatDate(memorial.created_at)}
                      </div>
                    </td>

                    {/* Действия */}
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center gap-2">
                        {/* Просмотр */}
                        <Link
                          href={`/memorial/${memorial.id}`}
                          className="p-2 text-blue-600 hover:text-blue-800 transition-colors"
                          title="Просмотреть мемориал"
                        >
                          <EyeIcon className="w-4 h-4" />
                        </Link>

                        {/* Одобрить */}
                        {memorial.status !== 'approved' && (
                          <button
                            onClick={() => handleUpdateStatus(memorial.id, 'approved')}
                            className="p-2 text-green-600 hover:text-green-800 transition-colors"
                            title="Одобрить"
                          >
                            <CheckIcon className="w-4 h-4" />
                          </button>
                        )}

                        {/* Отклонить */}
                        {memorial.status !== 'rejected' && (
                          <button
                            onClick={() => handleUpdateStatus(memorial.id, 'rejected')}
                            className="p-2 text-yellow-600 hover:text-yellow-800 transition-colors"
                            title="Отклонить"
                          >
                            <ExclamationTriangleIcon className="w-4 h-4" />
                          </button>
                        )}

                        {/* Удалить (только для админов) */}
                        {(currentUser?.role === 'admin' || currentUser?.role === 'super_admin') && (
                          <button
                            onClick={() => handleDelete(memorial.id)}
                            className="p-2 text-red-600 hover:text-red-800 transition-colors"
                            title="Удалить мемориал"
                          >
                            <TrashIcon className="w-4 h-4" />
                          </button>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {filteredMemorials.length === 0 && (
            <div className="text-center py-12">
              <BuildingLibraryIcon className="w-12 h-12 text-gray-300 mx-auto mb-3" />
              <p className="text-gray-500">
                {searchTerm || statusFilter !== 'all' 
                  ? 'Мемориалы не найдены по заданным критериям'
                  : 'Мемориалы не найдены'
                }
              </p>
            </div>
          )}
        </div>
      </div>
    </AdminGuard>
  );
}



