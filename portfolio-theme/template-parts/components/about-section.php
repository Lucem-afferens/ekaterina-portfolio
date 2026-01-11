<?php
/**
 * About Section Component
 * 
 * Компонент секции "Профессиональный путь" (About/Timeline).
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Получаем данные из SCF (используем get_field() напрямую, как в Tochka-Gg)
// Получаем ID текущей страницы для правильного контекста
$current_page_id = ekaterina_get_current_page_id();

// Проверяем, включена ли секция About
$about_enabled = function_exists( 'get_field' ) ? get_field( 'about_enabled', $current_page_id ) : true;
// Если поле не установлено, по умолчанию секция включена
if ( $about_enabled === null ) {
    $about_enabled = true;
}
// Преобразуем в boolean
$about_enabled = (bool) $about_enabled;

// Если секция отключена, не выводим её
if ( ! $about_enabled ) {
    return;
}

$about_title = function_exists( 'get_field' ) ? get_field( 'about_title', $current_page_id ) : null;
$about_title = $about_title ?: 'Профессиональный путь';

$about_timeline = function_exists( 'get_field' ) ? get_field( 'about_timeline', $current_page_id ) : false;

// Получаем тип медиа (photo или video)
$about_media_type_raw = function_exists( 'get_field' ) ? get_field( 'about_media_type', $current_page_id ) : 'photo';
// ВРЕМЕННАЯ ОТЛАДКА (удалите после проверки):
// Раскомментируйте следующую строку, чтобы увидеть, что возвращает SCF:
// error_log( 'DEBUG about_media_type_raw: ' . print_r( $about_media_type_raw, true ) );
// Нормализуем значение - SCF/ACF может вернуть массив, объект или значение с пробелами
$about_media_type = 'photo'; // По умолчанию фото

if ( ! empty( $about_media_type_raw ) ) {
    // Если это массив, извлекаем значение
    if ( is_array( $about_media_type_raw ) ) {
        // Проверяем различные возможные ключи
        if ( isset( $about_media_type_raw['value'] ) ) {
            $about_media_type = $about_media_type_raw['value'];
        } elseif ( isset( $about_media_type_raw['label'] ) ) {
            $about_media_type = $about_media_type_raw['label'];
        } elseif ( isset( $about_media_type_raw[0] ) ) {
            $about_media_type = $about_media_type_raw[0];
        }
    } 
    // Если это объект
    elseif ( is_object( $about_media_type_raw ) ) {
        if ( isset( $about_media_type_raw->value ) ) {
            $about_media_type = $about_media_type_raw->value;
        } elseif ( isset( $about_media_type_raw->label ) ) {
            $about_media_type = $about_media_type_raw->label;
        }
    }
    // Если это строка
    else {
        $about_media_type = $about_media_type_raw;
    }
    
    // Нормализуем: убираем пробелы, приводим к нижнему регистру
    $about_media_type = trim( strtolower( (string) $about_media_type ) );
    
    // Проверяем различные варианты написания "video"
    // Может быть: "video", "видео", "Video", "Видео" и т.д.
    if ( $about_media_type === 'video' || $about_media_type === 'видео' ) {
        $about_media_type = 'video';
    } else {
        // Если не video, то photo
        $about_media_type = 'photo';
    }
}

// Получаем фото или видео в зависимости от типа медиа
$about_image = false;
$about_video = false;

if ( $about_media_type === 'video' ) {
    $about_video = function_exists( 'get_field' ) ? get_field( 'about_video', $current_page_id ) : false;
    // Также получаем фото, если оно было заполнено (для обратной совместимости)
    // Но оно не будет использоваться, если выбран тип video
} else {
    $about_image = function_exists( 'get_field' ) ? get_field( 'about_image', $current_page_id ) : false;
}

// Если timeline не заполнен, используем дефолтные значения
if ( empty( $about_timeline ) ) {
    $about_timeline = array(
        array(
            'timeline_year' => '2022',
            'timeline_title' => 'Начало карьеры',
            'timeline_description' => 'Начало профессиональной деятельности, первые проекты и достижения',
        ),
        array(
            'timeline_year' => '2023',
            'timeline_title' => 'Корпоративный сегмент',
            'timeline_description' => 'Сотрудничество с крупнейшими компаниями региона, проведение бизнес-форумов и корпоративных праздников',
        ),
        array(
            'timeline_year' => '2024',
            'timeline_title' => 'Премиальные события',
            'timeline_description' => 'Специализация на свадьбах и приёмах высокого уровня, работа с международными площадками',
        ),
        array(
            'timeline_year' => '2025',
            'timeline_title' => 'Настоящее время',
            'timeline_description' => 'Текущий статус и достижения, количество успешных проектов',
        ),
    );
}

// Получаем URL изображения (если тип медиа - фото)
// get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
$about_image_url = '';
if ( $about_image && $about_media_type === 'photo' ) {
    if ( is_array( $about_image ) && ! empty( $about_image['url'] ) ) {
        // Если вернулся массив, используем URL из него
        $about_image_url = $about_image['url'];
    } elseif ( is_numeric( $about_image ) ) {
        // Если вернулся ID, получаем URL
        $about_image_url = wp_get_attachment_image_url( $about_image, 'full' );
    }
    // Принудительно используем HTTPS
    if ( $about_image_url ) {
        $about_image_url = set_url_scheme( $about_image_url, 'https' );
    }
}

// Обрабатываем URL видео
$about_video_url = '';
$video_embed_code = '';
$is_vk_video = false;
$vk_video_id = '';
$is_iframe_code = false;
$iframe_code = '';
// Проверяем тип медиа явно, чтобы не обрабатывать видео, если выбран тип photo
if ( $about_media_type === 'video' && $about_video ) {
    // Нормализуем значение - может быть строка, массив или значение с пробелами
    $video_raw = $about_video;
    if ( is_array( $video_raw ) ) {
        $video_raw = isset( $video_raw['url'] ) ? $video_raw['url'] : ( isset( $video_raw['value'] ) ? $video_raw['value'] : ( isset( $video_raw[0] ) ? $video_raw[0] : '' ) );
    }
    $video_raw = trim( (string) $video_raw );
    
    // Проверяем, является ли значение готовым iframe кодом (например, от VK)
    if ( ! empty( $video_raw ) && stripos( $video_raw, '<iframe' ) !== false ) {
        // Это готовый iframe код - извлекаем src или используем код целиком
        $is_iframe_code = true;
        
        // Пытаемся извлечь src из iframe
        if ( preg_match( '/src=["\']([^"\']+)["\']/', $video_raw, $src_matches ) ) {
            $about_video_url = $src_matches[1];
            // Если это VK video_ext.php, используем извлеченный src
            if ( strpos( $about_video_url, 'vk.com/video_ext.php' ) !== false ) {
                $video_embed_code = esc_url( $about_video_url );
                // Для VK video_ext.php можно использовать iframe напрямую
            } else {
                // Для других платформ также используем извлеченный src
                $video_embed_code = esc_url( $about_video_url );
            }
        } else {
            // Если не удалось извлечь src, сохраняем весь iframe код для вывода
            $iframe_code = $video_raw;
        }
    } else {
        // Это обычная ссылка - обрабатываем как раньше
        $about_video_url = $video_raw;
    }
    
    // Определяем тип видео и формируем embed код (только если это не iframe код)
    if ( ! $is_iframe_code && ! empty( $about_video_url ) ) {
        // YouTube (youtube.com/watch?v=..., youtu.be/..., youtube.com/embed/...)
        if ( preg_match( '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', $about_video_url, $matches ) ||
             preg_match( '/youtu\.be\/([a-zA-Z0-9_-]+)/', $about_video_url, $matches ) ||
             preg_match( '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', $about_video_url, $matches ) ) {
            $video_id = $matches[1];
            $video_embed_code = 'https://www.youtube.com/embed/' . esc_attr( $video_id ) . '?rel=0&modestbranding=1';
        }
        // Vimeo (vimeo.com/...)
        elseif ( preg_match( '/vimeo\.com\/(\d+)/', $about_video_url, $matches ) ) {
            $video_id = $matches[1];
            $video_embed_code = 'https://player.vimeo.com/video/' . esc_attr( $video_id );
        }
        // VK (vk.com/video..., vk.com/clip...)
        elseif ( preg_match( '/vk\.com\/(?:video|clip)(-?\d+)_(\d+)/', $about_video_url, $matches ) ) {
            // VK требует использования Widget API для встраивания
            // Сохраняем оригинальную ссылку и ID для виджета
            $is_vk_video = true;
            $vk_video_id = esc_attr( $matches[1] . '_' . $matches[2] );
            $video_embed_code = esc_url( $about_video_url ); // Сохраняем для ссылки
        }
        // Прямая ссылка на видеофайл (.mp4, .webm, .ogv и т.д.)
        elseif ( preg_match( '/\.(mp4|webm|ogv|mov)(\?.*)?$/i', $about_video_url ) ) {
            $video_embed_code = esc_url( $about_video_url ); // Прямая ссылка, используем как есть
        } else {
            // Если не распознали формат, но есть URL, попробуем использовать как есть (для нестандартных форматов)
            // Это может быть YouTube или Vimeo с нестандартным форматом URL
            $video_embed_code = esc_url( $about_video_url );
        }
    }
}
?>

<section id="about">
    <div class="about-grid">
        <div class="about-timeline">
            <div class="section-divider"></div>
            <h3><?php echo esc_html( $about_title ); ?></h3>
            <?php foreach ( $about_timeline as $item ) : 
                $year = ekaterina_get_repeater_field( $item, 'timeline_year', '' );
                $title = ekaterina_get_repeater_field( $item, 'timeline_title', '' );
                $description = ekaterina_get_repeater_field( $item, 'timeline_description', '' );
                
                if ( empty( $year ) || empty( $title ) ) {
                    continue;
                }
            ?>
                <div class="timeline-item">
                    <h4><?php echo esc_html( $year ); ?> — <?php echo esc_html( $title ); ?></h4>
                    <?php if ( ! empty( $description ) ) : ?>
                        <p><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php 
        // Показываем видео, если тип медиа = video И есть видео (или embed код)
        if ( $about_media_type === 'video' ) : 
            // Если есть embed код или iframe код, показываем его
            if ( ! empty( $video_embed_code ) || ! empty( $iframe_code ) ) :
        ?>
            <div class="about-media about-video">
                <?php
                // Если это готовый iframe код (например, от VK через кнопку "Экспортировать")
                if ( $is_iframe_code && ! empty( $iframe_code ) ) {
                    // Выводим iframe код напрямую (с безопасностью через wp_kses)
                    // Разрешаем только необходимые атрибуты iframe для безопасности
                    $allowed_iframe_attrs = array(
                        'iframe' => array(
                            'src' => true,
                            'width' => true,
                            'height' => true,
                            'frameborder' => true,
                            'allow' => true,
                            'allowfullscreen' => true,
                            'loading' => true,
                            'title' => true,
                            'style' => true,
                            'class' => true,
                            'id' => true,
                        ),
                    );
                    echo wp_kses( $iframe_code, $allowed_iframe_attrs );
                }
                // Если iframe код был обработан и извлечен src (VK video_ext.php или другие)
                elseif ( $is_iframe_code && ! empty( $video_embed_code ) ) {
                    // Используем извлеченный src для видео (VK video_ext.php работает через iframe)
                ?>
                    <iframe 
                        src="<?php echo esc_url( $video_embed_code ); ?>" 
                        frameborder="0" 
                        allow="autoplay; encrypted-media; fullscreen; picture-in-picture; screen-wake-lock" 
                        allowfullscreen
                        loading="lazy"
                        title="<?php echo esc_attr( $about_title ); ?>"
                        style="width: 100%; height: 100%; background-color: #000;"
                    ></iframe>
                <?php
                }
                // Специальная обработка для VK видео (старый способ - через ссылку, без iframe кода)
                elseif ( $is_vk_video && ! empty( $vk_video_id ) && ! $is_iframe_code ) {
                    // VK блокирует встраивание через iframe из-за политики безопасности (X-Frame-Options)
                    // Показываем превью с кнопкой перехода на VK
                ?>
                    <div class="vk-video-fallback" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding: 40px; text-align: center; background-color: #1a1a1a;">
                        <div style="margin-bottom: 24px;">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #9d7e5f;">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 14.5v-9l6 4.5-6 4.5z" fill="currentColor"/>
                            </svg>
                        </div>
                        <p style="margin-bottom: 20px; color: #f8f6f3; font-size: 18px;">Видео из ВКонтакте</p>
                        <p style="margin-bottom: 24px; color: rgba(248, 246, 243, 0.7); font-size: 14px;">Для просмотра видео откройте его в ВКонтакте</p>
                        <a href="<?php echo esc_url( $video_embed_code ); ?>" target="_blank" rel="noopener noreferrer" style="display: inline-block; padding: 14px 32px; background-color: #9d7e5f; color: #f8f6f3; text-decoration: none; border-radius: 4px; transition: background-color 0.3s; font-weight: 500;">Открыть в ВКонтакте</a>
                    </div>
                <?php
                }
                // Проверяем тип видео для правильной обработки
                elseif ( strpos( $video_embed_code, 'youtube.com/embed' ) !== false || 
                         strpos( $video_embed_code, 'player.vimeo.com' ) !== false ) {
                    // YouTube или Vimeo - используем iframe
                ?>
                    <iframe 
                        src="<?php echo esc_url( $video_embed_code ); ?>" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                        loading="lazy"
                        title="<?php echo esc_attr( $about_title ); ?>"
                    ></iframe>
                <?php
                } else {
                    // Прямая ссылка на видеофайл - используем video тег
                    // Определяем MIME тип по расширению файла
                    $mime_type = 'video/mp4'; // По умолчанию
                    if ( preg_match( '/\.webm$/i', $about_video_url ) ) {
                        $mime_type = 'video/webm';
                    } elseif ( preg_match( '/\.ogv$/i', $about_video_url ) ) {
                        $mime_type = 'video/ogg';
                    } elseif ( preg_match( '/\.mov$/i', $about_video_url ) ) {
                        $mime_type = 'video/quicktime';
                    }
                ?>
                    <video controls loading="lazy" preload="metadata" playsinline>
                        <source src="<?php echo esc_url( $video_embed_code ); ?>" type="<?php echo esc_attr( $mime_type ); ?>">
                        Ваш браузер не поддерживает воспроизведение видео.
                    </video>
                <?php
                }
                ?>
            </div>
        <?php 
            // Если тип видео, но embed код не сформирован (например, неправильная ссылка), показываем сообщение или ничего
            // Можно добавить сообщение об ошибке, если нужно
            endif;
        // Показываем фото ТОЛЬКО если тип медиа = photo (не video!)
        elseif ( $about_media_type === 'photo' && ! empty( $about_image_url ) ) : 
        ?>
            <div class="about-media about-image">
                <img src="<?php echo esc_url( $about_image_url ); ?>" alt="<?php echo esc_attr( $about_title ); ?>" loading="lazy" decoding="async" />
            </div>
        <?php endif; ?>
    </div>
</section>

