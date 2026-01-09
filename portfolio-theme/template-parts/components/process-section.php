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

// Получаем данные из SCF
$process_title = ekaterina_get_scf_field( 'process_title', 'Процесс работы' );
$process_description = ekaterina_get_scf_field( 'process_description', 'От первой встречи до завершения мероприятия' );
$process_steps = ekaterina_get_scf_repeater( 'process_steps' );
?>

<section id="process">
    <div class="process-container">
        <div class="process-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo esc_html( $process_title ); ?></h3>
            <?php if ( ! empty( $process_description ) ) : ?>
                <p><?php echo esc_html( $process_description ); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if ( ! empty( $process_steps ) ) : 
            foreach ( $process_steps as $index => $step ) : 
                $step_number = ekaterina_get_repeater_field( $step, 'step_number', str_pad( $index + 1, 2, '0', STR_PAD_LEFT ) );
                $step_title = ekaterina_get_repeater_field( $step, 'step_title', '' );
                $step_description = ekaterina_get_repeater_field( $step, 'step_description', '' );
                
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

