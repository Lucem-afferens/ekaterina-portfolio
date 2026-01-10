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

// Получаем данные из SCF (используем get_field() напрямую, как в contact-section)
// Получаем ID текущей страницы для правильного контекста
$current_page_id = ekaterina_get_current_page_id();

// Проверяем, включена ли секция Contact Channels
$contact_channels_enabled = function_exists( 'get_field' ) ? get_field( 'contact_channels_enabled', $current_page_id ) : true;
// Если поле не установлено, по умолчанию секция включена
if ( $contact_channels_enabled === null ) {
    $contact_channels_enabled = true;
}
// Преобразуем в boolean
$contact_channels_enabled = (bool) $contact_channels_enabled;

// Если секция отключена, не выводим её
if ( ! $contact_channels_enabled ) {
    return;
}

// Пробуем разные варианты имен полей для совместимости
$channels_label = function_exists( 'get_field' ) ? get_field( 'channels_label', $current_page_id ) : null;
// Также пробуем альтернативное имя поля из документации
if ( empty( $channels_label ) && function_exists( 'get_field' ) ) {
    $channels_label = get_field( 'contact_channels_label', $current_page_id );
}
// Также пробуем без передачи ID (автоматически используется текущая страница)
if ( empty( $channels_label ) && function_exists( 'get_field' ) ) {
    $channels_label = get_field( 'channels_label' );
}
if ( empty( $channels_label ) && function_exists( 'get_field' ) ) {
    $channels_label = get_field( 'contact_channels_label' );
}
$channels_label = $channels_label ?: 'Свяжитесь со мной:';

// Получаем социальные сети из repeater
$channels_social_networks = function_exists( 'get_field' ) ? get_field( 'channels_social_networks', $current_page_id ) : false;
if ( empty( $channels_social_networks ) || ! is_array( $channels_social_networks ) ) {
    // Также пробуем альтернативное имя поля
    $channels_social_networks = function_exists( 'get_field' ) ? get_field( 'contact_channels_social_networks', $current_page_id ) : false;
}
// Также пробуем без передачи ID
if ( ( empty( $channels_social_networks ) || ! is_array( $channels_social_networks ) ) && function_exists( 'get_field' ) ) {
    $channels_social_networks = get_field( 'channels_social_networks' );
}
if ( ( empty( $channels_social_networks ) || ! is_array( $channels_social_networks ) ) && function_exists( 'get_field' ) ) {
    $channels_social_networks = get_field( 'contact_channels_social_networks' );
}

// Обратная совместимость: преобразуем старые поля в repeater
if ( empty( $channels_social_networks ) || ! is_array( $channels_social_networks ) ) {
    $channels_social_networks = array();
    
    // Пробуем получить старые поля
    $channels_vk = function_exists( 'get_field' ) ? get_field( 'channels_vk', $current_page_id ) : null;
    if ( empty( $channels_vk ) && function_exists( 'get_field' ) ) {
        $channels_vk = get_field( 'contact_channels_vk_link', $current_page_id );
    }
    if ( empty( $channels_vk ) && function_exists( 'get_field' ) ) {
        $channels_vk = get_field( 'contact_channels_vk', $current_page_id );
    }
    if ( empty( $channels_vk ) && function_exists( 'get_field' ) ) {
        $channels_vk = get_field( 'channels_vk' );
    }
    if ( empty( $channels_vk ) && function_exists( 'get_field' ) ) {
        $channels_vk = get_field( 'contact_channels_vk_link' );
    }
    if ( empty( $channels_vk ) && function_exists( 'get_field' ) ) {
        $channels_vk = get_field( 'contact_channels_vk' );
    }
    
    $channels_telegram = function_exists( 'get_field' ) ? get_field( 'channels_telegram', $current_page_id ) : null;
    if ( empty( $channels_telegram ) && function_exists( 'get_field' ) ) {
        $channels_telegram = get_field( 'contact_channels_telegram_link', $current_page_id );
    }
    if ( empty( $channels_telegram ) && function_exists( 'get_field' ) ) {
        $channels_telegram = get_field( 'contact_channels_telegram', $current_page_id );
    }
    if ( empty( $channels_telegram ) && function_exists( 'get_field' ) ) {
        $channels_telegram = get_field( 'channels_telegram' );
    }
    if ( empty( $channels_telegram ) && function_exists( 'get_field' ) ) {
        $channels_telegram = get_field( 'contact_channels_telegram_link' );
    }
    if ( empty( $channels_telegram ) && function_exists( 'get_field' ) ) {
        $channels_telegram = get_field( 'contact_channels_telegram' );
    }
    
    $channels_phone = function_exists( 'get_field' ) ? get_field( 'channels_phone', $current_page_id ) : null;
    if ( empty( $channels_phone ) && function_exists( 'get_field' ) ) {
        $channels_phone = get_field( 'contact_channels_phone', $current_page_id );
    }
    if ( empty( $channels_phone ) && function_exists( 'get_field' ) ) {
        $channels_phone = get_field( 'channels_phone' );
    }
    if ( empty( $channels_phone ) && function_exists( 'get_field' ) ) {
        $channels_phone = get_field( 'contact_channels_phone' );
    }
    
    // Формируем массив из старых полей для совместимости
    if ( ! empty( $channels_vk ) ) {
        $channels_social_networks[] = array(
            'social_network' => 'vk',
            'social_link' => $channels_vk
        );
    }
    if ( ! empty( $channels_telegram ) ) {
        $channels_social_networks[] = array(
            'social_network' => 'telegram',
            'social_link' => $channels_telegram
        );
    }
    if ( ! empty( $channels_phone ) ) {
        $channels_social_networks[] = array(
            'social_network' => 'phone',
            'social_link' => $channels_phone
        );
    }
}

// Фильтруем массив, оставляя только элементы с заполненными полями
if ( ! empty( $channels_social_networks ) && is_array( $channels_social_networks ) ) {
    $channels_social_networks = array_filter( $channels_social_networks, function( $item ) {
        $network = isset( $item['social_network'] ) ? trim( $item['social_network'] ) : '';
        $link = isset( $item['social_link'] ) ? trim( $item['social_link'] ) : '';
        return ! empty( $network ) && ! empty( $link );
    } );
}
?>

<section id="contact-channels" class="contact-channels-section">
    <div class="contact-channels-container">
        <div class="contact-channels-content">
            <?php if ( ! empty( $channels_label ) ) : ?>
                <p class="contact-channels-label"><?php echo esc_html( $channels_label ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $channels_social_networks ) && is_array( $channels_social_networks ) ) : ?>
                <div class="contact-channels-links">
                    <?php foreach ( $channels_social_networks as $social ) : 
                        $network = isset( $social['social_network'] ) ? trim( $social['social_network'] ) : '';
                        $link = isset( $social['social_link'] ) ? trim( $social['social_link'] ) : '';
                        
                        // Если социальная сеть или ссылка не указаны, пропускаем
                        if ( empty( $network ) || empty( $link ) ) {
                            continue;
                        }
                        
                        // Получаем иконку и название для социальной сети
                        $icon_class = ekaterina_get_social_icon( $network );
                        $network_name = ekaterina_get_social_name( $network );
                        
                        // Формируем ссылку и текст для отображения в зависимости от типа
                        $social_url = $link;
                        $display_text = $link;
                        
                        if ( $network === 'phone' ) {
                            // Для телефона используем tel: протокол
                            // Очищаем номер от всех символов, кроме цифр и +
                            $phone_clean = preg_replace( '/[^0-9+]/', '', $link );
                            // Если номер начинается с 8, заменяем на +7 (для России)
                            if ( substr( $phone_clean, 0, 1 ) === '8' && strlen( $phone_clean ) === 11 ) {
                                $phone_clean = '+7' . substr( $phone_clean, 1 );
                            }
                            // Формируем tel: ссылку
                            $social_url = 'tel:' . $phone_clean;
                            $display_text = $link; // Отображаем полный номер телефона, как введен
                        } elseif ( $network === 'email' ) {
                            // Для email используем mailto: протокол
                            $social_url = 'mailto:' . $link;
                            $display_text = $link; // Отображаем email, как введен
                        } else {
                            // Для остальных - проверяем наличие протокола
                            if ( ! preg_match( '/^https?:\/\//', $social_url ) ) {
                                $social_url = 'https://' . $social_url;
                            }
                            $display_text = $network_name; // Отображаем название социальной сети
                        }
                    ?>
                        <a href="<?php echo esc_url( $social_url ); ?>" <?php echo ( $network === 'phone' || $network === 'email' ) ? '' : 'target="_blank" rel="noopener"'; ?> class="contact-channel-item" aria-label="<?php echo esc_attr( $network_name ); ?>">
                            <i class="<?php echo esc_attr( $icon_class ); ?>"></i>
                            <span><?php echo esc_html( $display_text ); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

