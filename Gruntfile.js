/* global module:true */
module.exports = function (grunt) {
  'use strict';

  require('load-grunt-tasks')(grunt);

  grunt.initConfig({

    dirs: {
      devDir: '.',
      js: 'js',
      css: 'css',
      img: 'img',
      sass: 'sass',
      vendor: 'js/vendor'
    },

    pkg: grunt.file.readJSON( 'package.json' ),

    concat: {
      dist: {
        src: [
          '<%= dirs.devDir %>/<%= dirs.js %>/plugins.js',
          '<%= dirs.devDir %>/<%= dirs.vendor %>/prettycheckable/dev/prettyCheckable.js',
          '<%= dirs.devDir %>/<%= dirs.vendor %>/iscroll/build/iscroll.js',
          '<%= dirs.devDir %>/<%= dirs.vendor %>/pikaday/pikaday.js',
          '<%= dirs.devDir %>/<%= dirs.vendor %>/swiper/idangerous.swiper.min.js',
          /* add here any plugins you have added via bower that are not to be included directly on the DOM */
          '<%= dirs.devDir %>/<%= dirs.js %>/main.js'
        ],
        dest: '<%= dirs.devDir %>/<%= dirs.js %>/scripts.js'
      }
    },

    uglify: {
      options: {
        mangle: true
      },
      target: {
        files: {
          '<%= dirs.js %>/scripts.min.js': ['<%= dirs.js %>/scripts.js']
        }
      }
    },

    compass: {
      dev: {
        options: {
          sassDir: '<%= dirs.devDir %>/<%= dirs.sass %>',
          cssDir: '<%= dirs.devDir %>/<%= dirs.css %>',
          imagesDir: '<%= dirs.devDir %>/<%= dirs.img %>',
          relativeAssets: true,
          outputStyle: 'expanded'
        }
      },
      dist: {
        options: {
          sassDir: '<%= dirs.sass %>',
          cssDir: '<%= dirs.css %>',
          imagesDir: '<%= dirs.img %>',
          relativeAssets: true,
          environment: 'production',
          outputStyle: 'compressed',
          force: true
        }
      }
    },

    csscomb: {
      dev: {
        files: {
          '<%= dirs.devDir %>/<%= dirs.css %>/main.css': ['<%= dirs.devDir %>/<%= dirs.css %>/main.css'],
          '<%= dirs.devDir %>/<%= dirs.css %>/main-ie.css': ['<%= dirs.devDir %>/<%= dirs.css %>/main-ie.css']
        }
      }
    },

    watch: {
      options: {
        livereload: true
      },
      sass: {
        files: '<%= dirs.devDir %>/<%= dirs.sass %>/**/*',
        tasks: [
          'compass:dev',
        ]
      },
      js: {
        files: [
          '<%= dirs.devDir %>/<%= dirs.js %>/plugins.js',
          '<%= dirs.devDir %>/<%= dirs.js %>/main.js',
        ],
        tasks: [
          'concat',
          'jshint',
        ]
      },
      php: {
        files: [
          '<%= dirs.devDir %>/*.php',
          '<%= dirs.devDir %>/*/*.php'
        ]
      }
    },

    jshint: {
      options: {
        'bitwise': true,
        'eqeqeq': true,
        'eqnull': true,
        'immed': true,
        'newcap': true,
        'esnext': true,
        'latedef': true,
        'noarg': true,
        'node': true,
        'undef': true,
        'browser': true,
        'trailing': true,
        'jquery': true,
        'curly': true,
        globals: {
          jQuery: true,
          console: true,
          alert: true
        }
      },
      beforeconcat: [
        '<%= dirs.devDir %>/<%= dirs.js %>/plugins.js',
        '<%= dirs.devDir %>/<%= dirs.js %>/main.js',
      ],
      afterconcat: ['<%= dirs.devDir %>/<%= dirs.js %>/scripts.js']
    },

    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: 'dist/<%= dirs.img %>',
          src: ['**/*.{png,jpg,gif}'],
          dest: 'dist/<%= dirs.img %>'
        }]
      }
    },

    // imageoptim: {
    //   options: {
    //     quitAfter: true
    //   },
    //   png: {
    //     options: {
    //       jpegMini: false,
    //       imageAlpha: true
    //     },
    //     src: ['dist/<%= dirs.img %>/**/*.png']
    //   },
    //   jpg: {
    //     options: {
    //       jpegMini: true,
    //       imageAlpha: false
    //     },
    //     src: ['dist/<%= dirs.img %>/**/*.jpg']
    //   }
    // },

  });

  grunt.registerTask('default', 'watch');

  grunt.registerTask('build', [
    'clean:dist',
    'copy',
    'compass:dist',
    'clean:sprite',
    'imagemin',
    // 'imageoptim',
    'concat',
    'uglify',
    'clean:release'
  ]);

};
