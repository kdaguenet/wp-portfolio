module.exports = function(grunt) {

    // load all grunt tasks
    //require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
    grunt.loadNpmTasks('grunt-concurrent');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-connect');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-html');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-scss-lint');


    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        devicesTarget : 'desktop',
        paths : {
            sources: '.', // location for development source files
            bower_components: 'bower_components',
            dist: '..', // location for distributed files,
            tmp: '.tmp'
        },
        concat : {
            options: {
                stripBanners: true
            },
            header_js: {
                src: [
                    "<%= paths.bower_components %>/jquery-ui/jquery-ui.js"
                ],
                dest: '<%= paths.dist %>/js/header.js'
            },

            vendor_css: {
                src: [
                    "<%= paths.dist %>/css/vendor.css"
                ],
                dest: '<%= paths.dist %>/css/vendor.css'
            },

            vendor_js: {
                src: [
                    "<%= paths.bower_components %>/foundation/js/foundation/foundation.js"
                ],
                dest: '<%= paths.dist %>/js/vendor.js'
            },
            //@todo use wildcards but respect order of js
            front_js: {
                src: [
                    "<%= paths.sources %>/js/main.js"
                ],
                dest: '<%= paths.dist %>/js/front.js'
            }
        },
        uglify: {
            dist: {
                options: {},
                files: {
                    '<%= paths.dist %>/js/header.js': ['<%= paths.dist %>/js/header.js'],
                    '<%= paths.dist %>/js/vendor.js': ['<%= paths.dist %>/js/vendor.js'],
                    '<%= paths.dist %>/js/front.js': ['<%= paths.dist %>/js/front.js']
                }
            }
        },
        copy: {
            dist: {
                files: [
                    //{
                    //    expand: true,
                    //    cwd: '<%= paths.sources %>/img/',
                    //    src: ['**/*.png', '**/*.jpg', '**/*.gif', '**/**.jpeg'],
                    //    dest: '<%= paths.dist %>/img/'
                    //},
                    //{
                    //    expand: true,
                    //    cwd: '<%= paths.sources %>/fonts/',
                    //    src: '*',
                    //    dest: '<%= paths.dist %>/fonts/'
                    //},
                    {
                        expand: true,
                        cwd: "<%= paths.bower_components %>/jquery/dist/",
                        src: 'jquery.js',
                        dest: '<%= paths.dist %>/js/'
                    }
                ]
            }
        },
        //sprite:{
        //    all: {
        //        src: '<%= paths.sources %>/spritesrc/*.png',
        //        retinaSrcFilter: ['<%= paths.sources %>/spritesrc/*@2x.png'],
        //        dest: '<%= paths.sources %>/img/sprites/spritesheet-'+version.parameters.assets_version+'.png',
        //        destCss: '<%= paths.sources %>/scss/_new_sprites.scss',
        //        retinaDest: '<%= paths.sources %>/img/sprites/spritesheet.retina@2x-'+version.parameters.assets_version+'.png'
        //    }
        //},
        sass: {
            build: {
                options: {
                    outputStyle: 'expanded',
                    includePaths: ['<%= paths.bower_components %>/foundation/scss'],
                    precision: 8
                },
                files: {
                    '<%= paths.dist %>/css/front.css': 'scss/main.scss',
                    '<%= paths.dist %>/css/vendor.css': 'scss/vendor.scss'
                }
            }
        },
        postcss: {
            options: {
                processors: [
                    require('autoprefixer')({
                        browsers: [
                            'last 1 version',
                            'ie 9'
                        ]
                    }),
                    require('cssnano')({
                        safe: true
                    })
                ]
            },
            dist: {
                src: ['<%= paths.dist %>/css/front.css', '<%= paths.dist %>/css/vendor.css']
            }
        },
        concurrent: {
            build: ['concat', 'sass'],
            postbuild: ['uglify:dist', 'postcss:dist'],
            lint: ['jshint', 'scsslint']
        },
        //clean: {
        //    sprites: 'img/sprites'
        //},
        watch: {
            js: {
                files: 'js/**/*.js',
                tasks: ['concat']
            },
            css: {
                files: 'scss/**/*.scss',
                tasks: ['sass', 'postcss:dist']
            },
            jshint: {
                files: 'js/**/*.js',
                tasks: ['jshint'],
                options: {
                    spawn: false
                }
            },
            scsslint: {
                files: 'scss/**/*.scss',
                tasks: ['scsslint'],
                options: {
                    spawn: false
                }
            }
        },
        scsslint: {
            all: [
                './scss/**/*.scss'
            ],
            options: {
                config: '.scss-lint.yml',
                exclude: ['./scss/vendor/**/*.scss'],
                force: true,
                compact: true,
                maxBuffer: 500 * 1024 //TODO restore to default when error count permits it
            }
        },
        jshint: {
            options: {
                jshintrc: true,
                force: true
            },
            all: ['Gruntfile.js', './js/**/*.js', '!./js/ie/jplayer/*.js', '!./js/lib/*.js', '!./js/components/lazyload.js']
        }
    });

    // on watch events configure jshint:all and scsslint:all to only run on changed file
    grunt.event.on('watch', function(action, filepath, target) {
        switch(target) {
            case 'jshint':
                grunt.config('jshint.all', filepath);
                break;
            case 'scsslint':
                grunt.config('scsslint.all', filepath);
                break;
        }
    });

    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

    // Build
    grunt.registerTask('default', ['concurrent:build', 'concurrent:postbuild','copy:dist']);

    // Development
    grunt.registerTask('dev', ['concurrent:build', 'postcss:dist', 'watch']);

    // Lint
    grunt.registerTask('lint', ['concurrent:lint']);

    // Regenerate spritesheet
    //grunt.registerTask('sprites', ['clean:sprites', 'sprite', 'imagemin:sprites', 'copy:dist']);

    // Build for composer
    grunt.registerTask('build', [/*'clean:sprites', 'sprite', 'imagemin:sprites', 'imagemin:img',*/ 'concurrent:build', 'concurrent:postbuild', 'copy:dist']);


    // Dev with modify-watch
    grunt.registerTask('modifywatchdev', [ 'modify-watch', 'dev']);

};
