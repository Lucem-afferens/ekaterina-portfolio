<?php
/**
 * Testimonial Modal Component
 * 
 * Компонент модального окна для формы отзыва.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- Модальное окно для формы отзыва -->
<div id="testimonial-modal" class="modal" aria-hidden="true" role="dialog">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <button class="modal-close" aria-label="Закрыть окно">
            <i class="fa-solid fa-times"></i>
        </button>
        
        <form id="testimonial-form" class="modal-form">
            <div class="form-group">
                <label for="testimonial-name">Ваше имя *</label>
                <input type="text" id="testimonial-name" name="name" required placeholder="Введите ваше имя">
            </div>
            
            <div class="form-group">
                <label for="testimonial-title">Ваша должность / Компания</label>
                <input type="text" id="testimonial-title" name="title" placeholder="Например: Генеральный директор, ПАО 'Компания'">
            </div>
            
            <div class="form-group">
                <label for="testimonial-email">Email *</label>
                <input type="email" id="testimonial-email" name="email" required placeholder="your@email.com">
            </div>
            
            <div class="form-group">
                <label for="testimonial-event">Тип мероприятия</label>
                <input type="text" id="testimonial-event" name="event" placeholder="Например: Корпоративный юбилей">
            </div>
            
            <div class="form-group">
                <label for="testimonial-message">Ваш отзыв *</label>
                <textarea id="testimonial-message" name="message" rows="6" required placeholder="Поделитесь вашими впечатлениями..."></textarea>
            </div>
            
            <button type="submit" class="modal-submit-btn">ОТПРАВИТЬ НА МОДЕРАЦИЮ</button>
        </form>
        
        <div id="testimonial-success" class="modal-success" style="display: none;">
            <div class="success-icon">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <h4>Отзыв отправлен на модерацию!</h4>
            <p>Спасибо за ваш отзыв. После проверки он будет опубликован на сайте.</p>
        </div>
    </div>
</div>

