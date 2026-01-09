# Сравнение реализации темы: Tochka-Gg vs Ekaterina

## 🔍 Ключевые отличия в работе с SCF полями

### ❌ ПРОБЛЕМА: В Ekaterina используется неправильный API

**Tochka-Gg (правильно):**
```php
// Использует get_field() - стандартный API ACF/SCF
$hero_title = function_exists('get_field') ? get_field('hero_title') : null;
$hero_image = function_exists('get_field') ? get_field('hero_background_image') : false;
```

**Ekaterina (неправильно):**
```php
// Использует SCF::get() - это может не работать правильно
$hero_name = ekaterina_get_scf_field('hero_name', '...');
// Внутри функции:
$value = SCF::get($field_name, $post_id);
```

### ✅ РЕШЕНИЕ: SCF совместим с API ACF

**Важно:** SCF (Secure Custom Fields) является форком ACF и **полностью совместим с его API**. Это означает:

1. **Используйте `get_field()` вместо `SCF::get()`**
2. **Используйте `function_exists('get_field')` вместо `class_exists('SCF')`**
3. **Для Options Page используйте `get_field('field_name', 'option')`**

---

## 📊 Детальное сравнение

### 1. Получение обычных полей

**Tochka-Gg:**
```php
$hero_title = (function_exists('get_field') ? get_field('hero_title') : null) ?: 'Default Title';
```

**Ekaterina (текущая реализация):**
```php
$hero_name = ekaterina_get_scf_field('hero_name', 'Default Name', 'html', $current_page_id);
// Внутри: SCF::get($field_name, $post_id)
```

**Проблема:** `SCF::get()` может работать не так, как ожидается. Нужно использовать `get_field()`.

---

### 2. Получение полей из Options Page

**Tochka-Gg:**
```php
$logo = function_exists('get_field') ? get_field('logo', 'option') : false;
$phone = function_exists('get_field') ? get_field('phone_main', 'option') : null;
```

**Ekaterina (текущая реализация):**
```php
$host_name = SCF::get_option_meta('theme_options', 'site_host_name');
// Или через функцию:
$host_name = ekaterina_get_scf_option('site_host_name', 'theme_options', '');
```

**Проблема:** `SCF::get_option_meta()` может не работать. Нужно использовать `get_field('field_name', 'option')`.

---

### 3. Получение Repeater полей

**Tochka-Gg:**
```php
if (function_exists('have_rows') && have_rows('services_list')) {
    while (have_rows('services_list')) {
        the_row();
        $service_title = get_sub_field('service_title');
        $service_description = get_sub_field('service_description');
    }
}
```

**Ekaterina (текущая реализация):**
```php
$stats_items = ekaterina_get_scf_repeater('stats_items', $current_page_id);
// Внутри: SCF::get($field_name, $post_id)
foreach ($stats_items as $stat) {
    $number = ekaterina_get_repeater_field($stat, 'stat_number', '0');
}
```

**Проблема:** `SCF::get()` для Repeater может возвращать неправильный формат. Нужно использовать `have_rows()` и `get_sub_field()`.

---

### 4. Получение изображений

**Tochka-Gg:**
```php
$hero_image = function_exists('get_field') ? get_field('hero_background_image') : false;
// get_field() для Image field возвращает массив:
// ['ID' => 123, 'url' => '...', 'alt' => '...', 'width' => 1920, 'height' => 1080]
if ($hero_image && is_array($hero_image) && !empty($hero_image['url'])) {
    $image_url = $hero_image['url'];
}
```

**Ekaterina (текущая реализация):**
```php
$hero_background_image = class_exists('SCF') && $current_page_id ? SCF::get('hero_background_image', $current_page_id) : '';
// Предполагается, что возвращается ID изображения
$hero_image_url = wp_get_attachment_image_url($hero_background_image, 'hero-image');
```

**Проблема:** `SCF::get()` для Image field может возвращать массив, а не ID. Нужно проверять формат данных.

---

### 5. Проверка существования плагина

**Tochka-Gg:**
```php
if (function_exists('get_field')) {
    // SCF установлен и работает
}
```

**Ekaterina (текущая реализация):**
```php
if (class_exists('SCF')) {
    // SCF установлен
}
```

**Проблема:** Проверка класса не гарантирует, что функции доступны. Лучше проверять `function_exists('get_field')`.

---

## 🔧 Что нужно исправить в Ekaterina

### 1. Переписать функции получения полей

**Текущая реализация (неправильно):**
```php
function ekaterina_get_scf_field($field_name, $default = '', $context = 'html', $post_id = null) {
    if (!class_exists('SCF')) {
        return $default;
    }
    $value = SCF::get($field_name, $post_id);
    // ...
}
```

**Правильная реализация (как в Tochka-Gg):**
```php
function ekaterina_get_scf_field($field_name, $default = '', $context = 'html', $post_id = null) {
    if (!function_exists('get_field')) {
        return $default;
    }
    // Если post_id не указан, используем текущую страницу
    if ($post_id === null) {
        $post_id = get_queried_object_id() ?: get_the_ID();
    }
    $value = get_field($field_name, $post_id);
    // ...
}
```

### 2. Исправить получение Options Page полей

**Текущая реализация (неправильно):**
```php
$value = SCF::get_option_meta($group_name, $field_name);
```

**Правильная реализация:**
```php
$value = get_field($field_name, 'option');
```

### 3. Исправить получение Repeater полей

**Текущая реализация (неправильно):**
```php
$repeater = SCF::get($field_name, $post_id);
```

**Правильная реализация:**
```php
// Использовать have_rows() и get_sub_field()
if (have_rows($field_name, $post_id)) {
    while (have_rows($field_name, $post_id)) {
        the_row();
        // Получаем подполя через get_sub_field()
    }
}
```

### 4. Исправить получение изображений

**Текущая реализация (неправильно):**
```php
$image_id = SCF::get($field_name, $post_id);
$image_url = wp_get_attachment_image_url($image_id, 'full');
```

**Правильная реализация:**
```php
$image = get_field($field_name, $post_id);
if ($image && is_array($image) && !empty($image['url'])) {
    $image_url = $image['url'];
} elseif ($image && is_numeric($image)) {
    // Если вернулся ID, получаем URL
    $image_url = wp_get_attachment_image_url($image, 'full');
}
```

---

## 📝 Резюме отличий

| Аспект | Tochka-Gg (правильно) | Ekaterina (неправильно) |
|--------|----------------------|------------------------|
| **API** | `get_field()` | `SCF::get()` |
| **Проверка плагина** | `function_exists('get_field')` | `class_exists('SCF')` |
| **Options Page** | `get_field('field', 'option')` | `SCF::get_option_meta()` |
| **Repeater** | `have_rows()` + `get_sub_field()` | `SCF::get()` + ручной парсинг |
| **Изображения** | Массив `['url', 'ID', 'alt']` | Предполагается ID |
| **ID страницы** | Не передается (автоматически) | Передается явно |

---

## ✅ Рекомендации по исправлению

1. **Заменить все `SCF::get()` на `get_field()`**
2. **Заменить все `SCF::get_option_meta()` на `get_field(..., 'option')`**
3. **Использовать `have_rows()` для Repeater полей**
4. **Исправить обработку изображений (проверять массив или ID)**
5. **Использовать `function_exists('get_field')` вместо `class_exists('SCF')`**

---

**Вывод:** Основная проблема в том, что в Ekaterina используется прямой доступ к классу SCF через `SCF::get()`, вместо использования стандартного API `get_field()`, который предоставляет SCF для совместимости с ACF.

