<?php
/**
 * Contact Section Component
 * 
 * Компонент секции "Контакты".
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Получаем данные из SCF
$contact_title = ekaterina_get_scf_field( 'contact_title', 'Обсудим ваше мероприятие' );
$contact_description = ekaterina_get_scf_field( 'contact_description', 'Свяжитесь со мной для обсуждения деталей вашего события.<br/>Отвечу на все вопросы и помогу определиться с форматом.' );
$contact_phone = ekaterina_get_scf_field( 'contact_phone', '+7 (912) 345-67-89' );
$contact_email = ekaterina_get_scf_field( 'contact_email', 'ekaterina@shulyatnikova.ru' );
$contact_location = ekaterina_get_scf_field( 'contact_location', 'Пермский край, Россия' );
$contact_work_hours = ekaterina_get_scf_repeater( 'contact_work_hours' );
$contact_vk_link = ekaterina_get_scf_field( 'contact_vk_link', 'https://vk.com/your-page', 'url' );
$contact_telegram_link = ekaterina_get_scf_field( 'contact_telegram_link', 'https://t.me/username', 'url' );
$contact_whatsapp_link = ekaterina_get_scf_field( 'contact_whatsapp_link', 'https://wa.me/79123456789', 'url' );

// Если время работы не заполнено, используем дефолтные значения
if ( empty( $contact_work_hours ) ) {
    $contact_work_hours = array(
        array( 'hours_label' => 'Консультации', 'hours_value' => 'Пн-Пт: 10:00 — 19:00' ),
        array( 'hours_label' => 'Мероприятия', 'hours_value' => 'По согласованию' ),
        array( 'hours_label' => 'Ответ на запрос', 'hours_value' => 'В течение 24 часов' ),
    );
}
?>

<section id="contact">
    <div class="contact-container">
        <div class="contact-content">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo esc_html( $contact_title ); ?></h3>
            <p class="contact-description"><?php echo wp_kses_post( $contact_description ); ?></p>
            
            <div class="contact-cards">
                <div class="contact-card">
                    <h4>Контактная информация</h4>
                    <div class="contact-info-item">
                        <p class="contact-info-label">Телефон</p>
                        <p class="contact-info-value"><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_phone ) ); ?>"><?php echo esc_html( $contact_phone ); ?></a></p>
                    </div>
                    <div class="contact-info-item">
                        <p class="contact-info-label">Электронная почта</p>
                        <p class="contact-info-value"><a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></p>
                    </div>
                    <div class="contact-info-item">
                        <p class="contact-info-label">Локация</p>
                        <p class="contact-info-value"><?php echo esc_html( $contact_location ); ?></p>
                    </div>
                </div>
                
                <div class="contact-card">
                    <h4>Время работы</h4>
                    <?php foreach ( $contact_work_hours as $hours ) : 
                        $label = ekaterina_get_repeater_field( $hours, 'hours_label', '' );
                        $value = ekaterina_get_repeater_field( $hours, 'hours_value', '' );
                        
                        if ( empty( $label ) ) {
                            continue;
                        }
                    ?>
                        <div class="contact-info-item">
                            <p class="contact-info-label"><?php echo esc_html( $label ); ?></p>
                            <p class="contact-info-value"><?php echo esc_html( $value ); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="contact-cta-buttons">
                <button class="contact-cta" id="open-request-modal">ОСТАВИТЬ ЗАЯВКУ</button>
                <a href="<?php echo esc_url( $contact_vk_link ); ?>" target="_blank" class="contact-cta contact-cta-secondary">НАПИСАТЬ</a>
            </div>
            
            <div class="contact-socials">
                <a href="<?php echo esc_url( $contact_vk_link ); ?>" target="_blank">
                    <i class="fa-brands fa-vk"></i>
                </a>
                <a href="<?php echo esc_url( $contact_whatsapp_link ); ?>" target="_blank">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="<?php echo esc_url( $contact_telegram_link ); ?>" target="_blank">
                    <i class="fa-brands fa-telegram"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php
// Подключаем модальные окна
get_template_part( 'template-parts/components/request-modal' );
get_template_part( 'template-parts/components/testimonial-modal' );
?>

