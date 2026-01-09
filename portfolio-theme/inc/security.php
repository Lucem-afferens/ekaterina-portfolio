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
 * @param string $field_name Имя поля SCF
 * @param mixed  $default    Значение по умолчанию
 * @param string $context    Контекст экранирования (html, attr, url)
 * @param int    $post_id    ID страницы/поста (если не указан, используется текущий)
 * @return string Экранированное значение
 */
function ekaterina_get_scf_field( $field_name, $default = '', $context = 'html', $post_id = null ) {
    if ( ! class_exists( 'SCF' ) ) {
        return $default;
    }

    // Получаем ID текущей страницы, если не указан
    if ( $post_id === null ) {
        $post_id = ekaterina_get_current_page_id();
    }

    // Получаем значение поля с указанием ID страницы
    $value = SCF::get( $field_name, $post_id );
    
    if ( empty( $value ) ) {
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
 * @param string $field_name Имя поля SCF
 * @param string $group_name Имя группы полей
 * @param mixed  $default    Значение по умолчанию
 * @param string $context    Контекст экранирования
 * @return string Экранированное значение
 */
function ekaterina_get_scf_option( $field_name, $group_name = 'theme_options', $default = '', $context = 'html' ) {
    if ( ! class_exists( 'SCF' ) ) {
        return $default;
    }

    $value = SCF::get_option_meta( $group_name, $field_name );
    
    if ( empty( $value ) ) {
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
 * @param string $field_name Имя поля SCF (изображение)
 * @param string $size       Размер изображения
 * @param array  $attr       Атрибуты изображения
 * @param int    $post_id    ID страницы/поста (если не указан, используется текущий)
 * @return string HTML код изображения
 */
function ekaterina_get_scf_image( $field_name, $size = 'full', $attr = array(), $post_id = null ) {
    if ( ! class_exists( 'SCF' ) ) {
        return '';
    }

    // Получаем ID текущей страницы, если не указан
    if ( $post_id === null ) {
        $post_id = ekaterina_get_current_page_id();
    }

    // Получаем ID изображения с указанием ID страницы
    $image_id = SCF::get( $field_name, $post_id );
    
    if ( empty( $image_id ) ) {
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
    global $post;
    $page_id = get_the_ID();
    
    // Если get_the_ID() не вернул ID, пытаемся получить из глобальной переменной
    if ( ! $page_id && isset( $post->ID ) ) {
        $page_id = $post->ID;
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

