<?php
/**
 * Expertise Section Component
 * 
 * Компонент секции "Экспертиза".
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

$expertise_title = function_exists( 'get_field' ) ? get_field( 'expertise_title', $current_page_id ) : null;
$expertise_title = $expertise_title ?: 'Экспертиза';

$expertise_items = function_exists( 'get_field' ) ? get_field( 'expertise_items', $current_page_id ) : false;
if ( empty( $expertise_items ) || ! is_array( $expertise_items ) ) {
    // Если элементы не заполнены, используем дефолтные значения
    $expertise_items = array(
        array( 'expertise_title' => 'Веб-разработка', 'expertise_description' => 'Разработка веб-приложений, интернет-магазинов, корпоративных сайтов' ),
        array( 'expertise_title' => 'UX/UI Дизайн', 'expertise_description' => 'Создание интерфейсов, проектирование пользовательского опыта, визуальный дизайн' ),
        array( 'expertise_title' => 'Мобильная разработка', 'expertise_description' => 'Разработка мобильных приложений для iOS и Android' ),
        array( 'expertise_title' => 'Консалтинг', 'expertise_description' => 'Техническое консультирование, аудит проектов, стратегическое планирование' ),
        array( 'expertise_title' => 'Интеграции', 'expertise_description' => 'Интеграция систем, работа с API, автоматизация процессов' ),
        array( 'expertise_title' => 'Поддержка', 'expertise_description' => 'Техническая поддержка проектов, обновления и оптимизация' ),
    );
}
?>

<section id="expertise">
    <div class="expertise-container">
        <div class="expertise-header">
            <div class="philosophy-divider" style="margin: 0 auto 48px;"></div>
            <h3><?php echo wp_kses_post( $expertise_title ); ?></h3>
        </div>
        <div class="expertise-grid">
            <?php foreach ( $expertise_items as $item ) : 
                // Получаем данные из repeater элемента напрямую
                $title = isset( $item['expertise_title'] ) ? $item['expertise_title'] : '';
                // Также проверяем альтернативное имя поля (expertise_name)
                if ( empty( $title ) && isset( $item['expertise_name'] ) ) {
                    $title = $item['expertise_name'];
                }
                $description = isset( $item['expertise_description'] ) ? $item['expertise_description'] : '';
                
                if ( empty( $title ) ) {
                    continue;
                }
            ?>
                <div class="expertise-item">
                    <h4><?php echo esc_html( $title ); ?></h4>
                    <?php if ( ! empty( $description ) ) : ?>
                        <p><?php echo esc_html( $description ); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

