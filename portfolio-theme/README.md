# Портфолио

Универсальная WordPress тема для создания портфолио. Подходит для портфолио различных специалистов и проектов.

## Описание

Тема разработана как универсальное решение для создания портфолио с полным управлением контентом через Secure Custom Fields (SCF). Тема включает все необходимые секции для презентации услуг, портфолио работ, отзывов клиентов и контактной информации. Подходит для портфолио различных специалистов: ведущих мероприятий, фотографов, дизайнеров, разработчиков и других профессионалов.

## Требования

- WordPress 6.0 или выше
- PHP 8.1 или выше
- MySQL 5.7 или выше / MariaDB 10.3 или выше
- Плагин Secure Custom Fields (SCF) - обязателен

## Установка

1. Загрузите папку темы в `/wp-content/themes/`
2. Активируйте тему в админ-панели WordPress: **Внешний вид → Темы**
3. Установите и активируйте плагин Secure Custom Fields (SCF)
4. Создайте главную страницу и выберите шаблон "Homepage"
5. Заполните SCF поля согласно `../SCF_FIELDS_DETAILED_GUIDE.md` или `../MIGRATION_GUIDE.md`

## Структура темы

```
portfolio-theme/
├── style.css                    # Заголовок темы
├── functions.php                # Основные функции темы
├── header.php                   # Шапка сайта
├── footer.php                   # Подвал сайта
├── index.php                    # Fallback шаблон
├── page.php                     # Шаблон страницы
├── 404.php                      # Страница 404
├── template-parts/              # Компоненты секций
│   └── components/              # Компоненты секций
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
│   ├── template-homepage.php    # Шаблон главной страницы
│   └── template-privacy-policy.php # Шаблон политики конфиденциальности
├── assets/                      # Компилированные ресурсы
│   ├── css/                     # Стили
│   ├── js/                      # Скрипты
│   ├── favicon/                 # Иконки сайта
│   └── images/                  # Изображения
├── src/                         # Исходные файлы
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

## Разработка

### Установка зависимостей

```bash
npm install
```

### Запуск dev-сервера

```bash
npm run dev
```

### Сборка темы

```bash
npm run build:theme
```

## Управление контентом

Весь контент управляется через Secure Custom Fields (SCF). Создайте группы полей в админ-панели WordPress согласно документации в `inc/scf-fields.php` и `MIGRATION_GUIDE.md`.

## Секции сайта

Тема включает следующие секции:

1. **Hero** - Главный баннер
2. **Introduction** - Введение
3. **Stats** - Статистика
4. **Philosophy** - Философия работы
5. **About** - Профессиональный путь
6. **Portfolio** - Портфолио работ
7. **Expertise** - Экспертиза
8. **Services** - Услуги
9. **Process** - Процесс работы
10. **Testimonials** - Отзывы
11. **Recognition** - Признание
12. **Contact** - Контакты
13. **Contact Channels** - Каналы связи

## Особенности

- Полностью адаптивный дизайн (от 320px)
- Оптимизация производительности
- SEO-оптимизация
- Формы с AJAX отправкой
- Модальные окна для форм
- Анимации и интерактивность
- Поддержка touch-устройств

## Документация

- `../SCF_FIELDS_DETAILED_GUIDE.md` - Детальное руководство по созданию SCF полей
- `../MIGRATION_GUIDE.md` - Руководство по миграции контента
- `../DEPLOYMENT.md` - Инструкция по деплою
- `../DEPLOY_BEGET.md` - Инструкция по деплою на Beget
- `../RESPONSIVE_DESIGN_GUIDE.md` - Руководство по адаптивному дизайну
- `inc/scf-fields.php` - Документация SCF полей

## Поддержка

При возникновении проблем:

1. Проверьте требования к серверу
2. Убедитесь, что плагин SCF установлен
3. Проверьте логи ошибок PHP
4. Следуйте инструкциям в документации

## Лицензия

Proprietary - Все права защищены

## Автор

Николай Д. - https://develonik.ru

