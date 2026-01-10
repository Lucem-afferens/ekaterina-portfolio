<?php
/**
 * Testimonials Section Component
 * 
 * Компонент секции "Отзывы".
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

// Проверяем, включена ли секция Отзывы
$testimonials_enabled = function_exists( 'get_field' ) ? get_field( 'testimonials_enabled', $current_page_id ) : true;
// Если поле не установлено, по умолчанию секция включена
if ( $testimonials_enabled === null ) {
    $testimonials_enabled = true;
}
// Преобразуем в boolean
$testimonials_enabled = (bool) $testimonials_enabled;

// Если секция отключена, не выводим её
if ( ! $testimonials_enabled ) {
    return;
}

$testimonials_title = function_exists( 'get_field' ) ? get_field( 'testimonials_title', $current_page_id ) : null;
$testimonials_title = $testimonials_title ?: 'Отзывы';

// Пробуем разные варианты имен полей для совместимости
$testimonials_description = function_exists( 'get_field' ) ? get_field( 'testimonials_description', $current_page_id ) : null;
if ( empty( $testimonials_description ) ) {
    // Пробуем альтернативное имя поля
    $testimonials_description = function_exists( 'get_field' ) ? get_field( 'testimonials_subtitle', $current_page_id ) : null;
}
$testimonials_description = $testimonials_description ?: 'Мнения тех, с кем я работала';

// Убираем автоматически созданные p теги из описания, если они есть
if ( ! empty( $testimonials_description ) ) {
    // Удаляем открывающие и закрывающие p теги
    $testimonials_description = preg_replace( '/<p[^>]*>/', '', $testimonials_description );
    $testimonials_description = preg_replace( '/<\/p>/', '', $testimonials_description );
    // Убираем лишние пробелы
    $testimonials_description = trim( $testimonials_description );
}

// Пробуем разные варианты имен полей для совместимости
$testimonials_list = function_exists( 'get_field' ) ? get_field( 'testimonials_list', $current_page_id ) : false;
if ( empty( $testimonials_list ) || ! is_array( $testimonials_list ) ) {
    // Пробуем альтернативное имя поля
    $testimonials_list = function_exists( 'get_field' ) ? get_field( 'testimonials_items', $current_page_id ) : false;
}
if ( empty( $testimonials_list ) || ! is_array( $testimonials_list ) ) {
    $testimonials_list = array();
}
?>

<section id="testimonials">
    <div class="testimonials-container">
        <div class="testimonials-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo wp_kses_post( $testimonials_title ); ?></h3>
            <?php if ( ! empty( $testimonials_description ) ) : ?>
                <div class="testimonials-description"><?php echo wp_kses_post( $testimonials_description ); ?></div>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $testimonials_list ) ) : 
            // Разбиваем отзывы на группы по 2 для разных grid-ов
            $testimonials_chunks = array_chunk( $testimonials_list, 2 );
        ?>
            <?php foreach ( $testimonials_chunks as $chunk ) : ?>
                <div class="testimonials-grid">
                    <?php foreach ( $chunk as $testimonial ) : 
                        // Получаем данные из repeater элемента напрямую
                        // Пробуем разные варианты имен полей для совместимости
                        $quote = isset( $testimonial['testimonial_quote'] ) ? $testimonial['testimonial_quote'] : '';
                        $author = isset( $testimonial['testimonial_author'] ) ? $testimonial['testimonial_author'] : '';
                        // Также проверяем альтернативное имя поля (testimonial_author_name)
                        if ( empty( $author ) && isset( $testimonial['testimonial_author_name'] ) ) {
                            $author = $testimonial['testimonial_author_name'];
                        }
                        $title = isset( $testimonial['testimonial_title'] ) ? $testimonial['testimonial_title'] : '';
                        // Также проверяем альтернативное имя поля (testimonial_author_title)
                        if ( empty( $title ) && isset( $testimonial['testimonial_author_title'] ) ) {
                            $title = $testimonial['testimonial_author_title'];
                        }
                        
                        if ( empty( $quote ) || empty( $author ) ) {
                            continue;
                        }
                    ?>
                        <div class="testimonial-card">
                            <p class="testimonial-quote"><?php echo esc_html( $quote ); ?></p>
                            <div class="testimonial-author">
                                <div class="author-divider"></div>
                                <div>
                                    <p class="author-name"><?php echo esc_html( $author ); ?></p>
                                    <?php if ( ! empty( $title ) ) : ?>
                                        <p class="author-title"><?php echo esc_html( $title ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <?php 
        // Получаем ссылку для кнопки "Оставить отзыв" из SCF
        $testimonials_cta_link = function_exists( 'get_field' ) ? get_field( 'testimonials_cta_link', $current_page_id ) : null;
        
        // Если ссылка указана, показываем кнопку со ссылкой
        if ( ! empty( $testimonials_cta_link ) ) :
            // Нормализуем ссылку (обеспечиваем HTTPS для внешних ссылок)
            $testimonials_cta_link = set_url_scheme( esc_url_raw( $testimonials_cta_link ), 'https' );
            
            // Определяем, является ли ссылка внешней (начинается с http:// или https:// и не относится к текущему домену)
            $is_external = false;
            if ( strpos( $testimonials_cta_link, 'http://' ) === 0 || strpos( $testimonials_cta_link, 'https://' ) === 0 ) {
                // Извлекаем домен из ссылки
                $link_host = parse_url( $testimonials_cta_link, PHP_URL_HOST );
                $site_host = parse_url( home_url(), PHP_URL_HOST );
                // Если домены не совпадают, это внешняя ссылка
                if ( $link_host && $site_host && $link_host !== $site_host ) {
                    $is_external = true;
                }
            }
        ?>
            <div class="testimonials-cta-container">
                <a href="<?php echo esc_url( $testimonials_cta_link ); ?>" 
                   class="testimonials-cta" 
                   <?php if ( $is_external ) : ?>
                       target="_blank" 
                       rel="noopener noreferrer"
                   <?php endif; ?>>
                    ОСТАВИТЬ ОТЗЫВ
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

