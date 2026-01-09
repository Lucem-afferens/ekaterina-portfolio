<?php
/**
 * SCF Fields Configuration
 * 
 * Документация и вспомогательные функции для работы с Secure Custom Fields (SCF).
 * 
 * ВАЖНО: SCF поля настраиваются через админ-панель WordPress.
 * Этот файл содержит документацию по структуре полей и вспомогательные функции.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ДОКУМЕНТАЦИЯ ПО НАСТРОЙКЕ SCF ПОЛЕЙ
 * 
 * Для настройки полей перейдите в админ-панели WordPress:
 * Настройки → Secure Custom Fields → Группы полей
 * 
 * Создайте следующие группы полей:
 */

/**
 * 1. ГРУППА ПОЛЕЙ: "Hero Section" (hero_section)
 * Привязка: Главная страница (Homepage Template)
 * 
 * Поля:
 * - hero_name (Text) - Имя ведущей
 * - hero_subtitle (Text) - Подзаголовок
 * - hero_background_image (Image) - Фоновое изображение
 * - hero_cta_text (Text) - Текст кнопки
 * - hero_cta_link (Text) - Ссылка кнопки
 */

/**
 * 2. ГРУППА ПОЛЕЙ: "Introduction Section" (introduction_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - intro_title (Text) - Заголовок
 * - intro_description (WYSIWYG) - Основной текст
 * - intro_image (Image) - Изображение
 */

/**
 * 3. ГРУППА ПОЛЕЙ: "Stats Section" (stats_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - stats_items (Repeater) - Статистика
 *   - stat_number (Number) - Число
 *   - stat_label (Text) - Подпись
 */

/**
 * 4. ГРУППА ПОЛЕЙ: "Philosophy Section" (philosophy_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - philosophy_title (Text) - Заголовок
 * - philosophy_quote (Textarea) - Цитата
 * - philosophy_principles (Repeater) - Принципы
 *   - principle_title (Text) - Заголовок принципа
 *   - principle_description (Textarea) - Описание принципа
 */

/**
 * 5. ГРУППА ПОЛЕЙ: "About Section" (about_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - about_title (Text) - Заголовок
 * - about_timeline (Repeater) - События карьеры
 *   - timeline_year (Text) - Год
 *   - timeline_title (Text) - Заголовок события
 *   - timeline_description (Textarea) - Описание события
 * - about_image (Image) - Изображение
 */

/**
 * 6. ГРУППА ПОЛЕЙ: "Portfolio Section" (portfolio_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - portfolio_title (Text) - Заголовок
 * - portfolio_description (Textarea) - Описание
 * - portfolio_items (Repeater) - Элементы портфолио
 *   - portfolio_image (Image) - Изображение
 *   - portfolio_title (Text) - Название
 *   - portfolio_category (Text) - Категория
 */

/**
 * 7. ГРУППА ПОЛЕЙ: "Expertise Section" (expertise_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - expertise_title (Text) - Заголовок
 * - expertise_items (Repeater) - Элементы экспертизы
 *   - expertise_title (Text) - Заголовок
 *   - expertise_description (Textarea) - Описание
 */

/**
 * 8. ГРУППА ПОЛЕЙ: "Services Section" (services_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - services_title (Text) - Заголовок
 * - services_description (Textarea) - Описание
 * - services_list (Repeater) - Услуги
 *   - service_title (Text) - Название услуги
 *   - service_description (Textarea) - Описание услуги
 *   - service_points (Repeater) - Пункты услуги
 *     - point_text (Text) - Текст пункта
 */

/**
 * 9. ГРУППА ПОЛЕЙ: "Process Section" (process_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - process_title (Text) - Заголовок
 * - process_description (Textarea) - Описание
 * - process_steps (Repeater) - Шаги процесса
 *   - step_number (Text) - Номер шага
 *   - step_title (Text) - Заголовок шага
 *   - step_description (Textarea) - Описание шага
 */

/**
 * 10. ГРУППА ПОЛЕЙ: "Testimonials Section" (testimonials_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - testimonials_title (Text) - Заголовок
 * - testimonials_description (Textarea) - Описание
 * - testimonials_list (Repeater) - Отзывы
 *   - testimonial_quote (Textarea) - Текст отзыва
 *   - testimonial_author (Text) - Имя автора
 *   - testimonial_title (Text) - Должность/Компания
 */

/**
 * 11. ГРУППА ПОЛЕЙ: "Recognition Section" (recognition_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - recognition_title (Text) - Заголовок
 * - recognition_stats (Repeater) - Статистика
 *   - recognition_number (Text) - Число
 *   - recognition_label (Text) - Подпись
 * - recognition_partners (Repeater) - Партнёры
 *   - partner_name (Text) - Название партнёра
 */

/**
 * 12. ГРУППА ПОЛЕЙ: "Contact Section" (contact_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - contact_title (Text) - Заголовок секции
 * - contact_description (Textarea) - Описание секции
 * - contact_info_title (Text) - Заголовок блока "Контактная информация"
 * - contact_info_items (Repeater) - Пункты контактной информации
 *   - contact_info_label (Text) - Заголовок пункта (Телефон, Email и т.д.)
 *   - contact_info_value (Text) - Значение пункта
 *   - contact_info_type (Select/Text) - Тип пункта (phone, email, url, text, map)
 *   - contact_info_map_link (URL/Text) - Ссылка на карту (опционально, только для типа "map")
 * - contact_work_hours_title (Text) - Заголовок блока "Время работы"
 * - contact_work_hours (Repeater) - Время работы
 *   - hours_label (Text) - Заголовок пункта (Консультации, Мероприятия и т.д.)
 *   - hours_value (Text) - Значение времени работы
 * - contact_vk_link (URL) - Ссылка ВК
 * - contact_telegram_link (URL) - Ссылка Telegram
 * - contact_whatsapp_link (URL) - Ссылка WhatsApp
 * 
 * ОБРАТНАЯ СОВМЕСТИМОСТЬ:
 * Старые поля contact_phone, contact_email, contact_location автоматически
 * преобразуются в repeater contact_info_items с дефолтными заголовками.
 * Если заголовок или значение не указаны, пункт не отображается.
 */

/**
 * 13. ГРУППА ПОЛЕЙ: "Contact Channels Section" (contact_channels_section)
 * Привязка: Главная страница
 * 
 * Поля:
 * - channels_label (Text) - Текст перед ссылками
 * - channels_vk (URL) - Ссылка ВК
 * - channels_telegram (URL) - Ссылка Telegram
 * - channels_phone (Text) - Телефон
 */

/**
 * 14. OPTIONS PAGE: "Theme Options" (theme_options)
 * Тип: Options Page
 * 
 * Поля:
 * - site_host_name (Text) - Имя ведущей (для header и footer)
 * - site_host_title (Text) - Должность/титул (для footer)
 * - site_phone (Text) - Основной телефон
 * - site_email (Email) - Основной email
 * - site_location (Text) - Локация
 * - site_vk (URL) - Ссылка ВК
 * - site_telegram (URL) - Ссылка Telegram
 * - site_whatsapp (URL) - Ссылка WhatsApp
 * - footer_copyright_text (Textarea) - Текст копирайта в футере (поддержка %year%)
 * - footer_top_link_text (Text) - Текст кнопки "Наверх" в футере
 */

/**
 * Вспомогательная функция для получения Repeater поля
 * 
 * SCF совместим с API ACF, поэтому используем get_field() вместо SCF::get()
 * Для Repeater полей лучше использовать have_rows() и get_sub_field(),
 * но для обратной совместимости возвращаем массив
 *
 * @param string $field_name Имя Repeater поля
 * @param int    $post_id    ID страницы/поста (если не указан, используется текущий)
 * @return array Массив значений или пустой массив
 */
function ekaterina_get_scf_repeater( $field_name, $post_id = null ) {
    // SCF предоставляет функцию get_field() для совместимости с ACF API
    if ( ! function_exists( 'get_field' ) ) {
        return array();
    }

    // Получаем ID текущей страницы, если не указан
    if ( $post_id === null ) {
        $post_id = ekaterina_get_current_page_id();
    }

    // Используем get_field() - стандартный API ACF/SCF
    // get_field() для Repeater возвращает массив массивов
    $repeater = get_field( $field_name, $post_id ? $post_id : false );
    
    // Проверяем, что значение не пустое и является массивом
    if ( $repeater === null || $repeater === false || ! is_array( $repeater ) || empty( $repeater ) ) {
        return array();
    }

    return $repeater;
}

/**
 * Вспомогательная функция для получения значения из Repeater
 *
 * @param array  $repeater_item Элемент Repeater
 * @param string $field_name    Имя поля внутри Repeater
 * @param mixed  $default       Значение по умолчанию
 * @return mixed Значение поля
 */
function ekaterina_get_repeater_field( $repeater_item, $field_name, $default = '' ) {
    if ( ! is_array( $repeater_item ) || ! isset( $repeater_item[ $field_name ] ) ) {
        return $default;
    }

    return $repeater_item[ $field_name ];
}

