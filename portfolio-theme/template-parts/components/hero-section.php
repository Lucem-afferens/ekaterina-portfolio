<?php
/**
 * Hero Section Component
 * 
 * Компонент секции Hero (главный баннер).
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Получаем данные из SCF (используем get_field() напрямую, как в Tochka-Gg)
$hero_name = function_exists( 'get_field' ) ? get_field( 'hero_name' ) : null;
$hero_name = $hero_name ?: 'Екатерина<br/>Шулятникова';

$hero_subtitle = function_exists( 'get_field' ) ? get_field( 'hero_subtitle' ) : null;
$hero_subtitle = $hero_subtitle ?: 'Ведущая премиальных мероприятий<br/>Пермский край';

$hero_background_image = function_exists( 'get_field' ) ? get_field( 'hero_background_image' ) : false;

$hero_cta_text = function_exists( 'get_field' ) ? get_field( 'hero_cta_text' ) : null;
$hero_cta_text = $hero_cta_text ?: 'ЗАБРОНИРОВАТЬ ДАТУ';

$hero_cta_link = function_exists( 'get_field' ) ? get_field( 'hero_cta_link' ) : null;
$hero_cta_link = $hero_cta_link ?: '#contact';

// Получаем URL изображения
// get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
$hero_image_url = '';
if ( $hero_background_image ) {
    if ( is_array( $hero_background_image ) && ! empty( $hero_background_image['url'] ) ) {
        // Если вернулся массив, используем URL из него
        $hero_image_url = $hero_background_image['url'];
    } elseif ( is_numeric( $hero_background_image ) ) {
        // Если вернулся ID, получаем URL
        $hero_image_url = wp_get_attachment_image_url( $hero_background_image, 'hero-image' );
    }
    // Принудительно используем HTTPS
    if ( $hero_image_url ) {
        $hero_image_url = set_url_scheme( $hero_image_url, 'https' );
    }
}
if ( ! $hero_image_url ) {
    $hero_image_url = set_url_scheme( get_template_directory_uri() . '/assets/images/main.png', 'https' );
}
?>

<section id="hero">
    <div class="hero-image">
        <img src="<?php echo esc_url( $hero_image_url ); ?>" alt="<?php echo esc_attr( $hero_name ); ?>" fetchpriority="high" decoding="async" />
    </div>
    <div class="hero-content">
        <h2><?php echo wp_kses_post( $hero_name ); ?></h2>
        <div class="hero-divider"></div>
        <div class="hero-subtitle"><?php echo wp_kses_post( $hero_subtitle ); ?></div>
        <a href="<?php echo esc_url( $hero_cta_link ); ?>" class="hero-cta"><?php echo esc_html( $hero_cta_text ); ?></a>
    </div>
    <div class="hero-scroll">
        <i class="fa-solid fa-chevron-down"></i>
    </div>
</section>

