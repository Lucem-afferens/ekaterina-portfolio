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

// Получаем данные из SCF
$about_title = ekaterina_get_scf_field( 'about_title', 'Профессиональный путь' );
$about_timeline = ekaterina_get_scf_repeater( 'about_timeline' );
$about_image = class_exists( 'SCF' ) ? SCF::get( 'about_image' ) : '';

// Если timeline не заполнен, используем дефолтные значения
if ( empty( $about_timeline ) ) {
    $about_timeline = array(
        array(
            'timeline_year' => '2022',
            'timeline_title' => 'Начало карьеры',
            'timeline_description' => 'Первые шаги в индустрии событий, работа с локальными площадками и частными мероприятиями',
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
            'timeline_description' => 'Статус ведущей премиум-сегмента Пермского края, более 300 успешных мероприятий',
        ),
    );
}

// Получаем URL изображения
$about_image_url = '';
if ( $about_image ) {
    $about_image_url = wp_get_attachment_image_url( $about_image, 'full' );
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
        <?php if ( $about_image_url ) : ?>
            <div class="about-image">
                <img src="<?php echo esc_url( $about_image_url ); ?>" alt="<?php echo esc_attr( $about_title ); ?>" loading="lazy" decoding="async" />
            </div>
        <?php endif; ?>
    </div>
</section>

