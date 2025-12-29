// tailwind.config.js
module.exports = {
    content: [
        "./templates/**/*.html.twig", // Scans all .html.twig files in the templates directory and subdirectories
        "./assets/js/**/*.js", // Also include other file types if necessary
        // ... other paths
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
