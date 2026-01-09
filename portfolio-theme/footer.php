<?php
/**
 * Footer Template
 * 
 * Шаблон подвала сайта. Содержит информацию о копирайте и ссылки.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */
?>

<footer id="footer">
    <div class="footer-container">
        <div class="footer-top">
            <div class="footer-brand">
                <?php
                // Получаем имя из SCF Options или используем название сайта
                $host_name = '';
                $host_title = '';
                if ( class_exists( 'SCF' ) ) {
                    $host_name = SCF::get_option_meta( 'theme_options', 'site_host_name' );
                    $host_title = SCF::get_option_meta( 'theme_options', 'site_host_title' );
                }
                if ( empty( $host_name ) ) {
                    $host_name = get_bloginfo( 'name' );
                }
                if ( empty( $host_title ) ) {
                    $host_title = 'Ведущая премиальных мероприятий';
                }
                ?>
                <h4><?php echo esc_html( $host_name ); ?></h4>
                <p><?php echo esc_html( $host_title ); ?></p>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="footer-copyright">
                &copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php esc_html_e( 'Все права защищены', 'ekaterina-portfolio' ); ?>
            </p>
            <a href="#hero" class="footer-top-link"><?php esc_html_e( 'Наверх', 'ekaterina-portfolio' ); ?></a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>

