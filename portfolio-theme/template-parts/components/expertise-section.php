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

// Получаем данные из SCF
$expertise_title = ekaterina_get_scf_field( 'expertise_title', 'Экспертиза' );
$expertise_items = ekaterina_get_scf_repeater( 'expertise_items' );

// Если элементы не заполнены, используем дефолтные значения
if ( empty( $expertise_items ) ) {
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
            <h3><?php echo esc_html( $expertise_title ); ?></h3>
        </div>
        <div class="expertise-grid">
            <?php foreach ( $expertise_items as $item ) : 
                $title = ekaterina_get_repeater_field( $item, 'expertise_title', '' );
                $description = ekaterina_get_repeater_field( $item, 'expertise_description', '' );
                
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

