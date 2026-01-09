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

// Получаем заголовок секции "Сотрудничество"
$recognition_partners_title = function_exists( 'get_field' ) ? get_field( 'recognition_partners_title', $current_page_id ) : null;
$recognition_partners_title = $recognition_partners_title ?: 'Сотрудничество';

// Пробуем разные варианты имен полей для совместимости
$recognition_partners = function_exists( 'get_field' ) ? get_field( 'recognition_partners', $current_page_id ) : false;
if ( empty( $recognition_partners ) || ! is_array( $recognition_partners ) ) {
    // Пробуем альтернативное имя поля
    $recognition_partners = function_exists( 'get_field' ) ? get_field( 'partners', $current_page_id ) : false;
}
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
                <h4><?php echo wp_kses_post( $recognition_partners_title ); ?></h4>
                <div class="partners-grid">
                    <?php foreach ( $recognition_partners as $partner ) : 
                        // Получаем данные из repeater элемента напрямую
                        // Пробуем разные варианты имен полей для совместимости
                        $partner_name = isset( $partner['partner_name'] ) ? $partner['partner_name'] : '';
                        // Также проверяем альтернативные имена полей
                        if ( empty( $partner_name ) && isset( $partner['name'] ) ) {
                            $partner_name = $partner['name'];
                        }
                        if ( empty( $partner_name ) && isset( $partner['title'] ) ) {
                            $partner_name = $partner['title'];
                        }
                        
                        // Получаем изображение партнера (опционально)
                        $partner_logo = isset( $partner['partner_logo'] ) ? $partner['partner_logo'] : false;
                        // Также проверяем альтернативное имя поля
                        if ( ! $partner_logo && isset( $partner['partner_image'] ) ) {
                            $partner_logo = $partner['partner_image'];
                        }
                        if ( ! $partner_logo && isset( $partner['logo'] ) ) {
                            $partner_logo = $partner['logo'];
                        }
                        if ( ! $partner_logo && isset( $partner['image'] ) ) {
                            $partner_logo = $partner['image'];
                        }
                        
                        // Получаем ID изображения
                        $logo_id = false;
                        $logo_url = '';
                        $logo_alt = $partner_name ?: 'Логотип партнера';
                        
                        if ( $partner_logo ) {
                            if ( is_array( $partner_logo ) && ! empty( $partner_logo['ID'] ) ) {
                                $logo_id = $partner_logo['ID'];
                                $logo_alt = isset( $partner_logo['alt'] ) ? $partner_logo['alt'] : $logo_alt;
                            } elseif ( is_numeric( $partner_logo ) ) {
                                $logo_id = $partner_logo;
                            }
                            
                            if ( $logo_id ) {
                                $logo_url = wp_get_attachment_image_url( $logo_id, 'medium' );
                                if ( $logo_url ) {
                                    // Принудительно используем HTTPS
                                    $logo_url = set_url_scheme( $logo_url, 'https' );
                                }
                            }
                        }
                        
                        // Показываем элемент, если есть хотя бы имя или логотип
                        if ( empty( $partner_name ) && empty( $logo_url ) ) {
                            continue;
                        }
                    ?>
                        <div class="partner-item">
                            <?php if ( ! empty( $logo_url ) ) : ?>
                                <div class="partner-logo">
                                    <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( $logo_alt ); ?>" loading="lazy" decoding="async" />
                                </div>
                            <?php endif; ?>
                            <?php if ( ! empty( $partner_name ) ) : ?>
                                <div class="partner-name">
                                    <p><?php echo wp_kses_post( nl2br( esc_html( $partner_name ) ) ); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

