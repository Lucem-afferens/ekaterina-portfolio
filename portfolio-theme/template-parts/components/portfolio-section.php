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

// Проверяем, включена ли секция Portfolio
$portfolio_enabled = function_exists( 'get_field' ) ? get_field( 'portfolio_enabled', $current_page_id ) : true;
// Если поле не установлено, по умолчанию секция включена
if ( $portfolio_enabled === null ) {
    $portfolio_enabled = true;
}
// Преобразуем в boolean
$portfolio_enabled = (bool) $portfolio_enabled;

// Если секция отключена, не выводим её
if ( ! $portfolio_enabled ) {
    return;
}

$portfolio_title = function_exists( 'get_field' ) ? get_field( 'portfolio_title', $current_page_id ) : null;
$portfolio_title = $portfolio_title ?: 'Портфолио';

$portfolio_description = function_exists( 'get_field' ) ? get_field( 'portfolio_description', $current_page_id ) : null;
$portfolio_description = $portfolio_description ?: 'Избранные работы из портфолио';

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

/**
 * Функция для вывода элемента портфолио
 * 
 * @param array $item Элемент из repeater
 * @return void
 */
function ekaterina_render_portfolio_item( $item ) {
    // Получаем данные из repeater элемента
    $portfolio_image = isset( $item['portfolio_image'] ) ? $item['portfolio_image'] : false;
    $title = isset( $item['portfolio_title'] ) ? $item['portfolio_title'] : '';
    $category = isset( $item['portfolio_category'] ) ? $item['portfolio_category'] : $title;
    $link = isset( $item['portfolio_link'] ) ? trim( $item['portfolio_link'] ) : '';
    $gallery_images_field = isset( $item['portfolio_gallery_images'] ) ? $item['portfolio_gallery_images'] : false;
    
    // Получаем ID изображения
    $image_id = false;
    if ( $portfolio_image ) {
        if ( is_array( $portfolio_image ) && ! empty( $portfolio_image['ID'] ) ) {
            $image_id = $portfolio_image['ID'];
        } elseif ( is_numeric( $portfolio_image ) ) {
            $image_id = $portfolio_image;
        }
    }
    
    if ( ! $image_id ) {
        return;
    }
    
    $image_url = wp_get_attachment_image_url( $image_id, 'portfolio-large' );
    if ( ! $image_url ) {
        return;
    }
    // Принудительно используем HTTPS
    $image_url = set_url_scheme( $image_url, 'https' );
    
    // Определяем тип действия по приоритетам:
    // 1. Если заполнена ссылка → открывается ссылка
    // 2. Если загружены фото для галереи → открывается галерея
    // 3. Если ничего не заполнено → просто изображение без реакции
    
    $action_type = 'none'; // По умолчанию нет действия
    $gallery_images = array();
    
    // Приоритет 1: Проверяем ссылку
    if ( ! empty( $link ) ) {
        $action_type = 'link';
        $link = esc_url( $link );
    }
    // Приоритет 2: Проверяем галерею изображений
    elseif ( ! empty( $gallery_images_field ) ) {
        $action_type = 'gallery';
        
        // Обрабатываем поле галереи (может быть массив ID или массив объектов)
        $gallery_ids = array();
        
        if ( is_array( $gallery_images_field ) ) {
            foreach ( $gallery_images_field as $gallery_item ) {
                $gallery_image_id = false;
                
                // Если это массив с ID
                if ( is_array( $gallery_item ) && ! empty( $gallery_item['ID'] ) ) {
                    $gallery_image_id = $gallery_item['ID'];
                } elseif ( is_numeric( $gallery_item ) ) {
                    $gallery_image_id = $gallery_item;
                }
                
                if ( $gallery_image_id ) {
                    $gallery_image_url = wp_get_attachment_image_url( $gallery_image_id, 'full' );
                    if ( $gallery_image_url ) {
                        $gallery_images[] = array(
                            'id' => $gallery_image_id,
                            'url' => set_url_scheme( $gallery_image_url, 'https' ),
                            'alt' => $category
                        );
                    }
                }
            }
        }
        
        // Если галерея пустая, убираем тип действия
        if ( empty( $gallery_images ) ) {
            $action_type = 'none';
        }
    }
    ?>
    <div class="portfolio-item" 
         <?php if ( $action_type === 'gallery' && ! empty( $gallery_images ) ) : ?>
             data-gallery-action="true"
             data-gallery-category="<?php echo esc_attr( $category ); ?>"
             data-gallery-images='<?php echo esc_attr( wp_json_encode( $gallery_images ) ); ?>'
         <?php endif; ?>
    >
        <?php if ( $action_type === 'link' && ! empty( $link ) ) : ?>
            <a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer" class="portfolio-link">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category ); ?>" loading="lazy" decoding="async" />
                <div class="portfolio-overlay">
                    <p><?php echo esc_html( $category ); ?></p>
                </div>
            </a>
        <?php elseif ( $action_type === 'gallery' && ! empty( $gallery_images ) ) : ?>
            <button type="button" class="portfolio-gallery-trigger" aria-label="Открыть галерею: <?php echo esc_attr( $category ); ?>">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category ); ?>" loading="lazy" decoding="async" />
                <div class="portfolio-overlay">
                    <p><?php echo esc_html( $category ); ?></p>
                </div>
            </button>
        <?php else : ?>
            <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $category ); ?>" loading="lazy" decoding="async" />
            <div class="portfolio-overlay">
                <p><?php echo esc_html( $category ); ?></p>
            </div>
        <?php endif; ?>
    </div>
    <?php
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
                        ekaterina_render_portfolio_item( $portfolio_items[ $current_index ] );
                    endfor; ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $current_index < $items_count ) : ?>
                <div class="portfolio-grid-2">
                    <?php for ( $i = 0; $i < 2 && $current_index < $items_count; $i++, $current_index++ ) : 
                        ekaterina_render_portfolio_item( $portfolio_items[ $current_index ] );
                    endfor; ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $current_index < $items_count ) : ?>
                <div class="portfolio-grid-3">
                    <?php for ( $i = 0; $i < 2 && $current_index < $items_count; $i++, $current_index++ ) : 
                        ekaterina_render_portfolio_item( $portfolio_items[ $current_index ] );
                    endfor; ?>
                </div>
            <?php endif; ?>
            
            <?php if ( $current_index < $items_count ) : ?>
                <div class="portfolio-grid-4">
                    <?php for ( $i = 0; $i < 2 && $current_index < $items_count; $i++, $current_index++ ) : 
                        ekaterina_render_portfolio_item( $portfolio_items[ $current_index ] );
                    endfor; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="portfolio-cta-container">
            <a href="#contact" class="portfolio-cta">СВЯЗАТЬСЯ СО МНОЙ</a>
        </div>
    </div>
</section>

