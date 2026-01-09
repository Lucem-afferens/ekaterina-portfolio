<?php
/**
 * Main Template File
 * 
 * Fallback шаблон для всех типов записей и страниц.
 * Используется, если более специфичный шаблон не найден.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main">
    <?php
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>

                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
            <?php
        }
    } else {
        ?>
        <p><?php esc_html_e( 'Контент не найден.', 'ekaterina-portfolio' ); ?></p>
        <?php
    }
    ?>
</main>

<?php
get_footer();

