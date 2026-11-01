import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",

                // CSS halaman
                "resources/css/pages/login.css",
                "resources/css/pages/dashboard.css",
                "resources/css/pages/tasks.css",
                "resources/css/pages/task-form.css",
                "resources/css/pages/task-detail.css",
                "resources/css/pages/user-form.css",
                "resources/css/pages/users.css",
                "resources/css/pages/projects.css",
                "resources/css/pages/project-form.css",
                "resources/css/pages/project-detail.css",
            ],
            refresh: true,
        }),
    ],
});
