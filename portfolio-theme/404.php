<?php
/**
 * 404 Error Page Template
 * 
 * Шаблон для страницы ошибки 404 (страница не найдена).
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'Страница не найдена', 'ekaterina-portfolio' ); ?></h1>
        </header>

        <div class="page-content">
            <p><?php esc_html_e( 'К сожалению, запрашиваемая страница не существует.', 'ekaterina-portfolio' ); ?></p>
            <p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="button">
                    <?php esc_html_e( 'Вернуться на главную', 'ekaterina-portfolio' ); ?>
                </a>
            </p>
        </div>
    </section>
</main>

<?php
get_footer();

