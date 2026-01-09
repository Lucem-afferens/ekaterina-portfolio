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
$intro_title = function_exists( 'get_field' ) ? get_field( 'intro_title' ) : null;
$intro_title = $intro_title ?: 'Создаю атмосферу<br/>незабываемых событий';

$intro_description = function_exists( 'get_field' ) ? get_field( 'intro_description' ) : '';
$intro_image = function_exists( 'get_field' ) ? get_field( 'intro_image' ) : false;

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
    $intro_description = '<p>Каждое мероприятие — это уникальная история, которую я помогаю рассказать с элегантностью и профессионализмом. Более десяти лет опыта в проведении корпоративных торжеств, свадеб и частных приёмов высокого уровня.</p>
    <p>Моя философия строится на внимании к деталям, безупречном чувстве времени и способности создавать эмоциональную связь с аудиторией любого формата.</p>
    <p>Работаю с ведущими площадками Пермского края и готова к выездным мероприятиям по всей России.</p>';
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

