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

// Получаем данные из SCF (используем get_field() напрямую, как в других секциях)
// Получаем ID текущей страницы для правильного контекста
$current_page_id = ekaterina_get_current_page_id();

$contact_title = function_exists( 'get_field' ) ? get_field( 'contact_title', $current_page_id ) : null;
$contact_title = $contact_title ?: 'Обсудим ваше мероприятие';

$contact_description = function_exists( 'get_field' ) ? get_field( 'contact_description', $current_page_id ) : null;
$contact_description = $contact_description ?: 'Свяжитесь со мной для обсуждения деталей вашего события.<br/>Отвечу на все вопросы и помогу определиться с форматом.';

// Убираем автоматически созданные p теги из описания, если они есть
if ( ! empty( $contact_description ) ) {
    // Удаляем открывающие и закрывающие p теги
    $contact_description = preg_replace( '/<p[^>]*>/', '', $contact_description );
    $contact_description = preg_replace( '/<\/p>/', '', $contact_description );
    // Убираем лишние пробелы
    $contact_description = trim( $contact_description );
}

// Заголовки блоков
$contact_info_title = function_exists( 'get_field' ) ? get_field( 'contact_info_title', $current_page_id ) : null;
$contact_info_title = $contact_info_title ?: 'Контактная информация';

$contact_work_hours_title = function_exists( 'get_field' ) ? get_field( 'contact_work_hours_title', $current_page_id ) : null;
$contact_work_hours_title = $contact_work_hours_title ?: 'Время работы';

// Контактная информация (теперь repeater для гибкости)
$contact_info_items = function_exists( 'get_field' ) ? get_field( 'contact_info_items', $current_page_id ) : false;
if ( empty( $contact_info_items ) || ! is_array( $contact_info_items ) ) {
    // Пробуем старые отдельные поля для обратной совместимости
    $contact_phone = function_exists( 'get_field' ) ? get_field( 'contact_phone', $current_page_id ) : null;
    $contact_email = function_exists( 'get_field' ) ? get_field( 'contact_email', $current_page_id ) : null;
    $contact_location = function_exists( 'get_field' ) ? get_field( 'contact_location', $current_page_id ) : null;
    
    // Формируем массив из старых полей для совместимости
    $contact_info_items = array();
    if ( ! empty( $contact_phone ) ) {
        $contact_info_items[] = array(
            'contact_info_label' => 'Телефон',
            'contact_info_value' => $contact_phone,
            'contact_info_type' => 'phone'
        );
    }
    if ( ! empty( $contact_email ) ) {
        $contact_info_items[] = array(
            'contact_info_label' => 'Электронная почта',
            'contact_info_value' => $contact_email,
            'contact_info_type' => 'email'
        );
    }
    if ( ! empty( $contact_location ) ) {
        $contact_info_items[] = array(
            'contact_info_label' => 'Локация',
            'contact_info_value' => $contact_location,
            'contact_info_type' => 'text'
        );
    }
}

// Фильтруем массив, оставляя только элементы с заполненными заголовками и значениями
if ( ! empty( $contact_info_items ) && is_array( $contact_info_items ) ) {
    $contact_info_items = array_filter( $contact_info_items, function( $item ) {
        $label = isset( $item['contact_info_label'] ) ? trim( $item['contact_info_label'] ) : '';
        $value = isset( $item['contact_info_value'] ) ? trim( $item['contact_info_value'] ) : '';
        return ! empty( $label ) && ! empty( $value );
    } );
}

// Время работы (repeater)
$contact_work_hours = function_exists( 'get_field' ) ? get_field( 'contact_work_hours', $current_page_id ) : false;
if ( empty( $contact_work_hours ) || ! is_array( $contact_work_hours ) ) {
    $contact_work_hours = array();
}

$contact_vk_link = function_exists( 'get_field' ) ? get_field( 'contact_vk_link', $current_page_id ) : null;
$contact_vk_link = $contact_vk_link ?: 'https://vk.com/your-page';

$contact_telegram_link = function_exists( 'get_field' ) ? get_field( 'contact_telegram_link', $current_page_id ) : null;
$contact_telegram_link = $contact_telegram_link ?: 'https://t.me/username';

$contact_whatsapp_link = function_exists( 'get_field' ) ? get_field( 'contact_whatsapp_link', $current_page_id ) : null;
$contact_whatsapp_link = $contact_whatsapp_link ?: 'https://wa.me/79123456789';
?>

<section id="contact">
    <div class="contact-container">
        <div class="contact-content">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo wp_kses_post( $contact_title ); ?></h3>
            <?php if ( ! empty( $contact_description ) ) : ?>
                <div class="contact-description"><?php echo wp_kses_post( $contact_description ); ?></div>
            <?php endif; ?>
            
            <div class="contact-cards">
                <?php
                // Собираем валидные элементы контактной информации
                $valid_info_items = array();
                if ( ! empty( $contact_info_items ) && is_array( $contact_info_items ) ) {
                    foreach ( $contact_info_items as $item ) {
                        $info_label = isset( $item['contact_info_label'] ) ? trim( $item['contact_info_label'] ) : '';
                        $info_value = isset( $item['contact_info_value'] ) ? trim( $item['contact_info_value'] ) : '';
                        
                        // Если заголовок или значение не указаны, пропускаем этот пункт
                        if ( empty( $info_label ) || empty( $info_value ) ) {
                            continue;
                        }
                        
                        $valid_info_items[] = $item;
                    }
                }
                ?>
                
                <?php if ( ! empty( $valid_info_items ) ) : ?>
                    <div class="contact-card">
                        <?php if ( ! empty( $contact_info_title ) ) : ?>
                            <h4><?php echo esc_html( $contact_info_title ); ?></h4>
                        <?php endif; ?>
                        <?php foreach ( $valid_info_items as $item ) : 
                            // Получаем данные из repeater элемента напрямую
                            $info_label = isset( $item['contact_info_label'] ) ? trim( $item['contact_info_label'] ) : '';
                            $info_value = isset( $item['contact_info_value'] ) ? trim( $item['contact_info_value'] ) : '';
                            $info_type = isset( $item['contact_info_type'] ) ? $item['contact_info_type'] : 'text';
                            
                            // Получаем опциональную ссылку на карту для типа "map"
                            $info_map_link = '';
                            if ( $info_type === 'map' && isset( $item['contact_info_map_link'] ) ) {
                                $info_map_link = trim( $item['contact_info_map_link'] );
                            }
                            
                            // Автоматически определяем тип по содержимому, если не указан
                            if ( $info_type === 'text' || empty( $info_type ) ) {
                                if ( filter_var( $info_value, FILTER_VALIDATE_EMAIL ) ) {
                                    $info_type = 'email';
                                } elseif ( preg_match( '/^[\d\s\-\+\(\)]+$/', $info_value ) && strlen( preg_replace( '/\D/', '', $info_value ) ) >= 10 ) {
                                    $info_type = 'phone';
                                } elseif ( filter_var( $info_value, FILTER_VALIDATE_URL ) || preg_match( '/^(https?:\/\/|www\.)/i', $info_value ) ) {
                                    $info_type = 'url';
                                }
                            }
                            
                            // Формируем ссылку в зависимости от типа
                            $value_output = '';
                            if ( $info_type === 'phone' ) {
                                $phone_clean = preg_replace( '/[^0-9+]/', '', $info_value );
                                $value_output = '<a href="tel:' . esc_attr( $phone_clean ) . '">' . esc_html( $info_value ) . '</a>';
                            } elseif ( $info_type === 'email' ) {
                                $value_output = '<a href="mailto:' . esc_attr( $info_value ) . '">' . esc_html( $info_value ) . '</a>';
                            } elseif ( $info_type === 'url' ) {
                                $url = $info_value;
                                if ( ! preg_match( '/^https?:\/\//', $url ) ) {
                                    $url = 'https://' . $url;
                                }
                                $value_output = '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $info_value ) . '</a>';
                            } elseif ( $info_type === 'map' ) {
                                // Для типа "map" показываем адрес как ссылку, если указана ссылка на карту
                                if ( ! empty( $info_map_link ) ) {
                                    // Обрабатываем ссылку на карту (может быть с протоколом или без)
                                    $map_url = $info_map_link;
                                    if ( ! preg_match( '/^https?:\/\//', $map_url ) ) {
                                        $map_url = 'https://' . $map_url;
                                    }
                                    $value_output = '<a href="' . esc_url( $map_url ) . '" target="_blank" rel="noopener">' . esc_html( $info_value ) . '</a>';
                                } else {
                                    // Если ссылка на карту не указана, показываем просто текст
                                    $value_output = esc_html( $info_value );
                                }
                            } else {
                                $value_output = esc_html( $info_value );
                            }
                        ?>
                            <div class="contact-info-item">
                                <p class="contact-info-label"><?php echo esc_html( $info_label ); ?></p>
                                <p class="contact-info-value"><?php echo wp_kses_post( $value_output ); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php
                // Собираем валидные элементы времени работы
                $valid_work_hours = array();
                if ( ! empty( $contact_work_hours ) && is_array( $contact_work_hours ) ) {
                    foreach ( $contact_work_hours as $hours ) {
                        $hours_label = isset( $hours['hours_label'] ) ? trim( $hours['hours_label'] ) : '';
                        // Также проверяем альтернативное имя поля
                        if ( empty( $hours_label ) && isset( $hours['contact_work_hours_label'] ) ) {
                            $hours_label = trim( $hours['contact_work_hours_label'] );
                        }
                        $hours_value = isset( $hours['hours_value'] ) ? trim( $hours['hours_value'] ) : '';
                        // Также проверяем альтернативное имя поля
                        if ( empty( $hours_value ) && isset( $hours['contact_work_hours_value'] ) ) {
                            $hours_value = trim( $hours['contact_work_hours_value'] );
                        }
                        
                        // Если заголовок или значение не указаны, пропускаем этот пункт
                        if ( empty( $hours_label ) || empty( $hours_value ) ) {
                            continue;
                        }
                        
                        $valid_work_hours[] = array(
                            'label' => $hours_label,
                            'value' => $hours_value
                        );
                    }
                }
                ?>
                
                <?php if ( ! empty( $valid_work_hours ) ) : ?>
                    <div class="contact-card">
                        <?php if ( ! empty( $contact_work_hours_title ) ) : ?>
                            <h4><?php echo esc_html( $contact_work_hours_title ); ?></h4>
                        <?php endif; ?>
                        <?php foreach ( $valid_work_hours as $hours ) : ?>
                            <div class="contact-info-item">
                                <p class="contact-info-label"><?php echo esc_html( $hours['label'] ); ?></p>
                                <p class="contact-info-value"><?php echo esc_html( $hours['value'] ); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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

