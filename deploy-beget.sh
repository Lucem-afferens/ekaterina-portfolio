#!/bin/bash

# Скрипт для подготовки темы к деплою на Beget
# Использование: ./deploy-beget.sh

set -e

echo "🚀 Подготовка темы Portfolio к деплою на Beget..."

# Цвета для вывода
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Проверка наличия необходимых файлов
echo -e "${YELLOW}Проверка структуры темы...${NC}"

REQUIRED_FILES=(
    "portfolio-theme/style.css"
    "portfolio-theme/functions.php"
    "portfolio-theme/header.php"
    "portfolio-theme/footer.php"
    "portfolio-theme/index.php"
    "portfolio-theme/templates/template-homepage.php"
)

for file in "${REQUIRED_FILES[@]}"; do
    if [ ! -f "$file" ]; then
        echo -e "${RED}❌ Ошибка: файл $file не найден${NC}"
        exit 1
    fi
done

echo -e "${GREEN}✅ Все обязательные файлы на месте${NC}"

# Сборка темы
echo -e "${YELLOW}Сборка темы...${NC}"
npm run build:theme

if [ $? -ne 0 ]; then
    echo -e "${RED}❌ Ошибка при сборке темы${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Сборка завершена${NC}"

# Проверка собранных файлов
echo -e "${YELLOW}Проверка собранных файлов...${NC}"

if [ ! -d "portfolio-theme/assets/css" ] || [ -z "$(ls -A portfolio-theme/assets/css/*.css 2>/dev/null)" ]; then
    echo -e "${RED}❌ Ошибка: CSS файлы не найдены${NC}"
    exit 1
fi

if [ ! -d "portfolio-theme/assets/js" ] || [ -z "$(ls -A portfolio-theme/assets/js/*.js 2>/dev/null)" ]; then
    echo -e "${RED}❌ Ошибка: JS файлы не найдены${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Собранные файлы на месте${NC}"

# Создание архива (опционально)
read -p "Создать ZIP-архив темы для загрузки? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${YELLOW}Создание архива...${NC}"
    cd portfolio-theme
    zip -r ../portfolio-theme.zip . -x "*.git*" -x "node_modules/*" -x "src/*"
    cd ..
    echo -e "${GREEN}✅ Архив создан: portfolio-theme.zip${NC}"
fi

# Итоговая информация
echo ""
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo -e "${GREEN}✅ Тема готова к деплою!${NC}"
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo ""
echo "Следующие шаги:"
echo "1. Загрузите папку portfolio-theme/ на сервер Beget"
echo "2. Путь на сервере: /public_html/wp-content/themes/"
echo "3. Установите права доступа: файлы 644, директории 755"
echo "4. Активируйте тему в админ-панели WordPress"
echo "5. Следуйте инструкциям в DEPLOY_BEGET.md"
echo ""

