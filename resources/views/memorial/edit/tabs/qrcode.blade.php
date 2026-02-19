                        <div x-show="activeTab === 'qrcode'" class="space-y-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <div class="flex items-start gap-3">
                                    <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h4 class="font-semibold text-blue-900 mb-1">QR-код для памятника</h4>
                                        <p class="text-sm text-blue-800">Распечатайте QR-код и прикрепите его к памятнику. Посетители смогут отсканировать его и перейти на страницу мемориала.</p>
                                    </div>
                                </div>
                            </div>

                            @if($memorial->exists)
                            <div class="grid md:grid-cols-2 gap-6">
                                <!-- Превью QR-кода -->
                                <div class="bg-gradient-to-br from-red-50 to-pink-50 border-2 border-red-100 rounded-xl p-6">
                                    <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Превью QR-кода
                                    </h4>
                                    <div class="flex flex-col items-center">
                                        <!-- Карточка с QR-кодом в стиле PNG -->
                                        <div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-sm border-4 border-white">
                                            <!-- Название сверху -->
                                            <div class="text-center mb-6">
                                                <h3 class="text-3xl font-bold text-slate-700">Страница памяти</h3>
                                            </div>
                                            
                                            <!-- QR-код -->
                                            <div class="mb-6">
                                                <div id="qrcode" class="flex items-center justify-center"></div>
                                            </div>
                                            
                                            <!-- Красная кнопка -->
                                            <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white px-6 py-3 rounded-xl mb-4 text-center">
                                                <p class="text-lg font-bold">
                                                    📱 Отсканируйте QR-код
                                                </p>
                                            </div>
                                            
                                            <!-- Текст призыва -->
                                            <div class="text-center space-y-2 mb-4">
                                                <p class="text-base text-gray-700">
                                                    чтобы оставить воспоминание<br>об этом человеке
                                                </p>
                                            </div>
                                            
                                            <!-- URL -->
                                            <div class="text-center">
                                                <p class="text-xs text-gray-400 font-mono">
                                                    {{ parse_url(route('memorial.show', $memorial->id), PHP_URL_HOST) }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Подсказка -->
                                        <div class="mt-4 flex items-center gap-2 text-sm text-gray-600 bg-white px-4 py-2 rounded-full shadow-sm">
                                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Так будет выглядеть ваш QR-код
                                        </div>
                                    </div>
                                </div>

                                <!-- Настройки и действия -->
                                <div class="space-y-4">
                                    <div class="bg-gradient-to-br from-red-500 to-pink-500 rounded-xl p-6 shadow-lg">
                                        <h4 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            Скачать и распечатать
                                        </h4>
                                        <div class="space-y-3">
                                            <button type="button" onclick="downloadQR('png')" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-red-600 rounded-lg transition-all font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Скачать PNG
                                            </button>
                                            <button type="button" onclick="downloadQR('svg')" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-white hover:bg-gray-50 text-red-600 rounded-lg transition-all font-medium shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                </svg>
                                                Скачать SVG
                                            </button>
                                            <button type="button" onclick="printQR()" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-all font-medium border-2 border-white/50 hover:border-white">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                                </svg>
                                                Распечатать
                                            </button>
                                        </div>
                                        <p class="text-white/80 text-xs mt-4 text-center">
                                            💡 Рекомендуем формат SVG для печати
                                        </p>
                                    </div>

                                    <!-- Инструкция по установке -->
                                    <div class="bg-white border-2 border-gray-200 rounded-xl p-6">
                                        <h4 class="text-lg font-semibold text-slate-700 mb-4 flex items-center gap-2">
                                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Инструкция по установке
                                        </h4>
                                        <div class="space-y-4">
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0 w-7 h-7 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold text-sm">1</div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 mb-1">Скачайте или распечатайте</h5>
                                                    <p class="text-sm text-gray-600">Выберите формат и сохраните изображение</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0 w-7 h-7 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold text-sm">2</div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 mb-1">Ламинируйте для защиты</h5>
                                                    <p class="text-sm text-gray-600">Защитите от погодных условий</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0 w-7 h-7 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold text-sm">3</div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 mb-1">Прикрепите к памятнику</h5>
                                                    <p class="text-sm text-gray-600">Используйте водостойкий клей</p>
                                                </div>
                                            </div>
                                            <div class="flex gap-3">
                                                <div class="flex-shrink-0 w-7 h-7 bg-red-100 text-red-600 rounded-full flex items-center justify-center font-bold text-sm">4</div>
                                                <div>
                                                    <h5 class="font-medium text-gray-900 mb-1">Проверьте работу</h5>
                                                    <p class="text-sm text-gray-600">Отсканируйте телефоном</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Скрытый canvas для генерации -->
                            <canvas id="qrCanvas" style="display: none;"></canvas>

                            @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                                <svg class="w-12 h-12 text-yellow-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <h4 class="text-lg font-semibold text-yellow-900 mb-2">Сначала сохраните мемориал</h4>
                                <p class="text-yellow-800">QR-код будет доступен после создания мемориала</p>
                            </div>
                            @endif
                        </div>

                        @if($memorial->exists)
                        <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
                        <script>
                            let qrcode = null;
                            const currentSize = 256; // Фиксированный средний размер
                            const memorialUrl = '{{ route('memorial.show', $memorial->id) }}';

                            function generateQR(size) {
                                const qrcodeDiv = document.getElementById('qrcode');
                                qrcodeDiv.innerHTML = '';
                                
                                qrcode = new QRCode(qrcodeDiv, {
                                    text: memorialUrl,
                                    width: size,
                                    height: size,
                                    colorDark: '#000000',
                                    colorLight: '#ffffff',
                                    correctLevel: QRCode.CorrectLevel.H
                                });
                            }

                            function downloadQR(format) {
                                if (format === 'png') {
                                    const canvas = document.querySelector('#qrcode canvas');
                                    if (canvas) {
                                        // Создаем новый canvas с дополнительным контентом
                                        const finalCanvas = document.createElement('canvas');
                                        const ctx = finalCanvas.getContext('2d');
                                        
                                        // Фиксированные размеры для QR 256px
                                        const qrSize = 256;
                                        const padding = 50;
                                        const headerHeight = 60;
                                        const footerHeight = 150;
                                        const totalWidth = qrSize + (padding * 2);
                                        const totalHeight = qrSize + headerHeight + footerHeight + (padding * 2);
                                        
                                        finalCanvas.width = totalWidth;
                                        finalCanvas.height = totalHeight;
                                        
                                        // Белый фон
                                        ctx.fillStyle = '#ffffff';
                                        ctx.fillRect(0, 0, totalWidth, totalHeight);
                                        
                                        // Название сайта
                                        ctx.fillStyle = '#334155';
                                        ctx.font = 'bold 32px Arial, sans-serif';
                                        ctx.textAlign = 'center';
                                        ctx.textBaseline = 'middle';
                                        ctx.fillText('Страница памяти', totalWidth / 2, padding + 30);
                                        
                                        // QR-код
                                        ctx.drawImage(canvas, padding, padding + headerHeight, qrSize, qrSize);
                                        
                                        // Красная кнопка с текстом
                                        const buttonY = padding + headerHeight + qrSize + 25;
                                        const buttonWidth = totalWidth - (padding * 2);
                                        const buttonHeight = 50;
                                        
                                        // Рисуем кнопку
                                        ctx.fillStyle = '#ef4444';
                                        ctx.beginPath();
                                        ctx.roundRect(padding, buttonY, buttonWidth, buttonHeight, 10);
                                        ctx.fill();
                                        
                                        // Текст на кнопке
                                        ctx.fillStyle = '#ffffff';
                                        ctx.font = 'bold 20px Arial, sans-serif';
                                        ctx.fillText('📱 Отсканируйте QR-код', totalWidth / 2, buttonY + 25);
                                        
                                        // Подзаголовок
                                        ctx.fillStyle = '#475569';
                                        ctx.font = '16px Arial, sans-serif';
                                        ctx.fillText('чтобы оставить воспоминание', totalWidth / 2, buttonY + 70);
                                        ctx.fillText('об этом человеке', totalWidth / 2, buttonY + 92);
                                        
                                        // URL
                                        ctx.fillStyle = '#cbd5e1';
                                        ctx.font = '13px monospace';
                                        const url = '{{ parse_url(route('memorial.show', $memorial->id), PHP_URL_HOST) }}';
                                        ctx.fillText(url, totalWidth / 2, buttonY + 118);
                                        
                                        // Скачиваем
                                        const link = document.createElement('a');
                                        link.download = 'memorial-qr-code.png';
                                        link.href = finalCanvas.toDataURL('image/png', 1.0);
                                        link.click();
                                    }
                                } else if (format === 'svg') {
                                    // Генерируем SVG QR-код с дополнительным контентом
                                    const svg = generateQRSVG(memorialUrl, currentSize);
                                    const blob = new Blob([svg], { type: 'image/svg+xml' });
                                    const url = URL.createObjectURL(blob);
                                    const link = document.createElement('a');
                                    link.download = 'memorial-qr-code.svg';
                                    link.href = url;
                                    link.click();
                                    URL.revokeObjectURL(url);
                                }
                            }

                            function generateQRSVG(text, size) {
                                // Используем библиотеку для генерации QR-кода
                                const qr = qrcodegen.QrCode.encodeText(text, qrcodegen.QrCode.Ecc.HIGH);
                                const border = 4;
                                const moduleSize = size / (qr.size + border * 2);
                                
                                let svg = '<?xml version="1.0" encoding="UTF-8"?>' +
'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 ' + size + ' ' + size + '" stroke="none">' +
'<rect width="100%" height="100%" fill="#ffffff"/>' +
'<path d="';
                                
                                for (let y = 0; y < qr.size; y++) {
                                    for (let x = 0; x < qr.size; x++) {
                                        if (qr.getModule(x, y)) {
                                            const px = (x + border) * moduleSize;
                                            const py = (y + border) * moduleSize;
                                            svg += 'M' + px + ',' + py + 'h' + moduleSize + 'v' + moduleSize + 'h-' + moduleSize + 'z ';
                                        }
                                    }
                                }
                                
                                svg += '" fill="#000000"/></svg>';
                                return svg;
                            }

                            function printQR() {
                                const canvas = document.querySelector('#qrcode canvas');
                                if (canvas) {
                                    const printWindow = window.open('', '_blank');
                                    printWindow.document.write('<html><head><title>QR-код мемориала</title>');
                                    printWindow.document.write('<style>');
                                    printWindow.document.write('body { display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; margin: 0; font-family: Arial, sans-serif; }');
                                    printWindow.document.write('.qr-container { text-align: center; padding: 40px; border: 2px solid #e5e7eb; border-radius: 12px; max-width: 400px; }');
                                    printWindow.document.write('.header { display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 20px; }');
                                    printWindow.document.write('.logo { width: 24px; height: 24px; fill: #ef4444; }');
                                    printWindow.document.write('.site-name { font-size: 18px; font-weight: bold; color: #334155; }');
                                    printWindow.document.write('img { max-width: 100%; height: auto; margin: 20px 0; }');
                                    printWindow.document.write('.call-to-action { margin-top: 20px; }');
                                    printWindow.document.write('.call-to-action p { margin: 8px 0; }');
                                    printWindow.document.write('.main-text { font-size: 14px; font-weight: 600; color: #334155; }');
                                    printWindow.document.write('.sub-text { font-size: 12px; color: #6b7280; }');
                                    printWindow.document.write('.url { font-size: 10px; color: #9ca3af; margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb; word-break: break-all; }');
                                    printWindow.document.write('@media print { body { padding: 20px; } }');
                                    printWindow.document.write('</style></head><body>');
                                    printWindow.document.write('<div class="qr-container">');
                                    printWindow.document.write('<div class="header">');
                                    printWindow.document.write('<svg class="logo" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>');
                                    printWindow.document.write('<span class="site-name">Страницы памяти</span>');
                                    printWindow.document.write('</div>');
                                    printWindow.document.write('<img src="' + canvas.toDataURL() + '" />');
                                    printWindow.document.write('<div class="call-to-action">');
                                    printWindow.document.write('<p class="main-text">Отсканируйте QR-код</p>');
                                    printWindow.document.write('<p class="sub-text">чтобы оставить воспоминание<br>об этом человеке</p>');
                                    printWindow.document.write('<p class="url">{{ route('memorial.show', $memorial->id) }}</p>');
                                    printWindow.document.write('</div>');
                                    printWindow.document.write('</div>');
                                    printWindow.document.write('</body></html>');
                                    printWindow.document.close();
                                    
                                    setTimeout(() => {
                                        printWindow.print();
                                    }, 250);
                                }
                            }

                            // Генерируем QR-код при загрузке страницы
                            document.addEventListener('DOMContentLoaded', function() {
                                if (document.getElementById('qrcode')) {
                                    generateQR(currentSize);
                                }
                            });

                            // Регенерируем QR-код при переключении на вкладку
                            document.addEventListener('alpine:initialized', () => {
                                Alpine.effect(() => {
                                    const activeTab = Alpine.store('activeTab');
                                    if (activeTab === 'qrcode' && document.getElementById('qrcode')) {
                                        setTimeout(() => generateQR(currentSize), 100);
                                    }
                                });
                            });
                        </script>
                        <script src="https://cdn.jsdelivr.net/npm/qrcodegen@1.8.0/js/qrcodegen.min.js"></script>
                        @endif
