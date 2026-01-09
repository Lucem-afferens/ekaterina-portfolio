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

// Получаем данные из SCF
$intro_title = ekaterina_get_scf_field( 'intro_title', 'Создаю атмосферу<br/>незабываемых событий' );
$intro_description = class_exists( 'SCF' ) ? SCF::get( 'intro_description' ) : '';
$intro_image = class_exists( 'SCF' ) ? SCF::get( 'intro_image' ) : '';

// Получаем URL изображения
$intro_image_url = '';
if ( $intro_image ) {
    $intro_image_url = wp_get_attachment_image_url( $intro_image, 'full' );
}
if ( ! $intro_image_url ) {
    $intro_image_url = get_template_directory_uri() . '/assets/images/portrait.png';
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

