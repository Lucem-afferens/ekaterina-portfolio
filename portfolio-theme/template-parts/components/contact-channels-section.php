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

// Получаем данные из SCF (используем get_field() напрямую, как в других секциях)
// Получаем ID текущей страницы для правильного контекста
$current_page_id = ekaterina_get_current_page_id();

$channels_label = function_exists( 'get_field' ) ? get_field( 'channels_label', $current_page_id ) : null;
$channels_label = $channels_label ?: 'Свяжитесь со мной:';

$channels_vk = function_exists( 'get_field' ) ? get_field( 'channels_vk', $current_page_id ) : null;
$channels_vk = $channels_vk ?: 'https://vk.com/your-page';

$channels_telegram = function_exists( 'get_field' ) ? get_field( 'channels_telegram', $current_page_id ) : null;
$channels_telegram = $channels_telegram ?: 'https://t.me/username';

$channels_phone = function_exists( 'get_field' ) ? get_field( 'channels_phone', $current_page_id ) : null;
$channels_phone = $channels_phone ?: '+7 (912) 345-67-89';
?>

<section id="contact-channels" class="contact-channels-section">
    <div class="contact-channels-container">
        <div class="contact-channels-content">
            <?php if ( ! empty( $channels_label ) ) : ?>
                <p class="contact-channels-label"><?php echo esc_html( $channels_label ); ?></p>
            <?php endif; ?>
            <div class="contact-channels-links">
                <?php if ( ! empty( $channels_vk ) ) : ?>
                    <a href="<?php echo esc_url( $channels_vk ); ?>" target="_blank" class="contact-channel-item" aria-label="ВКонтакте">
                        <i class="fa-brands fa-vk"></i>
                        <span>ВКонтакте</span>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty( $channels_telegram ) ) : ?>
                    <a href="<?php echo esc_url( $channels_telegram ); ?>" target="_blank" class="contact-channel-item" aria-label="Telegram">
                        <i class="fa-brands fa-telegram"></i>
                        <span>Telegram</span>
                    </a>
                <?php endif; ?>
                <?php if ( ! empty( $channels_phone ) ) : ?>
                    <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $channels_phone ) ); ?>" class="contact-channel-item" aria-label="Телефон">
                        <i class="fa-solid fa-phone"></i>
                        <span><?php echo esc_html( $channels_phone ); ?></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

