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
    $event_type = isset( $_POST['event-type'] ) ? sanitize_text_field( $_POST['event-type'] ) : '';
    $date = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
    $message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

    // Валидация обязательных полей
    if ( empty( $name ) || empty( $phone ) ) {
        wp_send_json_error( array( 'message' => 'Пожалуйста, заполните все обязательные поля' ) );
    }

    // Получение email получателя из настроек темы
    $recipient_email = ekaterina_get_scf_option( 'site_email', 'theme_options', get_option( 'admin_email' ) );

    // Формирование темы письма
    $subject = sprintf( 'Новая заявка с сайта %s', get_bloginfo( 'name' ) );

    // Формирование тела письма
    $email_body = "Новая заявка с сайта\n\n";
    $email_body .= "Имя: $name\n";
    $email_body .= "Телефон: $phone\n";
    if ( ! empty( $email ) ) {
        $email_body .= "Email: $email\n";
    }
    if ( ! empty( $event_type ) ) {
        $email_body .= "Тип мероприятия: $event_type\n";
    }
    if ( ! empty( $date ) ) {
        $email_body .= "Дата: $date\n";
    }
    if ( ! empty( $message ) ) {
        $email_body .= "Сообщение: $message\n";
    }

    // Отправка email
    $sent = wp_mail( $recipient_email, $subject, $email_body );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => 'Заявка успешно отправлена' ) );
    } else {
        wp_send_json_error( array( 'message' => 'Ошибка при отправке заявки' ) );
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
        $email_body .= "Тип мероприятия: $event\n";
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

