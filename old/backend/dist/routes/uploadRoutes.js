"use strict";
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
const express_1 = require("express");
const multer_1 = __importDefault(require("multer"));
const path_1 = __importDefault(require("path"));
const fs_1 = __importDefault(require("fs"));
const sharp_1 = __importDefault(require("sharp"));
const router = (0, express_1.Router)();
// ensure upload directory exists by YYYY/MM
const ensureDir = (dir) => {
    if (!fs_1.default.existsSync(dir)) {
        fs_1.default.mkdirSync(dir, { recursive: true });
    }
};
// Multer memory storage for processing via sharp
const upload = (0, multer_1.default)({ storage: multer_1.default.memoryStorage(), limits: { fileSize: 10 * 1024 * 1024 } });
router.post('/', upload.single('file'), async (req, res) => {
    try {
        if (!req.file) {
            res.status(400).json({ error: 'Файл не найден' });
            return;
        }
        const now = new Date();
        const year = String(now.getFullYear());
        const month = String(now.getMonth() + 1).padStart(2, '0');
        // сохраняем рядом с dist: backend/uploads/YYYY/MM
        const dir = path_1.default.resolve(__dirname, '..', 'uploads', year, month);
        ensureDir(dir);
        const baseName = `${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
        const filePath = path_1.default.join(dir, `${baseName}.webp`);
        // process to webp, resize longest side to 1600, strip metadata
        const pipeline = (0, sharp_1.default)(req.file.buffer)
            .rotate()
            .resize({ width: 1600, height: 1600, fit: 'inside', withoutEnlargement: true })
            .withMetadata({ exif: undefined, icc: undefined })
            .webp({ quality: 80, effort: 4 });
        await pipeline.toFile(filePath);
        const publicPath = `/uploads/${year}/${month}/${path_1.default.basename(filePath)}`;
        res.status(201).json({
            url: publicPath,
            format: 'webp',
            width: 1600,
            message: 'Файл загружен и преобразован',
        });
    }
    catch (err) {
        console.error('Upload error:', err);
        res.status(500).json({ error: 'Ошибка загрузки файла' });
    }
});
exports.default = router;
//# sourceMappingURL=uploadRoutes.js.map