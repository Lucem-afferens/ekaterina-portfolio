<?php
/**
 * Template for displaying pages
 * 
 * Шаблон для отображения страниц WordPress.
 * Если это страница политики конфиденциальности, используется специальный шаблон.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Проверяем, является ли это страницей политики конфиденциальности
if ( function_exists( 'ekaterina_is_privacy_policy_page' ) && ekaterina_is_privacy_policy_page() ) {
    $privacy_template = locate_template( 'templates/template-privacy-policy.php' );
    if ( $privacy_template ) {
        include( $privacy_template );
        return;
    }
}

// Обычный шаблон страницы
get_header();
?>

<main id="main" class="site-main">
    <?php
    while ( have_posts() ) {
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>

            <div class="entry-content">
                <?php
                the_content();

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . esc_html__( 'Страницы:', 'ekaterina-portfolio' ),
                    'after'  => '</div>',
                ) );
                ?>
            </div>
        </article>
        <?php
    }
    ?>
</main>

<?php
get_footer();

