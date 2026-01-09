<?php
/**
 * Enqueue Assets
 * 
 * Подключение стилей и скриптов темы.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Подключение стилей
 */
function ekaterina_enqueue_styles() {
    // Основной файл стилей темы
    // Ищем собранный CSS файл (может иметь хеш в имени)
    $css_path = EKATERINA_THEME_DIR . '/assets/css/';
    $css_files = glob( $css_path . 'main.*.css' );
    
    if ( ! empty( $css_files ) && file_exists( $css_files[0] ) ) {
        // Используем собранный файл с хешем
        $css_file = basename( $css_files[0] );
        $css_version = filemtime( $css_files[0] );
        wp_enqueue_style(
            'ekaterina-main-style',
            EKATERINA_ASSETS_URI . '/css/' . $css_file,
            array(),
            $css_version
        );
    } else {
        // Fallback на исходный файл, если сборка не выполнена
        $fallback_css = $css_path . 'main.css';
        $css_version = file_exists( $fallback_css ) ? filemtime( $fallback_css ) : EKATERINA_THEME_VERSION;
        wp_enqueue_style(
            'ekaterina-main-style',
            EKATERINA_ASSETS_URI . '/css/main.css',
            array(),
            $css_version
        );
    }

    // Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    // Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap',
        array(),
        null
    );
}
add_action( 'wp_enqueue_scripts', 'ekaterina_enqueue_styles' );

/**
 * Подключение скриптов
 */
function ekaterina_enqueue_scripts() {
    // Основной JavaScript файл темы
    // Ищем собранный JS файл (может иметь хеш в имени)
    $js_path = EKATERINA_THEME_DIR . '/assets/js/';
    $js_files = glob( $js_path . 'main.*.js' );
    
    if ( ! empty( $js_files ) && file_exists( $js_files[0] ) ) {
        // Используем собранный файл с хешем
        $js_file = basename( $js_files[0] );
        $js_version = filemtime( $js_files[0] );
        wp_enqueue_script(
            'ekaterina-main-script',
            EKATERINA_ASSETS_URI . '/js/' . $js_file,
            array(),
            $js_version,
            true
        );
    } else {
        // Fallback на исходный файл, если сборка не выполнена
        $fallback_js = $js_path . 'main.js';
        $js_version = file_exists( $fallback_js ) ? filemtime( $fallback_js ) : EKATERINA_THEME_VERSION;
        wp_enqueue_script(
            'ekaterina-main-script',
            EKATERINA_ASSETS_URI . '/js/main.js',
            array(),
            $js_version,
            true
        );
    }

    // Font Awesome JS
    wp_enqueue_script(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js',
        array(),
        '6.4.0',
        true
    );

    // Локализация скрипта для AJAX
    wp_localize_script( 'ekaterina-main-script', 'ekaterinaAjax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'ekaterina_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'ekaterina_enqueue_scripts' );

/**
 * Добавление preconnect для внешних ресурсов
 */
function ekaterina_resource_hints( $urls, $relation_type ) {
    if ( 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
        $urls[] = array(
            'href' => 'https://cdnjs.cloudflare.com',
            'crossorigin',
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'ekaterina_resource_hints', 10, 2 );

