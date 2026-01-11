<?php
/**
 * Portfolio Gallery Modal Component
 * 
 * Компонент модального окна для галереи портфолио.
 * Показывает все изображения из выбранной категории.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- Модальное окно для галереи портфолио -->
<div id="portfolio-gallery-modal" class="modal portfolio-gallery-modal" aria-hidden="true" role="dialog">
    <div class="modal-overlay"></div>
    <div class="modal-content portfolio-gallery-content">
        <button class="modal-close portfolio-gallery-close" aria-label="Закрыть галерею">
            <i class="fa-solid fa-times"></i>
        </button>
        
        <!-- Состояние 1: Сетка изображений -->
        <div class="portfolio-gallery-grid-view" id="portfolio-gallery-grid-view">
            <div class="portfolio-gallery-header">
                <h3 class="portfolio-gallery-title" id="portfolio-gallery-title"></h3>
                <div class="portfolio-gallery-counter">
                    <span id="portfolio-gallery-total">0</span> фото
                </div>
            </div>
            <div class="portfolio-gallery-grid" id="portfolio-gallery-grid">
                <!-- Изображения будут добавлены через JavaScript -->
            </div>
        </div>
        
        <!-- Состояние 2: Просмотр изображения -->
        <div class="portfolio-gallery-viewer-view" id="portfolio-gallery-viewer-view" style="display: none;">
            <div class="portfolio-gallery-header">
                <h3 class="portfolio-gallery-title" id="portfolio-gallery-viewer-title"></h3>
                <div class="portfolio-gallery-counter">
                    <span id="portfolio-gallery-current">1</span> / <span id="portfolio-gallery-viewer-total">1</span>
                </div>
            </div>
            
            <div class="portfolio-gallery-main">
                <button class="portfolio-gallery-nav portfolio-gallery-prev" aria-label="Предыдущее изображение" id="portfolio-gallery-prev">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                
                <div class="portfolio-gallery-viewport">
                    <div class="portfolio-gallery-slider" id="portfolio-gallery-slider">
                        <!-- Изображения будут добавлены через JavaScript -->
                    </div>
                </div>
                
                <button class="portfolio-gallery-nav portfolio-gallery-next" aria-label="Следующее изображение" id="portfolio-gallery-next">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
            
            <div class="portfolio-gallery-thumbnails" id="portfolio-gallery-thumbnails">
                <!-- Миниатюры будут добавлены через JavaScript -->
            </div>
        </div>
    </div>
</div>

