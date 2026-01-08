/**
 * Конфигурация Vite для проекта портфолио
 * 
 * Vite - современный инструмент сборки для фронтенд-разработки,
 * который обеспечивает быстрый dev-сервер с горячей перезагрузкой модулей (HMR)
 * 
 * Настройки оптимизированы для:
 * - Минификации HTML, CSS и JavaScript
 * - Оптимизации изображений
 * - Улучшения производительности загрузки
 */

import { defineConfig } from 'vite';
import { resolve } from 'path';
import { minify } from 'html-minifier-terser';

export default defineConfig(({ mode }) => {
  // Определяем, находимся ли мы в production режиме
  const isProduction = mode === 'production';
  
  return {
    // Плагины для оптимизации
    plugins: [
      // Плагин для минификации HTML
      {
        name: 'html-minifier',
        transformIndexHtml: {
          order: 'post', // Выполняем после других плагинов
          handler(html, ctx) {
            // Минифицируем HTML только в production режиме
            if (isProduction) {
              return minify(html, {
                removeComments: true, // Удаляем комментарии
                collapseWhitespace: true, // Удаляем лишние пробелы
                removeAttributeQuotes: false, // Оставляем кавычки для совместимости
                removeEmptyAttributes: true, // Удаляем пустые атрибуты
                minifyCSS: true, // Минифицируем встроенный CSS
                minifyJS: true, // Минифицируем встроенный JS
                removeRedundantAttributes: true, // Удаляем избыточные атрибуты
                useShortDoctype: true, // Используем короткий DOCTYPE
                removeScriptTypeAttributes: true, // Удаляем type="text/javascript"
                removeStyleLinkTypeAttributes: true, // Удаляем type="text/css"
                caseSensitive: false, // Не учитываем регистр
                keepClosingSlash: false, // Удаляем закрывающие слэши
                sortAttributes: false, // Не сортируем атрибуты
                sortClassName: false, // Не сортируем классы
              });
            }
            return html;
          },
        },
      },
    ],
  
  // Настройки сервера разработки
  server: {
    // Порт для dev-сервера
    port: 8080,
    // Автоматически открывать браузер при запуске
    open: true,
    // Разрешить доступ с других устройств в локальной сети
    host: true
  },
  
  // Настройки сборки для production
  build: {
    // Директория для выходных файлов
    outDir: 'dist',
    // Минификация включена по умолчанию, но явно указываем для ясности
    minify: 'terser', // Используем terser для лучшей минификации (альтернатива: 'esbuild')
    // Исходная карта для отладки (отключаем в production для уменьшения размера)
    sourcemap: false,
    // Оптимизация размера чанков
    chunkSizeWarningLimit: 1000,
    // Настройки terser для более агрессивной минификации
    terserOptions: {
      compress: {
        drop_console: true, // Удаляем console.log в production
        drop_debugger: true, // Удаляем debugger
        pure_funcs: ['console.log', 'console.info'], // Удаляем указанные функции
      },
      format: {
        comments: false, // Удаляем комментарии
      },
    },
    // Оптимизация rollup
    rollupOptions: {
      output: {
        // Оптимизация имен файлов для кеширования
        manualChunks: undefined,
        // Минификация имен файлов
        assetFileNames: 'assets/[name].[hash][extname]',
        chunkFileNames: 'assets/[name].[hash].js',
        entryFileNames: 'assets/[name].[hash].js',
      },
    },
    // Оптимизация CSS
    cssCodeSplit: true,
    cssMinify: true,
    // Включение сжатия
    reportCompressedSize: true,
    // Оптимизация размера
    target: 'es2015', // Поддержка современных браузеров для меньшего размера
  },
  
  // Базовый путь для приложения (для деплоя в поддиректорию)
  base: '/',
  
    // Оптимизация зависимостей
    optimizeDeps: {
      // Предварительная оптимизация зависимостей
      include: [],
    },
  };
});

