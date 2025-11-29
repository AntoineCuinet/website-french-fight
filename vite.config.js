import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";

export default defineConfig({
    plugins: [
        symfonyPlugin({
            stimulus: false // Disabling built-in stimulus bridge to avoid the null error, we handle controllers manually in app.js
        }),
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/app.js",
            },
        },
    },
});