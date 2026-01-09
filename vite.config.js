/**
 * Конфигурация Vite для проекта портфолио
 * 
 * Vite - современный инструмент сборки для фронтенд-разработки,
 * который обеспечивает быстрый dev-сервер с горячей перезагрузкой модулей (HMR)
 * 
 * Настройки оптимизированы для сборки WordPress темы
 */

import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig(({ mode }) => {
  const isProduction = mode === 'production';
  
  return {
    // Настройки сервера разработки
    server: {
      port: 8080,
      open: true,
      host: true
    },
    
    // Настройки сборки для production
    build: {
      // Директория для выходных файлов (для темы WordPress)
      outDir: 'portfolio-theme/assets',
      // Минификация включена по умолчанию
      minify: isProduction ? 'terser' : false,
      // Исходная карта для отладки
      sourcemap: !isProduction,
      // Оптимизация размера чанков
      chunkSizeWarningLimit: 1000,
      // Настройки terser для более агрессивной минификации
      terserOptions: isProduction ? {
        compress: {
          drop_console: true,
          drop_debugger: true,
        },
        format: {
          comments: false,
        },
      } : {},
      // Оптимизация rollup
      rollupOptions: {
        input: {
          main: resolve(__dirname, 'portfolio-theme/src/js/main.js'),
        },
        output: {
          assetFileNames: (assetInfo) => {
            const info = assetInfo.name.split('.');
            const ext = info[info.length - 1];
            if (ext === 'css') {
              return 'css/main.[hash][extname]';
            }
            return '[name].[hash][extname]';
          },
          chunkFileNames: 'js/[name].[hash].js',
          entryFileNames: 'js/main.[hash].js',
        },
      },
      // Оптимизация CSS
      cssCodeSplit: false, // Один файл CSS для темы
      cssMinify: isProduction,
      // Включение сжатия
      reportCompressedSize: true,
      // Оптимизация размера
      target: 'es2015',
    },
    
    // Базовый путь для приложения
    base: '/',
    
    // Оптимизация зависимостей
    optimizeDeps: {
      include: [],
    },
  };
});
