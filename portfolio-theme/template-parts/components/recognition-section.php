<?php
/**
 * Recognition Section Component
 * 
 * Компонент секции "Признание".
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

$recognition_title = function_exists( 'get_field' ) ? get_field( 'recognition_title', $current_page_id ) : null;
$recognition_title = $recognition_title ?: 'Признание';

$recognition_stats = function_exists( 'get_field' ) ? get_field( 'recognition_stats', $current_page_id ) : false;
if ( empty( $recognition_stats ) || ! is_array( $recognition_stats ) ) {
    $recognition_stats = array();
}

$recognition_partners = function_exists( 'get_field' ) ? get_field( 'recognition_partners', $current_page_id ) : false;
if ( empty( $recognition_partners ) || ! is_array( $recognition_partners ) ) {
    $recognition_partners = array();
}
?>

<section id="recognition">
    <div class="recognition-container">
        <div class="recognition-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo wp_kses_post( $recognition_title ); ?></h3>
        </div>
        
        <?php if ( ! empty( $recognition_stats ) ) : ?>
            <div class="recognition-stats">
                <?php foreach ( $recognition_stats as $stat ) : 
                    // Получаем данные из repeater элемента напрямую
                    $number = isset( $stat['recognition_number'] ) ? $stat['recognition_number'] : '';
                    // Также проверяем альтернативное имя поля (number)
                    if ( empty( $number ) && isset( $stat['number'] ) ) {
                        $number = $stat['number'];
                    }
                    $label = isset( $stat['recognition_label'] ) ? $stat['recognition_label'] : '';
                    // Также проверяем альтернативное имя поля (label)
                    if ( empty( $label ) && isset( $stat['label'] ) ) {
                        $label = $stat['label'];
                    }
                    
                    if ( empty( $number ) || empty( $label ) ) {
                        continue;
                    }
                ?>
                    <div>
                        <div class="recognition-stat-number"><?php echo esc_html( $number ); ?></div>
                        <p class="recognition-stat-label"><?php echo esc_html( $label ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ( ! empty( $recognition_partners ) ) : ?>
            <div class="recognition-partners">
                <h4>Сотрудничество</h4>
                <div class="partners-grid">
                    <?php foreach ( $recognition_partners as $partner ) : 
                        // Получаем данные из repeater элемента напрямую
                        $partner_name = isset( $partner['partner_name'] ) ? $partner['partner_name'] : '';
                        
                        if ( empty( $partner_name ) ) {
                            continue;
                        }
                    ?>
                        <div>
                            <p><?php echo wp_kses_post( nl2br( esc_html( $partner_name ) ) ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

