module.exports = function(grunt) {

    grunt.initConfig({
        sass: {
            options: {
                sourcemap: 'none',
                noCache: true,
                update: false
            },
            admin: {
                options: {
                    style: 'expanded',
                },
                files: [{
                    expand: true,
                    src: '*.scss',
                    cwd: 'admin/css',
                    dest: 'admin/css',
                    ext: '.css'
                }]
            },
            adminmin: {
                options: {
                    style: 'compressed',
                },
                files: [{
                    expand: true,
                    src: '*.scss',
                    cwd: 'admin/css',
                    dest: 'admin/css',
                    ext: '.min.css'
                }]
            }
        },
        uglify: {
            options: {
                mangle: false,
                compress: false
            },
            admin: {
                files: [{
                    expand: true,
                    src: [ '**/*.js', '!*.min.js' ],
                    cwd: 'admin/js',
                    dest: 'admin/js',
                    ext: '.min.js'
                }]
            }
        },
        watch: {
            adminsass: {
                files: [ 'admin/css/*.scss' ],
                tasks: [ 'sass:admin', 'sass:adminmin' ]
            },
            adminjs: {
                files: [ 'admin/js/*.js', 'admin/js/!*.min.js' ],
                tasks: [ 'uglify:admin' ]
            }
        }
    });

    // Load our dependencies
    grunt.loadNpmTasks( 'grunt-contrib-sass' );
    grunt.loadNpmTasks( 'grunt-contrib-uglify' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );
    grunt.loadNpmTasks( 'grunt-newer' );

    // Register our tasks
    grunt.registerTask( 'default', [ 'newer:sass', 'newer:uglify', 'watch' ] );

    // Register a watch function
    grunt.event.on( 'watch', function( action, filepath, target ) {
        grunt.log.writeln( target + ': ' + filepath + ' has ' + action );
    });

};