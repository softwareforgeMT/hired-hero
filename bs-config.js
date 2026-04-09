module.exports = {
    files: [
        'app/**/*',
        'public/**/*',
        'resources/views/**/*',
    ],
    proxy: 'localhost:8000', // replace with your local server URL
    port: 3000, // choose a port that is not used by other services
    reloadDelay: 500 // set a delay to avoid premature reloads
};
