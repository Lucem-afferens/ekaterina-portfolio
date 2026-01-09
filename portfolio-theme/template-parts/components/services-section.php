<?php
/**
 * Services Section Component
 * 
 * Компонент секции "Услуги".
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

$services_title = function_exists( 'get_field' ) ? get_field( 'services_title', $current_page_id ) : null;
$services_title = $services_title ?: 'Услуги';

$services_description = function_exists( 'get_field' ) ? get_field( 'services_description', $current_page_id ) : null;
$services_description = $services_description ?: 'Комплексный подход к организации вашего события';

// Убираем автоматически созданные p теги из описания, если они есть
if ( ! empty( $services_description ) ) {
    // Удаляем открывающие и закрывающие p теги
    $services_description = preg_replace( '/<p[^>]*>/', '', $services_description );
    $services_description = preg_replace( '/<\/p>/', '', $services_description );
    // Убираем лишние пробелы
    $services_description = trim( $services_description );
}

$services_list = function_exists( 'get_field' ) ? get_field( 'services_list', $current_page_id ) : false;
if ( empty( $services_list ) || ! is_array( $services_list ) ) {
    $services_list = array();
}
?>

<section id="services">
    <div class="services-container">
        <div class="services-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo wp_kses_post( $services_title ); ?></h3>
            <?php if ( ! empty( $services_description ) ) : ?>
                <div class="services-description"><?php echo wp_kses_post( $services_description ); ?></div>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $services_list ) ) : 
            // Разбиваем услуги на две группы для разных grid-ов
            $services_count = count( $services_list );
            $first_group = array_slice( $services_list, 0, 3 );
            $second_group = array_slice( $services_list, 3, 2 );
        ?>
            <?php if ( ! empty( $first_group ) ) : ?>
                <div class="services-grid-1">
                    <?php foreach ( $first_group as $service ) : 
                        // Получаем данные из repeater элемента напрямую
                        $service_title = isset( $service['service_title'] ) ? $service['service_title'] : '';
                        $service_description = isset( $service['service_description'] ) ? $service['service_description'] : '';
                        $service_points = isset( $service['service_points'] ) && is_array( $service['service_points'] ) ? $service['service_points'] : array();
                        
                        if ( empty( $service_title ) ) {
                            continue;
                        }
                    ?>
                        <div class="service-card">
                            <h4><?php echo esc_html( $service_title ); ?></h4>
                            <div class="service-divider"></div>
                            <?php if ( ! empty( $service_description ) ) : ?>
                                <p><?php echo esc_html( $service_description ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $service_points ) && is_array( $service_points ) ) : ?>
                                <ul class="service-list">
                                    <?php foreach ( $service_points as $point ) : 
                                        $point_text = is_array( $point ) ? ( isset( $point['point_text'] ) ? $point['point_text'] : '' ) : $point;
                                        if ( empty( $point_text ) ) continue;
                                    ?>
                                        <li><span>—</span><span><?php echo esc_html( $point_text ); ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ( ! empty( $second_group ) ) : ?>
                <div class="services-grid-2">
                    <?php foreach ( $second_group as $service ) : 
                        // Получаем данные из repeater элемента напрямую
                        $service_title = isset( $service['service_title'] ) ? $service['service_title'] : '';
                        $service_description = isset( $service['service_description'] ) ? $service['service_description'] : '';
                        $service_points = isset( $service['service_points'] ) && is_array( $service['service_points'] ) ? $service['service_points'] : array();
                        
                        if ( empty( $service_title ) ) {
                            continue;
                        }
                    ?>
                        <div class="service-card">
                            <h4><?php echo esc_html( $service_title ); ?></h4>
                            <div class="service-divider"></div>
                            <?php if ( ! empty( $service_description ) ) : ?>
                                <p><?php echo esc_html( $service_description ); ?></p>
                            <?php endif; ?>
                            <?php if ( ! empty( $service_points ) && is_array( $service_points ) ) : ?>
                                <ul class="service-list">
                                    <?php foreach ( $service_points as $point ) : 
                                        $point_text = is_array( $point ) ? ( isset( $point['point_text'] ) ? $point['point_text'] : '' ) : $point;
                                        if ( empty( $point_text ) ) continue;
                                    ?>
                                        <li><span>—</span><span><?php echo esc_html( $point_text ); ?></span></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

