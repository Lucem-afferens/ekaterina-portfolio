<?php
/**
 * Process Section Component
 * 
 * Компонент секции "Процесс работы".
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

$process_title = function_exists( 'get_field' ) ? get_field( 'process_title', $current_page_id ) : null;
$process_title = $process_title ?: 'Процесс работы';

$process_description = function_exists( 'get_field' ) ? get_field( 'process_description', $current_page_id ) : null;
$process_description = $process_description ?: 'От первой встречи до завершения мероприятия';

// Убираем автоматически созданные p теги из описания, если они есть
if ( ! empty( $process_description ) ) {
    // Удаляем открывающие и закрывающие p теги
    $process_description = preg_replace( '/<p[^>]*>/', '', $process_description );
    $process_description = preg_replace( '/<\/p>/', '', $process_description );
    // Убираем лишние пробелы
    $process_description = trim( $process_description );
}

$process_steps = function_exists( 'get_field' ) ? get_field( 'process_steps', $current_page_id ) : false;
if ( empty( $process_steps ) || ! is_array( $process_steps ) ) {
    $process_steps = array();
}
?>

<section id="process">
    <div class="process-container">
        <div class="process-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo wp_kses_post( $process_title ); ?></h3>
            <?php if ( ! empty( $process_description ) ) : ?>
                <div class="process-description"><?php echo wp_kses_post( $process_description ); ?></div>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $process_steps ) ) : 
            foreach ( $process_steps as $index => $step ) : 
                // Получаем данные из repeater элемента напрямую
                $step_number = isset( $step['step_number'] ) ? $step['step_number'] : str_pad( $index + 1, 2, '0', STR_PAD_LEFT );
                $step_title = isset( $step['step_title'] ) ? $step['step_title'] : '';
                $step_description = isset( $step['step_description'] ) ? $step['step_description'] : '';
                
                if ( empty( $step_title ) ) {
                    continue;
                }
        ?>
            <div class="process-step">
                <div class="process-number">
                    <span><?php echo esc_html( $step_number ); ?></span>
                </div>
                <div class="process-content">
                    <h4><?php echo esc_html( $step_title ); ?></h4>
                    <?php if ( ! empty( $step_description ) ) : ?>
                        <p><?php echo esc_html( $step_description ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
            endforeach;
        endif; 
        ?>
    </div>
</section>

