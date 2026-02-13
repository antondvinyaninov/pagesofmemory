import type { NextRequest } from 'next/server';

// Локальный плейсхолдер, чтобы не получать 404 и не зависеть от внешних сервисов
export async function GET(_req: NextRequest, context: { params: { w?: string; h?: string } }) {
  const width = Math.max(1, Number(context.params.w ?? 300) || 300);
  const height = Math.max(1, Number(context.params.h ?? 300) || 300);

  const bg = '#e5e7eb'; // gray-200

  const svg = `<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
  <rect width="100%" height="100%" fill="${bg}"/>
</svg>`;

  return new Response(svg, {
    status: 200,
    headers: {
      'Content-Type': 'image/svg+xml; charset=utf-8',
      'Cache-Control': 'public, max-age=3600',
    },
  });
}


