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

// Получаем данные из SCF
$recognition_title = ekaterina_get_scf_field( 'recognition_title', 'Признание' );
$recognition_stats = ekaterina_get_scf_repeater( 'recognition_stats' );
$recognition_partners = ekaterina_get_scf_repeater( 'recognition_partners' );
?>

<section id="recognition">
    <div class="recognition-container">
        <div class="recognition-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo esc_html( $recognition_title ); ?></h3>
        </div>
        
        <?php if ( ! empty( $recognition_stats ) ) : ?>
            <div class="recognition-stats">
                <?php foreach ( $recognition_stats as $stat ) : 
                    $number = ekaterina_get_repeater_field( $stat, 'recognition_number', '' );
                    $label = ekaterina_get_repeater_field( $stat, 'recognition_label', '' );
                    
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
                        $partner_name = ekaterina_get_repeater_field( $partner, 'partner_name', '' );
                        
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

