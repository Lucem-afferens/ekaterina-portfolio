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
                // SCF совместим с API ACF, используем get_field(..., 'option')
                $host_name = function_exists( 'get_field' ) ? get_field( 'site_host_name', 'option' ) : null;
                $host_title = function_exists( 'get_field' ) ? get_field( 'site_host_title', 'option' ) : null;
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
            <?php
            // Получаем текст копирайта из SCF Options
            $footer_copyright = function_exists( 'get_field' ) ? get_field( 'footer_copyright_text', 'option' ) : null;
            if ( empty( $footer_copyright ) ) {
                // Формируем копирайт по умолчанию: год + имя + "Все права защищены"
                $current_year = date( 'Y' );
                $copyright_name = ! empty( $host_name ) ? $host_name : get_bloginfo( 'name' );
                $footer_copyright = sprintf( '© %s %s. Все права защищены', $current_year, $copyright_name );
            } else {
                // Если указан пользовательский текст, заменяем %year% на текущий год
                $footer_copyright = str_replace( '%year%', date( 'Y' ), $footer_copyright );
            }
            
            // Получаем текст кнопки "Наверх"
            $footer_top_link_text = function_exists( 'get_field' ) ? get_field( 'footer_top_link_text', 'option' ) : null;
            if ( empty( $footer_top_link_text ) ) {
                $footer_top_link_text = 'Наверх';
            }
            ?>
            <p class="footer-copyright">
                <?php echo wp_kses_post( $footer_copyright ); ?>
            </p>
            <a href="#hero" class="footer-top-link"><?php echo esc_html( $footer_top_link_text ); ?></a>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>

