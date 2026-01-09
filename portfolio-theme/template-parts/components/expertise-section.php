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
        array( 'expertise_title' => 'Корпоративные мероприятия', 'expertise_description' => 'Юбилеи компаний, бизнес-форумы, награждения, тимбилдинги, новогодние корпоративы' ),
        array( 'expertise_title' => 'Свадебные торжества', 'expertise_description' => 'Классические и тематические свадьбы, выездные регистрации, камерные церемонии' ),
        array( 'expertise_title' => 'Частные приёмы', 'expertise_description' => 'Юбилеи, семейные торжества, дни рождения в премиум-формате' ),
        array( 'expertise_title' => 'Благотворительные вечера', 'expertise_description' => 'Гала-ужины, аукционы, фандрайзинговые мероприятия' ),
        array( 'expertise_title' => 'Презентации и запуски', 'expertise_description' => 'Открытия бизнесов, презентации продуктов, пресс-конференции' ),
        array( 'expertise_title' => 'Конференции', 'expertise_description' => 'Модерация панельных дискуссий, ведение пленарных заседаний' ),
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

