# Руководство по миграции контента

Это руководство поможет вам заполнить все SCF поля темы Ekaterina Portfolio контентом из оригинального статического сайта.

## Требования

- WordPress установлен и настроен
- Плагин Secure Custom Fields (SCF) установлен и активирован
- Тема Ekaterina Portfolio активирована

## Настройка SCF полей

### Шаг 1: Создание групп полей

1. Перейдите в админ-панели WordPress: **Настройки → Secure Custom Fields → Группы полей**
2. Создайте следующие группы полей согласно структуре ниже

### Шаг 2: Структура групп полей

#### 1. Hero Section (hero_section)
**Привязка:** Главная страница (Homepage Template)

| Поле | Тип | Название | Значение по умолчанию |
|------|-----|----------|----------------------|
| hero_name | Text | Имя ведущей | Екатерина<br/>Шулятникова |
| hero_subtitle | Text | Подзаголовок | Ведущая премиальных мероприятий<br/>Пермский край |
| hero_background_image | Image | Фоновое изображение | main.png |
| hero_cta_text | Text | Текст кнопки | ЗАБРОНИРОВАТЬ ДАТУ |
| hero_cta_link | Text | Ссылка кнопки | #contact |

#### 2. Introduction Section (introduction_section)
**Привязка:** Главная страница

| Поле | Тип | Название | Значение по умолчанию |
|------|-----|----------|----------------------|
| intro_title | Text | Заголовок | Создаю атмосферу<br/>незабываемых событий |
| intro_description | WYSIWYG | Основной текст | [Текст из index.html, строки 126-128] |
| intro_image | Image | Изображение | portrait.png |

#### 3. Stats Section (stats_section)
**Привязка:** Главная страница

| Поле | Тип | Название |
|------|-----|----------|
| stats_items | Repeater | Статистика |
| └─ stat_number | Number | Число |
| └─ stat_label | Text | Подпись |

**Пример заполнения:**
- 300 / МЕРОПРИЯТИЙ ПРОВЕДЕНО
- 3 / ЛЕТ ОПЫТА
- 95 / ПОВТОРНЫХ КЛИЕНТОВ
- 24 / ЧАСА НА ОТВЕТ

#### 4. Philosophy Section (philosophy_section)
**Привязка:** Главная страница

| Поле | Тип | Название |
|------|-----|----------|
| philosophy_title | Text | Заголовок |
| philosophy_quote | Textarea | Цитата |
| philosophy_principles | Repeater | Принципы |
| └─ principle_title | Text | Заголовок принципа |
| └─ principle_description | Textarea | Описание принципа |

#### 5. About Section (about_section)
**Привязка:** Главная страница

| Поле | Тип | Название |
|------|-----|----------|
| about_title | Text | Заголовок |
| about_timeline | Repeater | События карьеры |
| └─ timeline_year | Text | Год |
| └─ timeline_title | Text | Заголовок события |
| └─ timeline_description | Textarea | Описание события |
| about_image | Image | Изображение |

#### 6. Portfolio Section (portfolio_section)
**Привязка:** Главная страница

| Поле | Тип | Название |
|------|-----|----------|
| portfolio_title | Text | Заголовок |
| portfolio_description | Textarea | Описание |
| portfolio_items | Repeater | Элементы портфолио |
| └─ portfolio_image | Image | Изображение |
| └─ portfolio_title | Text | Название |
| └─ portfolio_category | Text | Категория |

#### 7-13. Остальные секции

Аналогично заполните остальные секции:
- Expertise Section
- Services Section
- Process Section
- Testimonials Section
- Recognition Section
- Contact Section
- Contact Channels Section

#### 14. Theme Options (theme_options)
**Тип:** Options Page

| Поле | Тип | Название |
|------|-----|----------|
| site_host_name | Text | Имя ведущей |
| site_host_title | Text | Должность/титул |
| site_phone | Text | Основной телефон |
| site_email | Email | Основной email |
| site_location | Text | Локация |
| site_vk | URL | Ссылка ВК |
| site_telegram | URL | Ссылка Telegram |
| site_whatsapp | URL | Ссылка WhatsApp |

## Маппинг контента из index.html

### Hero Section
- Строки 108-111 → hero_name, hero_subtitle, hero_cta_text

### Introduction Section
- Строки 125-128 → intro_title, intro_description

### Stats Section
- Строки 137-150 → stats_items (Repeater)

### Philosophy Section
- Строки 160-174 → philosophy_title, philosophy_quote, philosophy_principles

### About Section
- Строки 184-200 → about_timeline (Repeater)

### Portfolio Section
- Строки 217-274 → portfolio_items (Repeater)

И так далее для остальных секций.

## Чек-лист после заполнения

- [ ] Все группы полей созданы
- [ ] Все поля заполнены контентом
- [ ] Изображения загружены в Media Library
- [ ] Главная страница использует шаблон "Homepage"
- [ ] Все секции отображаются корректно
- [ ] Формы работают
- [ ] Ссылки работают
- [ ] Адаптивность проверена

## Полезные советы

1. Используйте Media Library для загрузки всех изображений
2. Для Repeater полей добавляйте элементы по одному
3. Проверяйте отображение после заполнения каждой секции
4. Сохраняйте резервные копии перед изменениями


