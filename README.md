# Портфолио

Универсальная WordPress тема для создания портфолио. Подходит для портфолио различных специалистов: ведущих мероприятий, фотографов, дизайнеров, разработчиков и других профессионалов.

## Технологии

- **WordPress** - система управления контентом
- **PHP** - серверная логика
- **CSS3** - стилизация
- **JavaScript (Vanilla)** - интерактивность и анимации
- **Secure Custom Fields (SCF)** - управление полями контента
- **Vite** - инструмент сборки и dev-сервер

## Установка и запуск

### Требования

- **WordPress** 6.0 или выше
- **PHP** 8.1 или выше
- **MySQL** 5.7 или выше / **MariaDB** 10.3 или выше
- **Node.js** версии 18 или выше (для разработки)
- Плагин **Secure Custom Fields (SCF)** - обязателен

### Установка темы

1. Загрузите папку темы `portfolio-theme` в `/wp-content/themes/`
2. Активируйте тему в WordPress: **Внешний вид → Темы**
3. Установите и активируйте плагин Secure Custom Fields (SCF)
4. Создайте главную страницу и выберите шаблон "Homepage"
5. Заполните SCF поля согласно `SCF_FIELDS_DETAILED_GUIDE.md`

### Разработка

#### Установка зависимостей

```bash
npm install
```

#### Запуск dev-сервера для разработки стилей/скриптов

```bash
npm run dev
```

#### Сборка темы для production

```bash
npm run build:theme
```

## Структура проекта

```
portfolio-theme/
├── style.css                    # Заголовок темы WordPress
├── functions.php                # Основные функции темы
├── header.php                   # Шапка сайта
├── footer.php                   # Подвал сайта
├── index.php                    # Fallback шаблон
├── page.php                     # Шаблон страницы
├── 404.php                      # Страница 404
├── template-parts/              # Компоненты секций
│   └── components/              # Компоненты (hero, portfolio, services и т.д.)
│       ├── hero-section.php
│       ├── introduction-section.php
│       ├── stats-banner.php
│       ├── philosophy-section.php
│       ├── about-section.php
│       ├── portfolio-section.php
│       ├── expertise-section.php
│       ├── services-section.php
│       ├── process-section.php
│       ├── testimonials-section.php
│       ├── recognition-section.php
│       ├── contact-section.php
│       ├── contact-channels-section.php
│       ├── request-modal.php
│       ├── testimonial-modal.php
│       └── portfolio-gallery-modal.php
├── templates/                   # Кастомные шаблоны
│   ├── template-homepage.php    # Главная страница
│   └── template-privacy-policy.php # Политика конфиденциальности
├── assets/                      # Скомпилированные ресурсы
│   ├── css/                     # Стили
│   ├── js/                      # Скрипты
│   ├── favicon/                 # Иконки сайта
│   └── images/                  # Изображения
├── src/                         # Исходные файлы для сборки
│   ├── css/                     # Исходные стили
│   └── js/                      # Исходные скрипты
│       └── modules/             # Модули JavaScript
└── inc/                         # Вспомогательные PHP файлы
    ├── theme-setup.php          # Настройка темы
    ├── enqueue-assets.php       # Подключение ресурсов
    ├── scf-fields.php           # Документация SCF полей
    ├── theme-functions.php      # Кастомные функции
    └── security.php             # Функции безопасности
```

Корневая структура проекта:
```
./
├── portfolio-theme/             # Папка темы WordPress
├── .github/                     # GitHub Actions workflows
│   ├── workflows/
│   │   └── deploy.yml           # Workflow для автоматического деплоя
│   └── GITHUB_SECRETS.md        # Инструкция по настройке секретов
├── vite.config.js               # Конфигурация Vite
├── package.json                 # Зависимости Node.js
└── [документация].md            # Файлы документации
```

## Особенности

- ✅ Полностью адаптивный дизайн (от 320px)
- ✅ Плавные анимации и переходы
- ✅ Элегантный минималистичный дизайн
- ✅ Оптимизированная производительность
- ✅ SEO-оптимизация
- ✅ Полное управление контентом через WordPress Admin Panel
- ✅ Интеграция с Secure Custom Fields (SCF)
- ✅ Формы с AJAX отправкой в Email и Telegram
- ✅ Модальные окна для форм
- ✅ Поддержка touch-устройств
- ✅ Accessibility (WCAG compliant)

## Секции сайта

Тема включает следующие секции:

1. **Hero** - Главный баннер
2. **Introduction** - Введение
3. **Stats** - Статистика
4. **Philosophy** - Философия работы
5. **About** - Профессиональный путь
6. **Portfolio** - Портфолио работ (с опциональными ссылками)
7. **Expertise** - Экспертиза
8. **Services** - Услуги
9. **Process** - Процесс работы
10. **Testimonials** - Отзывы
11. **Recognition** - Признание/Сотрудничество
12. **Contact** - Контакты
13. **Contact Channels** - Каналы связи

## Документация

- `SCF_FIELDS_DETAILED_GUIDE.md` - Детальное руководство по созданию SCF полей
- `MIGRATION_GUIDE.md` - Руководство по миграции контента
- `DEPLOYMENT.md` - Инструкция по деплою
- `DEPLOY_BEGET.md` - Инструкция по деплою на Beget
- `RESPONSIVE_DESIGN_GUIDE.md` - Руководство по адаптивному дизайну
- `portfolio-theme/README.md` - Документация темы
- `.github/GITHUB_SECRETS.md` - Инструкция по настройке секретов для GitHub Actions

## Разработка

Проект использует Vite для быстрой разработки стилей и скриптов с горячей перезагрузкой модулей. Все изменения в `src/css/` и `src/js/` автоматически компилируются в `assets/`.

## Лицензия

Proprietary - Все права защищены.




