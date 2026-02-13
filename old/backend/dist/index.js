"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = __importDefault(require("express"));
const cors_1 = __importDefault(require("cors"));
const helmet_1 = __importDefault(require("helmet"));
const path_1 = __importDefault(require("path"));
const config_1 = require("./config");
const authRoutes_1 = __importDefault(require("./routes/authRoutes"));
const uploadRoutes_1 = __importDefault(require("./routes/uploadRoutes"));
/**
 * Создание Express приложения
 */
const app = (0, express_1.default)();
/**
 * Middleware для безопасности
 */
app.use((0, helmet_1.default)());
// Разрешаем встраивание ресурсов (изображений) с другого origin (frontend :3000)
app.use(helmet_1.default.crossOriginResourcePolicy({ policy: 'cross-origin' }));
/**
 * Настройка CORS
 */
app.use((0, cors_1.default)({
    origin: config_1.config.corsOrigin,
    credentials: true
}));
/**
 * Middleware для парсинга JSON
 */
app.use(express_1.default.json({ limit: '10mb' }));
app.use(express_1.default.urlencoded({ extended: true, limit: '10mb' }));
/**
 * Статика для загруженных файлов
 * Используем путь относительно сборки (dist), чтобы избежать зависимостей от process.cwd()
 */
const uploadsDir = path_1.default.resolve(__dirname, '..', 'uploads');
app.use('/uploads', express_1.default.static(uploadsDir));
// В dev (ts-node-dev) файлы сохраняются в backend/src/uploads, поэтому также раздаём оттуда
const uploadsDevDir = path_1.default.resolve(__dirname, 'uploads');
app.use('/uploads', express_1.default.static(uploadsDevDir));
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
app.use('/api/auth', authRoutes_1.default);
app.use('/api/uploads', uploadRoutes_1.default);
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
app.use((err, _req, res, _next) => {
    console.error('Ошибка сервера:', err);
    res.status(500).json({
        error: 'Внутренняя ошибка сервера',
        message: config_1.config.nodeEnv === 'development' ? err.message : 'Что-то пошло не так'
    });
});
/**
 * Запуск сервера
 */
const startServer = () => {
    try {
        app.listen(config_1.config.port, () => {
            console.log(`🚀 Сервер запущен на порту ${config_1.config.port}`);
            console.log(`📱 Режим: ${config_1.config.nodeEnv}`);
            console.log(`🌐 CORS разрешен для: ${config_1.config.corsOrigin}`);
            console.log(`📊 Health check: http://localhost:${config_1.config.port}/health`);
        });
    }
    catch (error) {
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
//# sourceMappingURL=index.js.map