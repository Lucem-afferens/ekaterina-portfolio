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
$testimonials_title = function_exists( 'get_field' ) ? get_field( 'testimonials_title' ) : null;
$testimonials_title = $testimonials_title ?: 'Отзывы';

$testimonials_description = function_exists( 'get_field' ) ? get_field( 'testimonials_description' ) : null;
$testimonials_description = $testimonials_description ?: 'Мнения тех, с кем я работала';

// Убираем автоматически созданные p теги из описания, если они есть
if ( ! empty( $testimonials_description ) ) {
    // Удаляем открывающие и закрывающие p теги
    $testimonials_description = preg_replace( '/<p[^>]*>/', '', $testimonials_description );
    $testimonials_description = preg_replace( '/<\/p>/', '', $testimonials_description );
    // Убираем лишние пробелы
    $testimonials_description = trim( $testimonials_description );
}

$testimonials_list = ekaterina_get_scf_repeater( 'testimonials_list' );
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
                        $quote = ekaterina_get_repeater_field( $testimonial, 'testimonial_quote', '' );
                        $author = ekaterina_get_repeater_field( $testimonial, 'testimonial_author', '' );
                        $title = ekaterina_get_repeater_field( $testimonial, 'testimonial_title', '' );
                        
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
        
        <div class="testimonials-cta-container">
            <button class="testimonials-cta" id="open-testimonial-modal">ОСТАВИТЬ ОТЗЫВ</button>
        </div>
    </div>
</section>

