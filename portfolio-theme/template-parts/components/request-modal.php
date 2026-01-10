<?php
/**
 * Request Modal Component
 * 
 * Компонент модального окна для формы заявки.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Предотвращаем прямой доступ к файлу
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<!-- Модальное окно для формы заявки -->
<div id="request-modal" class="modal" aria-hidden="true" role="dialog">
    <div class="modal-overlay"></div>
    <div class="modal-content">
        <button class="modal-close" aria-label="Закрыть окно">
            <i class="fa-solid fa-times"></i>
        </button>
        
        <form id="request-form" class="modal-form">
            <div class="form-group">
                <label for="request-name">Ваше имя *</label>
                <input type="text" id="request-name" name="name" required placeholder="Введите ваше имя">
            </div>
            
            <div class="form-group">
                <label for="request-phone">Телефон *</label>
                <input type="tel" id="request-phone" name="phone" required placeholder="+7 (___) ___-__-__">
            </div>
            
            <div class="form-group">
                <label for="request-email">Email</label>
                <input type="email" id="request-email" name="email" placeholder="your@email.com">
            </div>
            
            <div class="form-group">
                <label for="request-event-type">Тип мероприятия</label>
                <select id="request-event-type" name="event-type">
                    <option value="">Выберите тип мероприятия</option>
                    <option value="wedding">Свадьба</option>
                    <option value="corporate">Корпоративное мероприятие</option>
                    <option value="private">Частный приём</option>
                    <option value="charity">Благотворительное мероприятие</option>
                    <option value="conference">Конференция</option>
                    <option value="other">Другое</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="request-date">Предполагаемая дата</label>
                <input type="date" id="request-date" name="date">
            </div>
            
            <div class="form-group">
                <label for="request-message">Сообщение</label>
                <textarea id="request-message" name="message" rows="4" placeholder="Расскажите о вашем мероприятии..."></textarea>
            </div>
            
            <?php
            // Получаем URL страницы политики конфиденциальности через вспомогательную функцию
            $privacy_url = ekaterina_get_privacy_policy_url();
            ?>
            <div class="form-group form-group-checkbox">
                <label class="checkbox-label" for="request-privacy">
                    <input type="checkbox" id="request-privacy" name="privacy" required aria-required="true">
                    <span class="checkbox-text">
                        Я согласен(а) с 
                        <a href="<?php echo esc_url( $privacy_url ); ?>" target="_blank" rel="noopener noreferrer" class="privacy-link">
                            политикой конфиденциальности
                        </a>
                        *
                    </span>
                </label>
            </div>
            
            <button type="submit" class="modal-submit-btn">ОТПРАВИТЬ ЗАЯВКУ</button>
        </form>
        
        <div id="request-success" class="modal-success" style="display: none;">
            <div class="success-icon">
                <i class="fa-solid fa-check-circle"></i>
            </div>
            <h4>Заявка отправлена!</h4>
            <p>Спасибо за вашу заявку. Я свяжусь с вами в ближайшее время.</p>
        </div>
    </div>
</div>

