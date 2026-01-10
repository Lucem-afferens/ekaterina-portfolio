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

/**
 * Создание страницы политики конфиденциальности при активации темы
 */
function ekaterina_create_privacy_policy_page() {
    // Проверяем, существует ли уже страница политики конфиденциальности
    $privacy_page = get_page_by_path( 'privacy-policy' );
    
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
            'page_template' => 'template-privacy-policy.php',
        ) );

        // Устанавливаем страницу политики конфиденциальности в настройках WordPress
        if ( $privacy_page_id && ! is_wp_error( $privacy_page_id ) ) {
            update_option( 'wp_page_for_privacy_policy', $privacy_page_id );
        }
    }
}
add_action( 'after_switch_theme', 'ekaterina_create_privacy_policy_page' );

