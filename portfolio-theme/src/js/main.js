/**
 * Main JavaScript File
 * 
 * Основной файл JavaScript для темы Portfolio Theme.
 * Содержит всю интерактивность сайта.
 *
 * @package Portfolio_Theme
 * @since 1.0.0
 */

// Импортируем CSS для сборки через Vite
import '../css/main.css';

(function() {
    'use strict';

    // ========================================
    // Header Scroll Effect
    // ========================================
    window.addEventListener('scroll', function() {
        const header = document.getElementById('header');
        if (window.scrollY > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // ========================================
    // Hero Animation on Load
    // ========================================
    window.addEventListener('load', function() {
        setTimeout(() => {
            const heroContent = document.querySelector('.hero-content');
            if (!heroContent) return;
            
            const h2 = heroContent.querySelector('h2');
            const divider = heroContent.querySelector('.hero-divider');
            const subtitle = heroContent.querySelector('.hero-subtitle');
            const cta = heroContent.querySelector('.hero-cta');
            
            if (h2) {
                h2.style.opacity = '1';
                h2.style.transform = 'translateY(0)';
            }
            
            if (divider) {
                setTimeout(() => {
                    divider.style.transform = 'scaleX(1)';
                }, 400);
            }
            
            if (subtitle) {
                // Отменяем CSS анимацию и показываем через JavaScript для более точного контроля
                subtitle.style.animation = 'none';
                setTimeout(() => {
                    subtitle.style.opacity = '1';
                    subtitle.style.transform = 'translateY(0)';
                    subtitle.classList.add('visible');
                }, 600);
            }
            
            if (cta) {
                setTimeout(() => {
                    cta.style.opacity = '1';
                    cta.style.transform = 'translateY(0)';
                }, 800);
            }
        }, 300);
    });

    // ========================================
    // Counter Animation
    // ========================================
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;
        
        // Определяем суффикс для отображения после числа
        let suffix = '';
        if (target === 95) {
            suffix = '%';
        } else if (target === 300 || target === 3) {
            suffix = '+';
        }
        
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                element.textContent = target + suffix;
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + suffix;
            }
        }, 16);
    }

    // ========================================
    // Intersection Observer for Animations
    // ========================================
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                
                // Animate counters
                if (entry.target.classList.contains('stat-item')) {
                    const counter = entry.target.querySelector('.counter');
                    if (counter && counter.textContent === '0') {
                        animateCounter(counter);
                    }
                }
                
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe stat items
    document.querySelectorAll('.stat-item').forEach(item => {
        observer.observe(item);
    });

    // Observe portfolio items
    document.querySelectorAll('.portfolio-item').forEach((item, index) => {
        observer.observe(item);
    });

    // ========================================
    // Touch Devices: Portfolio Overlay
    // ========================================
    // Определяем touch устройство более точно
    const isTouchDevice = 'ontouchstart' in window || 
                          navigator.maxTouchPoints > 0 || 
                          (window.matchMedia && window.matchMedia('(pointer: coarse)').matches) ||
                          (window.matchMedia && window.matchMedia('(hover: none)').matches);
    
    if (isTouchDevice) {
        // Настройки для Intersection Observer: проверяем видимость 95%
        const portfolioObserverOptions = {
            threshold: [0, 0.5, 0.75, 0.95, 1.0], // Несколько порогов для плавности
            rootMargin: '0px'
        };

        const portfolioObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const portfolioItem = entry.target;
                
                // Показываем overlay когда элемент виден на 95% или больше
                if (entry.isIntersecting && entry.intersectionRatio >= 0.95) {
                    portfolioItem.classList.add('fully-visible');
                } else {
                    portfolioItem.classList.remove('fully-visible');
                }
            });
        }, portfolioObserverOptions);

        // Наблюдаем за элементами портфолио
        function observePortfolioItems() {
            document.querySelectorAll('.portfolio-item').forEach(item => {
                portfolioObserver.observe(item);
            });
        }

        // Запускаем наблюдение при загрузке страницы
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', observePortfolioItems);
        } else {
            observePortfolioItems();
        }
        
        // Также запускаем после полной загрузки для динамически добавленных элементов
        window.addEventListener('load', observePortfolioItems);
    }

    // ========================================
    // Smooth Scroll
    // ========================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
                
                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // ========================================
    // Parallax Effect for Hero
    // ========================================
    window.addEventListener('scroll', function() {
        const heroImage = document.querySelector('.hero-image');
        const scrolled = window.pageYOffset;
        if (heroImage && scrolled < 900) {
            heroImage.style.transform = `translateY(${scrolled * 0.5}px)`;
        }
    });

    // ========================================
    // Mobile Menu Toggle
    // ========================================
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const headerNav = document.getElementById('header-nav');
    
    if (mobileMenuToggle && headerNav) {
        mobileMenuToggle.addEventListener('click', function() {
            const isExpanded = mobileMenuToggle.getAttribute('aria-expanded') === 'true';
            mobileMenuToggle.setAttribute('aria-expanded', !isExpanded);
            headerNav.classList.toggle('active');
            document.body.style.overflow = !isExpanded ? 'hidden' : '';
        });
        
        // Close menu when clicking on a link
        const navLinks = headerNav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                headerNav.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideNav = headerNav.contains(event.target);
            const isClickOnToggle = mobileMenuToggle.contains(event.target);
            
            if (!isClickInsideNav && !isClickOnToggle && headerNav.classList.contains('active')) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                headerNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Close menu on window resize if it becomes desktop view
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768 && headerNav.classList.contains('active')) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                headerNav.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }

    // ========================================
    // Modal Windows Management
    // ========================================
    
    // Функция для открытия модального окна
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            
            // Фокус на первом поле формы
            const firstInput = modal.querySelector('input, textarea, select');
            if (firstInput) {
                setTimeout(() => firstInput.focus(), 100);
            }
        }
    }
    
    // Функция для закрытия модального окна
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            
            // Сброс формы и скрытие сообщения об успехе
            const form = modal.querySelector('form');
            const success = modal.querySelector('.modal-success');
            if (form) {
                form.reset();
                form.style.display = 'block';
            }
            if (success) {
                success.style.display = 'none';
            }
        }
    }
    
    // Открытие модального окна заявки
    const openRequestModalBtn = document.getElementById('open-request-modal');
    if (openRequestModalBtn) {
        openRequestModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal('request-modal');
        });
    }
    
    // Кнопка "Оставить отзыв" теперь является ссылкой, поэтому обработчик модального окна удален
    
    // Закрытие модальных окон по кнопке закрытия
    document.querySelectorAll('.modal-close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Закрытие модальных окон по клику на overlay
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Закрытие модальных окон по клавише Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.active').forEach(modal => {
                closeModal(modal.id);
            });
        }
    });
    
    // ========================================
    // Form Handling with WordPress AJAX
    // ========================================
    
    /**
     * Функция валидации российского номера телефона
     * Принимает отформатированный номер (+7 (XXX) XXX-XX-XX) или сырые цифры
     * Возвращает объект с результатом валидации
     */
    function validatePhone(phone) {
        if (!phone || typeof phone !== 'string') {
            return { valid: false, message: 'Номер телефона не указан' };
        }
        
        // Убираем все нецифровые символы кроме +
        const cleanPhone = phone.replace(/[^\d+]/g, '');
        
        // Убираем + для проверки
        const digitsOnly = cleanPhone.replace(/\+/g, '');
        
        // Если начинается с 8, заменяем на 7
        let normalized = digitsOnly;
        if (normalized.startsWith('8')) {
            normalized = '7' + normalized.slice(1);
        }
        
        // Проверяем, что номер начинается с 7 и содержит 11 цифр (7 + 10 цифр номера)
        if (!normalized.startsWith('7')) {
            return { valid: false, message: 'Номер должен начинаться с +7 или 8' };
        }
        
        if (normalized.length !== 11) {
            return { valid: false, message: 'Номер должен содержать 11 цифр (включая код страны 7)' };
        }
        
        // Проверяем, что код оператора (вторая цифра после 7) корректный (3, 4, 5, 6, 7, 8, 9)
        const operatorCode = normalized.charAt(1);
        if (!['3', '4', '5', '6', '7', '8', '9'].includes(operatorCode)) {
            return { valid: false, message: 'Неверный код оператора' };
        }
        
        // Форматируем номер для отображения (+7 (XXX) XXX-XX-XX)
        const phoneDigits = normalized.slice(1); // 10 цифр без 7
        const formatted = '+7 (' + phoneDigits.slice(0, 3) + ') ' + phoneDigits.slice(3, 6) + '-' + phoneDigits.slice(6, 8) + '-' + phoneDigits.slice(8, 10);
        
        return { valid: true, normalized: '+7' + phoneDigits, formatted: formatted };
    }
    
    // Обработка формы заявки
    const requestForm = document.getElementById('request-form');
    if (requestForm) {
        requestForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Получаем данные формы
            const formData = new FormData(this);
            const nameInput = document.getElementById('request-name');
            const phoneInput = document.getElementById('request-phone');
            const privacyCheckbox = document.getElementById('request-privacy');
            
            const name = nameInput ? nameInput.value.trim() : '';
            const phone = phoneInput ? phoneInput.value.trim() : '';
            
            // Валидация обязательных полей
            if (!name || !phone) {
                alert('Пожалуйста, заполните все обязательные поля');
                if (!name && nameInput) {
                    nameInput.focus();
                } else if (!phone && phoneInput) {
                    phoneInput.focus();
                }
                return;
            }
            
            // Валидация номера телефона
            const phoneValidation = validatePhone(phone);
            if (!phoneValidation.valid) {
                alert('Ошибка: ' + phoneValidation.message + '\n\nПожалуйста, введите корректный номер телефона в формате: +7 (XXX) XXX-XX-XX');
                if (phoneInput) {
                    phoneInput.classList.add('error');
                    phoneInput.focus();
                    // Показываем сообщение об ошибке
                    let phoneErrorMessage = phoneInput.parentElement.querySelector('.error-message');
                    if (!phoneErrorMessage) {
                        phoneErrorMessage = document.createElement('span');
                        phoneErrorMessage.className = 'error-message';
                        phoneInput.parentElement.appendChild(phoneErrorMessage);
                    }
                    phoneErrorMessage.textContent = phoneValidation.message;
                }
                return;
            }
            
            // Убираем класс ошибки, если валидация прошла успешно
            if (phoneInput) {
                phoneInput.classList.remove('error');
                const phoneErrorMessage = phoneInput.parentElement.querySelector('.error-message');
                if (phoneErrorMessage) {
                    phoneErrorMessage.remove();
                }
            }
            
            // Обновляем значение телефона на нормализованное (без форматирования) перед отправкой
            // Но в поле оставляем отформатированное для красоты
            if (phoneValidation.normalized) {
                // Отправляем нормализованный номер (для обработки на сервере)
                formData.set('phone', phoneValidation.formatted || phoneValidation.normalized);
                // Обновляем поле на отформатированное значение для красоты
                if (phoneInput && phoneValidation.formatted && phone !== phoneValidation.formatted) {
                    phoneInput.value = phoneValidation.formatted;
                }
            }
            
            // Валидация чекбокса политики конфиденциальности
            if (!privacyCheckbox || !privacyCheckbox.checked) {
                alert('Необходимо согласиться с политикой конфиденциальности');
                if (privacyCheckbox) {
                    privacyCheckbox.focus();
                }
                return;
            }
            
            // Добавляем nonce для безопасности
            formData.append('action', 'ekaterina_request_form');
            if (typeof ekaterinaAjax !== 'undefined') {
                formData.append('nonce', ekaterinaAjax.nonce);
            }
            
            // Отправка через WordPress AJAX
            fetch(ekaterinaAjax.ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Скрываем форму и показываем сообщение об успехе
                    this.style.display = 'none';
                    const successMessage = document.getElementById('request-success');
                    if (successMessage) {
                        successMessage.style.display = 'block';
                    }
                    
                    // Автоматически закрываем модальное окно через 6 секунд
                    setTimeout(() => {
                        closeModal('request-modal');
                    }, 6000);
                } else {
                    // Обработка ошибок валидации с сервера
                    const errorMessage = data.data && data.data.message ? data.data.message : 'Ошибка при отправке заявки';
                    
                    // Если ошибка связана с телефоном, показываем сообщение под полем
                    if (errorMessage.toLowerCase().includes('телефон') || errorMessage.toLowerCase().includes('номер')) {
                        if (phoneInput) {
                            phoneInput.classList.add('error');
                            phoneInput.focus();
                            // Показываем сообщение об ошибке
                            let phoneErrorMessage = phoneInput.parentElement.querySelector('.error-message');
                            if (!phoneErrorMessage) {
                                phoneErrorMessage = document.createElement('span');
                                phoneErrorMessage.className = 'error-message';
                                phoneInput.parentElement.appendChild(phoneErrorMessage);
                            }
                            phoneErrorMessage.textContent = errorMessage;
                        }
                    }
                    
                    alert(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при отправке заявки. Пожалуйста, попробуйте позже.');
            });
        });
    }
    
    // Обработка формы отзыва больше не используется (кнопка теперь ведет на ссылку)
    
    // ========================================
    // Phone Input Mask and Validation
    // ========================================
    const phoneInput = document.getElementById('request-phone');
    if (phoneInput) {
        let phoneErrorMessage = null; // Элемент для сообщения об ошибке
        
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Убираем сообщение об ошибке при вводе
            if (phoneErrorMessage) {
                phoneErrorMessage.remove();
                phoneErrorMessage = null;
            }
            phoneInput.classList.remove('error');
            
            // Если начинается с 8, заменяем на 7
            if (value.startsWith('8')) {
                value = '7' + value.slice(1);
            }
            
            // Форматируем только если начинается с 7
            if (value.startsWith('7') && value.length > 1) {
                let formatted = '+7';
                if (value.length > 1) {
                    formatted += ' (' + value.slice(1, 4);
                }
                if (value.length >= 4) {
                    formatted += ') ' + value.slice(4, 7);
                }
                if (value.length >= 7) {
                    formatted += '-' + value.slice(7, 9);
                }
                if (value.length >= 9) {
                    formatted += '-' + value.slice(9, 11);
                }
                e.target.value = formatted;
            } else if (value.length === 0) {
                e.target.value = '';
            }
        });
        
        // Валидация при потере фокуса (blur)
        phoneInput.addEventListener('blur', function(e) {
            const phone = e.target.value.trim();
            if (phone) {
                const validation = validatePhone(phone);
                if (!validation.valid) {
                    phoneInput.classList.add('error');
                    // Создаем сообщение об ошибке
                    if (!phoneErrorMessage) {
                        phoneErrorMessage = document.createElement('span');
                        phoneErrorMessage.className = 'error-message';
                        phoneErrorMessage.textContent = validation.message;
                        phoneInput.parentElement.appendChild(phoneErrorMessage);
                    } else {
                        phoneErrorMessage.textContent = validation.message;
                    }
                } else {
                    phoneInput.classList.remove('error');
                    if (phoneErrorMessage) {
                        phoneErrorMessage.remove();
                        phoneErrorMessage = null;
                    }
                    // Обновляем значение на отформатированное (если отличается)
                    if (validation.formatted && phone !== validation.formatted) {
                        e.target.value = validation.formatted;
                    }
                }
            }
        });
        
        // Обработка вставки текста
        phoneInput.addEventListener('paste', function(e) {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text');
            const numbers = pasted.replace(/\D/g, '');
            if (numbers.length > 0) {
                phoneInput.value = '';
                phoneInput.dispatchEvent(new Event('input', { bubbles: true }));
                phoneInput.value = numbers;
                phoneInput.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });
    }

})();

