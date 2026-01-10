<?php
/**
 * Theme Functions
 * 
 * Кастомные функции темы, хуки и фильтры.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Добавление классов к body в зависимости от страницы
 */
function ekaterina_body_classes( $classes ) {
    // Добавляем класс для главной страницы
    if ( is_front_page() ) {
        $classes[] = 'is-homepage';
    }

    return $classes;
}
add_filter( 'body_class', 'ekaterina_body_classes' );

/**
 * Функция fallback для меню, если меню не настроено
 */
function ekaterina_default_menu() {
    ?>
    <a href="#about">О себе</a>
    <a href="#portfolio">Портфолио</a>
    <a href="#services">Услуги</a>
    <a href="#testimonials">Отзывы</a>
    <a href="#contact" class="header-cta">СВЯЗАТЬСЯ</a>
    <?php
}

/**
 * Кастомная длина excerpt
 */
function ekaterina_excerpt_length( $length ) {
    return 30;
}
add_filter( 'excerpt_length', 'ekaterina_excerpt_length' );

/**
 * Кастомный текст "Читать далее"
 */
function ekaterina_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'ekaterina_excerpt_more' );

/**
 * Функция для отправки сообщения в Telegram через Bot API
 *
 * @param string $bot_token Токен бота
 * @param string $chat_id ID чата
 * @param string $message Текст сообщения
 * @return bool|WP_Error true при успехе, WP_Error при ошибке
 */
function ekaterina_send_telegram_message( $bot_token, $chat_id, $message ) {
    if ( empty( $bot_token ) || empty( $chat_id ) ) {
        return new WP_Error( 'telegram_config', 'Telegram Bot Token или Chat ID не указаны' );
    }

    $api_url = sprintf( 'https://api.telegram.org/bot%s/sendMessage', $bot_token );
    
    $data = array(
        'chat_id' => $chat_id,
        'text' => $message,
        'parse_mode' => 'HTML', // Используем HTML для форматирования
    );

    $response = wp_remote_post( $api_url, array(
        'body' => $data,
        'timeout' => 15,
    ) );

    if ( is_wp_error( $response ) ) {
        return $response;
    }

    $body = wp_remote_retrieve_body( $response );
    $result = json_decode( $body, true );

    if ( isset( $result['ok'] ) && $result['ok'] === true ) {
        return true;
    }

    $error_message = isset( $result['description'] ) ? $result['description'] : 'Неизвестная ошибка Telegram API';
    return new WP_Error( 'telegram_api', $error_message );
}

/**
 * Обработчик AJAX для формы заявки
 */
function ekaterina_handle_request_form() {
    // Проверка nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ekaterina_nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Ошибка безопасности' ) );
    }

    // Получение данных формы
    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $telegram = isset( $_POST['telegram'] ) ? sanitize_text_field( $_POST['telegram'] ) : '';
    $event_type = isset( $_POST['event-type'] ) ? sanitize_text_field( $_POST['event-type'] ) : '';
    $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';
    $privacy = isset( $_POST['privacy'] ) ? sanitize_text_field( $_POST['privacy'] ) : '';
    
    // Очистка и форматирование Telegram username (убираем @ если есть, добавляем если нет)
    if ( ! empty( $telegram ) ) {
        $telegram = trim( $telegram );
        // Убираем @ в начале, если есть
        if ( strpos( $telegram, '@' ) === 0 ) {
            $telegram = substr( $telegram, 1 );
        }
        // Добавляем @ для отображения
        $telegram_display = '@' . $telegram;
    } else {
        $telegram_display = '';
    }

    // Валидация обязательных полей
    if ( empty( $name ) || empty( $phone ) ) {
        wp_send_json_error( array( 'message' => 'Пожалуйста, заполните все обязательные поля' ) );
    }

    // Валидация согласия с политикой конфиденциальности
    if ( empty( $privacy ) ) {
        wp_send_json_error( array( 'message' => 'Необходимо согласиться с политикой конфиденциальности' ) );
    }

    // Маппинг типов проектов для читаемого отображения
    $event_types_map = array(
        'wedding' => 'Свадьба',
        'corporate' => 'Корпоративное мероприятие',
        'private' => 'Частное мероприятие',
        'charity' => 'Благотворительное мероприятие',
        'conference' => 'Конференция',
        'other' => 'Другое',
    );
    $event_type_display = isset( $event_types_map[ $event_type ] ) ? $event_types_map[ $event_type ] : $event_type;

    // Формирование темы письма
    $subject = sprintf( 'Новая заявка с сайта %s', get_bloginfo( 'name' ) );

    // Формирование тела письма для Email (текстовый формат)
    $email_body = "Новая заявка с сайта\n\n";
    $email_body .= "Имя: $name\n";
    $email_body .= "Телефон: $phone\n";
    if ( ! empty( $email ) ) {
        $email_body .= "Email: $email\n";
    }
    if ( ! empty( $telegram_display ) ) {
        $email_body .= "Telegram: $telegram_display\n";
    }
    if ( ! empty( $event_type_display ) ) {
        $email_body .= "Тип проекта: $event_type_display\n";
    }
    if ( ! empty( $date ) ) {
        // Форматируем дату для читаемости
        $date_formatted = date_i18n( 'd.m.Y', strtotime( $date ) );
        $email_body .= "Предполагаемая дата: $date_formatted\n";
    }
    if ( ! empty( $message ) ) {
        $email_body .= "\nСообщение:\n$message\n";
    }
    $email_body .= "\n---\n";
    $email_body .= "Время отправки: " . date_i18n( 'd.m.Y H:i' ) . "\n";
    $email_body .= "IP адрес: " . ( isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'не определен' ) . "\n";

    // Формирование сообщения для Telegram (HTML формат)
    $telegram_message = "<b>📋 Новая заявка с сайта</b>\n\n";
    $telegram_message .= "<b>Имя:</b> " . esc_html( $name ) . "\n";
    $telegram_message .= "<b>Телефон:</b> " . esc_html( $phone ) . "\n";
    if ( ! empty( $email ) ) {
        $telegram_message .= "<b>Email:</b> " . esc_html( $email ) . "\n";
    }
    if ( ! empty( $telegram_display ) ) {
        $telegram_message .= "<b>Telegram:</b> " . esc_html( $telegram_display ) . "\n";
    }
    if ( ! empty( $event_type_display ) ) {
        $telegram_message .= "<b>Тип проекта:</b> " . esc_html( $event_type_display ) . "\n";
    }
    if ( ! empty( $date ) ) {
        $date_formatted = date_i18n( 'd.m.Y', strtotime( $date ) );
        $telegram_message .= "<b>Предполагаемая дата:</b> " . esc_html( $date_formatted ) . "\n";
    }
    if ( ! empty( $message ) ) {
        $telegram_message .= "\n<b>Сообщение:</b>\n" . esc_html( $message ) . "\n";
    }
    $telegram_message .= "\n---\n";
    $telegram_message .= "<i>Время: " . date_i18n( 'd.m.Y H:i' ) . "</i>";

    // Получение настроек отправки из Options Page
    $form_email = ekaterina_get_scf_option( 'form_email', 'form_settings', '' );
    $site_email = ekaterina_get_scf_option( 'site_email', 'theme_options', get_option( 'admin_email' ) );
    $recipient_email = ! empty( $form_email ) ? $form_email : $site_email;

    $telegram_bot_token = ekaterina_get_scf_option( 'form_telegram_bot_token', 'form_settings', '' );
    $telegram_chat_id = ekaterina_get_scf_option( 'form_telegram_chat_id', 'form_settings', '' );

    // Флаги успешной отправки
    $email_sent = false;
    $telegram_sent = false;
    $errors = array();

    // Отправка в Email (если указан email)
    if ( ! empty( $recipient_email ) && is_email( $recipient_email ) ) {
        $email_sent = wp_mail( $recipient_email, $subject, $email_body );
        if ( ! $email_sent ) {
            $errors[] = 'Ошибка отправки email';
        }
    }

    // Отправка в Telegram (если указаны bot token и chat id)
    if ( ! empty( $telegram_bot_token ) && ! empty( $telegram_chat_id ) ) {
        $telegram_result = ekaterina_send_telegram_message( $telegram_bot_token, $telegram_chat_id, $telegram_message );
        if ( ! is_wp_error( $telegram_result ) ) {
            $telegram_sent = true;
        } else {
            $errors[] = 'Ошибка отправки в Telegram: ' . $telegram_result->get_error_message();
        }
    }

    // Проверяем, была ли хотя бы одна успешная отправка
    if ( $email_sent || $telegram_sent ) {
        wp_send_json_success( array( 'message' => 'Заявка успешно отправлена' ) );
    } else {
        // Если ни один способ не сработал, возвращаем ошибку
        $error_message = ! empty( $errors ) ? implode( ', ', $errors ) : 'Ошибка при отправке заявки. Проверьте настройки отправки.';
        wp_send_json_error( array( 'message' => $error_message ) );
    }
}
add_action( 'wp_ajax_ekaterina_request_form', 'ekaterina_handle_request_form' );
add_action( 'wp_ajax_nopriv_ekaterina_request_form', 'ekaterina_handle_request_form' );

/**
 * Обработчик AJAX для формы отзыва
 */
function ekaterina_handle_testimonial_form() {
    // Проверка nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'ekaterina_nonce' ) ) {
        wp_send_json_error( array( 'message' => 'Ошибка безопасности' ) );
    }

    // Получение данных формы
    $name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $title = isset( $_POST['title'] ) ? sanitize_text_field( $_POST['title'] ) : '';
    $email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
    $event = isset( $_POST['event'] ) ? sanitize_text_field( $_POST['event'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    // Валидация обязательных полей
    if ( empty( $name ) || empty( $email ) || empty( $message ) ) {
        wp_send_json_error( array( 'message' => 'Пожалуйста, заполните все обязательные поля' ) );
    }

    // Валидация email
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => 'Пожалуйста, введите корректный email адрес' ) );
    }

    // Получение email получателя из настроек темы
    $recipient_email = ekaterina_get_scf_option( 'site_email', 'theme_options', get_option( 'admin_email' ) );

    // Формирование темы письма
    $subject = sprintf( 'Новый отзыв с сайта %s', get_bloginfo( 'name' ) );

    // Формирование тела письма
    $email_body = "Новый отзыв с сайта (требует модерации)\n\n";
    $email_body .= "Имя: $name\n";
    if ( ! empty( $title ) ) {
        $email_body .= "Должность/Компания: $title\n";
    }
    $email_body .= "Email: $email\n";
    if ( ! empty( $event ) ) {
        $email_body .= "Тип проекта: $event\n";
    }
    $email_body .= "Отзыв: $message\n";

    // Отправка email
    $sent = wp_mail( $recipient_email, $subject, $email_body );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => 'Отзыв успешно отправлен на модерацию' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Ошибка при отправке отзыва' ) );
    }
}
add_action( 'wp_ajax_ekaterina_testimonial_form', 'ekaterina_handle_testimonial_form' );
add_action( 'wp_ajax_nopriv_ekaterina_testimonial_form', 'ekaterina_handle_testimonial_form' );

/**
 * Функция для получения иконки Font Awesome для социальной сети
 *
 * @param string $network Название социальной сети
 * @return string Класс иконки Font Awesome
 */
function ekaterina_get_social_icon( $network ) {
    $icons = array(
        'vk' => 'fa-brands fa-vk',
        'telegram' => 'fa-brands fa-telegram',
        'whatsapp' => 'fa-brands fa-whatsapp',
        'instagram' => 'fa-brands fa-instagram',
        'facebook' => 'fa-brands fa-facebook',
        'twitter' => 'fa-brands fa-twitter',
        'youtube' => 'fa-brands fa-youtube',
        'linkedin' => 'fa-brands fa-linkedin',
        'ok' => 'fa-brands fa-odnoklassniki',
        'tiktok' => 'fa-brands fa-tiktok',
        'phone' => 'fa-solid fa-phone',
        'email' => 'fa-solid fa-envelope',
        'website' => 'fa-solid fa-globe',
    );
    
    $network = strtolower( trim( $network ) );
    return isset( $icons[ $network ] ) ? $icons[ $network ] : 'fa-solid fa-link';
}

/**
 * Функция для получения названия социальной сети для aria-label
 *
 * @param string $network Название социальной сети
 * @return string Название для aria-label
 */
function ekaterina_get_social_name( $network ) {
    $names = array(
        'vk' => 'ВКонтакте',
        'telegram' => 'Telegram',
        'whatsapp' => 'WhatsApp',
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'twitter' => 'Twitter',
        'youtube' => 'YouTube',
        'linkedin' => 'LinkedIn',
        'ok' => 'Одноклассники',
        'tiktok' => 'TikTok',
        'phone' => 'Телефон',
        'email' => 'Email',
        'website' => 'Сайт',
    );
    
    $network = strtolower( trim( $network ) );
    return isset( $names[ $network ] ) ? $names[ $network ] : $network;
}

/**
 * Функция для проверки, является ли страница страницей политики конфиденциальности
 *
 * @param int|WP_Post|null $post_id ID страницы или объект страницы. Если не указан, используется текущая страница.
 * @return bool true если это страница политики конфиденциальности
 */
function ekaterina_is_privacy_policy_page( $post_id = null ) {
    if ( ! $post_id ) {
        global $post;
        if ( ! $post ) {
            return false;
        }
        $post_id = $post->ID;
        $post_obj = $post;
    } else {
        $post_obj = get_post( $post_id );
        if ( ! $post_obj ) {
            return false;
        }
    }
    
    // Проверяем, что это страница (post_type = 'page')
    if ( $post_obj->post_type !== 'page' ) {
        return false;
    }
    
    // Проверяем шаблон страницы
    $page_template = get_page_template_slug( $post_id );
    if ( $page_template === 'templates/template-privacy-policy.php' ) {
        return true;
    }
    
    // Проверяем по slug
    $post_slug = $post_obj->post_name;
    $slug_patterns = array( 'privacy-policy', 'politika-konfidentsialnosti', 'politika', 'privacy' );
    foreach ( $slug_patterns as $pattern ) {
        if ( $post_slug === $pattern || strpos( strtolower( $post_slug ), $pattern ) !== false ) {
            return true;
        }
    }
    
    // Проверяем по названию
    $post_title = $post_obj->post_title;
    $title_patterns = array( 'Политика конфиденциальности', 'Privacy Policy', 'политика', 'privacy' );
    foreach ( $title_patterns as $pattern ) {
        if ( stripos( $post_title, $pattern ) !== false ) {
            return true;
        }
    }
    
    return false;
}

/**
 * Функция для получения URL страницы политики конфиденциальности
 *
 * @return string URL страницы политики конфиденциальности
 */
function ekaterina_get_privacy_policy_url() {
    // Сначала проверяем настройки WordPress
    $privacy_page_id = get_option( 'wp_page_for_privacy_policy' );
    if ( $privacy_page_id ) {
        $privacy_page = get_post( $privacy_page_id );
        if ( $privacy_page && $privacy_page->post_status === 'publish' ) {
            $url = get_permalink( $privacy_page_id );
            if ( $url && $url !== home_url( '/' ) ) {
                return $url;
            }
        }
    }
    
    // Если не найдено через настройки, используем функцию WordPress
    $url = get_privacy_policy_url();
    if ( $url && $url !== home_url( '/' ) ) {
        return $url;
    }
    
    // Если не найдено, ищем по slug (различные варианты)
    $slug_variants = array( 'privacy-policy', 'politika-konfidentsialnosti', 'privacy', 'politika' );
    foreach ( $slug_variants as $slug ) {
        $privacy_page = get_page_by_path( $slug );
        if ( $privacy_page && $privacy_page->post_status === 'publish' ) {
            $url = get_permalink( $privacy_page->ID );
            if ( $url && $url !== home_url( '/' ) ) {
                return $url;
            }
        }
    }
    
    // Если не найдено, ищем по названию
    $title_variants = array( 'Политика конфиденциальности', 'Privacy Policy' );
    foreach ( $title_variants as $title ) {
        $privacy_page_by_title = get_page_by_title( $title );
        if ( $privacy_page_by_title && $privacy_page_by_title->post_status === 'publish' ) {
            $url = get_permalink( $privacy_page_by_title->ID );
            if ( $url && $url !== home_url( '/' ) ) {
                return $url;
            }
        }
    }
    
    // Если не найдено, ищем любую опубликованную страницу с "политика" в названии или slug
    $pages = get_pages( array(
        'post_status' => 'publish',
        'number' => 1,
        'meta_key' => '_wp_page_template',
        'meta_value' => 'templates/template-privacy-policy.php',
    ) );
    
    if ( ! empty( $pages ) ) {
        $url = get_permalink( $pages[0]->ID );
        if ( $url && $url !== home_url( '/' ) ) {
            return $url;
        }
    }
    
    // Fallback на стандартный URL
    return home_url( '/privacy-policy/' );
}

