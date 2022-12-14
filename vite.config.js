import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    server: {
        host: true,
        port: 8000,
        hmr: {
            host: "localhost",
        },
    },
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/app.js",
                "resources/js/app-online-room.js",
                "resources/js/app-online-color-picker.js",
                "resources/js/app-offline-color-picker.js",
                "resources/js/app-online-game.js",
                "resources/js/app-offline-game.js",
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            find: /^~(.*)$/,
            replacement: "$1",
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
