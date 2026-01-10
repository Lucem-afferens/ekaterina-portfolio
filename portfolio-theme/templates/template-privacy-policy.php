<?php
/**
 * Template Name: Privacy Policy
 * 
 * Шаблон страницы политики конфиденциальности.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main" class="site-main privacy-policy-page">
    <?php
    while ( have_posts() ) {
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <div class="privacy-policy-container">
                <header class="privacy-policy-header">
                    <h1 class="privacy-policy-title"><?php the_title(); ?></h1>
                    <div class="privacy-policy-divider"></div>
                    <?php if ( get_the_excerpt() ) : ?>
                        <p class="privacy-policy-intro"><?php echo esc_html( get_the_excerpt() ); ?></p>
                    <?php endif; ?>
                </header>

                <div class="privacy-policy-content">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Страницы:', 'portfolio-theme' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>

                <footer class="privacy-policy-footer">
                    <p class="privacy-policy-updated">
                        <strong>Последнее обновление:</strong> <?php echo esc_html( get_the_modified_date( 'd.m.Y' ) ); ?>
                    </p>
                </footer>
            </div>
        </article>
        <?php
    }
    ?>
</main>

<?php
get_footer();

