<?php
/**
 * Contact Channels Section Component
 * 
 * Компонент секции "Каналы связи".
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Получаем данные из SCF
$channels_label = ekaterina_get_scf_field( 'channels_label', 'Свяжитесь со мной:' );
$channels_vk = ekaterina_get_scf_field( 'channels_vk', 'https://vk.com/your-page', 'url' );
$channels_telegram = ekaterina_get_scf_field( 'channels_telegram', 'https://t.me/username', 'url' );
$channels_phone = ekaterina_get_scf_field( 'channels_phone', '+7 (912) 345-67-89' );
?>

<section id="contact-channels" class="contact-channels-section">
    <div class="contact-channels-container">
        <div class="contact-channels-content">
            <p class="contact-channels-label"><?php echo esc_html( $channels_label ); ?></p>
            <div class="contact-channels-links">
                <a href="<?php echo esc_url( $channels_vk ); ?>" target="_blank" class="contact-channel-item" aria-label="ВКонтакте">
                    <i class="fa-brands fa-vk"></i>
                    <span>ВКонтакте</span>
                </a>
                <a href="<?php echo esc_url( $channels_telegram ); ?>" target="_blank" class="contact-channel-item" aria-label="Telegram">
                    <i class="fa-brands fa-telegram"></i>
                    <span>Telegram</span>
                </a>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $channels_phone ) ); ?>" class="contact-channel-item" aria-label="Телефон">
                    <i class="fa-solid fa-phone"></i>
                    <span><?php echo esc_html( $channels_phone ); ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

