import express from 'express';
import cors from 'cors';
import helmet from 'helmet';
import path from 'path';
import { config } from './config';
import authRoutes from './routes/authRoutes';
import uploadRoutes from './routes/uploadRoutes';

/**
 * Создание Express приложения
 */
const app = express();

/**
 * Middleware для безопасности
 */
app.use(helmet());
// Разрешаем встраивание ресурсов (изображений) с другого origin (frontend :3000)
app.use(helmet.crossOriginResourcePolicy({ policy: 'cross-origin' }));

/**
 * Настройка CORS
 */
app.use(cors({
  origin: config.corsOrigin,
  credentials: true
}));

/**
 * Middleware для парсинга JSON
 */
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

/**
 * Статика для загруженных файлов
 * Используем путь относительно сборки (dist), чтобы избежать зависимостей от process.cwd()
 */
const uploadsDir = path.resolve(__dirname, '..', 'uploads');
app.use('/uploads', express.static(uploadsDir));
// В dev (ts-node-dev) файлы сохраняются в backend/src/uploads, поэтому также раздаём оттуда
const uploadsDevDir = path.resolve(__dirname, 'uploads');
app.use('/uploads', express.static(uploadsDevDir));

/**
 * Базовый маршрут для проверки здоровья сервера
 */
app.get('/health', (_req, res) => {
  res.status(200).json({
    status: 'OK',
    message: 'Сервер работает',
    timestamp: new Date().toISOString()
  });
});

/**
 * API маршруты
 */
app.use('/api/auth', authRoutes);
app.use('/api/uploads', uploadRoutes);

/**
 * Обработчик для несуществующих маршрутов
 */
app.use('*', (req, res) => {
  res.status(404).json({
    error: 'Маршрут не найден',
    path: req.originalUrl
  });
});

/**
 * Глобальный обработчик ошибок
 */
app.use((err: Error, _req: express.Request, res: express.Response, _next: express.NextFunction) => {
  console.error('Ошибка сервера:', err);
  res.status(500).json({
    error: 'Внутренняя ошибка сервера',
    message: config.nodeEnv === 'development' ? err.message : 'Что-то пошло не так'
  });
});

/**
 * Запуск сервера
 */
const startServer = (): void => {
  try {
    app.listen(config.port, () => {
      console.log(`🚀 Сервер запущен на порту ${config.port}`);
      console.log(`📱 Режим: ${config.nodeEnv}`);
      console.log(`🌐 CORS разрешен для: ${config.corsOrigin}`);
      console.log(`📊 Health check: http://localhost:${config.port}/health`);
    });
  } catch (error) {
    console.error('Ошибка запуска сервера:', error);
    process.exit(1);
  }
};

/**
 * Обработка сигналов завершения работы
 */
process.on('SIGTERM', () => {
  console.log('Получен сигнал SIGTERM. Завершение работы сервера...');
  process.exit(0);
});

process.on('SIGINT', () => {
  console.log('Получен сигнал SIGINT. Завершение работы сервера...');
  process.exit(0);
});

// Запуск сервера
startServer();
