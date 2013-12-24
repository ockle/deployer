module.exports = function(grunt){
    grunt.initConfig({
        bower: {
            install: {
                options: {
                    targetDir: './public/assets/',
                    layout: 'byComponent',
                    install: true,
                    verbose: false,
                    cleanTargetDir: false,
                    cleanBowerDir: false,
                    bowerOptions: {}
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bower-task');
};