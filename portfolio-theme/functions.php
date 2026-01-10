<?php
/**
 * Portfolio Theme Functions
 * 
 * Основной файл функций темы. Подключает все необходимые модули
 * и инициализирует функциональность темы.
 * 
 * Универсальная тема для создания портфолио.
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
// Используем set_url_scheme для принудительного использования HTTPS
define( 'EKATERINA_THEME_URI', set_url_scheme( get_template_directory_uri(), 'https' ) );
define( 'EKATERINA_ASSETS_URI', EKATERINA_THEME_URI . '/assets' );

/**
 * Проверка наличия плагина Secure Custom Fields
 */
function ekaterina_check_scf_plugin() {
    if ( ! class_exists( 'SCF' ) ) {
        add_action( 'admin_notices', function() {
            ?>
            <div class="notice notice-error">
                <p><strong>Портфолио:</strong> Для работы темы требуется плагин <a href="https://wordpress.org/plugins/secure-custom-fields/" target="_blank">Secure Custom Fields (SCF)</a>. Пожалуйста, установите и активируйте его.</p>
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

/**
 * Создание страницы политики конфиденциальности при активации темы
 */
function ekaterina_create_privacy_policy_page() {
    // Проверяем, существует ли уже страница политики конфиденциальности по slug
    $privacy_page = get_page_by_path( 'privacy-policy' );
    
    // Также проверяем по ID из настроек WordPress
    $privacy_page_id_option = get_option( 'wp_page_for_privacy_policy' );
    if ( $privacy_page_id_option ) {
        $privacy_page_by_id = get_post( $privacy_page_id_option );
        if ( $privacy_page_by_id && $privacy_page_by_id->post_status === 'publish' ) {
            $privacy_page = $privacy_page_by_id;
        }
    }
    
    if ( ! $privacy_page ) {
        // Создаем страницу с базовым контентом политики конфиденциальности
        $privacy_content = '<!-- wp:paragraph -->
<p>Настоящая Политика конфиденциальности определяет порядок обработки и защиты персональных данных пользователей сайта.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>1. Общие положения</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>1.1. Настоящая Политика конфиденциальности разработана в соответствии с Федеральным законом от 27.07.2006 № 152-ФЗ "О персональных данных".</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>1.2. Настоящая Политика определяет порядок обработки персональных данных и меры по обеспечению безопасности персональных данных на сайте.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>2. Персональные данные</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>2.1. При заполнении форм на сайте мы можем собирать следующую информацию:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Имя</li>
<li>Номер телефона</li>
<li>Адрес электронной почты</li>
<li>Другая информация, которую вы добровольно предоставляете</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>3. Цели обработки персональных данных</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>3.1. Персональные данные обрабатываются в следующих целях:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Обработка заявок и обращений</li>
<li>Связь с пользователями по вопросам, связанным с использованием сайта</li>
<li>Предоставление информации о услугах</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>4. Согласие на обработку персональных данных</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>4.1. Отправляя форму на сайте, вы даете согласие на обработку ваших персональных данных в соответствии с настоящей Политикой конфиденциальности.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>4.2. Вы имеете право отозвать согласие на обработку персональных данных, направив соответствующее уведомление.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>5. Защита персональных данных</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>5.1. Мы принимаем необходимые технические и организационные меры для защиты персональных данных от неправомерного доступа, уничтожения, изменения, блокирования, копирования, предоставления, распространения, а также от иных неправомерных действий.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>6. Права пользователей</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>6.1. Вы имеете право:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul>
<li>Получать информацию, касающуюся обработки ваших персональных данных</li>
<li>Требовать уточнения, блокирования или уничтожения персональных данных</li>
<li>Отозвать согласие на обработку персональных данных</li>
<li>Обжаловать действия или бездействие оператора в уполномоченный орган по защите прав субъектов персональных данных</li>
</ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>7. Контактная информация</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>7.1. По всем вопросам, связанным с обработкой персональных данных, вы можете обращаться через форму обратной связи на сайте.</p>
<!-- /wp:paragraph -->';

        $privacy_page_id = wp_insert_post( array(
            'post_title'    => 'Политика конфиденциальности',
            'post_content'  => $privacy_content,
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'post_name'     => 'privacy-policy',
            'post_author'   => 1,
        ), true ); // true для возврата WP_Error при ошибке

        // Устанавливаем шаблон страницы и страницу политики конфиденциальности в настройках WordPress
        if ( $privacy_page_id && ! is_wp_error( $privacy_page_id ) ) {
            // Назначаем шаблон страницы (путь должен быть относительно корня темы)
            update_post_meta( $privacy_page_id, '_wp_page_template', 'templates/template-privacy-policy.php' );
            // Устанавливаем страницу политики конфиденциальности в настройках WordPress
            update_option( 'wp_page_for_privacy_policy', $privacy_page_id );
            // Очищаем кеш постоянных ссылок для правильной работы permalink
            delete_option( 'rewrite_rules' );
        }
    } else {
        // Если страница уже существует, проверяем и обновляем её при необходимости
        if ( $privacy_page && $privacy_page->post_status !== 'publish' ) {
            // Если страница существует, но не опубликована, публикуем её
            wp_update_post( array(
                'ID'          => $privacy_page->ID,
                'post_status' => 'publish',
            ) );
        }
        
        // Убеждаемся, что шаблон назначен правильно
        $current_template = get_post_meta( $privacy_page->ID, '_wp_page_template', true );
        if ( empty( $current_template ) || $current_template === 'default' ) {
            update_post_meta( $privacy_page->ID, '_wp_page_template', 'templates/template-privacy-policy.php' );
        }
        
        // Убеждаемся, что страница установлена в настройках WordPress
        $current_privacy_id = get_option( 'wp_page_for_privacy_policy' );
        if ( empty( $current_privacy_id ) || $current_privacy_id != $privacy_page->ID ) {
            update_option( 'wp_page_for_privacy_policy', $privacy_page->ID );
        }
    }
}
add_action( 'after_switch_theme', 'ekaterina_create_privacy_policy_page' );

/**
 * Хук для обновления постоянных ссылок при сохранении страницы политики
 */
function ekaterina_update_privacy_page_permalink( $post_id ) {
    // Проверяем, что это страница политики конфиденциальности
    $privacy_page_id = get_option( 'wp_page_for_privacy_policy' );
    if ( $privacy_page_id == $post_id ) {
        // Очищаем кеш постоянных ссылок
        delete_option( 'rewrite_rules' );
    }
    
    // Автоматически назначаем шаблон для страницы политики, если это она
    if ( function_exists( 'ekaterina_is_privacy_policy_page' ) ) {
        $post = get_post( $post_id );
        if ( $post && ekaterina_is_privacy_policy_page( $post_id ) ) {
            $current_template = get_post_meta( $post_id, '_wp_page_template', true );
            if ( empty( $current_template ) || $current_template === 'default' ) {
                update_post_meta( $post_id, '_wp_page_template', 'templates/template-privacy-policy.php' );
            }
            // Устанавливаем страницу в настройках WordPress, если еще не установлена
            $current_privacy_id = get_option( 'wp_page_for_privacy_policy' );
            if ( empty( $current_privacy_id ) || $current_privacy_id != $post_id ) {
                update_option( 'wp_page_for_privacy_policy', $post_id );
            }
        }
    }
}
add_action( 'save_post_page', 'ekaterina_update_privacy_page_permalink' );

/**
 * Функция для автоматического назначения шаблона для существующих страниц политики
 * Вызывается при загрузке админ-панели
 */
function ekaterina_fix_privacy_page_template() {
    // Проверяем только в админ-панели
    if ( ! is_admin() ) {
        return;
    }
    
    // Ищем все страницы с политикой конфиденциальности
    $pages = get_pages( array(
        'post_status' => array( 'publish', 'draft' ),
        'number' => -1,
    ) );
    
    foreach ( $pages as $page ) {
        if ( function_exists( 'ekaterina_is_privacy_policy_page' ) && ekaterina_is_privacy_policy_page( $page->ID ) ) {
            $current_template = get_post_meta( $page->ID, '_wp_page_template', true );
            if ( empty( $current_template ) || $current_template === 'default' ) {
                update_post_meta( $page->ID, '_wp_page_template', 'templates/template-privacy-policy.php' );
            }
            // Убеждаемся, что страница опубликована
            if ( $page->post_status !== 'publish' ) {
                wp_update_post( array(
                    'ID' => $page->ID,
                    'post_status' => 'publish',
                ) );
            }
            // Устанавливаем страницу в настройках WordPress
            update_option( 'wp_page_for_privacy_policy', $page->ID );
        }
    }
}
add_action( 'admin_init', 'ekaterina_fix_privacy_page_template', 20 );

