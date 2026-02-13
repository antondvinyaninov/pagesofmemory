'use client';

import React, { useState, useEffect } from 'react';
import Link from 'next/link';
import Avatar from '@/components/layout/Avatar';
import { useParams, useRouter } from 'next/navigation';
import { useAuth } from '@/contexts/AuthContext';
import {
  HeartIcon,
  BookOpenIcon,
  MapPinIcon,
  ChartBarIcon,
  CogIcon,
  ShareIcon,
  PhotoIcon,
  PlusIcon,
  EyeIcon,
  ChatBubbleLeftIcon,
  EllipsisHorizontalIcon,
  PaperAirplaneIcon,
  MapIcon,
  BuildingLibraryIcon,
  UserIcon,
  TrophyIcon,
  PlayIcon,
  PencilIcon,
  XMarkIcon,
  VideoCameraIcon,
  DocumentIcon,
  TrashIcon,
  FlagIcon,
  LinkIcon,
  ArrowDownTrayIcon
} from '@heroicons/react/24/outline';

/**
 * Страница мемориала по аналогии с PHP проектом
 */
export default function MemorialPage() {
  const params = useParams();
  const router = useRouter();
  const { isAuthenticated, user } = useAuth();
  const [memorial, setMemorial] = useState<any>(null);
  const [memories, setMemories] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [newMemory, setNewMemory] = useState('');
  const [activeTab, setActiveTab] = useState('memories'); // memories, necrologue, burial
  const [selectedRelationship, setSelectedRelationship] = useState('');
  const [selectedFamilyRelation, setSelectedFamilyRelation] = useState('');
  const [customRelationship, setCustomRelationship] = useState('');
  const [relationshipComment, setRelationshipComment] = useState('');
  
  // Состояния для добавления воспоминания
  const [attachedMedia, setAttachedMedia] = useState<File[]>([]);
  const [mediaPreview, setMediaPreview] = useState<string[]>([]);
  const [isSubmittingMemory, setIsSubmittingMemory] = useState(false);
  
  // Состояния для лайков и комментариев
  const [likedMemories, setLikedMemories] = useState<Set<number>>(new Set());
  const [showComments, setShowComments] = useState<Record<number, boolean>>({});
  const [newComment, setNewComment] = useState<Record<number, string>>({});
  const [memoryComments, setMemoryComments] = useState<Record<number, any[]>>({});
  const [activeDropdown, setActiveDropdown] = useState<number | null>(null);
  
  // Хелпер: переводит /api/placeholder/W/H в реальный плейсхолдер
  const resolvePlaceholder = (url?: string | null): string | null => {
    if (!url) return url ?? null;
    if (!url.startsWith('/api/placeholder/')) return url;
    const parts = url.split('/');
    const w = parts[parts.length - 2] || '300';
    const h = parts[parts.length - 1] || '300';
    // Локальный плейсхолдер через Next API
    return `/api/placeholder/${w}/${h}`;
  };
  const resolvedAvatar = (url?: string | null): string => resolvePlaceholder(url) || '/api/placeholder/32/32';
  
  // Подхватываем локальный черновик фото из /memorial/manage для текущего id
  useEffect(() => {
    try {
      const rawId = (params as any)?.id;
      const memorialId = typeof rawId === 'string' ? rawId : Array.isArray(rawId) ? rawId[0] : String(rawId ?? '');
      if (!memorialId) return;
      const draftKey = `memorial_draft_${memorialId}`;
      const raw = localStorage.getItem(draftKey);
      if (!raw) return;
      const draft = JSON.parse(raw);
      if (draft && draft.photo_url) {
        setMemorial((prev: any) => prev ? { ...prev, photo_url: draft.photo_url } : prev);
      }
    } catch {}
  }, [params]);

  // После загрузки демо-данных всегда подставим фото из черновика, если оно есть
  useEffect(() => {
    try {
      const rawId = (params as any)?.id;
      const memorialId = typeof rawId === 'string' ? rawId : Array.isArray(rawId) ? rawId[0] : String(rawId ?? '');
      if (!memorialId) return;
      const draftKey = `memorial_draft_${memorialId}`;
      const raw = localStorage.getItem(draftKey);
      if (!raw) return;
      const draft = JSON.parse(raw);
      if (draft?.photo_url) {
        setMemorial((prev: any) => prev ? { ...prev, photo_url: draft.photo_url } : prev);
      }
    } catch {}
  }, [memorial, params]);

  // Единоразово конвертируем плейсхолдеры в рабочие ссылки для мемориала
  const [placeholdersFixed, setPlaceholdersFixed] = useState(false);
  useEffect(() => {
    if (placeholdersFixed) return;
    if (!memorial) return;
    const needsFix = (memorial.photo_url && String(memorial.photo_url).startsWith('/api/placeholder/'))
      || (memorial.media?.photos?.some((p: any) => typeof p?.url === 'string' && p.url.startsWith('/api/placeholder/')));
    if (!needsFix) { setPlaceholdersFixed(true); return; }
    const fixed = {
      ...memorial,
      photo_url: resolvePlaceholder(memorial.photo_url),
      media: memorial.media ? {
        ...memorial.media,
        photos: Array.isArray(memorial.media.photos)
          ? memorial.media.photos.map((p: any) => ({ ...p, url: resolvePlaceholder(p.url) }))
          : memorial.media?.photos,
        videos: memorial.media.videos
      } : undefined
    };
    setMemorial(fixed);
    setPlaceholdersFixed(true);
  }, [memorial, placeholdersFixed]);

  // Единоразово конвертируем плейсхолдеры в воспоминаниях (аватары и фото)
  const [memoryPlaceholdersFixed, setMemoryPlaceholdersFixed] = useState(false);
  useEffect(() => {
    if (memoryPlaceholdersFixed) return;
    if (!memories || memories.length === 0) return;
    const anyPlaceholder = memories.some((m: any) =>
      (typeof m.author_avatar === 'string' && m.author_avatar.startsWith('/api/placeholder/'))
      || (typeof m.photo_url === 'string' && m.photo_url.startsWith('/api/placeholder/'))
      || (Array.isArray(m.media_files) && m.media_files.some((f: any) => typeof f?.url === 'string' && f.url.startsWith('/api/placeholder/')))
    );
    if (!anyPlaceholder) { setMemoryPlaceholdersFixed(true); return; }
    const fixedMemories = memories.map((m: any) => ({
      ...m,
      author_avatar: resolvedAvatar(m.author_avatar),
      photo_url: resolvePlaceholder(m.photo_url),
      media_files: Array.isArray(m.media_files)
        ? m.media_files.map((f: any) => ({ ...f, url: resolvePlaceholder(f.url) }))
        : m.media_files
    }));
    setMemories(fixedMemories);
    setMemoryPlaceholdersFixed(true);
  }, [memories, memoryPlaceholdersFixed]);
  
  // Проверка, является ли пользователь администратором мемориала
  const isMemorialAdmin = isAuthenticated && user && (
    user.id === memorial?.created_by || // Создатель мемориала
    user.role === 'admin' // Или системный администратор
  );

  // Функции для работы с медиафайлами
  const handleMediaUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(event.target.files || []);
    
    // Проверяем лимит (до 5 файлов)
    if (attachedMedia.length + files.length > 5) {
      alert('Можно прикрепить не более 5 файлов');
      return;
    }

    // Проверяем типы файлов
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/webm'];
    const validFiles = files.filter(file => {
      if (!allowedTypes.includes(file.type)) {
        alert(`Файл ${file.name} имеет неподдерживаемый формат`);
        return false;
      }
      if (file.size > 50 * 1024 * 1024) { // 50MB лимит
        alert(`Файл ${file.name} слишком большой (максимум 50MB)`);
        return false;
      }
      return true;
    });

    // Добавляем файлы и создаём превью
    setAttachedMedia(prev => [...prev, ...validFiles]);
    
    validFiles.forEach(file => {
      const reader = new FileReader();
      reader.onload = (e) => {
        setMediaPreview(prev => [...prev, e.target?.result as string]);
      };
      reader.readAsDataURL(file);
    });
  };

  const removeMedia = (index: number) => {
    setAttachedMedia(prev => prev.filter((_, i) => i !== index));
    setMediaPreview(prev => prev.filter((_, i) => i !== index));
  };

  const getFileIcon = (file: File) => {
    if (file.type.startsWith('image/')) return PhotoIcon;
    if (file.type.startsWith('video/')) return VideoCameraIcon;
    return DocumentIcon;
  };

  // Функция для форматирования даты на русском языке
  const formatRussianDate = (dateString: string) => {
    const date = new Date(dateString);
    const months = [
      'января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
      'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
    ];
    
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    
    return `${day} ${month} ${year} в ${hours}:${minutes}`;
  };

  // Функции для лайков и комментариев
  const handleLike = (memoryId: number) => {
    const newLikedMemories = new Set(likedMemories);
    const isLiked = likedMemories.has(memoryId);
    
    if (isLiked) {
      newLikedMemories.delete(memoryId);
    } else {
      newLikedMemories.add(memoryId);
    }
    
    setLikedMemories(newLikedMemories);
    
    // Обновляем счетчик лайков в воспоминании
    setMemories(prev => prev.map(memory => 
      memory.id === memoryId 
        ? { ...memory, likes: memory.likes + (isLiked ? -1 : 1) }
        : memory
    ));
    
    // TODO: отправить запрос на сервер
    console.log(isLiked ? 'Убрали лайк' : 'Поставили лайк', memoryId);
  };

  const toggleComments = (memoryId: number) => {
    setShowComments(prev => ({
      ...prev,
      [memoryId]: !prev[memoryId]
    }));
    
    // Загружаем комментарии, если еще не загружены
    if (!memoryComments[memoryId] && !showComments[memoryId]) {
      // TODO: загрузить комментарии с сервера
      setMemoryComments(prev => ({
        ...prev,
        [memoryId]: [
          {
            id: 1,
            author_name: 'Мария Петрова',
            author_avatar: '/api/placeholder/32/32',
            content: 'Какие трогательные воспоминания! Спасибо, что поделились.',
            created_at: new Date().toISOString(),
          },
          {
            id: 2,
            author_name: 'Алексей Сидоров',
            author_avatar: '/api/placeholder/32/32',
            content: 'Помню этот день! Иван Иванович всегда был таким добрым.',
            created_at: new Date().toISOString(),
          }
        ]
      }));
    }
  };

  const handleCommentSubmit = (memoryId: number) => {
    const commentText = newComment[memoryId]?.trim();
    if (!commentText || !isAuthenticated) return;

    const newCommentObj = {
      id: Date.now(),
      author_name: user?.name || 'Анонимный пользователь',
      author_avatar: '/api/placeholder/32/32',
      content: commentText,
      created_at: new Date().toISOString(),
    };

    // Добавляем комментарий
    setMemoryComments(prev => ({
      ...prev,
      [memoryId]: [...(prev[memoryId] || []), newCommentObj]
    }));

    // Обновляем счетчик комментариев
    setMemories(prev => prev.map(memory => 
      memory.id === memoryId 
        ? { ...memory, comments: memory.comments + 1 }
        : memory
    ));

    // Очищаем поле ввода
    setNewComment(prev => ({
      ...prev,
      [memoryId]: ''
    }));

    // TODO: отправить на сервер
    console.log('Добавили комментарий:', commentText, memoryId);
  };

  // Функции для выпадающего меню
  const toggleDropdown = (memoryId: number) => {
    setActiveDropdown(activeDropdown === memoryId ? null : memoryId);
  };

  const closeDropdown = () => {
    setActiveDropdown(null);
  };

  const handleEdit = (memoryId: number) => {
    console.log('Редактировать воспоминание:', memoryId);
    closeDropdown();
    // TODO: открыть форму редактирования
  };

  const handleDelete = (memoryId: number) => {
    if (confirm('Вы уверены, что хотите удалить это воспоминание?')) {
      setMemories(prev => prev.filter(memory => memory.id !== memoryId));
      console.log('Удалили воспоминание:', memoryId);
    }
    closeDropdown();
  };

  const handleReport = (memoryId: number) => {
    console.log('Пожаловаться на воспоминание:', memoryId);
    alert('Жалоба отправлена на модерацию');
    closeDropdown();
  };

  const handleShare = (memoryId: number) => {
    const url = `${window.location.origin}/memorial/${memorial?.id}#memory-${memoryId}`;
    navigator.clipboard.writeText(url);
    alert('Ссылка скопирована в буфер обмена');
    closeDropdown();
  };

  const handleDownload = (memoryId: number) => {
    console.log('Скачать медиафайлы воспоминания:', memoryId);
    // TODO: реализовать скачивание
    closeDropdown();
  };

  const handleGoToEdit = (): void => {
    if (!memorial?.id) return;
    router.push(`/memorial/manage?id=${memorial.id}`);
  };

  /**
   * Компонент религиозного символа
   */
  const ReligiousSymbol = ({ religion, size = 'w-8 h-8' }: { religion: string; size?: string }) => {
    const symbols = {
      orthodox: { 
        name: 'Православие', 
        color: 'fill-red-400',
        svg: (
          <svg viewBox="0 0 24 24" className={`${size} fill-red-400 drop-shadow-lg`}>
            <path d="M12 2v20M8 6h8M6 10h12M8 18h8" stroke="currentColor" strokeWidth="2" fill="none"/>
            <path d="M12 2L10 4h4l-2-2zM12 22l-2-2h4l-2 2z" fill="currentColor"/>
          </svg>
        )
      },
      catholic: { 
        name: 'Католицизм', 
        color: 'fill-red-300',
        svg: (
          <svg viewBox="0 0 24 24" className={`${size} fill-red-300 drop-shadow-lg`}>
            <path d="M12 2v20M6 8h12" stroke="currentColor" strokeWidth="3" fill="none" strokeLinecap="round"/>
          </svg>
        )
      },
      islam: { 
        name: 'Ислам', 
        color: 'fill-green-400',
        svg: (
          <svg viewBox="0 0 24 24" className={`${size} fill-green-400 drop-shadow-lg`}>
            <path d="M12 2C8.5 6 6 10 8 14c1.5 3 4.5 4 6 2 0 2 1 4 4 4s4-2 4-4c0-4-3-8-7-10l-3-4z" fill="currentColor"/>
            <circle cx="18" cy="6" r="2" fill="currentColor"/>
          </svg>
        )
      },
      judaism: { 
        name: 'Иудаизм', 
        color: 'fill-blue-400',
        svg: (
          <svg viewBox="0 0 24 24" className={`${size} fill-blue-400 drop-shadow-lg`}>
            <path d="M12 2l2.5 4.33L12 8.66 9.5 6.33 12 2zM12 15.34l2.5 4.33L12 22l-2.5-2.33L12 15.34zM5.5 6.33L8 10.66l2.5-4.33L8 4L5.5 6.33zM16 4l-2.5 2.33L16 10.66l2.5-4.33L16 4zM5.5 17.67L8 13.34l2.5 4.33L8 20l-2.5-2.33zM16 20l-2.5-2.33L16 13.34l2.5 4.33L16 20z" fill="currentColor"/>
          </svg>
        )
      },
      buddhism: { 
        name: 'Буддизм', 
        color: 'fill-orange-400',
        svg: (
          <svg viewBox="0 0 24 24" className={`${size} fill-orange-400 drop-shadow-lg`}>
            <circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" strokeWidth="2"/>
            <circle cx="7" cy="9" r="1" fill="currentColor"/>
            <circle cx="17" cy="15" r="1" fill="currentColor"/>
            <path d="M12 2C12 8 8 12 2 12c6 0 10 4 10 10 0-6 4-10 10-10-6 0-10-4-10-10z" fill="currentColor"/>
          </svg>
        )
      },
      hinduism: { 
        name: 'Индуизм', 
        color: 'fill-orange-300',
        svg: (
          <svg viewBox="0 0 24 24" className={`${size} fill-orange-300 drop-shadow-lg`}>
            <path d="M12 2C8 4 6 8 6 12s2 8 6 10c4-2 6-6 6-10s-2-8-6-10z" fill="currentColor"/>
            <path d="M12 6c-2 1-3 3-3 6s1 5 3 6c2-1 3-3 3-6s-1-5-3-6z" fill="none" stroke="white" strokeWidth="1"/>
            <circle cx="12" cy="9" r="1" fill="white"/>
          </svg>
        )
      },
      other: { 
        name: 'Другая религия', 
        color: 'fill-purple-400',
        svg: (
          <svg viewBox="0 0 24 24" className={`${size} fill-purple-400 drop-shadow-lg`}>
            <circle cx="12" cy="12" r="8" fill="none" stroke="currentColor" strokeWidth="2"/>
            <path d="M8 12h8M12 8v8" stroke="currentColor" strokeWidth="1"/>
          </svg>
        )
      },
      none: null
    };

    const religionData = symbols[religion as keyof typeof symbols];
    
    if (!religionData) return null;

    return (
      <div title={religionData.name} className="inline-block">
        {religionData.svg}
      </div>
    );
  };

  // Закрытие меню при клике вне его
  useEffect(() => {
    const handleClickOutside = () => {
      setActiveDropdown(null);
    };

    if (activeDropdown !== null) {
      document.addEventListener('click', handleClickOutside);
      return () => document.removeEventListener('click', handleClickOutside);
    }
  }, [activeDropdown]);

  // Временные данные для демонстрации (как в PHP проекте)
  useEffect(() => {
    // Имитация загрузки данных
    setTimeout(() => {
      setMemorial({
        id: 1,
        created_by: 1, // ID создателя мемориала (для демонстрации)
        first_name: 'Иван',
        last_name: 'Иванов', 
        middle_name: 'Иванович',
        birth_date: '1945-03-15',
        death_date: '2023-01-10',
        birth_place: 'Москва, Россия',
        photo_url: '/api/placeholder/300/300',
        biography: 'Любящий муж, отец и дедушка...',
        necrologue: 'С глубоким прискорбием сообщаем о кончине выдающегося инженера-конструктора, заслуженного деятеля науки Российской Федерации Ивана Ивановича Иванова. Человек неиссякаемой энергии, блестящего ума и добрейшего сердца покинул нас 10 января 2024 года, оставив неизгладимый след в истории отечественного машиностроения и в сердцах всех, кто его знал.',
        burial_place: 'Новодевичье кладбище',
        burial_address: 'Москва, Лужнецкий проезд, 2',
        burial_location: 'Участок 2, ряд 15, место 7',
        burial_coordinates: '55.726944, 37.553889',
        religion: 'orthodox', // orthodox, catholic, islam, judaism, buddhism, hinduism, other, none
        full_biography: 'Иван Иванович Иванов родился 15 марта 1945 года в Москве в простой рабочей семье. Отец - слесарь на заводе, мать - воспитательница в детском саду. С детства проявлял необычайный интерес к технике и точным наукам. В школе был отличником, активно участвовал в кружке юных техников, где собирал радиоприемники и модели самолетов.',
        achievements: [
          { year: '1968', title: 'Окончание МГТУ им. Н.Э. Баумана с красным дипломом', description: 'Диплом с отличием по специальности "Машиностроение и приборостроение". Дипломная работа признана лучшей на факультете.' },
          { year: '1975', title: 'Первый патент на изобретение', description: 'Патент №524891 "Устройство для автоматической сварки тонкостенных конструкций"' },
          { year: '1982', title: 'Звание "Лауреат премии Совета Министров СССР"', description: 'За разработку и внедрение новой технологии производства авиационных двигателей' },
          { year: '1985', title: 'Звание "Заслуженный изобретатель РСФСР"', description: 'За выдающиеся достижения в области машиностроения и вклад в техническое развитие страны' },
          { year: '1995', title: 'Доктор технических наук', description: 'Защитил докторскую диссертацию по теме "Оптимизация конструкций энергетических установок"' },
          { year: '2005', title: 'Почетное звание "Главный конструктор"', description: 'Назначен главным конструктором Центрального КБ машиностроения' },
          { year: '2010', title: 'Орден "За заслуги перед Отечеством" IV степени', description: 'За многолетнюю плодотворную деятельность и большой вклад в развитие машиностроения' },
          { year: '2015', title: 'Звание "Заслуженный деятель науки РФ"', description: 'За выдающиеся заслуги в области научной деятельности' }
        ],
        media: {
          photos: [
            { id: 1, url: '/api/placeholder/400/300', title: 'За чертежной доской в КБ', year: '1975' },
            { id: 2, url: '/api/placeholder/400/300', title: 'Семейное фото на даче', year: '1980' },
            { id: 3, url: '/api/placeholder/400/300', title: 'Вручение ордена в Кремле', year: '2010' },
            { id: 4, url: '/api/placeholder/400/300', title: 'С коллегами на заводе', year: '1985' },
            { id: 5, url: '/api/placeholder/400/300', title: 'Выпускной в Бауманке', year: '1968' },
            { id: 6, url: '/api/placeholder/400/300', title: 'Турнир по шахматам', year: '1995' },
            { id: 7, url: '/api/placeholder/400/300', title: 'Золотая свадьба', year: '2021' },
            { id: 8, url: '/api/placeholder/400/300', title: 'С внуками на прогулке', year: '2020' }
          ],
          videos: [
            { id: 1, url: '/api/placeholder/600/400', title: 'Интервью телеканалу "Наука" о своей карьере', duration: '15:32' },
            { id: 2, url: '/api/placeholder/600/400', title: 'Выступление на международной конференции', duration: '8:45' },
            { id: 3, url: '/api/placeholder/600/400', title: 'Документальный фильм о КБ (фрагмент)', duration: '12:18' },
            { id: 4, url: '/api/placeholder/600/400', title: 'Поздравления от коллег к юбилею', duration: '6:24' }
          ]
        }
      });

      setMemories([
        {
          id: 1,
          user_id: 1, // ID пользователя (для проверки прав)
          author_name: 'Петр Петров',
          author_avatar: '/api/placeholder/48/48',
          content: 'Помню, как мы вместе проводили время. Это были прекрасные моменты.',
          photo_url: '/api/placeholder/600/400',
          created_at: '2024-01-10T12:30:00Z',
          likes: 8,
          comments: 3,
          views: 142
        },
        {
          id: 2,
          user_id: 2, // ID другого пользователя
          author_name: 'Ольга Смирнова',
          author_avatar: '/api/placeholder/48/48',
          content: 'Вспоминаю наши интересные беседы за чашкой чая. Иван Иванович всегда мог найти нужные слова поддержки и дать мудрый совет.',
          photo_url: null,
          created_at: '2024-01-09T15:45:00Z',
          likes: 6,
          comments: 2,
          views: 98
        },
        {
          id: 3,
          user_id: 3, // ID третьего пользователя
          author_name: 'Михаил Сергеев',
          author_avatar: '/api/placeholder/48/48',
          content: 'Помню наши совместные проекты в конструкторском бюро. Иван Иванович всегда поражал своим вниманием к деталям.',
          photo_url: null,
          created_at: '2024-01-08T09:20:00Z',
          likes: 4,
          comments: 0,
          views: 67
        }
      ]);

      setLoading(false);
    }, 1000);
  }, [params.id]);

  /**
   * Обработка отправки нового воспоминания
   */
  const handleSubmitMemory = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!newMemory.trim()) return;

    setIsSubmittingMemory(true);
    
    try {
      // TODO: здесь будет реальная отправка на сервер с медиафайлами
      // const formData = new FormData();
      // formData.append('content', newMemory);
      // attachedMedia.forEach((file, index) => {
      //   formData.append(`media_${index}`, file);
      // });

      // Добавляем новое воспоминание
      const newMemoryObj = {
        id: Date.now(),
        author_name: user?.name || 'Анонимный пользователь',
        author_avatar: '/api/placeholder/48/48',
        content: newMemory,
        media_files: attachedMedia.map((file, index) => ({
          type: file.type.startsWith('image/') ? 'image' : 'video',
          url: mediaPreview[index] || '/api/placeholder/400/300',
          name: file.name
        })),
        created_at: new Date().toISOString().split('T')[0],
        likes: 0,
        comments: 0,
        views: 0
      };

      setMemories([newMemoryObj, ...memories]);
      
      // Очищаем форму
      setNewMemory('');
      setAttachedMedia([]);
      setMediaPreview([]);
      
    } catch (error) {
      console.error('Ошибка при добавлении воспоминания:', error);
      alert('Не удалось добавить воспоминание. Попробуйте еще раз.');
    } finally {
      setIsSubmittingMemory(false);
    }
  };

  if (loading) {
    return (
      <div className="min-h-screen bg-gray-200 flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-red-500"></div>
      </div>
    );
  }

  if (!memorial) {
    return (
      <div className="min-h-screen bg-gray-200 flex items-center justify-center">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-slate-700 mb-4">Мемориал не найден</h1>
          <Link href="/" className="text-red-500 hover:text-red-600">
            Вернуться на главную
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-gray-200 pt-6">
      {/* Карточка мемориала */}
      <div className="mb-4">
        <div className="container mx-auto px-4">
          <div className="relative bg-slate-700 text-white rounded-xl overflow-hidden shadow-xl">
            <div className="absolute inset-0 bg-gradient-to-br from-slate-700/95 to-slate-800/85"></div>
            
            {/* Религиозный символ в правом верхнем углу */}
            {memorial.religion && (
              <div className="absolute top-6 right-6 z-10">
                <ReligiousSymbol religion={memorial.religion} size="w-12 h-12" />
              </div>
            )}
            
            <div className="relative p-6">
              <div className="flex flex-col md:flex-row items-center md:items-start gap-6">
                {/* Фото */}
                <div className="w-60 h-60 md:w-64 md:h-64 bg-gray-300 rounded-xl overflow-hidden flex-shrink-0 shadow-lg">
                  <img 
                    src={resolvePlaceholder(memorial.photo_url) || memorial.photo_url} 
                    alt={`${memorial.first_name} ${memorial.last_name}`}
                    className="w-full h-full object-cover"
                    onError={(e) => {
                      const target = e.target as HTMLImageElement;
                      target.src = '/api/placeholder/320/320';
                    }}
                  />
                </div>

                    {/* Информация */}
                    <div className="flex-1 text-center md:text-left">
                      <h1 className="text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 leading-tight">
                        {memorial.first_name} {memorial.middle_name} {memorial.last_name}
                      </h1>
                      
                      {/* Даты жизни как на памятнике */}
                      <div className="mb-5">
                        <div className="text-xl md:text-2xl lg:text-3xl font-bold text-red-400">
                          {new Date(memorial.birth_date).toLocaleDateString('ru-RU')}
                          <span className="mx-4">—</span>
                          {new Date(memorial.death_date).toLocaleDateString('ru-RU')}
                        </div>
                      </div>
                      
                      {/* Место рождения */}
                      {memorial.birth_place && (
                        <div className="mb-5 flex items-center justify-center md:justify-start gap-3">
                          <MapPinIcon className="w-5 h-5 md:w-6 md:h-6 text-white" />
                          <span className="text-base md:text-lg lg:text-xl opacity-90">{memorial.birth_place}</span>
                        </div>
                      )}
                      
                      {memorial.biography && (
                        <div className="mb-4">
                          <div className="text-lg md:text-xl lg:text-2xl font-semibold text-white mb-2 italic">
                            {memorial.biography}
                          </div>
                        </div>
                      )}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

          {/* Основной контент */}
          <div className="container mx-auto px-4">
            <div className="grid lg:grid-cols-[280px_1fr_320px] gap-4">
          {/* Сайдбар */}
          <aside className="lg:sticky lg:top-4 h-fit">
            <div className="bg-white rounded-xl shadow-md overflow-hidden">
              {/* Меню навигации */}
              <nav className="p-0">
                {/* Мобильная версия - горизонтальный скролл */}
                <div className="lg:hidden flex overflow-x-auto gap-2 p-4 scrollbar-hide">
                  <button 
                    onClick={() => setActiveTab('memories')}
                    className={`flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors ${
                      activeTab === 'memories'
                        ? 'bg-red-50 text-red-600'
                        : 'text-slate-700 hover:bg-gray-50'
                    }`}
                  >
                    <BookOpenIcon className="w-4 h-4" />
                    Воспоминания
                  </button>
                  <button 
                    onClick={() => setActiveTab('about')}
                    className={`flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors ${
                      activeTab === 'about'
                        ? 'bg-red-50 text-red-600'
                        : 'text-slate-700 hover:bg-gray-50'
                    }`}
                  >
                    <UserIcon className="w-4 h-4" />
                    О человеке
                  </button>
                  <button 
                    onClick={() => setActiveTab('burial')}
                    className={`flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors ${
                      activeTab === 'burial'
                        ? 'bg-red-50 text-red-600'
                        : 'text-slate-700 hover:bg-gray-50'
                    }`}
                  >
                    <MapPinIcon className="w-4 h-4" />
                    Захоронение
                  </button>
                      <button 
                        onClick={() => setActiveTab('media')}
                        className={`flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors ${
                          activeTab === 'media'
                            ? 'bg-red-50 text-red-600'
                            : 'text-slate-700 hover:bg-gray-50'
                        }`}
                      >
                        <PhotoIcon className="w-4 h-4" />
                        Медиа
                      </button>
                      <button 
                        onClick={() => setActiveTab('people')}
                        className={`flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors ${
                          activeTab === 'people'
                            ? 'bg-red-50 text-red-600'
                            : 'text-slate-700 hover:bg-gray-50'
                        }`}
                      >
                        <UserIcon className="w-4 h-4" />
                        Близкие люди
                      </button>
                  {isAuthenticated && (
                    <>
                      <button 
                        onClick={() => setActiveTab('statistics')}
                        className={`flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors ${
                          activeTab === 'statistics'
                            ? 'bg-red-50 text-red-600'
                            : 'text-slate-700 hover:bg-gray-50'
                        }`}
                      >
                        <ChartBarIcon className="w-4 h-4" />
                        Статистика
                      </button>
                      <button 
                        onClick={() => setActiveTab('settings')}
                        className={`flex items-center gap-2 px-4 py-2 rounded-lg whitespace-nowrap text-sm transition-colors ${
                          activeTab === 'settings'
                            ? 'bg-red-50 text-red-600'
                            : 'text-slate-700 hover:bg-gray-50'
                        }`}
                      >
                        <CogIcon className="w-4 h-4" />
                        Настройки
                      </button>
                    </>
                  )}
                </div>

                {/* Десктопная версия - вертикальное меню */}
                <div className="hidden lg:block">
                  <button 
                    onClick={() => setActiveTab('memories')}
                    className={`flex items-center gap-4 px-6 py-5 w-full text-left transition-all duration-200 border-b border-gray-100 font-medium ${
                      activeTab === 'memories' 
                        ? 'bg-red-50 text-red-600' 
                        : 'text-slate-700 hover:bg-red-50 hover:text-red-600'
                    }`}
                  >
                    <BookOpenIcon className="w-6 h-6 text-red-500" />
                    <span className="text-base">Воспоминания</span>
                  </button>
                  <button 
                    onClick={() => setActiveTab('about')}
                    className={`flex items-center gap-4 px-6 py-5 w-full text-left transition-all duration-200 border-b border-gray-100 font-medium ${
                      activeTab === 'about' 
                        ? 'bg-red-50 text-red-600' 
                        : 'text-slate-700 hover:bg-red-50 hover:text-red-600'
                    }`}
                  >
                    <UserIcon className="w-6 h-6 text-red-500" />
                    <span className="text-base">О человеке</span>
                  </button>
                  <button 
                    onClick={() => setActiveTab('burial')}
                    className={`flex items-center gap-4 px-6 py-5 w-full text-left transition-all duration-200 border-b border-gray-100 font-medium ${
                      activeTab === 'burial' 
                        ? 'bg-red-50 text-red-600' 
                        : 'text-slate-700 hover:bg-red-50 hover:text-red-600'
                    }`}
                  >
                    <MapPinIcon className="w-6 h-6 text-red-500" />
                    <span className="text-base">Захоронение</span>
                  </button>
                      <button 
                        onClick={() => setActiveTab('media')}
                        className={`flex items-center gap-4 px-6 py-5 w-full text-left transition-all duration-200 border-b border-gray-100 font-medium ${
                          activeTab === 'media' 
                            ? 'bg-red-50 text-red-600' 
                            : 'text-slate-700 hover:bg-red-50 hover:text-red-600'
                        }`}
                      >
                        <PhotoIcon className="w-6 h-6 text-red-500" />
                        <span className="text-base">Медиа</span>
                      </button>
                      <button 
                        onClick={() => setActiveTab('people')}
                        className={`flex items-center gap-4 px-6 py-5 w-full text-left transition-all duration-200 border-b border-gray-100 font-medium ${
                          activeTab === 'people' 
                            ? 'bg-red-50 text-red-600' 
                            : 'text-slate-700 hover:bg-red-50 hover:text-red-600'
                        }`}
                      >
                        <UserIcon className="w-6 h-6 text-red-500" />
                        <span className="text-base">Близкие люди</span>
                      </button>
                  {isAuthenticated && (
                    <>
                      <button 
                        onClick={() => setActiveTab('statistics')}
                        className={`flex items-center gap-4 px-6 py-5 w-full text-left transition-all duration-200 border-b border-gray-100 font-medium ${
                          activeTab === 'statistics' 
                            ? 'bg-red-50 text-red-600' 
                            : 'text-slate-700 hover:bg-red-50 hover:text-red-600'
                        }`}
                      >
                        <ChartBarIcon className="w-6 h-6 text-red-500" />
                        <span className="text-base">Статистика</span>
                      </button>
                      <button 
                        onClick={() => setActiveTab('settings')}
                        className={`flex items-center gap-4 px-6 py-5 w-full text-left transition-all duration-200 font-medium ${
                          activeTab === 'settings' 
                            ? 'bg-red-50 text-red-600' 
                            : 'text-slate-700 hover:bg-red-50 hover:text-red-600'
                        }`}
                      >
                        <CogIcon className="w-6 h-6 text-red-500" />
                        <span className="text-base">Настройки</span>
                      </button>
                    </>
                  )}
                </div>
              </nav>
            </div>
          </aside>

          {/* Основной контент */}
          <main className="space-y-4">
            {/* Секция воспоминаний */}
            {activeTab === 'memories' && (
            <section id="memories">
              {/* Форма добавления воспоминания */}
              {isAuthenticated ? (
                <div className="bg-white rounded-xl shadow-md overflow-hidden mb-4">
                  <div className="px-6 py-4 border-b border-gray-100">
                    <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                      <PlusIcon className="w-5 h-5 text-red-500" />
                      Поделитесь воспоминаниями
                    </h3>
                  </div>
                  
                  <form onSubmit={handleSubmitMemory} className="p-6">
                    <textarea
                      value={newMemory}
                      onChange={(e) => setNewMemory(e.target.value)}
                      placeholder="Напишите ваше воспоминание..."
                      className="w-full p-4 border border-gray-300 rounded-lg resize-vertical min-h-[120px] focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 transition-colors"
                    />
                    
                    {/* Превью прикрепленных файлов */}
                    {attachedMedia.length > 0 && (
                      <div className="mt-4 p-4 bg-gray-50 rounded-lg">
                        <div className="flex items-center justify-between mb-3">
                          <h4 className="text-sm font-medium text-slate-700">
                            Прикреплённые файлы ({attachedMedia.length}/5)
                          </h4>
                        </div>
                        <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                          {attachedMedia.map((file, index) => {
                            const IconComponent = getFileIcon(file);
                            return (
                              <div key={index} className="relative group">
                                <div className="aspect-square bg-white rounded-lg border border-gray-200 overflow-hidden">
                                  {file.type.startsWith('image/') ? (
                                    <img 
                                      src={mediaPreview[index]} 
                                      alt={file.name}
                                      className="w-full h-full object-cover"
                                    />
                                  ) : (
                                    <div className="w-full h-full flex flex-col items-center justify-center p-2">
                                      <IconComponent className="w-8 h-8 text-gray-400 mb-1" />
                                      <span className="text-xs text-gray-500 text-center truncate w-full">
                                        {file.name}
                                      </span>
                                    </div>
                                  )}
                                </div>
                                {/* Кнопка удаления */}
                                <button
                                  type="button"
                                  onClick={() => removeMedia(index)}
                                  className="absolute -top-2 -right-2 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
                                >
                                  <XMarkIcon className="w-4 h-4" />
                                </button>
                              </div>
                            );
                          })}
                        </div>
                      </div>
                    )}
                    
                    <div className="flex justify-between items-center mt-4">
                      <div className="flex gap-2">
                        {/* Кнопка добавления медиафайлов */}
                        <label className="flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors cursor-pointer">
                          <PhotoIcon className="w-5 h-5" />
                          Добавить фото/видео
                          <input
                            type="file"
                            multiple
                            accept="image/*,video/*"
                            onChange={handleMediaUpload}
                            className="hidden"
                            disabled={attachedMedia.length >= 5}
                          />
                        </label>
                        
                        {attachedMedia.length > 0 && (
                          <span className="flex items-center text-sm text-gray-500">
                            {attachedMedia.length}/5 файлов
                          </span>
                        )}
                      </div>
                      
                      <button 
                        type="submit" 
                        disabled={isSubmittingMemory || !newMemory.trim()}
                        className="bg-red-500 hover:bg-red-600 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2"
                      >
                        {isSubmittingMemory ? (
                          <>
                            <div className="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                            Публикуем...
                          </>
                        ) : (
                          <>
                            <PaperAirplaneIcon className="w-5 h-5" />
                            Опубликовать
                          </>
                        )}
                      </button>
                    </div>
                  </form>
                </div>
              ) : (
                /* Блок для неавторизованных пользователей */
                <div className="bg-white rounded-xl shadow-md overflow-hidden mb-4">
                  <div className="px-6 py-4 border-b border-gray-100">
                    <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                      <PlusIcon className="w-5 h-5 text-red-500" />
                      Поделитесь воспоминаниями
                    </h3>
                  </div>
                  
                  <div className="p-6 text-center">
                    <div className="mb-4">
                      <UserIcon className="w-16 h-16 text-gray-300 mx-auto mb-3" />
                      <h4 className="text-lg font-medium text-slate-700 mb-2">
                        Войдите, чтобы оставить воспоминание
                      </h4>
                      <p className="text-gray-500 mb-4">
                        Поделитесь своими воспоминаниями об этом человеке. Войдите в систему или зарегистрируйтесь.
                      </p>
                    </div>
                    
                    <div className="flex flex-col sm:flex-row gap-3 justify-center">
                      <Link 
                        href="/auth/login"
                        className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2"
                      >
                        Войти
                      </Link>
                      <Link 
                        href="/auth/register"
                        className="border border-red-500 text-red-500 hover:bg-red-50 px-6 py-2 rounded-lg transition-colors flex items-center justify-center gap-2"
                      >
                        Регистрация
                      </Link>
                    </div>
                  </div>
                </div>
              )}

              {/* Список воспоминаний */}
              <div className="space-y-4">
                {memories.map((memory) => (
                  <div key={memory.id} className="bg-white rounded-xl shadow-md overflow-hidden">
                    {/* Заголовок воспоминания */}
                    <div className="flex items-center justify-between p-6 pb-0">
                      <div className="flex items-center gap-4">
                        <img 
                          src={memory.author_avatar} 
                          alt={memory.author_name}
                          className="w-12 h-12 rounded-full object-cover"
                        />
                        <div>
                          <h4 className="font-semibold text-slate-700">{memory.author_name}</h4>
                          <p className="text-sm text-gray-500">{formatRussianDate(memory.created_at)}</p>
                        </div>
                      </div>
                      
                      {isAuthenticated && (
                        <div className="relative">
                          <button 
                            onClick={(e) => {
                              e.stopPropagation();
                              toggleDropdown(memory.id);
                            }}
                            className="p-2 text-gray-400 hover:text-gray-600 transition-colors"
                          >
                            <EllipsisHorizontalIcon className="w-5 h-5" />
                          </button>
                          
                          {/* Выпадающее меню - показываем только если есть доступные опции */}
                          {activeDropdown === memory.id && (
                            (user?.id === memory.user_id || isMemorialAdmin || (user?.id !== memory.user_id && !isMemorialAdmin)) && (
                              <div 
                                className="absolute right-0 top-full mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                                onClick={(e) => e.stopPropagation()}
                              >
                                {/* Только для автора воспоминания или админа мемориала */}
                                {(user?.id === memory.user_id || isMemorialAdmin) && (
                                  <>
                                    <button
                                      onClick={() => handleEdit(memory.id)}
                                      className="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                      <PencilIcon className="w-4 h-4" />
                                      Редактировать
                                    </button>
                                    <button
                                      onClick={() => handleDelete(memory.id)}
                                      className="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                    >
                                      <TrashIcon className="w-4 h-4" />
                                      Удалить
                                    </button>
                                  </>
                                )}
                                
                                {/* Пожаловаться (только для НЕ автора и НЕ админа) */}
                                {user?.id !== memory.user_id && !isMemorialAdmin && (
                                  <button
                                    onClick={() => handleReport(memory.id)}
                                    className="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors"
                                  >
                                    <FlagIcon className="w-4 h-4" />
                                    Пожаловаться
                                  </button>
                                )}
                              </div>
                            )
                          )}
                        </div>
                      )}
                    </div>

                    {/* Контент воспоминания */}
                    <div className="px-6 py-4">
                      <p className="text-slate-700 leading-relaxed">{memory.content}</p>
                    </div>

                    {/* Медиафайлы воспоминания */}
                    {memory.media_files && memory.media_files.length > 0 && (
                      <div className="px-6">
                        <div className="grid gap-2 rounded-lg overflow-hidden">
                          {memory.media_files.length === 1 && (
                            // Один файл - полная ширина
                            <div className="w-full">
                              {memory.media_files[0].type === 'image' ? (
                                <img 
                                  src={memory.media_files[0].url} 
                                  alt="Фото воспоминания"
                                  className="w-full h-auto max-h-96 object-cover rounded-lg"
                                />
                              ) : (
                                <video 
                                  src={memory.media_files[0].url}
                                  controls
                                  className="w-full h-auto max-h-96 rounded-lg"
                                >
                                  Ваш браузер не поддерживает видео.
                                </video>
                              )}
                            </div>
                          )}
                          
                          {memory.media_files.length === 2 && (
                            // Два файла - по 50%
                            <div className="grid grid-cols-2 gap-2">
                              {memory.media_files.map((file: any, index: number) => (
                                <div key={index} className="aspect-square">
                                  {file.type === 'image' ? (
                                    <img 
                                      src={file.url} 
                                      alt={`Фото воспоминания ${index + 1}`}
                                      className="w-full h-full object-cover rounded-lg"
                                    />
                                  ) : (
                                    <video 
                                      src={file.url}
                                      controls
                                      className="w-full h-full object-cover rounded-lg"
                                    >
                                      Ваш браузер не поддерживает видео.
                                    </video>
                                  )}
                                </div>
                              ))}
                            </div>
                          )}
                          
                          {memory.media_files.length >= 3 && (
                            // Три и более файлов - мозаика
                            <div className="grid grid-cols-2 gap-2">
                              {/* Первый файл - большой */}
                              <div className="row-span-2">
                                {memory.media_files[0].type === 'image' ? (
                                  <img 
                                    src={memory.media_files[0].url} 
                                    alt="Фото воспоминания 1"
                                    className="w-full h-full object-cover rounded-lg"
                                  />
                                ) : (
                                  <video 
                                    src={memory.media_files[0].url}
                                    controls
                                    className="w-full h-full object-cover rounded-lg"
                                  >
                                    Ваш браузер не поддерживает видео.
                                  </video>
                                )}
                              </div>
                              
                              {/* Остальные файлы - маленькие */}
                              {memory.media_files.slice(1, 5).map((file: any, index: number) => (
                                <div key={index + 1} className="aspect-square relative">
                                  {file.type === 'image' ? (
                                    <img 
                                      src={file.url} 
                                      alt={`Фото воспоминания ${index + 2}`}
                                      className="w-full h-full object-cover rounded-lg"
                                    />
                                  ) : (
                                    <video 
                                      src={file.url}
                                      controls
                                      className="w-full h-full object-cover rounded-lg"
                                    >
                                      Ваш браузер не поддерживает видео.
                                    </video>
                                  )}
                                  
                                  {/* Показываем "+N" для последнего элемента, если файлов больше 5 */}
                                  {index === 3 && memory.media_files.length > 5 && (
                                    <div className="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                      <span className="text-white text-lg font-bold">
                                        +{memory.media_files.length - 4}
                                      </span>
                                    </div>
                                  )}
                                </div>
                              ))}
                            </div>
                          )}
                        </div>
                      </div>
                    )}
                    
                    {/* Старый формат фото (для совместимости) */}
                    {memory.photo_url && !memory.media_files && (
                      <div className="px-6">
                        <img 
                          src={memory.photo_url} 
                          alt="Фото воспоминания"
                          className="w-full rounded-lg"
                        />
                      </div>
                    )}

                    {/* Действия */}
                    <div className="px-6 py-4 border-t border-gray-100">
                      <div className="flex items-center justify-between mb-3">
                        <div className="flex items-center gap-4">
                          <button 
                            onClick={() => handleLike(memory.id)}
                            className={`flex items-center gap-2 transition-colors ${
                              likedMemories.has(memory.id)
                                ? 'text-red-500'
                                : 'text-gray-500 hover:text-red-500'
                            }`}
                          >
                            <HeartIcon className={`w-5 h-5 ${likedMemories.has(memory.id) ? 'fill-current' : ''}`} />
                            {memory.likes}
                          </button>
                          <button 
                            onClick={() => toggleComments(memory.id)}
                            className={`flex items-center gap-2 transition-colors ${
                              showComments[memory.id]
                                ? 'text-blue-500'
                                : 'text-gray-500 hover:text-blue-500'
                            }`}
                          >
                            <ChatBubbleLeftIcon className="w-5 h-5" />
                            {memory.comments}
                          </button>
                        </div>
                        
                        <div className="flex items-center gap-2 text-gray-500 text-sm">
                          <EyeIcon className="w-4 h-4" />
                          {memory.views}
                        </div>
                      </div>

                      {/* Секция комментариев */}
                      {showComments[memory.id] && (
                        <div className="border-t border-gray-100 pt-4">
                          {/* Список комментариев */}
                          {memoryComments[memory.id]?.length > 0 && (
                            <div className="space-y-3 mb-4">
                              {memoryComments[memory.id].map((comment) => (
                                <div key={comment.id} className="flex gap-3">
                                  <img 
                                    src={comment.author_avatar} 
                                    alt={comment.author_name}
                                    className="w-8 h-8 rounded-full object-cover flex-shrink-0"
                                  />
                                  <div className="flex-1 min-w-0">
                                    <div className="bg-gray-100 rounded-lg px-3 py-2">
                                      <p className="font-medium text-sm text-slate-700">{comment.author_name}</p>
                                      <p className="text-slate-700 text-sm">{comment.content}</p>
                                    </div>
                                    <p className="text-xs text-gray-500 mt-1">
                                      {formatRussianDate(comment.created_at)}
                                    </p>
                                  </div>
                                </div>
                              ))}
                            </div>
                          )}

                          {/* Форма добавления комментария */}
                          {isAuthenticated && (
                            <div className="flex gap-3">
                              <img 
                                src={user?.avatar || '/api/placeholder/32/32'} 
                                alt={user?.name || 'Пользователь'}
                                className="w-8 h-8 rounded-full object-cover flex-shrink-0"
                              />
                              <div className="flex-1">
                                <div className="flex gap-2">
                                  <input
                                    type="text"
                                    value={newComment[memory.id] || ''}
                                    onChange={(e) => setNewComment(prev => ({
                                      ...prev,
                                      [memory.id]: e.target.value
                                    }))}
                                    placeholder="Написать комментарий..."
                                    className="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                                    onKeyDown={(e) => {
                                      if (e.key === 'Enter' && !e.shiftKey) {
                                        e.preventDefault();
                                        handleCommentSubmit(memory.id);
                                      }
                                    }}
                                  />
                                  <button
                                    onClick={() => handleCommentSubmit(memory.id)}
                                    disabled={!newComment[memory.id]?.trim()}
                                    className="px-3 py-2 bg-red-500 text-white text-sm rounded-lg hover:bg-red-600 disabled:bg-gray-300 disabled:cursor-not-allowed transition-colors"
                                  >
                                    <PaperAirplaneIcon className="w-4 h-4" />
                                  </button>
                                </div>
                              </div>
                            </div>
                          )}

                          {/* Сообщение для неавторизованных */}
                          {!isAuthenticated && (
                            <div className="text-center py-3 text-gray-500 text-sm">
                              <Link href="/auth/login" className="text-red-500 hover:text-red-600">
                                Войдите
                              </Link>, чтобы оставить комментарий
                            </div>
                          )}
                        </div>
                      )}
                    </div>
                  </div>
                ))}
              </div>
            </section>
            )}

            {/* О человеке */}
            {activeTab === 'about' && (
              <section id="about" className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                    <UserIcon className="w-5 h-5 text-red-500" />
                    О человеке
                  </h3>
                </div>
                
                <div className="p-6 space-y-8">
                  {/* Памятные слова (некролог) */}
                  <div>
                    <h4 className="text-xl font-semibold text-slate-700 mb-4 flex items-center gap-2">
                      <BuildingLibraryIcon className="w-5 h-5 text-red-500" />
                      Памятные слова
                    </h4>
                    <div className="bg-gray-50 rounded-lg p-6">
                      <h2 className="text-2xl font-bold text-slate-700 mb-4">
                        {memorial.first_name} {memorial.middle_name} {memorial.last_name}
                      </h2>
                      <p className="text-lg text-gray-600 mb-6">
                        {new Date(memorial.birth_date).toLocaleDateString('ru-RU')} — {new Date(memorial.death_date).toLocaleDateString('ru-RU')}
                      </p>
                      <div className="prose max-w-none text-slate-700 leading-relaxed">
                        <p className="italic text-lg">
                          {memorial.necrologue || 'С глубоким прискорбием сообщаем о кончине выдающегося человека. Светлая память навсегда останется в наших сердцах.'}
                        </p>
                      </div>
                    </div>
                  </div>

                  {/* Основные факты */}
                  <div>
                    <h4 className="text-xl font-semibold text-slate-700 mb-4">Основные факты</h4>
                    <div className="grid md:grid-cols-3 gap-6">
                      <div className="bg-gray-50 rounded-lg p-4">
                        <h5 className="font-semibold text-slate-700 mb-2">Родился</h5>
                        <p className="text-gray-600">{new Date(memorial.birth_date).toLocaleDateString('ru-RU')}</p>
                        <p className="text-sm text-gray-500 mt-1">Москва, район Сокольники</p>
                        <p className="text-xs text-gray-400 mt-1">В семье слесаря и воспитательницы</p>
                      </div>
                      <div className="bg-gray-50 rounded-lg p-4">
                        <h5 className="font-semibold text-slate-700 mb-2">Образование</h5>
                        <p className="text-gray-600">МГТУ им. Н.Э. Баумана</p>
                        <p className="text-sm text-gray-500 mt-1">1963-1968, красный диплом</p>
                        <p className="text-xs text-gray-400 mt-1">Доктор технических наук (1995)</p>
                      </div>
                      <div className="bg-gray-50 rounded-lg p-4">
                        <h5 className="font-semibold text-slate-700 mb-2">Карьера</h5>
                        <p className="text-gray-600">Главный конструктор</p>
                        <p className="text-sm text-gray-500 mt-1">КБ машиностроения, 55 лет</p>
                        <p className="text-xs text-gray-400 mt-1">От инженера до руководителя</p>
                      </div>
                    </div>
                    
                    {/* Религиозная принадлежность */}
                    {memorial.religion && memorial.religion !== 'none' && (
                      <div className="bg-blue-50 rounded-lg p-4 flex items-center gap-4">
                        <div className="flex items-center justify-center w-12 h-12 rounded-full bg-white shadow-sm">
                          <ReligiousSymbol religion={memorial.religion} size="w-6 h-6" />
                        </div>
                        <div>
                          <h5 className="font-semibold text-slate-700 mb-1">Вероисповедание</h5>
                          <p className="text-gray-600">
                            {memorial.religion === 'orthodox' && 'Православное христианство'}
                            {memorial.religion === 'catholic' && 'Католическое христианство'}
                            {memorial.religion === 'islam' && 'Ислам'}
                            {memorial.religion === 'judaism' && 'Иудаизм'}
                            {memorial.religion === 'buddhism' && 'Буддизм'}
                            {memorial.religion === 'hinduism' && 'Индуизм'}
                            {memorial.religion === 'other' && 'Другое вероисповедание'}
                          </p>
                        </div>
                      </div>
                    )}
                  </div>

                  {/* История жизни */}
                  {memorial.full_biography && (
                    <div>
                      <h4 className="text-xl font-semibold text-slate-700 mb-4">История жизни</h4>
                      <div className="prose max-w-none text-slate-700 leading-relaxed space-y-4">
                        <p><strong>Детство и юность:</strong> {memorial.full_biography} Уже в старших классах школы №425 им. А.С. Пушкина Иван определился с выбором будущей профессии. Учителя отмечали его выдающиеся способности к математике и физике.</p>
                        
                        <p><strong>Студенческие годы (1963-1968):</strong> В Московском государственном техническом университете им. Н.Э. Баумана Иван проявил себя не только как отличный студент, но и как активный общественник. Был старостой группы, участвовал в студенческих конференциях, публиковал первые научные работы. Дипломная работа "Новые подходы к проектированию сварочного оборудования" была отмечена как лучшая на факультете.</p>
                        
                        <p><strong>Начало карьеры (1968-1980):</strong> После окончания университета начал работу в Центральном конструкторском бюро машиностроения младшим инженером-конструктором. Быстро зарекомендовал себя как талантливый специалист с нестандартным мышлением. Уже через 7 лет получил первый патент на изобретение. В 1975 году был назначен ведущим конструктором отдела.</p>
                        
                        <p><strong>Семейная жизнь:</strong> В 1971 году женился на Марии Петровне Смирновой, выпускнице МГУ, филологе. Познакомились на танцах в Доме культуры. Мария Петровна всегда была верной спутницей и поддержкой в карьере. В семье родилось двое детей: сын Алексей (1973) пошел по стопам отца и стал инженером, дочь Елена (1976) выбрала медицину. Дедушка пятерых внуков: Ивана, Петра, Марии, Анны и маленького Артема.</p>
                        
                        <p><strong>Зрелые годы (1980-2010):</strong> Период наиболее активной и плодотворной деятельности. Руководил несколькими крупными проектами, результаты которых используются до сих пор. В 1995 году защитил докторскую диссертацию, в 2005 году был назначен главным конструктором предприятия. Под его руководством было разработано более 30 инновационных проектов.</p>
                        
                        <p><strong>Увлечения и характер:</strong> Всю жизнь увлекался шахматами, достиг звания кандидата в мастера спорта. Был президентом шахматного клуба при КБ, организовывал городские турниры. Большой ценитель классической музыки, особенно любил Баха и Чайковского. Библиотека насчитывала более 3000 томов технической и художественной литературы.</p>
                      </div>
                    </div>
                  )}

                  {/* Семья */}
                  <div>
                    <h4 className="text-xl font-semibold text-slate-700 mb-4">Семья</h4>
                    <div className="bg-blue-50 rounded-lg p-6">
                      <div className="grid md:grid-cols-2 gap-6">
                        <div>
                          <h5 className="font-semibold text-slate-700 mb-3">Супруга</h5>
                          <p className="text-gray-600">Мария Петровна Иванова (урожд. Смирнова)</p>
                          <p className="text-sm text-gray-500">В браке 52 года (1971-2023)</p>
                          <p className="text-xs text-gray-400 mt-1">Филолог, выпускница МГУ, кандидат наук</p>
                        </div>
                        <div>
                          <h5 className="font-semibold text-slate-700 mb-3">Дети</h5>
                          <div className="space-y-2">
                            <div>
                              <p className="text-gray-600">Алексей Иванович (1973)</p>
                              <p className="text-xs text-gray-400">Инженер-конструктор, работает в КБ</p>
                            </div>
                            <div>
                              <p className="text-gray-600">Елена Ивановна (1976)</p>
                              <p className="text-xs text-gray-400">Врач-кардиолог, к.м.н.</p>
                            </div>
                            <p className="text-sm text-gray-500 mt-3 font-medium">Внуки: Иван, Петр, Мария, Анна, Артем</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Характер и увлечения */}
                  <div>
                    <h4 className="text-xl font-semibold text-slate-700 mb-4">Характер и увлечения</h4>
                    <div className="grid md:grid-cols-2 gap-6">
                      <div className="space-y-3">
                        <h5 className="font-semibold text-slate-700">Увлечения</h5>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-red-500 rounded-full"></div>
                          <span className="text-gray-700">Шахматы (кандидат в мастера спорта)</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-red-500 rounded-full"></div>
                          <span className="text-gray-700">Классическая музыка (Бах, Чайковский)</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-red-500 rounded-full"></div>
                          <span className="text-gray-700">Чтение (библиотека 3000+ книг)</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-red-500 rounded-full"></div>
                          <span className="text-gray-700">Садоводство на даче</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-red-500 rounded-full"></div>
                          <span className="text-gray-700">Наставничество молодых специалистов</span>
                        </div>
                      </div>
                      <div className="space-y-3">
                        <h5 className="font-semibold text-slate-700">Черты характера</h5>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                          <span className="text-gray-700">Исключительная порядочность</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                          <span className="text-gray-700">Принципиальность и честность</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                          <span className="text-gray-700">Душевная щедрость</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                          <span className="text-gray-700">Высокая эрудиция</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <div className="w-2 h-2 bg-blue-500 rounded-full"></div>
                          <span className="text-gray-700">Преданность семье и делу</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  {/* Достижения и награды */}
                  <div>
                    <h4 className="text-xl font-semibold text-slate-700 mb-4">Достижения и награды</h4>
                    {memorial.achievements && memorial.achievements.length > 0 ? (
                      <div className="space-y-4">
                        {memorial.achievements.map((achievement: any, index: number) => (
                          <div key={index} className="flex gap-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div className="flex-shrink-0">
                              <div className="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <TrophyIcon className="w-6 h-6 text-red-500" />
                              </div>
                            </div>
                            <div className="flex-1">
                              <div className="flex items-center gap-3 mb-2">
                                <h5 className="font-semibold text-slate-700">{achievement.title}</h5>
                                <span className="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full">
                                  {achievement.year}
                                </span>
                              </div>
                              <p className="text-gray-600 text-sm">{achievement.description}</p>
                            </div>
                          </div>
                        ))}
                        
                        <div className="mt-6 p-4 bg-green-50 rounded-lg">
                          <h5 className="font-semibold text-slate-700 mb-2">Профессиональные достижения</h5>
                          <div className="grid md:grid-cols-2 gap-4">
                            <ul className="text-sm text-gray-600 space-y-1">
                              <li>• Более 30 разработанных проектов</li>
                              <li>• 15 патентов на изобретения</li>
                              <li>• Наставник для 50+ молодых специалистов</li>
                            </ul>
                            <ul className="text-sm text-gray-600 space-y-1">
                              <li>• Участие в 12 международных конференциях</li>
                              <li>• Автор 45+ научных публикаций</li>
                              <li>• Член-корреспондент академии наук</li>
                            </ul>
                          </div>
                        </div>
                      </div>
                    ) : (
                      <div className="text-center py-8 text-gray-500">
                        <TrophyIcon className="w-12 h-12 mx-auto mb-4 text-gray-300" />
                        <p>Достижения пока не добавлены</p>
                      </div>
                    )}
                  </div>
                </div>
              </section>
            )}

            {/* Место захоронения */}
            {activeTab === 'burial' && (
              <section id="burial" className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                    <MapPinIcon className="w-5 h-5 text-red-500" />
                    Место захоронения
                  </h3>
                </div>
                
                <div className="p-6">
                  <div className="grid md:grid-cols-2 gap-6 mb-6">
                    {/* Фото места захоронения */}
                    <div className="bg-gray-100 rounded-lg h-48 flex items-center justify-center">
                      <PhotoIcon className="w-12 h-12 text-gray-400" />
                    </div>

                    {/* Информация */}
                    <div className="space-y-4">
                      <h4 className="text-xl font-semibold text-slate-700">
                        {memorial.burial_place}
                      </h4>
                      
                      {memorial.burial_address && (
                        <p className="flex items-center gap-2 text-gray-600">
                          <MapPinIcon className="w-4 h-4" />
                          {memorial.burial_address}
                        </p>
                      )}
                      
                      {memorial.burial_location && (
                        <p className="flex items-center gap-2 text-gray-600">
                          <BuildingLibraryIcon className="w-4 h-4" />
                          {memorial.burial_location}
                        </p>
                      )}
                      
                      {memorial.burial_coordinates && (
                        <p className="flex items-center gap-2 text-gray-600">
                          <MapIcon className="w-4 h-4" />
                          {memorial.burial_coordinates}
                        </p>
                      )}
                    </div>
                  </div>

                  {/* Карта */}
                  {memorial.burial_coordinates && (
                    <div className="bg-gray-100 rounded-lg h-96 flex items-center justify-center">
                      <div className="text-center text-gray-500">
                        <MapIcon className="w-12 h-12 mx-auto mb-2" />
                        <p>Карта будет здесь</p>
                        <p className="text-sm">Координаты: {memorial.burial_coordinates}</p>
                      </div>
                    </div>
                  )}
                </div>
              </section>
            )}

            {/* Медиа (Фото и Видео) */}
            {activeTab === 'media' && (
              <section id="media" className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                    <PhotoIcon className="w-5 h-5 text-red-500" />
                    Фото и видео
                  </h3>
                </div>
                
                <div className="p-6">
                  {memorial.media ? (
                    <div className="space-y-8">
                      {/* Фотографии */}
                      {memorial.media.photos && memorial.media.photos.length > 0 && (
                        <div>
                          <h4 className="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                            <PhotoIcon className="w-5 h-5" />
                            Фотографии ({memorial.media.photos.length})
                          </h4>
                          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            {memorial.media.photos.map((photo: any) => (
                              <div key={photo.id} className="group cursor-pointer">
                                <div className="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                                  <img 
                                    src={photo.url} 
                                    alt={photo.title}
                                    className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                  />
                                </div>
                                <div className="mt-2">
                                  <p className="text-sm font-medium text-slate-700">{photo.title}</p>
                                  <p className="text-xs text-gray-500">{photo.year}</p>
                                </div>
                              </div>
                            ))}
                          </div>
                        </div>
                      )}

                      {/* Видео */}
                      {memorial.media.videos && memorial.media.videos.length > 0 && (
                        <div>
                          <h4 className="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                            <PlayIcon className="w-5 h-5" />
                            Видео ({memorial.media.videos.length})
                          </h4>
                          <div className="grid md:grid-cols-2 gap-6">
                            {memorial.media.videos.map((video: any) => (
                              <div key={video.id} className="group cursor-pointer">
                                <div className="relative aspect-video bg-gray-100 rounded-lg overflow-hidden">
                                  <img 
                                    src={video.url} 
                                    alt={video.title}
                                    className="w-full h-full object-cover"
                                  />
                                  <div className="absolute inset-0 bg-black bg-opacity-30 group-hover:bg-opacity-40 transition-colors flex items-center justify-center">
                                    <div className="w-12 h-12 bg-white bg-opacity-90 rounded-full flex items-center justify-center">
                                      <PlayIcon className="w-6 h-6 text-slate-700 ml-1" />
                                    </div>
                                  </div>
                                  <div className="absolute bottom-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded">
                                    {video.duration}
                                  </div>
                                </div>
                                <div className="mt-2">
                                  <p className="text-sm font-medium text-slate-700">{video.title}</p>
                                </div>
                              </div>
                            ))}
                          </div>
                        </div>
                      )}
                    </div>
                  ) : (
                    <div className="text-center py-8 text-gray-500">
                      <PhotoIcon className="w-12 h-12 mx-auto mb-4 text-gray-300" />
                      <p>Медиафайлы пока не добавлены</p>
                    </div>
                  )}
                </div>
              </section>
            )}

            {/* Близкие люди */}
            {activeTab === 'people' && (
              <section id="people" className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                    <UserIcon className="w-5 h-5 text-red-500" />
                    Близкие люди
                  </h3>
                </div>
                
                <div className="p-6">
                  {/* Добавить новую связь (только для авторизованных) */}
                  {isAuthenticated && (
                    <div className="mb-6 p-4 bg-gray-50 rounded-lg">
                      <h4 className="font-semibold text-slate-700 mb-3">Добавить связь</h4>
                      <div className="grid md:grid-cols-3 gap-3">
                        <input
                          type="text"
                          placeholder="Имя человека"
                          className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500"
                        />
                        <select className="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-red-500">
                          <option value="">Кем приходился</option>
                          <option value="parent">Отец/Мать</option>
                          <option value="spouse">Супруг(а)</option>
                          <option value="child">Сын/Дочь</option>
                          <option value="sibling">Брат/Сестра</option>
                          <option value="grandchild">Внук/Внучка</option>
                          <option value="friend">Друг</option>
                          <option value="colleague">Коллега</option>
                          <option value="other">Другое</option>
                        </select>
                        <button className="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                          Добавить
                        </button>
                      </div>
                    </div>
                  )}

                  {/* Список близких людей */}
                  <div className="space-y-6">
                    {/* Родители */}
                    <div>
                      <h4 className="font-semibold text-slate-700 mb-4">Родители</h4>
                      <div className="space-y-3">
                        <div className="flex items-center justify-between p-4 bg-indigo-50 rounded-lg">
                          <div className="flex items-center gap-4">
                            <Avatar name="Александр Петрович" size={48} />
                            <div className="flex-1">
                              <p className="font-medium text-slate-700">Александр Петрович Иванов</p>
                              <p className="text-sm text-indigo-600 font-medium">Отец</p>
                              <p className="text-xs text-gray-500">1920 — 1998</p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            {isAuthenticated && (
                              <button className="text-gray-400 hover:text-gray-600">
                                <CogIcon className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </div>

                        <div className="flex items-center justify-between p-4 bg-violet-50 rounded-lg">
                          <div className="flex items-center gap-4">
                            <Avatar name="Анна Ивановна" size={48} />
                            <div className="flex-1">
                              <p className="font-medium text-slate-700">Анна Ивановна Иванова</p>
                              <p className="text-sm text-violet-600 font-medium">Мать</p>
                              <p className="text-xs text-gray-500">1922 — 2005</p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            {isAuthenticated && (
                              <button className="text-gray-400 hover:text-gray-600">
                                <CogIcon className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    {/* Семья */}
                    <div>
                      <h4 className="font-semibold text-slate-700 mb-4">Семья</h4>
                      <div className="space-y-3">
                        {/* Супруга */}
                        <div className="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                          <div className="flex items-center gap-4">
                            <Avatar name="Мария Петровна" size={48} />
                            <div className="flex-1">
                              <p className="font-medium text-slate-700">Мария Петровна Иванова</p>
                              <p className="text-sm text-blue-600 font-medium">Супруга</p>
                              <p className="text-xs text-gray-500">В браке 52 года</p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            {isAuthenticated && (
                              <button className="text-gray-400 hover:text-gray-600">
                                <CogIcon className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </div>

                        {/* Дети */}
                        <div className="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                          <div className="flex items-center gap-4">
                            <Avatar name="Алексей Иванович" size={48} />
                            <div className="flex-1">
                              <p className="font-medium text-slate-700">Алексей Иванович Иванов</p>
                              <p className="text-sm text-green-600 font-medium">Сын</p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            {isAuthenticated && (
                              <button className="text-gray-400 hover:text-gray-600">
                                <CogIcon className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </div>

                        <div className="flex items-center justify-between p-4 bg-pink-50 rounded-lg">
                          <div className="flex items-center gap-4">
                            <Avatar name="Елена Ивановна" size={48} />
                            <div className="flex-1">
                              <p className="font-medium text-slate-700">Елена Ивановна Петрова</p>
                              <p className="text-sm text-pink-600 font-medium">Дочь</p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            {isAuthenticated && (
                              <button className="text-gray-400 hover:text-gray-600">
                                <CogIcon className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </div>
                      </div>
                    </div>

                    {/* Друзья и коллеги */}
                    <div>
                      <h4 className="font-semibold text-slate-700 mb-4">Друзья и коллеги</h4>
                      <div className="space-y-3">
                        <div className="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                          <div className="flex items-center gap-4">
                            <Avatar name="Петр Сергеевич" size={48} />
                            <div className="flex-1">
                              <p className="font-medium text-slate-700">Петр Сергеевич Васильев</p>
                              <p className="text-sm text-purple-600 font-medium">Коллега</p>
                              <p className="text-xs text-gray-500">Работали вместе 25 лет</p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            {isAuthenticated && (
                              <button className="text-gray-400 hover:text-gray-600">
                                <CogIcon className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </div>

                        <div className="flex items-center justify-between p-4 bg-orange-50 rounded-lg">
                          <div className="flex items-center gap-4">
                            <Avatar name="Михаил Андреевич" size={48} />
                            <div className="flex-1">
                              <p className="font-medium text-slate-700">Михаил Андреевич Сидоров</p>
                              <p className="text-sm text-orange-600 font-medium">Друг</p>
                              <p className="text-xs text-gray-500">Дружили с университета</p>
                            </div>
                          </div>
                          <div className="flex items-center gap-2">
                            {isAuthenticated && (
                              <button className="text-gray-400 hover:text-gray-600">
                                <CogIcon className="w-4 h-4" />
                              </button>
                            )}
                          </div>
                        </div>

                        {/* Кнопка показать всех */}
                        <div className="text-center pt-4">
                          <button className="text-blue-600 hover:text-blue-700 transition-colors font-medium">
                            Показать всех (12 человек)
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
            )}

            {/* Статистика */}
            {activeTab === 'statistics' && isAuthenticated && (
              <section id="statistics" className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                    <ChartBarIcon className="w-5 h-5 text-red-500" />
                    Статистика
                  </h3>
                </div>
                
                <div className="p-6">
                  <div className="grid md:grid-cols-3 gap-4 mb-6">
                    <div className="bg-gray-50 rounded-lg p-4 text-center">
                      <div className="text-2xl font-bold text-slate-700">24</div>
                      <div className="text-sm text-gray-500">Воспоминаний</div>
                    </div>
                    <div className="bg-gray-50 rounded-lg p-4 text-center">
                      <div className="text-2xl font-bold text-slate-700">156</div>
                      <div className="text-sm text-gray-500">Просмотров</div>
                    </div>
                    <div className="bg-gray-50 rounded-lg p-4 text-center">
                      <div className="text-2xl font-bold text-slate-700">8</div>
                      <div className="text-sm text-gray-500">Участников</div>
                    </div>
                  </div>
                  <p className="text-gray-600">Здесь будут отображаться подробные аналитические данные о посещениях и взаимодействии с мемориалом.</p>
                </div>
              </section>
            )}

            {/* Настройки */}
            {activeTab === 'settings' && isAuthenticated && (
              <section id="settings" className="bg-white rounded-xl shadow-md overflow-hidden">
                <div className="px-6 py-4 border-b border-gray-100">
                  <h3 className="text-lg font-semibold text-slate-700 flex items-center gap-2">
                    <CogIcon className="w-5 h-5 text-red-500" />
                    Настройки мемориала
                  </h3>
                </div>
                
                <div className="p-6 space-y-6">
                  <div>
                    <h4 className="text-base font-semibold text-slate-700 mb-3">Приватность</h4>
                    <div className="space-y-2">
                      <label className="flex items-center gap-3">
                        <input type="radio" name="privacy" className="text-red-500" defaultChecked />
                        <span>Публичный мемориал</span>
                      </label>
                      <label className="flex items-center gap-3">
                        <input type="radio" name="privacy" className="text-red-500" />
                        <span>Только для семьи и друзей</span>
                      </label>
                      <label className="flex items-center gap-3">
                        <input type="radio" name="privacy" className="text-red-500" />
                        <span>Приватный</span>
                      </label>
                    </div>
                  </div>
                  
                  <div>
                    <h4 className="text-base font-semibold text-slate-700 mb-3">Модерация</h4>
                    <label className="flex items-center gap-3">
                      <input type="checkbox" className="text-red-500" defaultChecked />
                      <span>Проверять воспоминания перед публикацией</span>
                    </label>
                  </div>

                  <div className="pt-4 border-t border-gray-200">
                    <button className="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition-colors">
                      Сохранить настройки
                    </button>
                  </div>
                </div>
              </section>
            )}
          </main>

          {/* Правая боковая панель */}
          <aside className="lg:sticky lg:top-4 h-fit space-y-4">
            {/* Быстрые действия */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden">
              <div className="px-4 py-3 border-b border-gray-100">
                <h3 className="text-sm font-semibold text-slate-700">Быстрые действия</h3>
              </div>
              <div className="p-4 space-y-2">
                {/* Кнопка редактирования только для администратора */}
                {isMemorialAdmin && (
                  <button onClick={handleGoToEdit} className="w-full flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <PencilIcon className="w-4 h-4 text-green-500" />
                    <span className="text-sm text-slate-700">Редактировать</span>
                  </button>
                )}
                
                <button className="w-full flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                  <ShareIcon className="w-4 h-4 text-blue-500" />
                  <span className="text-sm text-slate-700">Поделиться</span>
                </button>
                
                <button className="w-full flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                  <BookOpenIcon className="w-4 h-4 text-red-500" />
                  <span className="text-sm text-slate-700">Оставить воспоминание</span>
                </button>
              </div>
            </div>

            {/* Моя связь с покойным */}
            <div className="bg-white rounded-xl shadow-md overflow-hidden">
              <div className="px-4 py-3 border-b border-gray-100">
                <h3 className="text-sm font-semibold text-slate-700">Кем я был</h3>
                <p className="text-xs text-gray-500 mt-1">для Ивана Ивановича</p>
              </div>
              <div className="p-4">
                {/* Выпадающий список отношений */}
                <div className="space-y-3">
                  <select 
                    value={selectedRelationship}
                    onChange={(e) => setSelectedRelationship(e.target.value)}
                    className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                  >
                    <option value="">Выберите ваше отношение...</option>
                    <option value="family">Родственником</option>
                    <option value="friend">Другом</option>
                    <option value="colleague">Коллегой</option>
                    <option value="neighbor">Соседом</option>
                    <option value="student">Учеником</option>
                    <option value="teacher">Учителем</option>
                    <option value="doctor">Врачом</option>
                    <option value="acquaintance">Знакомым</option>
                    <option value="other">Другое</option>
                  </select>
                  
                  {/* Дополнительное поле для "Родственником" */}
                  {selectedRelationship === 'family' && (
                    <select 
                      value={selectedFamilyRelation}
                      onChange={(e) => setSelectedFamilyRelation(e.target.value)}
                      className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    >
                      <option value="">Укажите степень родства...</option>
                      <option value="son">Сыном</option>
                      <option value="daughter">Дочерью</option>
                      <option value="father">Отцом</option>
                      <option value="mother">Матерью</option>
                      <option value="brother">Братом</option>
                      <option value="sister">Сестрой</option>
                      <option value="grandfather">Дедушкой</option>
                      <option value="grandmother">Бабушкой</option>
                      <option value="grandson">Внуком</option>
                      <option value="granddaughter">Внучкой</option>
                      <option value="uncle">Дядей</option>
                      <option value="aunt">Тётей</option>
                      <option value="nephew">Племянником</option>
                      <option value="niece">Племянницей</option>
                      <option value="cousin">Двоюродным братом/сестрой</option>
                      <option value="husband">Мужем</option>
                      <option value="wife">Женой</option>
                      <option value="son-in-law">Зятем</option>
                      <option value="daughter-in-law">Невесткой</option>
                      <option value="father-in-law">Тестем/Свёкром</option>
                      <option value="mother-in-law">Тёщей/Свекровью</option>
                      <option value="other-family">Другое родство</option>
                    </select>
                  )}
                  
                  {/* Дополнительное поле для "Другое родство" */}
                  {selectedFamilyRelation === 'other-family' && (
                    <input 
                      type="text" 
                      value={customRelationship}
                      onChange={(e) => setCustomRelationship(e.target.value)}
                      placeholder="Укажите родство..."
                      className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  )}
                  
                  {/* Дополнительное поле для "Другое" */}
                  {selectedRelationship === 'other' && (
                    <input 
                      type="text" 
                      value={customRelationship}
                      onChange={(e) => setCustomRelationship(e.target.value)}
                      placeholder="Укажите как именно..."
                      className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500"
                    />
                  )}
                  
                  {/* Поле для комментария (всегда показывается, если выбрано отношение) */}
                  {selectedRelationship && (
                    <textarea 
                      value={relationshipComment}
                      onChange={(e) => setRelationshipComment(e.target.value)}
                      placeholder="Расскажите подробнее о ваших отношениях (необязательно)..."
                      className="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500 resize-vertical min-h-[80px]"
                      rows={3}
                    />
                  )}
                  
                  <button className="w-full bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded-lg transition-colors">
                    Отметить связь
                  </button>
                </div>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </div>
  );
}
