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

// Получаем данные из SCF (используем get_field() напрямую, как в других секциях)
// Получаем ID текущей страницы для правильного контекста
$current_page_id = ekaterina_get_current_page_id();

$portfolio_title = function_exists( 'get_field' ) ? get_field( 'portfolio_title', $current_page_id ) : null;
$portfolio_title = $portfolio_title ?: 'Портфолио';

$portfolio_description = function_exists( 'get_field' ) ? get_field( 'portfolio_description', $current_page_id ) : null;
$portfolio_description = $portfolio_description ?: 'Избранные моменты из проведённых мероприятий';

// Убираем автоматически созданные p теги из описания, если они есть
if ( ! empty( $portfolio_description ) ) {
    // Удаляем открывающие и закрывающие p теги
    $portfolio_description = preg_replace( '/<p[^>]*>/', '', $portfolio_description );
    $portfolio_description = preg_replace( '/<\/p>/', '', $portfolio_description );
    // Убираем лишние пробелы
    $portfolio_description = trim( $portfolio_description );
}

$portfolio_items = function_exists( 'get_field' ) ? get_field( 'portfolio_items', $current_page_id ) : false;
if ( empty( $portfolio_items ) || ! is_array( $portfolio_items ) ) {
    $portfolio_items = array();
}
?>

<section id="portfolio">
    <div class="portfolio-container">
        <div class="portfolio-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo wp_kses_post( $portfolio_title ); ?></h3>
            <?php if ( ! empty( $portfolio_description ) ) : ?>
                <div class="portfolio-description"><?php echo wp_kses_post( $portfolio_description ); ?></div>
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
                        
                        // Получаем данные из repeater элемента напрямую
                        // get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
                        $portfolio_image = isset( $item['portfolio_image'] ) ? $item['portfolio_image'] : false;
                        $title = isset( $item['portfolio_title'] ) ? $item['portfolio_title'] : '';
                        $category = isset( $item['portfolio_category'] ) ? $item['portfolio_category'] : $title;
                        
                        // Получаем ID изображения
                        $image_id = false;
                        if ( $portfolio_image ) {
                            if ( is_array( $portfolio_image ) && ! empty( $portfolio_image['ID'] ) ) {
                                $image_id = $portfolio_image['ID'];
                            } elseif ( is_numeric( $portfolio_image ) ) {
                                $image_id = $portfolio_image;
                            }
                        }
                        
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
                        
                        // Получаем данные из repeater элемента напрямую
                        // get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
                        $portfolio_image = isset( $item['portfolio_image'] ) ? $item['portfolio_image'] : false;
                        $title = isset( $item['portfolio_title'] ) ? $item['portfolio_title'] : '';
                        $category = isset( $item['portfolio_category'] ) ? $item['portfolio_category'] : $title;
                        
                        // Получаем ID изображения
                        $image_id = false;
                        if ( $portfolio_image ) {
                            if ( is_array( $portfolio_image ) && ! empty( $portfolio_image['ID'] ) ) {
                                $image_id = $portfolio_image['ID'];
                            } elseif ( is_numeric( $portfolio_image ) ) {
                                $image_id = $portfolio_image;
                            }
                        }
                        
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
                        
                        // Получаем данные из repeater элемента напрямую
                        // get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
                        $portfolio_image = isset( $item['portfolio_image'] ) ? $item['portfolio_image'] : false;
                        $title = isset( $item['portfolio_title'] ) ? $item['portfolio_title'] : '';
                        $category = isset( $item['portfolio_category'] ) ? $item['portfolio_category'] : $title;
                        
                        // Получаем ID изображения
                        $image_id = false;
                        if ( $portfolio_image ) {
                            if ( is_array( $portfolio_image ) && ! empty( $portfolio_image['ID'] ) ) {
                                $image_id = $portfolio_image['ID'];
                            } elseif ( is_numeric( $portfolio_image ) ) {
                                $image_id = $portfolio_image;
                            }
                        }
                        
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
                        
                        // Получаем данные из repeater элемента напрямую
                        // get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
                        $portfolio_image = isset( $item['portfolio_image'] ) ? $item['portfolio_image'] : false;
                        $title = isset( $item['portfolio_title'] ) ? $item['portfolio_title'] : '';
                        $category = isset( $item['portfolio_category'] ) ? $item['portfolio_category'] : $title;
                        
                        // Получаем ID изображения
                        $image_id = false;
                        if ( $portfolio_image ) {
                            if ( is_array( $portfolio_image ) && ! empty( $portfolio_image['ID'] ) ) {
                                $image_id = $portfolio_image['ID'];
                            } elseif ( is_numeric( $portfolio_image ) ) {
                                $image_id = $portfolio_image;
                            }
                        }
                        
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

