<script>
function photoUpload() {
    return {
        previewUrl: '{{ $memorial->photo ? asset('storage/' . $memorial->photo) : '' }}',
        
        init() {
            // Если есть существующее фото, показываем его
            if (this.previewUrl) {
                console.log('Загружено существующее фото:', this.previewUrl);
            }
        },
        
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.previewUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}

function educationList() {
    const oldEducation = @json(old('education'));
    let initialEducations = [];

    if (Array.isArray(oldEducation)) {
        initialEducations = oldEducation;
    } else {
        const savedEducation = @json($memorial->education ?? '');
        const savedEducationDetails = @json($memorial->education_details ?? '');

        if (typeof savedEducation === 'string' && savedEducation.trim() !== '') {
            initialEducations = [{
                name: savedEducation,
                details: typeof savedEducationDetails === 'string' ? savedEducationDetails : ''
            }];
        }
    }

    return {
        educations: initialEducations.slice(0, 5),
        
        addEducation() {
            if (this.educations.length < 5) {
                this.educations.push({
                    name: '',
                    details: ''
                });
            }
        },
        
        removeEducation(index) {
            this.educations.splice(index, 1);
        }
    }
}

function careerList() {
    const oldCareer = @json(old('career'));
    let initialCareers = [];

    if (Array.isArray(oldCareer)) {
        initialCareers = oldCareer;
    } else {
        const savedCareer = @json($memorial->career ?? '');
        const savedCareerDetails = @json($memorial->career_details ?? '');

        if (typeof savedCareer === 'string' && savedCareer.trim() !== '') {
            initialCareers = [{
                position: savedCareer,
                details: typeof savedCareerDetails === 'string' ? savedCareerDetails : ''
            }];
        }
    }

    return {
        careers: initialCareers.slice(0, 5),
        
        addCareer() {
            if (this.careers.length < 5) {
                this.careers.push({
                    position: '',
                    details: ''
                });
            }
        },
        
        removeCareer(index) {
            this.careers.splice(index, 1);
        }
    }
}

function normalizeExistingDocumentFiles(initialFiles = []) {
    if (!Array.isArray(initialFiles)) {
        return [];
    }

    return initialFiles
        .filter((item) => item && typeof item === 'object' && typeof item.path === 'string' && item.path.trim() !== '')
        .map((item) => {
            const path = item.path.trim();
            const title = typeof item.title === 'string' ? item.title : '';
            const isPdf = item.isPdf === true || /\.pdf$/i.test(path);
            const url = typeof item.url === 'string' ? item.url : '';

            return {
                title,
                preview: !isPdf ? url : null,
                isPdf,
                path
            };
        });
}

function achievementsList(initialFiles = []) {
    return {
        files: normalizeExistingDocumentFiles(initialFiles),
        
        addAchievementFile() {
            const index = this.files.length;
            this.files.push({
                title: '',
                preview: null,
                isPdf: false,
                path: null
            });
            
            // Автоматически открываем диалог выбора файла
            this.$nextTick(() => {
                document.getElementById('achievement_file_' + index).click();
            });
        },
        
        removeFile(index) {
            this.files.splice(index, 1);
        },
        
        handleFilePreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                if (!this.files[index]?.path) {
                    this.removeFile(index);
                }
                return;
            }
            
            // Проверяем тип файла
            if (file.type === 'application/pdf') {
                this.files[index].isPdf = true;
                this.files[index].preview = null;
                this.files[index].path = null;
            } else if (file.type.startsWith('image/')) {
                this.files[index].isPdf = false;
                this.files[index].path = null;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.files[index].preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

function militaryConflicts(initialCustomConflicts = []) {
    const customConflicts = Array.isArray(initialCustomConflicts)
        ? initialCustomConflicts
            .filter((value) => typeof value === 'string' && value.trim() !== '')
            .map((value) => ({ name: value.trim() }))
        : [];

    return {
        customConflicts,
        
        addCustomConflict() {
            this.customConflicts.push({
                name: ''
            });
        },
        
        removeConflict(index) {
            this.customConflicts.splice(index, 1);
        }
    }
}

function militaryFilesList(initialFiles = []) {
    return {
        files: normalizeExistingDocumentFiles(initialFiles),
        
        addFile() {
            const index = this.files.length;
            this.files.push({
                title: '',
                preview: null,
                isPdf: false,
                path: null
            });
            
            // Автоматически открываем диалог выбора файла
            this.$nextTick(() => {
                document.getElementById('military_file_' + index).click();
            });
        },
        
        removeFile(index) {
            this.files.splice(index, 1);
        },
        
        handleFilePreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                if (!this.files[index]?.path) {
                    this.removeFile(index);
                }
                return;
            }
            
            // Проверяем тип файла
            if (file.type === 'application/pdf') {
                this.files[index].isPdf = true;
                this.files[index].preview = null;
                this.files[index].path = null;
            } else if (file.type.startsWith('image/')) {
                this.files[index].isPdf = false;
                this.files[index].path = null;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.files[index].preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    }
}

// Функция для замены сокращений в названиях регионов
function expandRegionAbbreviations(text) {
    if (!text) return text;
    
    const replacements = {
        ' Респ': ' Республика',
        ' обл': ' область',
        ' край': ' край',
        ' АО': ' автономный округ',
        ' Аобл': ' автономная область',
        ' г': ' город'
    };
    
    let result = text;
    for (const [abbr, full] of Object.entries(replacements)) {
        result = result.replace(new RegExp(abbr + '(?![а-яА-Я])', 'g'), full);
    }
    
    return result;
}

function birthPlaceAutocomplete() {
    return {
        suggestions: [],
        showSuggestions: false,
        selectedCity: '{{ old('birth_place', $memorial->birth_place ?? '') }}',
        
        init() {
            console.log('=== birthPlaceAutocomplete ИНИЦИАЛИЗИРОВАН ===');
            const inputField = document.getElementById('birth_place_input');
            console.log('Поле birth_place_input найдено:', inputField);
            console.log('Начальное значение selectedCity:', this.selectedCity);
            
            // Инициализируем видимое поле значением из БД
            if (inputField && this.selectedCity) {
                inputField.value = this.selectedCity;
                console.log('Установлено значение из БД в видимое поле:', this.selectedCity);
            }
        },
        
        async searchCity(query) {
            console.log('searchCity вызван, query:', query);
            console.log('selectedCity перед поиском:', this.selectedCity);
            
            if (query.length < 2) {
                this.suggestions = [];
                console.log('Запрос слишком короткий, очищаем подсказки');
                return;
            }
            
            try {
                console.log('Отправляем запрос в DaData...');
                const response = await fetch('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Token 300ba9e25ef32f0d6ea7c41826b2255b138e19e2'
                    },
                    body: JSON.stringify({
                        query: query,
                        from_bound: { value: 'city' },
                        to_bound: { value: 'city' },
                        locations: [{ country: '*' }]
                    })
                });
                
                const data = await response.json();
                console.log('Получены подсказки от DaData:', data.suggestions);
                this.suggestions = data.suggestions;
                this.showSuggestions = true;
                console.log('showSuggestions установлен в true');
            } catch (error) {
                console.error('Ошибка поиска города:', error);
            }
        },
        
        selectCity(suggestion) {
            console.log('selectCity вызван, suggestion:', suggestion);
            const city = suggestion.data.city || suggestion.data.settlement;
            const region = suggestion.data.region_with_type;
            const fullAddress = expandRegionAbbreviations(`${city}, ${region}`);
            
            console.log('Формируем адрес:', fullAddress);
            document.getElementById('birth_place_input').value = fullAddress;
            this.selectedCity = fullAddress;
            this.showSuggestions = false;
            
            console.log('Значение установлено:');
            console.log('  - birth_place_input.value:', document.getElementById('birth_place_input').value);
            console.log('  - selectedCity:', this.selectedCity);
            console.log('  - скрытое поле birth_place:', document.querySelector('input[name="birth_place"]')?.value);
        }
    }
}

function burialPhotos() {
    return {
        photos: [],
        existingPhotos: @json($memorial->burial_photos ?? []),
        s3Endpoint: '{{ config('filesystems.disks.s3.endpoint') }}',
        s3Bucket: '{{ config('filesystems.disks.s3.bucket') }}',
        
        init() {
            console.log('burialPhotos инициализирован');
            console.log('Существующие фото из БД:', this.existingPhotos);
            console.log('S3 Endpoint:', this.s3Endpoint);
            console.log('S3 Bucket:', this.s3Bucket);
            
            // Загружаем существующие фото
            if (this.existingPhotos && this.existingPhotos.length > 0) {
                this.existingPhotos.forEach((photoPath) => {
                    // Формируем полный URL для S3
                    let photoUrl;
                    if (photoPath.startsWith('http')) {
                        photoUrl = photoPath;
                    } else {
                        // Убираем начальный слеш если есть
                        const cleanPath = photoPath.startsWith('/') ? photoPath.substring(1) : photoPath;
                        photoUrl = `${this.s3Endpoint}/${this.s3Bucket}/${cleanPath}`;
                    }
                    
                    console.log('Загружаем фото:', photoUrl);
                    
                    this.photos.push({
                        preview: photoUrl,
                        existing: true,
                        url: photoPath // Сохраняем путь, а не URL
                    });
                });
                console.log('Загружено фото:', this.photos.length);
            }
        },
        
        addPhoto() {
            const index = this.photos.length;
            this.photos.push({
                preview: null,
                existing: false
            });
            
            // Автоматически открываем диалог выбора файла
            this.$nextTick(() => {
                document.getElementById('burial_photo_' + index).click();
            });
        },
        
        removePhoto(index) {
            console.log('Удаление фото:', index, this.photos[index]);
            this.photos.splice(index, 1);
        },
        
        handlePhotoPreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                this.removePhoto(index);
                return;
            }
            
            // Проверяем размер файла (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер: 10MB');
                this.removePhoto(index);
                return;
            }
            
            console.log('Обработка нового фото:', file.name);
            
            // Создаем превью
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photos[index].preview = e.target.result;
                console.log('Превью создано для индекса:', index);
            };
            reader.readAsDataURL(file);
        }
    }
}

function burialCityAutocomplete() {
    return {
        suggestions: [],
        showSuggestions: false,
        selectedCity: '{{ old('burial_city', $memorial->burial_city ?? '') }}',
        
        init() {
            // Инициализируем видимое поле значением из БД
            const inputField = document.getElementById('burial_city_input');
            if (inputField && this.selectedCity) {
                inputField.value = this.selectedCity;
            }
        },
        
        async searchCity(query) {
            if (query.length < 2) {
                this.suggestions = [];
                return;
            }
            
            try {
                const response = await fetch('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Token 300ba9e25ef32f0d6ea7c41826b2255b138e19e2'
                    },
                    body: JSON.stringify({
                        query: query,
                        from_bound: { value: 'city' },
                        to_bound: { value: 'city' },
                        locations: [{ country: '*' }]
                    })
                });
                
                const data = await response.json();
                this.suggestions = data.suggestions;
                this.showSuggestions = true;
            } catch (error) {
                console.error('Ошибка поиска города:', error);
            }
        },
        
        selectCity(suggestion) {
            const city = suggestion.data.city || suggestion.data.settlement;
            const region = suggestion.data.region_with_type;
            const fullAddress = expandRegionAbbreviations(`${city}, ${region}`);
            
            document.getElementById('burial_city_input').value = fullAddress;
            this.selectedCity = fullAddress;
            this.showSuggestions = false;
        }
    }
}
</script>

<!-- Яндекс.Карты API -->
<script src="https://api-maps.yandex.ru/2.1/?apikey={{ env('YANDEX_MAPS_API_KEY') }}&lang=ru_RU" type="text/javascript"></script>

<script type="text/javascript">
    var myMap, myPlacemark;
    
    function burialMap() {
        return {
            mapVisible: false,
            mapInitialized: false,
            latitude: {{ old('burial_latitude', $memorial->burial_latitude ?? 55.751244) }},
            longitude: {{ old('burial_longitude', $memorial->burial_longitude ?? 37.618423) }},
            
            init() {
                // Карта всегда скрыта по умолчанию
                console.log('burialMap инициализирован, карта скрыта');
            },
            
            showMap() {
                this.mapVisible = true;
                
                // Инициализируем карту только один раз
                if (!this.mapInitialized) {
                    ymaps.ready(() => {
                        this.initMap();
                    });
                } else {
                    // Если карта уже инициализирована, центрируем по городу
                    this.centerMapByCity();
                }
            },
            
            hideMap() {
                this.mapVisible = false;
            },
            
            initMap() {
                const self = this;
                const hasCoords = {{ $memorial->burial_latitude ? 'true' : 'false' }};
                
                this.mapInitialized = true;
                
                myMap = new ymaps.Map("burial-map", {
                    center: [this.latitude, this.longitude],
                    zoom: 12,
                    controls: ['zoomControl', 'searchControl', 'typeSelector', 'fullscreenControl']
                });

                // Добавляем кнопку геолокации
                const geolocationButton = new ymaps.control.Button({
                    data: {
                        content: '📍 Где я',
                        title: 'Определить моё местоположение'
                    },
                    options: {
                        selectOnClick: false,
                        maxWidth: 150
                    }
                });
                
                geolocationButton.events.add('click', function() {
                    console.log('Кнопка геолокации нажата');
                    if (navigator.geolocation) {
                        console.log('Запрашиваем геолокацию...');
                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                const coords = [position.coords.latitude, position.coords.longitude];
                                console.log('Геолокация получена:', coords);
                                
                                // Центрируем карту на текущем местоположении
                                myMap.setCenter(coords, 16);
                                
                                // Ставим метку
                                self.latitude = coords[0];
                                self.longitude = coords[1];
                                
                                if (myPlacemark) {
                                    myPlacemark.geometry.setCoordinates(coords);
                                } else {
                                    myPlacemark = createPlacemark(coords);
                                    myMap.geoObjects.add(myPlacemark);
                                }
                                
                                console.log('Метка установлена на текущем местоположении');
                            },
                            function(error) {
                                console.error('Ошибка геолокации:', error);
                                console.log('Код ошибки:', error.code, 'Сообщение:', error.message);
                                
                                // Пробуем определить местоположение через Яндекс.Карты
                                console.log('Пытаемся определить местоположение через Яндекс.Карты...');
                                ymaps.geolocation.get({
                                    provider: 'yandex',
                                    mapStateAutoApply: true
                                }).then(function(result) {
                                    const coords = result.geoObjects.position;
                                    console.log('Местоположение определено через Яндекс:', coords);
                                    
                                    myMap.setCenter(coords, 16);
                                    
                                    self.latitude = coords[0];
                                    self.longitude = coords[1];
                                    
                                    if (myPlacemark) {
                                        myPlacemark.geometry.setCoordinates(coords);
                                    } else {
                                        myPlacemark = createPlacemark(coords);
                                        myMap.geoObjects.add(myPlacemark);
                                    }
                                    
                                    console.log('Метка установлена (через Яндекс)');
                                }).catch(function(err) {
                                    console.error('Ошибка определения через Яндекс:', err);
                                    alert('Не удалось определить ваше местоположение. Попробуйте:\n1. Разрешить доступ к геолокации в настройках браузера\n2. Включить службы геолокации в системе\n3. Использовать HTTPS соединение');
                                });
                            },
                            {
                                enableHighAccuracy: false,
                                timeout: 5000,
                                maximumAge: 60000
                            }
                        );
                    } else {
                        console.error('Геолокация не поддерживается браузером');
                        alert('Ваш браузер не поддерживает геолокацию');
                    }
                });
                
                myMap.controls.add(geolocationButton, {
                    float: 'right'
                });

                // Если есть сохраненные координаты, создаем метку
                if (hasCoords) {
                    myPlacemark = createPlacemark([this.latitude, this.longitude]);
                    myMap.geoObjects.add(myPlacemark);
                } else {
                    // Центрируем по городу захоронения
                    this.centerMapByCity();
                }

                myMap.events.add('click', function (e) {
                    var coords = e.get('coords');
                    
                    // Обновляем координаты в Alpine
                    self.latitude = coords[0];
                    self.longitude = coords[1];
                    
                    // Если метка уже создана – просто передвигаем ее
                    if (myPlacemark) {
                        myPlacemark.geometry.setCoordinates(coords);
                    }
                    // Если нет – создаем.
                    else {
                        myPlacemark = createPlacemark(coords);
                        myMap.geoObjects.add(myPlacemark);
                    }
                });
            },
            
            centerMapByCity() {
                console.log('=== centerMapByCity вызван ===');
                const burialCityInput = document.getElementById('burial_city_input')?.value || '';
                const burialCityHidden = document.querySelector('input[name="burial_city"]')?.value || '';
                
                console.log('burial_city_input значение:', burialCityInput);
                console.log('burial_city (скрытое поле) значение:', burialCityHidden);
                
                const burialCity = burialCityInput || burialCityHidden;
                console.log('Используем город для центрирования:', burialCity);
                
                if (burialCity.length > 0) {
                    console.log('Отправляем запрос геокодирования для:', burialCity);
                    ymaps.geocode(burialCity, {
                        results: 1
                    }).then(function(res) {
                        const firstGeoObject = res.geoObjects.get(0);
                        console.log('Результат геокодирования:', firstGeoObject);
                        if (firstGeoObject) {
                            const coords = firstGeoObject.geometry.getCoordinates();
                            console.log('Координаты найдены:', coords);
                            myMap.setCenter(coords, 12);
                            console.log('Карта центрирована');
                        } else {
                            console.log('Геообъект не найден');
                        }
                    }).catch(function(error) {
                        console.error('Ошибка геокодирования:', error);
                    });
                } else {
                    console.log('Город не указан, центрирование пропущено');
                }
            },
            
            async searchCemetery(query) {
                if (query.length < 3) return;
                if (!this.mapInitialized) return;
                
                const self = this;
                
                try {
                    ymaps.geocode(query + ' кладбище', {
                        results: 1
                    }).then(function(res) {
                        const firstGeoObject = res.geoObjects.get(0);
                        if (firstGeoObject) {
                            const coords = firstGeoObject.geometry.getCoordinates();
                            self.latitude = coords[0];
                            self.longitude = coords[1];
                            myMap.setCenter(coords, 16);
                            
                            // Ставим метку
                            if (myPlacemark) {
                                myPlacemark.geometry.setCoordinates(coords);
                            } else {
                                myPlacemark = createPlacemark(coords);
                                myMap.geoObjects.add(myPlacemark);
                            }
                        }
                    });
                } catch (error) {
                    console.error('Ошибка поиска кладбища:', error);
                }
            }
        }
    }
    
    function createPlacemark(coords) {
        // Формируем текст для метки
        const fullName = '{{ $memorial->last_name }} {{ $memorial->first_name }} {{ $memorial->middle_name }}';
        const burialPlace = document.getElementById('burial_place')?.value || '';
        const burialLocation = document.getElementById('burial_location')?.value || '';
        
        let caption = fullName;
        if (burialPlace) {
            caption += '\n' + burialPlace;
        }
        if (burialLocation) {
            caption += '\n' + burialLocation;
        }
        
        console.log('Создаем метку с текстом:', caption);
        
        return new ymaps.Placemark(coords, {
            iconCaption: caption,
            balloonContent: `<strong>${fullName}</strong><br>${burialPlace}<br>${burialLocation}`
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        });
    }
</script>

<script>
function biographyEditor() {
    return {
        editor: null,
        editorFullscreen: null,
        isFullscreen: false,
        
        init() {
            // Ждем загрузки Quill
            if (typeof Quill === 'undefined') {
                setTimeout(() => this.init(), 100);
                return;
            }
            
            this.editor = new Quill('#biography-editor', {
                theme: 'snow',
                placeholder: 'Расскажите о жизни человека, его достижениях, характере, увлечениях...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'header': [1, 2, 3, false] }],
                        ['clean']
                    ]
                }
            });
            
            // Загружаем существующий контент
            const content = this.$refs.textarea.value;
            if (content) {
                this.editor.root.innerHTML = content;
            }
            
            // Синхронизируем с textarea при изменении
            this.editor.on('text-change', () => {
                this.$refs.textarea.value = this.editor.root.innerHTML;
            });
        },
        
        toggleFullscreen() {
            this.isFullscreen = !this.isFullscreen;
            
            if (this.isFullscreen) {
                // Создаем fullscreen редактор
                this.$nextTick(() => {
                    this.editorFullscreen = new Quill('#biography-editor-fullscreen', {
                        theme: 'snow',
                        placeholder: 'Расскажите о жизни человека, его достижениях, характере, увлечениях...',
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                [{ 'header': [1, 2, 3, false] }],
                                ['clean']
                            ]
                        }
                    });
                    
                    // Копируем контент из основного редактора
                    this.editorFullscreen.root.innerHTML = this.editor.root.innerHTML;
                    
                    // Синхронизируем изменения
                    this.editorFullscreen.on('text-change', () => {
                        this.editor.root.innerHTML = this.editorFullscreen.root.innerHTML;
                        this.$refs.textarea.value = this.editorFullscreen.root.innerHTML;
                    });
                });
            } else {
                // Копируем контент обратно при закрытии
                if (this.editorFullscreen) {
                    this.editor.root.innerHTML = this.editorFullscreen.root.innerHTML;
                    this.$refs.textarea.value = this.editor.root.innerHTML;
                }
            }
        }
    }
}

function mediaPhotos() {
    const existingPhotos = @json($memorial->media_photos ?? []);

    return {
        photos: [],
        existingPhotos,
        s3Endpoint: '{{ config('filesystems.disks.s3.endpoint') }}',
        s3Bucket: '{{ config('filesystems.disks.s3.bucket') }}',

        init() {
            if (!Array.isArray(this.existingPhotos)) {
                return;
            }

            this.existingPhotos
                .filter((photoPath) => typeof photoPath === 'string' && photoPath.trim() !== '')
                .forEach((photoPath) => {
                    let photoUrl = photoPath;
                    if (!photoPath.startsWith('http')) {
                        const cleanPath = photoPath.startsWith('/') ? photoPath.substring(1) : photoPath;
                        photoUrl = `${this.s3Endpoint}/${this.s3Bucket}/${cleanPath}`;
                    }

                    this.photos.push({
                        preview: photoUrl,
                        existing: true,
                        url: photoPath
                    });
                });
        },
        
        addPhoto() {
            if (this.photos.length < 5) {
                const index = this.photos.length;
                this.photos.push({
                    preview: null,
                    existing: false
                });
                
                // Открываем диалог выбора файла
                this.$nextTick(() => {
                    document.getElementById('media_photo_' + index).click();
                });
            }
        },
        
        removePhoto(index) {
            this.photos.splice(index, 1);
        },
        
        handlePhotoPreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                this.removePhoto(index);
                return;
            }

            if (file.size > 10 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер: 10MB');
                this.removePhoto(index);
                return;
            }
            
            const reader = new FileReader();
            reader.onload = (e) => {
                this.photos[index].preview = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }
}

function mediaVideos() {
    const existingVideos = @json($memorial->media_videos ?? []);

    return {
        videos: [],
        existingVideos,
        s3Endpoint: '{{ config('filesystems.disks.s3.endpoint') }}',
        s3Bucket: '{{ config('filesystems.disks.s3.bucket') }}',

        init() {
            if (!Array.isArray(this.existingVideos)) {
                return;
            }

            this.existingVideos
                .filter((videoPath) => typeof videoPath === 'string' && videoPath.trim() !== '')
                .forEach((videoPath) => {
                    let videoUrl = videoPath;
                    if (!videoPath.startsWith('http')) {
                        const cleanPath = videoPath.startsWith('/') ? videoPath.substring(1) : videoPath;
                        videoUrl = `${this.s3Endpoint}/${this.s3Bucket}/${cleanPath}`;
                    }

                    this.videos.push({
                        preview: videoUrl,
                        existing: true,
                        url: videoPath
                    });
                });
        },
        
        addVideo() {
            if (this.videos.length < 2) {
                const index = this.videos.length;
                this.videos.push({
                    preview: null,
                    existing: false
                });
                
                // Открываем диалог выбора файла
                this.$nextTick(() => {
                    document.getElementById('media_video_' + index).click();
                });
            }
        },
        
        removeVideo(index) {
            this.videos.splice(index, 1);
        },
        
        handleVideoPreview(event, index) {
            const file = event.target.files[0];
            if (!file) {
                this.removeVideo(index);
                return;
            }

            if (file.size > 100 * 1024 * 1024) {
                alert('Файл слишком большой. Максимальный размер: 100MB');
                this.removeVideo(index);
                return;
            }
            
            this.videos[index].preview = URL.createObjectURL(file);
        }
    }
}
</script>
