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
// Сначала проверяем шаблон страницы
$page_template = get_page_template_slug();
$is_privacy_page = ( $page_template === 'templates/template-privacy-policy.php' );

// Если шаблон не назначен, проверяем по slug и названию
if ( ! $is_privacy_page ) {
    global $post;
    if ( $post ) {
        // Проверяем по slug (URL-слаг страницы)
        $post_slug = $post->post_name;
        // Проверяем по названию страницы
        $post_title = $post->post_title;
        
        // Сравниваем slug (может быть 'privacy-policy', 'politika-konfidentsialnosti' и т.д.)
        if ( $post_slug === 'privacy-policy' || 
             $post_slug === 'politika-konfidentsialnosti' ||
             strpos( strtolower( $post_slug ), 'privacy' ) !== false ||
             strpos( strtolower( $post_slug ), 'politika' ) !== false ) {
            $is_privacy_page = true;
        }
        
        // Проверяем по названию страницы
        if ( ! $is_privacy_page && (
             stripos( $post_title, 'Политика конфиденциальности' ) !== false ||
             stripos( $post_title, 'Privacy Policy' ) !== false ||
             stripos( $post_title, 'политика' ) !== false ) ) {
            $is_privacy_page = true;
        }
    }
}

// Если это страница политики, используем специальный шаблон
if ( $is_privacy_page ) {
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

