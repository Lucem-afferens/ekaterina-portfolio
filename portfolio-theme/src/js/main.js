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
            const p = heroContent.querySelector('p');
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
            
            if (p) {
                setTimeout(() => {
                    p.style.opacity = '1';
                    p.style.transform = 'translateY(0)';
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
    const isTouchDevice = 'ontouchstart' in window || 
                          navigator.maxTouchPoints > 0 || 
                          (window.matchMedia && window.matchMedia('(pointer: coarse)').matches);
    
    if (isTouchDevice) {
        const portfolioObserverOptions = {
            threshold: [0, 0.95, 1.0],
            rootMargin: '0px'
        };

        const portfolioObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const portfolioItem = entry.target;
                
                if (entry.isIntersecting && entry.intersectionRatio >= 0.95) {
                    portfolioItem.classList.add('fully-visible');
                } else {
                    portfolioItem.classList.remove('fully-visible');
                }
            });
        }, portfolioObserverOptions);

        window.addEventListener('load', function() {
            document.querySelectorAll('.portfolio-item').forEach(item => {
                portfolioObserver.observe(item);
            });
        });
        
        document.querySelectorAll('.portfolio-item').forEach(item => {
            portfolioObserver.observe(item);
        });
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
    
    // Открытие модального окна отзыва
    const openTestimonialModalBtn = document.getElementById('open-testimonial-modal');
    if (openTestimonialModalBtn) {
        openTestimonialModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal('testimonial-modal');
        });
    }
    
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
    
    // Обработка формы заявки
    const requestForm = document.getElementById('request-form');
    if (requestForm) {
        requestForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Получаем данные формы
            const formData = new FormData(this);
            const name = document.getElementById('request-name').value.trim();
            const phone = document.getElementById('request-phone').value.trim();
            
            // Валидация обязательных полей
            if (!name || !phone) {
                alert('Пожалуйста, заполните все обязательные поля');
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
                    alert(data.data.message || 'Ошибка при отправке заявки');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при отправке заявки. Пожалуйста, попробуйте позже.');
            });
        });
    }
    
    // Обработка формы отзыва
    const testimonialForm = document.getElementById('testimonial-form');
    if (testimonialForm) {
        testimonialForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Получаем данные формы
            const formData = new FormData(this);
            const name = document.getElementById('testimonial-name').value.trim();
            const email = document.getElementById('testimonial-email').value.trim();
            const message = document.getElementById('testimonial-message').value.trim();
            
            // Валидация обязательных полей
            if (!name || !email || !message) {
                alert('Пожалуйста, заполните все обязательные поля');
                return;
            }
            
            // Валидация email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                alert('Пожалуйста, введите корректный email адрес');
                return;
            }
            
            // Добавляем nonce для безопасности
            formData.append('action', 'ekaterina_testimonial_form');
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
                    const successMessage = document.getElementById('testimonial-success');
                    if (successMessage) {
                        successMessage.style.display = 'block';
                    }
                    
                    // Автоматически закрываем модальное окно через 6 секунд
                    setTimeout(() => {
                        closeModal('testimonial-modal');
                    }, 6000);
                } else {
                    alert(data.data.message || 'Ошибка при отправке отзыва');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ошибка при отправке отзыва. Пожалуйста, попробуйте позже.');
            });
        });
    }
    
    // ========================================
    // Phone Input Mask
    // ========================================
    const phoneInput = document.getElementById('request-phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
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

