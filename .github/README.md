# GitHub Actions Workflows

Этот каталог содержит workflows для автоматизации деплоя темы Portfolio.

## Доступные workflows

### `deploy.yml`
Автоматический деплой темы на сервер при push в ветку `main`.

**Триггеры:**
- Push в ветку `main` с изменениями в `portfolio-theme/`, `package.json`, `vite.config.js`
- Ручной запуск через GitHub UI (Actions → Deploy Portfolio Theme → Run workflow)

**Что делает:**
1. Проверяет код из репозитория
2. Устанавливает Node.js и зависимости
3. Собирает тему через `npm run build:theme`
4. Проверяет наличие собранных файлов
5. Загружает тему на сервер через FTP/SFTP

**Требования:**
- Все необходимые GitHub Secrets должны быть настроены (см. `GITHUB_SECRETS.md`)

## Настройка

Перед первым запуском необходимо настроить GitHub Secrets. Подробная инструкция в файле `GITHUB_SECRETS.md`.

