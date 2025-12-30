// tailwind.config.js
module.exports = {
    content: [
        "./templates/**/*.html.twig", // Scans all .html.twig files in the templates directory and subdirectories
        "./assets/js/**/*.js", // Also include other file types if necessary
        // ... other paths
    ],
    theme: {
        extend: {
            colors: {
                primary: "#2aa090",
                "primary-dark": "#13665b",
                "text-dark": "#222",
                "text-light": "#717171",
            },
            fontFamily: {
                sans: ["Poppins", "sans-serif"],
            },
        },
    },
    plugins: [],
};
