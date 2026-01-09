# Исправление: Переход на правильный API SCF

## 🔴 КРИТИЧЕСКАЯ ПРОБЛЕМА НАЙДЕНА

**Проблема:** В теме Ekaterina использовался неправильный API для работы с SCF полями.

**В Ekaterina (неправильно):**
- Использовался `SCF::get()` - прямой доступ к классу
- Использовался `SCF::get_option_meta()` - неправильный метод для Options Page
- Использовался `class_exists('SCF')` для проверки

**В Tochka-Gg (правильно):**
- Используется `get_field()` - стандартный API ACF/SCF
- Используется `get_field('field', 'option')` для Options Page
- Используется `function_exists('get_field')` для проверки

## ✅ РЕШЕНИЕ

**SCF (Secure Custom Fields) является форком ACF и полностью совместим с его API.**

Это означает, что нужно использовать стандартные функции ACF:
- `get_field()` вместо `SCF::get()`
- `get_field('field', 'option')` вместо `SCF::get_option_meta()`
- `have_rows()` и `get_sub_field()` для Repeater полей

---

## 📝 Что исправлено

### 1. Функции получения полей (`inc/security.php`)

**Было:**
```php
function ekaterina_get_scf_field($field_name, $default = '', $context = 'html', $post_id = null) {
    if (!class_exists('SCF')) {
        return $default;
    }
    $value = SCF::get($field_name, $post_id);
    // ...
}
```

**Стало:**
```php
function ekaterina_get_scf_field($field_name, $default = '', $context = 'html', $post_id = null) {
    if (!function_exists('get_field')) {
        return $default;
    }
    $value = get_field($field_name, $post_id ? $post_id : false);
    // ...
}
```

### 2. Функция получения Options Page полей

**Было:**
```php
$value = SCF::get_option_meta($group_name, $field_name);
```

**Стало:**
```php
$value = get_field($field_name, 'option');
```

### 3. Функция получения Repeater полей (`inc/scf-fields.php`)

**Было:**
```php
$repeater = SCF::get($field_name, $post_id);
```

**Стало:**
```php
$repeater = get_field($field_name, $post_id ? $post_id : false);
```

### 4. Функция получения изображений

**Было:**
```php
$image_id = SCF::get($field_name, $post_id);
$image_url = wp_get_attachment_image_url($image_id, 'full');
```

**Стало:**
```php
$image_data = get_field($field_name, $post_id ? $post_id : false);
// get_field() для Image field возвращает массив ['ID', 'url', 'alt'] или ID
if (is_array($image_data) && !empty($image_data['url'])) {
    $image_url = $image_data['url'];
} elseif (is_numeric($image_data)) {
    $image_url = wp_get_attachment_image_url($image_data, 'full');
}
```

### 5. Компоненты обновлены для использования get_field() напрямую

**Обновленные компоненты:**
- `hero-section.php` - использует `get_field()` напрямую
- `introduction-section.php` - использует `get_field()` напрямую
- `about-section.php` - использует `get_field()` напрямую
- `stats-banner.php` - использует `get_field()` напрямую
- `header.php` - использует `get_field(..., 'option')` для Options Page
- `footer.php` - использует `get_field(..., 'option')` для Options Page

**Остальные компоненты** используют обновленные функции `ekaterina_get_scf_field()` и `ekaterina_get_scf_repeater()`, которые теперь внутри используют `get_field()`.

---

## 🔍 Ключевые отличия от Tochka-Gg

| Аспект | Tochka-Gg | Ekaterina (до исправления) | Ekaterina (после исправления) |
|--------|-----------|---------------------------|------------------------------|
| **API** | `get_field()` | `SCF::get()` ❌ | `get_field()` ✅ |
| **Проверка** | `function_exists('get_field')` | `class_exists('SCF')` ❌ | `function_exists('get_field')` ✅ |
| **Options** | `get_field('field', 'option')` | `SCF::get_option_meta()` ❌ | `get_field('field', 'option')` ✅ |
| **Изображения** | Массив `['url', 'ID']` | Предполагается ID ❌ | Проверка массива/ID ✅ |
| **Repeater** | `get_field()` возвращает массив | `SCF::get()` ❌ | `get_field()` ✅ |

---

## 📋 Измененные файлы

1. **`portfolio-theme/inc/security.php`**
   - `ekaterina_get_scf_field()` - теперь использует `get_field()`
   - `ekaterina_get_scf_option()` - теперь использует `get_field(..., 'option')`
   - `ekaterina_get_scf_image()` - теперь использует `get_field()` и правильно обрабатывает массив/ID

2. **`portfolio-theme/inc/scf-fields.php`**
   - `ekaterina_get_scf_repeater()` - теперь использует `get_field()`

3. **`portfolio-theme/template-parts/components/hero-section.php`**
   - Использует `get_field()` напрямую (как в Tochka-Gg)
   - Правильная обработка изображений (массив или ID)

4. **`portfolio-theme/template-parts/components/introduction-section.php`**
   - Использует `get_field()` напрямую
   - Правильная обработка изображений

5. **`portfolio-theme/template-parts/components/about-section.php`**
   - Использует `get_field()` напрямую
   - Правильная обработка изображений

6. **`portfolio-theme/template-parts/components/stats-banner.php`**
   - Использует `get_field()` напрямую

7. **`portfolio-theme/header.php`**
   - Использует `get_field(..., 'option')` для Options Page

8. **`portfolio-theme/footer.php`**
   - Использует `get_field(..., 'option')` для Options Page

---

## 🚀 Что делать дальше

1. **Загрузите обновленные файлы на сервер**
2. **Очистите кеш:**
   - Кеш браузера (Ctrl+F5)
   - Кеш WordPress (если используется плагин кеширования)
3. **Проверьте:**
   - Откройте страницу "Главная" для редактирования
   - Измените поле (например, `hero_name`)
   - Нажмите "Обновить"
   - Откройте главную страницу сайта
   - **Изменения должны отобразиться!**

---

## ⚠️ Важные замечания

1. **SCF полностью совместим с API ACF** - используйте стандартные функции
2. **Не используйте `SCF::get()` напрямую** - используйте `get_field()`
3. **Для Options Page** используйте `get_field('field_name', 'option')`
4. **Для изображений** проверяйте, что вернулось: массив или ID
5. **Для Repeater** `get_field()` возвращает массив массивов

---

## 📚 Дополнительная информация

См. файл `COMPARISON_TOCHKA_GG.md` для подробного сравнения реализаций.

---

**Дата исправления:** 2025-01-09  
**Версия темы:** 1.0.2

