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

// Получаем данные из SCF
$hero_name = ekaterina_get_scf_field( 'hero_name', 'Екатерина<br/>Шулятникова' );
$hero_subtitle = ekaterina_get_scf_field( 'hero_subtitle', 'Ведущая премиальных мероприятий<br/>Пермский край' );
$hero_background_image = class_exists( 'SCF' ) ? SCF::get( 'hero_background_image' ) : '';
$hero_cta_text = ekaterina_get_scf_field( 'hero_cta_text', 'ЗАБРОНИРОВАТЬ ДАТУ' );
$hero_cta_link = ekaterina_get_scf_field( 'hero_cta_link', '#contact', 'url' );

// Получаем URL изображения
$hero_image_url = '';
if ( $hero_background_image ) {
    $hero_image_url = wp_get_attachment_image_url( $hero_background_image, 'hero-image' );
}
if ( ! $hero_image_url ) {
    $hero_image_url = get_template_directory_uri() . '/assets/images/main.png';
}
?>

<section id="hero">
    <div class="hero-image">
        <img src="<?php echo esc_url( $hero_image_url ); ?>" alt="<?php echo esc_attr( $hero_name ); ?>" fetchpriority="high" decoding="async" />
    </div>
    <div class="hero-content">
        <h2><?php echo wp_kses_post( $hero_name ); ?></h2>
        <div class="hero-divider"></div>
        <p><?php echo wp_kses_post( $hero_subtitle ); ?></p>
        <a href="<?php echo esc_url( $hero_cta_link ); ?>" class="hero-cta"><?php echo esc_html( $hero_cta_text ); ?></a>
    </div>
    <div class="hero-scroll">
        <i class="fa-solid fa-chevron-down"></i>
    </div>
</section>

