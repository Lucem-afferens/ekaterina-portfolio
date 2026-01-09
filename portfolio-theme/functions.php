<?php
/**
 * Portfolio Theme Functions
 * 
 * Основной файл функций темы. Подключает все необходимые модули
 * и инициализирует функциональность темы.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Определяем константы темы
define( 'EKATERINA_THEME_VERSION', '1.0.0' );
define( 'EKATERINA_THEME_DIR', get_template_directory() );
define( 'EKATERINA_THEME_URI', get_template_directory_uri() );
define( 'EKATERINA_ASSETS_URI', EKATERINA_THEME_URI . '/assets' );

/**
 * Проверка наличия плагина Secure Custom Fields
 */
function ekaterina_check_scf_plugin() {
    if ( ! class_exists( 'SCF' ) ) {
        add_action( 'admin_notices', function() {
            ?>
            <div class="notice notice-error">
                <p><strong>Portfolio Theme:</strong> Для работы темы требуется плагин <a href="https://wordpress.org/plugins/secure-custom-fields/" target="_blank">Secure Custom Fields (SCF)</a>. Пожалуйста, установите и активируйте его.</p>
            </div>
            <?php
        });
    }
}
add_action( 'admin_init', 'ekaterina_check_scf_plugin' );

/**
 * Подключение файлов темы
 */
require_once EKATERINA_THEME_DIR . '/inc/theme-setup.php';
require_once EKATERINA_THEME_DIR . '/inc/enqueue-assets.php';
require_once EKATERINA_THEME_DIR . '/inc/theme-functions.php';
require_once EKATERINA_THEME_DIR . '/inc/security.php';
require_once EKATERINA_THEME_DIR . '/inc/scf-fields.php';

