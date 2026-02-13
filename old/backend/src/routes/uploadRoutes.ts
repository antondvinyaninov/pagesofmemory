import { Router } from 'express';
import multer from 'multer';
import path from 'path';
import fs from 'fs';
import sharp from 'sharp';

const router = Router();

// ensure upload directory exists by YYYY/MM
const ensureDir = (dir: string): void => {
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
  }
};

// Multer memory storage for processing via sharp
const upload = multer({ storage: multer.memoryStorage(), limits: { fileSize: 10 * 1024 * 1024 } });

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
    const dir = path.resolve(__dirname, '..', 'uploads', year, month);
    ensureDir(dir);

    const baseName = `${Date.now()}-${Math.random().toString(36).slice(2, 8)}`;
    const filePath = path.join(dir, `${baseName}.webp`);

    // process to webp, resize longest side to 1600, strip metadata
    const pipeline = sharp(req.file.buffer)
      .rotate()
      .resize({ width: 1600, height: 1600, fit: 'inside', withoutEnlargement: true })
      .withMetadata({ exif: undefined, icc: undefined })
      .webp({ quality: 80, effort: 4 });

    await pipeline.toFile(filePath);

    const publicPath = `/uploads/${year}/${month}/${path.basename(filePath)}`;

    res.status(201).json({
      url: publicPath,
      format: 'webp',
      width: 1600,
      message: 'Файл загружен и преобразован',
    });
  } catch (err: any) {
    console.error('Upload error:', err);
    res.status(500).json({ error: 'Ошибка загрузки файла' });
  }
});

export default router;

