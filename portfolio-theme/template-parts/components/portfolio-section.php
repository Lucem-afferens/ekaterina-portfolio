<?php
/**
 * Portfolio Section Component
 * 
 * Компонент секции "Портфолио".
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Получаем данные из SCF
$portfolio_title = ekaterina_get_scf_field( 'portfolio_title', 'Портфолио' );
$portfolio_description = ekaterina_get_scf_field( 'portfolio_description', 'Избранные моменты из проведённых мероприятий' );
$portfolio_items = ekaterina_get_scf_repeater( 'portfolio_items' );
?>

<section id="portfolio">
    <div class="portfolio-container">
        <div class="portfolio-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo esc_html( $portfolio_title ); ?></h3>
            <?php if ( ! empty( $portfolio_description ) ) : ?>
                <p><?php echo esc_html( $portfolio_description ); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $portfolio_items ) ) : 
            // Разбиваем элементы на группы для разных grid-ов
            $items_count = count( $portfolio_items );
            $current_index = 0;
        ?>
            <?php if ( $current_index < $items_count ) : ?>
                <div class="portfolio-grid-1">
                    <?php for ( $i = 0; $i < 2 && $current_index < $items_count; $i++, $current_index++ ) : 
                        $item = $portfolio_items[ $current_index ];
                        $image_id = ekaterina_get_repeater_field( $item, 'portfolio_image' );
                        $title = ekaterina_get_repeater_field( $item, 'portfolio_title', '' );
                        $category = ekaterina_get_repeater_field( $item, 'portfolio_category', $title );
                        
                        if ( ! $image_id ) continue;
                        
                        $image_url = wp_get_attachment_image_url( $image_id, 'portfolio-large' );
                        if ( ! $image_url ) continue;
                        // Принудительно используем HTTPS
                        $image_url = set_url_scheme( $image_url, 'https' );
                    ?>
                        <div class="portfolio-item">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category ); ?>" loading="lazy" decoding="async" />
                            <div class="portfolio-overlay">
                                <p><?php echo esc_html( $category ); ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $current_index < $items_count ) : ?>
                <div class="portfolio-grid-2">
                    <?php for ( $i = 0; $i < 2 && $current_index < $items_count; $i++, $current_index++ ) : 
                        $item = $portfolio_items[ $current_index ];
                        $image_id = ekaterina_get_repeater_field( $item, 'portfolio_image' );
                        $title = ekaterina_get_repeater_field( $item, 'portfolio_title', '' );
                        $category = ekaterina_get_repeater_field( $item, 'portfolio_category', $title );
                        
                        if ( ! $image_id ) continue;
                        
                        $image_url = wp_get_attachment_image_url( $image_id, 'portfolio-large' );
                        if ( ! $image_url ) continue;
                        // Принудительно используем HTTPS
                        $image_url = set_url_scheme( $image_url, 'https' );
                    ?>
                        <div class="portfolio-item">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category ); ?>" loading="lazy" decoding="async" />
                            <div class="portfolio-overlay">
                                <p><?php echo esc_html( $category ); ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $current_index < $items_count ) : ?>
                <div class="portfolio-grid-3">
                    <?php for ( $i = 0; $i < 2 && $current_index < $items_count; $i++, $current_index++ ) : 
                        $item = $portfolio_items[ $current_index ];
                        $image_id = ekaterina_get_repeater_field( $item, 'portfolio_image' );
                        $title = ekaterina_get_repeater_field( $item, 'portfolio_title', '' );
                        $category = ekaterina_get_repeater_field( $item, 'portfolio_category', $title );
                        
                        if ( ! $image_id ) continue;
                        
                        $image_url = wp_get_attachment_image_url( $image_id, 'portfolio-large' );
                        if ( ! $image_url ) continue;
                        // Принудительно используем HTTPS
                        $image_url = set_url_scheme( $image_url, 'https' );
                    ?>
                        <div class="portfolio-item">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category ); ?>" loading="lazy" decoding="async" />
                            <div class="portfolio-overlay">
                                <p><?php echo esc_html( $category ); ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $current_index < $items_count ) : ?>
                <div class="portfolio-grid-4">
                    <?php for ( $i = 0; $i < 2 && $current_index < $items_count; $i++, $current_index++ ) : 
                        $item = $portfolio_items[ $current_index ];
                        $image_id = ekaterina_get_repeater_field( $item, 'portfolio_image' );
                        $title = ekaterina_get_repeater_field( $item, 'portfolio_title', '' );
                        $category = ekaterina_get_repeater_field( $item, 'portfolio_category', $title );
                        
                        if ( ! $image_id ) continue;
                        
                        $image_url = wp_get_attachment_image_url( $image_id, 'portfolio-large' );
                        if ( ! $image_url ) continue;
                        // Принудительно используем HTTPS
                        $image_url = set_url_scheme( $image_url, 'https' );
                    ?>
                        <div class="portfolio-item">
                            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category ); ?>" loading="lazy" decoding="async" />
                            <div class="portfolio-overlay">
                                <p><?php echo esc_html( $category ); ?></p>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="portfolio-cta-container">
            <a href="#contact" class="portfolio-cta">ОБСУДИТЬ ВАШЕ МЕРОПРИЯТИЕ</a>
        </div>
    </div>
</section>

