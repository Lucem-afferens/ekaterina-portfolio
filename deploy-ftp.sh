#!/bin/bash

# Скрипт для автоматического деплоя темы на сервер через FTP/SFTP
# Использование: ./deploy-ftp.sh

set -e

# Цвета для вывода
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}════════════════════════════════════════${NC}"
echo -e "${BLUE}🚀 Автоматический деплой темы Portfolio${NC}"
echo -e "${BLUE}════════════════════════════════════════${NC}"
echo ""

# Проверка переменных окружения или запрос данных
if [ -f .env.deploy ]; then
    echo -e "${YELLOW}Загружаю конфигурацию из .env.deploy...${NC}"
    source .env.deploy
else
    echo -e "${YELLOW}Конфигурационный файл .env.deploy не найден.${NC}"
    echo -e "${YELLOW}Создайте файл .env.deploy:${NC}"
    echo -e "  1. Скопируйте пример: ${BLUE}cp env.deploy.example .env.deploy${NC}"
    echo -e "  2. Заполните ваши FTP данные в файле .env.deploy"
    echo ""
    read -p "Продолжить с ручным вводом данных? (y/n) " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Создайте файл .env.deploy и запустите скрипт снова"
        exit 0
    fi
fi

# Получение FTP данных
FTP_HOST=${FTP_HOST:-""}
FTP_PORT=${FTP_PORT:-"21"}
FTP_USER=${FTP_USER:-""}
FTP_PASS=${FTP_PASS:-""}
FTP_PATH=${FTP_PATH:-"/public_html/wp-content/themes/"}
FTP_PROTOCOL=${FTP_PROTOCOL:-"ftp"}  # ftp или sftp

# Запрос данных, если не указаны в конфиге
if [ -z "$FTP_HOST" ]; then
    read -p "FTP хост (например, ftp.ekaterina-shul.ru): " FTP_HOST
fi

if [ -z "$FTP_USER" ]; then
    read -p "FTP логин: " FTP_USER
fi

if [ -z "$FTP_PASS" ]; then
    read -sp "FTP пароль: " FTP_PASS
    echo
fi

if [ -z "$FTP_PORT" ] || [ "$FTP_PORT" = "21" ] && [ "$FTP_PROTOCOL" = "ftp" ]; then
    read -p "FTP порт [${FTP_PORT}]: " input_port
    FTP_PORT=${input_port:-$FTP_PORT}
fi

if [ -z "$FTP_PATH" ] || [ "$FTP_PATH" = "/public_html/wp-content/themes/" ]; then
    read -p "Путь на сервере [${FTP_PATH}]: " input_path
    FTP_PATH=${input_path:-$FTP_PATH}
fi

if [ -z "$FTP_PROTOCOL" ] || [ "$FTP_PROTOCOL" = "ftp" ]; then
    read -p "Протокол (ftp/sftp) [${FTP_PROTOCOL}]: " input_protocol
    FTP_PROTOCOL=${input_protocol:-$FTP_PROTOCOL}
fi

# Валидация обязательных полей
if [ -z "$FTP_HOST" ] || [ -z "$FTP_USER" ] || [ -z "$FTP_PASS" ]; then
    echo -e "${RED}❌ Ошибка: не указаны обязательные параметры (FTP_HOST, FTP_USER, FTP_PASS)${NC}"
    echo -e "${YELLOW}Создайте файл .env.deploy и заполните все поля${NC}"
    exit 1
fi

# Проверка наличия необходимых инструментов
echo -e "${YELLOW}Проверка инструментов для деплоя...${NC}"

if [ "$FTP_PROTOCOL" = "sftp" ]; then
    if ! command -v sftp &> /dev/null; then
        echo -e "${RED}❌ Ошибка: команда sftp не найдена${NC}"
        echo -e "${YELLOW}Установите openssh-client или используйте FTP${NC}"
        exit 1
    fi
    # Проверяем sshpass для автоматической авторизации (опционально)
    if ! command -v sshpass &> /dev/null; then
        echo -e "${YELLOW}⚠️  sshpass не установлен. Для автоматического деплоя через SFTP рекомендуется:${NC}"
        echo -e "${BLUE}   brew install hudochenkov/sshpass/sshpass${NC}"
        echo -e "${YELLOW}   Или используйте FTP протокол${NC}"
        read -p "Продолжить с SFTP (потребуется ввод пароля)? (y/n) " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            exit 0
        fi
    fi
    echo -e "${GREEN}✅ SFTP инструменты готовы${NC}"
else
    if command -v lftp &> /dev/null; then
        echo -e "${GREEN}✅ lftp найден (рекомендуется)${NC}"
    elif command -v ftp &> /dev/null; then
        echo -e "${YELLOW}⚠️  Используется базовый FTP клиент (ограниченная функциональность)${NC}"
        echo -e "${YELLOW}   Для полного функционала установите: ${BLUE}brew install lftp${NC}"
    else
        echo -e "${RED}❌ Ошибка: FTP клиент не найден${NC}"
        echo -e "${YELLOW}Установите lftp: ${BLUE}brew install lftp${NC}"
        exit 1
    fi
fi

# Проверка структуры темы
echo -e "${YELLOW}Проверка структуры темы...${NC}"

REQUIRED_FILES=(
    "portfolio-theme/style.css"
    "portfolio-theme/functions.php"
    "portfolio-theme/header.php"
    "portfolio-theme/footer.php"
    "portfolio-theme/index.php"
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
if [ ! -d "portfolio-theme/assets/css" ] || [ -z "$(ls -A portfolio-theme/assets/css/*.css 2>/dev/null)" ]; then
    echo -e "${RED}❌ Ошибка: CSS файлы не найдены${NC}"
    exit 1
fi

if [ ! -d "portfolio-theme/assets/js" ] || [ -z "$(ls -A portfolio-theme/assets/js/*.js 2>/dev/null)" ]; then
    echo -e "${RED}❌ Ошибка: JS файлы не найдены${NC}"
    exit 1
fi

echo -e "${GREEN}✅ Собранные файлы на месте${NC}"

# Подтверждение деплоя
echo ""
echo -e "${YELLOW}Параметры подключения:${NC}"
echo "  Хост: $FTP_HOST"
echo "  Порт: $FTP_PORT"
echo "  Пользователь: $FTP_USER"
echo "  Путь: $FTP_PATH"
echo "  Протокол: $FTP_PROTOCOL"
echo ""
read -p "Продолжить деплой? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Деплой отменен"
    exit 0
fi

# Создание временной директории для деплоя
TEMP_DIR=$(mktemp -d)
echo -e "${YELLOW}Подготовка файлов для загрузки...${NC}"

# Копирование темы во временную директорию (исключая ненужные файлы)
rsync -av --exclude='node_modules' \
          --exclude='src' \
          --exclude='.git' \
          --exclude='.DS_Store' \
          --exclude='*.map' \
          portfolio-theme/ "$TEMP_DIR/portfolio-theme/"

echo -e "${GREEN}✅ Файлы подготовлены${NC}"

# Деплой через FTP/SFTP
echo -e "${YELLOW}Загрузка файлов на сервер...${NC}"

# Выполнение деплоя в зависимости от протокола
if [ "$FTP_PROTOCOL" = "sftp" ]; then
    # SFTP деплой
    echo -e "${YELLOW}Подключение через SFTP...${NC}"
    
    # Создаем скрипт для SFTP
    SFTP_SCRIPT="$TEMP_DIR/sftp_upload.sh"
    cat > "$SFTP_SCRIPT" << EOF
#!/bin/bash
# SFTP скрипт для загрузки темы

# Создаем директорию на сервере, если не существует
sshpass -p "$FTP_PASS" ssh -o StrictHostKeyChecking=no -p $FTP_PORT $FTP_USER@$FTP_HOST "mkdir -p $FTP_PATH/portfolio-theme" 2>/dev/null || true

# Загружаем файлы через SFTP
sshpass -p "$FTP_PASS" sftp -P $FTP_PORT -o StrictHostKeyChecking=no -b - $FTP_USER@$FTP_HOST << SFTP_EOF
cd $FTP_PATH
put -r $TEMP_DIR/portfolio-theme portfolio-theme
SFTP_EOF

EOF
    chmod +x "$SFTP_SCRIPT"
    
    # Выполняем загрузку
    if command -v sshpass &> /dev/null; then
        bash "$SFTP_SCRIPT"
    else
        # Без sshpass - интерактивный режим
        echo -e "${YELLOW}Подключение через SFTP (требуется ввод пароля)...${NC}"
        sftp -P "$FTP_PORT" "$FTP_USER@$FTP_HOST" << EOF
cd $FTP_PATH
put -r $TEMP_DIR/portfolio-theme portfolio-theme
quit
EOF
    fi
    
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ Файлы успешно загружены через SFTP${NC}"
    else
        echo -e "${RED}❌ Ошибка при загрузке через SFTP${NC}"
        echo -e "${YELLOW}Проверьте правильность FTP данных${NC}"
        rm -rf "$TEMP_DIR"
        exit 1
    fi
    
elif command -v lftp &> /dev/null; then
    # LFTP деплой (рекомендуемый для FTP)
    echo -e "${YELLOW}Подключение через FTP (lftp)...${NC}"
    
    lftp -c "
    set ftp:ssl-allow no
    set ftp:passive-mode yes
    set ftp:list-options -a
    open -p $FTP_PORT -u $FTP_USER,$FTP_PASS $FTP_HOST
    cd $FTP_PATH || mkdir -p $FTP_PATH; cd $FTP_PATH
    mirror -R $TEMP_DIR/portfolio-theme portfolio-theme --delete --verbose --exclude-glob=*.map --exclude-glob=node_modules --exclude-glob=src --exclude-glob=.git
    quit
    " || {
        echo -e "${RED}❌ Ошибка при загрузке через FTP${NC}"
        echo -e "${YELLOW}Проверьте правильность FTP данных${NC}"
        rm -rf "$TEMP_DIR"
        exit 1
    }
    
    echo -e "${GREEN}✅ Файлы успешно загружены через FTP${NC}"
    
else
    echo -e "${RED}❌ Для FTP требуется установить lftp${NC}"
    echo -e "${YELLOW}Установите: ${BLUE}brew install lftp${NC}"
    echo -e "${YELLOW}Или используйте SFTP (протокол: sftp)${NC}"
    rm -rf "$TEMP_DIR"
    exit 1
fi

# Очистка
rm -rf "$TEMP_DIR"

echo ""
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo -e "${GREEN}✅ Деплой завершен успешно!${NC}"
echo -e "${GREEN}════════════════════════════════════════${NC}"
echo ""
echo "Следующие шаги:"
echo "1. Войдите в админ-панель WordPress"
echo "2. Перейдите в Внешний вид → Темы"
echo "3. Найдите и активируйте тему 'Portfolio Theme'"
echo "4. Установите плагин Secure Custom Fields (SCF)"
echo "5. Заполните SCF поля согласно MIGRATION_GUIDE.md"
echo ""

