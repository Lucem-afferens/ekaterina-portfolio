<?php
/**
 * Philosophy Section Component
 * 
 * Компонент секции "Философия работы".
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Получаем данные из SCF
// Получаем ID текущей страницы для правильного контекста
$current_page_id = ekaterina_get_current_page_id();

// Проверяем, включена ли секция Philosophy
$philosophy_enabled = function_exists( 'get_field' ) ? get_field( 'philosophy_enabled', $current_page_id ) : true;
// Если поле не установлено, по умолчанию секция включена
if ( $philosophy_enabled === null ) {
    $philosophy_enabled = true;
}
// Преобразуем в boolean
$philosophy_enabled = (bool) $philosophy_enabled;

// Если секция отключена, не выводим её
if ( ! $philosophy_enabled ) {
    return;
}

$philosophy_title = function_exists( 'get_field' ) ? get_field( 'philosophy_title', $current_page_id ) : null;
$philosophy_title = $philosophy_title ?: 'Философия работы';
$philosophy_quote = function_exists( 'get_field' ) ? get_field( 'philosophy_quote', $current_page_id ) : null;
$philosophy_quote = $philosophy_quote ?: '"Настоящая элегантность не привлекает внимание к себе, она создаёт пространство, в котором каждый гость чувствует себя особенным"';
$philosophy_principles = function_exists( 'get_field' ) ? get_field( 'philosophy_principles', $current_page_id ) : false;

// Если принципы не заполнены, используем дефолтные значения
if ( empty( $philosophy_principles ) ) {
    $philosophy_principles = array(
        array(
            'principle_title' => 'Индивидуальность',
            'principle_description' => 'Каждый сценарий создаётся с учётом характера события и пожеланий заказчика',
        ),
        array(
            'principle_title' => 'Безупречность',
            'principle_description' => 'Тщательная подготовка и внимание к каждой детали программы',
        ),
        array(
            'principle_title' => 'Естественность',
            'principle_description' => 'Органичное ведение без излишней театральности',
        ),
    );
}
?>

<section id="philosophy">
    <div class="philosophy-container">
        <div class="philosophy-content">
            <div class="philosophy-divider"></div>
            <h3><?php echo esc_html( $philosophy_title ); ?></h3>
            <p class="philosophy-quote"><?php echo esc_html( $philosophy_quote ); ?></p>
            <div class="philosophy-grid">
                <?php foreach ( $philosophy_principles as $principle ) : 
                    $title = ekaterina_get_repeater_field( $principle, 'principle_title', '' );
                    $description = ekaterina_get_repeater_field( $principle, 'principle_description', '' );
                    
                    if ( empty( $title ) ) {
                        continue;
                    }
                ?>
                    <div class="philosophy-item">
                        <h4><?php echo esc_html( $title ); ?></h4>
                        <p><?php echo esc_html( $description ); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

