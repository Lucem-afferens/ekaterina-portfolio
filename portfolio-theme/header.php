<?php
/**
 * Header Template
 * 
 * Шаблон шапки сайта. Содержит навигацию и основные мета-теги.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header id="header">
    <div class="header-container">
        <div>
            <h1 class="header-logo">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                    <?php 
                    // Получаем имя из SCF Options или используем название сайта
                    $host_name = '';
                    if ( class_exists( 'SCF' ) ) {
                        $host_name = SCF::get_option_meta( 'theme_options', 'site_host_name' );
                    }
                    if ( empty( $host_name ) ) {
                        $host_name = get_bloginfo( 'name' );
                    }
                    echo esc_html( $host_name );
                    ?>
                </a>
            </h1>
        </div>
        <button class="mobile-menu-toggle" aria-label="Открыть меню" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <nav class="header-nav" id="header-nav">
            <?php
            // Навигационное меню через WordPress
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => '',
                    'items_wrap'     => '%3$s',
                ) );
            } else {
                // Fallback меню, если меню не настроено
                ekaterina_default_menu();
            }
            ?>
        </nav>
    </div>
</header>

