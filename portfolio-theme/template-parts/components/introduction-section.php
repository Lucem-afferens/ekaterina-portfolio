<?php
/**
 * Introduction Section Component
 * 
 * Компонент секции "О себе" (Introduction).
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Получаем данные из SCF (используем get_field() напрямую, как в Tochka-Gg)
// Получаем ID текущей страницы для правильного контекста
$current_page_id = ekaterina_get_current_page_id();

// Проверяем, включена ли секция Introduction
$introduction_enabled = function_exists( 'get_field' ) ? get_field( 'introduction_enabled', $current_page_id ) : true;
// Если поле не установлено, по умолчанию секция включена
if ( $introduction_enabled === null ) {
    $introduction_enabled = true;
}
// Преобразуем в boolean
$introduction_enabled = (bool) $introduction_enabled;

// Если секция отключена, не выводим её
if ( ! $introduction_enabled ) {
    return;
}

$intro_title = function_exists( 'get_field' ) ? get_field( 'intro_title', $current_page_id ) : null;
$intro_title = $intro_title ?: 'Создаю качественные<br/>решения для вашего проекта';

$intro_description = function_exists( 'get_field' ) ? get_field( 'intro_description', $current_page_id ) : '';
$intro_image = function_exists( 'get_field' ) ? get_field( 'intro_image', $current_page_id ) : false;

// Получаем URL изображения
// get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
$intro_image_url = '';
if ( $intro_image ) {
    if ( is_array( $intro_image ) && ! empty( $intro_image['url'] ) ) {
        // Если вернулся массив, используем URL из него
        $intro_image_url = $intro_image['url'];
    } elseif ( is_numeric( $intro_image ) ) {
        // Если вернулся ID, получаем URL
    $intro_image_url = wp_get_attachment_image_url( $intro_image, 'full' );
    }
    // Принудительно используем HTTPS
    if ( $intro_image_url ) {
        $intro_image_url = set_url_scheme( $intro_image_url, 'https' );
    }
}
if ( ! $intro_image_url ) {
    $intro_image_url = set_url_scheme( get_template_directory_uri() . '/assets/images/portrait.png', 'https' );
}

// Если описание не заполнено, используем дефолтный текст
if ( empty( $intro_description ) ) {
    $intro_description = '<p>Краткое описание вашей деятельности и опыта. Расскажите о вашем подходе, достижениях и специализации.</p>
    <p>Ваша философия работы и основные принципы, которые вы используете в своей деятельности.</p>
    <p>География работы, особенности сотрудничества, форматы работы (офлайн/онлайн, локально/удаленно).</p>';
}
?>

<section id="introduction">
    <div class="intro-grid">
        <div class="intro-image">
            <img src="<?php echo esc_url( $intro_image_url ); ?>" alt="<?php echo esc_attr( $intro_title ); ?>" fetchpriority="high" decoding="async" />
        </div>
        <div class="intro-content">
            <div class="section-divider"></div>
            <h3><?php echo wp_kses_post( $intro_title ); ?></h3>
            <?php echo wp_kses_post( wpautop( $intro_description ) ); ?>
        </div>
    </div>
</section>

