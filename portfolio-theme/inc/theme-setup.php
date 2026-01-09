<?php
/**
 * Theme Setup
 * 
 * Настройка поддержки функций темы, меню, размеров изображений и т.д.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Настройка темы
 */
function ekaterina_theme_setup() {
    // Поддержка автоматического title-tag
    add_theme_support( 'title-tag' );

    // Поддержка миниатюр записей
    add_theme_support( 'post-thumbnails' );

    // Поддержка HTML5 разметки
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Поддержка выборочного обновления виджетов
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Регистрация меню навигации
    register_nav_menus( array(
        'primary' => esc_html__( 'Основное меню', 'ekaterina-portfolio' ),
    ) );

    // Регистрация размеров изображений
    add_image_size( 'portfolio-thumbnail', 600, 400, true );
    add_image_size( 'portfolio-large', 1200, 800, true );
    add_image_size( 'hero-image', 1920, 1080, true );
}
add_action( 'after_setup_theme', 'ekaterina_theme_setup' );

/**
 * Установка ширины контента для встроенных медиа
 */
function ekaterina_content_width() {
    $GLOBALS['content_width'] = apply_filters( 'ekaterina_content_width', 1440 );
}
add_action( 'after_setup_theme', 'ekaterina_content_width', 0 );

