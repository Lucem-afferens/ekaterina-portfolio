<?php
/**
 * Template Name: Homepage
 * 
 * Кастомный шаблон главной страницы.
 * Подключает все компоненты секций сайта.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

get_header();

// Сохраняем ID текущей страницы в глобальной переменной для использования в компонентах
global $ekaterina_current_page_id;
$ekaterina_current_page_id = get_queried_object_id();
if ( ! $ekaterina_current_page_id ) {
    $ekaterina_current_page_id = get_the_ID();
}
?>

<main id="main" class="site-main">
    <?php
    // Подключаем все компоненты секций
    get_template_part( 'template-parts/components/hero-section' );
    get_template_part( 'template-parts/components/introduction-section' );
    get_template_part( 'template-parts/components/stats-banner' );
    get_template_part( 'template-parts/components/philosophy-section' );
    get_template_part( 'template-parts/components/about-section' );
    get_template_part( 'template-parts/components/portfolio-section' );
    get_template_part( 'template-parts/components/expertise-section' );
    get_template_part( 'template-parts/components/services-section' );
    get_template_part( 'template-parts/components/process-section' );
    get_template_part( 'template-parts/components/testimonials-section' );
    get_template_part( 'template-parts/components/recognition-section' );
    get_template_part( 'template-parts/components/contact-section' );
    get_template_part( 'template-parts/components/contact-channels-section' );
    
    // Модальные окна
    get_template_part( 'template-parts/components/portfolio-gallery-modal' );
    ?>
</main>

<?php
get_footer();

