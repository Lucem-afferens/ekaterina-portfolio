<?php
/**
 * Stats Banner Component
 * 
 * Компонент секции статистики.
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

// Проверяем, включена ли секция Stats
$stats_enabled = function_exists( 'get_field' ) ? get_field( 'stats_enabled', $current_page_id ) : true;
// Если поле не установлено, по умолчанию секция включена
if ( $stats_enabled === null ) {
    $stats_enabled = true;
}
// Преобразуем в boolean
$stats_enabled = (bool) $stats_enabled;

// Если секция отключена, не выводим её
if ( ! $stats_enabled ) {
    return;
}

$stats_items = function_exists( 'get_field' ) ? get_field( 'stats_items', $current_page_id ) : false;

// Если статистика не заполнена, используем дефолтные значения
if ( empty( $stats_items ) ) {
    $stats_items = array(
        array(
            'stat_number' => '300',
            'stat_label' => 'ПРОЕКТОВ ВЫПОЛНЕНО',
        ),
        array(
            'stat_number' => '3',
            'stat_label' => 'ЛЕТ ОПЫТА',
        ),
        array(
            'stat_number' => '95',
            'stat_label' => 'ПОВТОРНЫХ КЛИЕНТОВ',
        ),
        array(
            'stat_number' => '24',
            'stat_label' => 'ЧАСА НА ОТВЕТ',
        ),
    );
}
?>

<section id="stats-banner">
    <div class="stats-container">
        <div class="stats-grid">
            <?php foreach ( $stats_items as $stat ) : 
                $number = ekaterina_get_repeater_field( $stat, 'stat_number', '0' );
                $label = ekaterina_get_repeater_field( $stat, 'stat_label', '' );
                
                if ( empty( $label ) ) {
                    continue;
                }
                
                // Определяем суффикс
                $suffix = '';
                if ( $number == '95' ) {
                    $suffix = '%';
                } elseif ( in_array( $number, array( '300', '3' ) ) ) {
                    $suffix = '+';
                }
            ?>
                <div class="stat-item">
                    <div class="stat-number counter" data-target="<?php echo esc_attr( $number ); ?>">0<?php echo esc_html( $suffix ); ?></div>
                    <p class="stat-label"><?php echo esc_html( $label ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

