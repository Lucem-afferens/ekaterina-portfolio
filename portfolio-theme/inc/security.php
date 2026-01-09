<?php
/**
 * Security Functions
 * 
 * Функции безопасности: экранирование данных, санитизация, защита от прямого доступа.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Вспомогательная функция для безопасного вывода SCF поля
 * 
 * SCF совместим с API ACF, поэтому используем get_field() вместо SCF::get()
 *
 * @param string $field_name Имя поля SCF
 * @param mixed  $default    Значение по умолчанию
 * @param string $context    Контекст экранирования (html, attr, url)
 * @param int    $post_id    ID страницы/поста (если не указан, используется текущий)
 * @return string Экранированное значение
 */
function ekaterina_get_scf_field( $field_name, $default = '', $context = 'html', $post_id = null ) {
    // SCF предоставляет функцию get_field() для совместимости с ACF API
    if ( ! function_exists( 'get_field' ) ) {
        return $default;
    }

    // Получаем ID текущей страницы, если не указан
    if ( $post_id === null ) {
        $post_id = ekaterina_get_current_page_id();
    }

    // Используем get_field() - стандартный API ACF/SCF
    // Если post_id = 0 или false, get_field() автоматически использует текущую страницу
    $value = get_field( $field_name, $post_id ? $post_id : false );
    
    // Проверяем, что значение не пустое (учитываем, что "0" - это валидное значение)
    if ( $value === null || $value === false || $value === '' ) {
        return $default;
    }

    switch ( $context ) {
        case 'attr':
            return esc_attr( $value );
        case 'url':
            return esc_url( $value );
        case 'html':
        default:
            return esc_html( $value );
    }
}

/**
 * Вспомогательная функция для безопасного вывода SCF поля из Options
 * 
 * SCF совместим с API ACF, поэтому используем get_field(..., 'option')
 *
 * @param string $field_name Имя поля SCF
 * @param string $group_name Имя группы полей (не используется, оставлено для совместимости)
 * @param mixed  $default    Значение по умолчанию
 * @param string $context    Контекст экранирования
 * @return string Экранированное значение
 */
function ekaterina_get_scf_option( $field_name, $group_name = 'theme_options', $default = '', $context = 'html' ) {
    // SCF предоставляет функцию get_field() для совместимости с ACF API
    if ( ! function_exists( 'get_field' ) ) {
        return $default;
    }

    // Используем get_field() с параметром 'option' для Options Page
    $value = get_field( $field_name, 'option' );
    
    // Проверяем, что значение не пустое (учитываем, что "0" - это валидное значение)
    if ( $value === null || $value === false || $value === '' ) {
        return $default;
    }

    switch ( $context ) {
        case 'attr':
            return esc_attr( $value );
        case 'url':
            return esc_url( $value );
        case 'html':
        default:
            return esc_html( $value );
    }
}

/**
 * Вспомогательная функция для безопасного вывода изображения SCF
 * 
 * SCF совместим с API ACF, поэтому используем get_field() вместо SCF::get()
 * get_field() для Image field возвращает массив ['ID', 'url', 'alt', 'width', 'height'] или ID
 *
 * @param string $field_name Имя поля SCF (изображение)
 * @param string $size       Размер изображения
 * @param array  $attr       Атрибуты изображения
 * @param int    $post_id    ID страницы/поста (если не указан, используется текущий)
 * @return string HTML код изображения
 */
function ekaterina_get_scf_image( $field_name, $size = 'full', $attr = array(), $post_id = null ) {
    // SCF предоставляет функцию get_field() для совместимости с ACF API
    if ( ! function_exists( 'get_field' ) ) {
        return '';
    }

    // Получаем ID текущей страницы, если не указан
    if ( $post_id === null ) {
        $post_id = ekaterina_get_current_page_id();
    }

    // Используем get_field() - стандартный API ACF/SCF
    $image_data = get_field( $field_name, $post_id ? $post_id : false );
    
    if ( empty( $image_data ) ) {
        return '';
    }

    // get_field() для Image field может вернуть массив или ID
    $image_id = null;
    if ( is_array( $image_data ) && isset( $image_data['ID'] ) ) {
        // Если вернулся массив, берем ID из него
        $image_id = $image_data['ID'];
    } elseif ( is_numeric( $image_data ) ) {
        // Если вернулся ID напрямую
        $image_id = $image_data;
    } else {
        return '';
    }

    $image = wp_get_attachment_image( $image_id, $size, false, $attr );
    
    return $image;
}

/**
 * Получение ID текущей страницы для использования в SCF
 *
 * @return int ID страницы или 0, если не найдено
 */
function ekaterina_get_current_page_id() {
    // Сначала проверяем глобальную переменную, установленную в шаблоне
    global $ekaterina_current_page_id;
    if ( isset( $ekaterina_current_page_id ) && $ekaterina_current_page_id ) {
        return (int) $ekaterina_current_page_id;
    }
    
    // Используем get_queried_object_id() - работает даже в get_template_part()
    $page_id = get_queried_object_id();
    
    // Если не получили ID, пытаемся через get_the_ID()
    if ( ! $page_id ) {
        $page_id = get_the_ID();
    }
    
    // Если все еще нет ID, пытаемся получить из глобальной переменной $post
    if ( ! $page_id ) {
        global $post;
        if ( isset( $post->ID ) ) {
            $page_id = $post->ID;
        }
    }
    
    // Если все еще нет ID, пытаемся получить через get_queried_object
    if ( ! $page_id ) {
        $queried_object = get_queried_object();
        if ( $queried_object && isset( $queried_object->ID ) ) {
            $page_id = $queried_object->ID;
        }
    }
    
    return $page_id ? (int) $page_id : 0;
}

/**
 * Защита от прямого доступа к файлам в директории inc/
 */
if ( ! function_exists( 'ekaterina_block_direct_access' ) ) {
    function ekaterina_block_direct_access() {
        // Эта функция вызывается в каждом файле inc/ для проверки ABSPATH
    }
}

