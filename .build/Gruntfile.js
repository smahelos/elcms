// AdminLTE Gruntfile
'use strict';

module.exports = function (grunt) { // jshint ignore:line

    grunt.initConfig({
        pkg: grunt.file.readJSON('../www/Themes/admin/AdminLTE-2.4.2/package.json'),
        "file-creator": {
            createMainLess: {
                "c:/www_root/www/elcms/www/Themes/admin/AdminLTE-2.4.2/build/less/main.less": function (fs, fd, done) {
                    var files = grunt.file.expand([
                        "../www/Themes/admin/AdminLTE-2.4.2/build/less/AdminLTE.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/bower_components/bootstrap/less/**/*.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/bower_components/font-awesome/less/*.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/bower_components/morris.js/less/*.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/build/build_standalone.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/build/less/custom.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/build/less/aceEditor.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/build/less/comments.less",
                        "../www/Themes/admin/AdminLTE-2.4.2/build/less/nette_ajax_spinner.less"
                        //"../www/Themes/admin/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/build/build.less",
                        //"../www/Themes/admin/AdminLTE-2.4.2/bower_components/bootstrap-datepicker/less/datepicker.less",
                    ]);
                    files.forEach(function (file, i) {
                        fs.writeSync(fd, '@import "' + file + '";\n');
                    });
                    done();
                }
            }
        },
        watch: {
            less: {
                // Compiles less files upon saving
                files: ['../www/Themes/admin/AdminLTE-2.4.2/build/less/*.less'],
                tasks: ['less:development', 'less:production', 'replace', 'notify:less']
            },
            js: {
                // Compile js files upon saving
                files: ['../www/Themes/admin/AdminLTE-2.4.2/build/js/*.js'],
                tasks: ['js', 'notify:js']
            },
            skins: {
                // Compile any skin less files upon saving
                files: ['../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/*.less'],
                tasks: ['less:skins', 'less:minifiedSkins', 'notify:less']
            }
        },
        // Notify end of tasks
        notify: {
            less: {
                options: {
                    title: 'AdminLTE',
                    message: 'LESS finished running'
                }
            },
            js: {
                options: {
                    title: 'AdminLTE',
                    message: 'JS bundler finished running'
                }
            }
        },
        // 'less'-task configuration
        // This task will compile all less files upon saving to create both AdminLTE.css and AdminLTE.min.css
        less: {
            // Development not compressed
            development: {
                options: {
                    sourceMap: true,
                    sourceMapFilename: 'c:/www_root/www/elcms/www/Themes/admin/AdminLTE-2.4.2/dist/css/main.css.map',
                    sourceMapBasepath: 'c:/www_root/www/elcms/www/Themes/admin/AdminLTE-2.4.2/dist/css/',
                    sourceMapRootpath: "c:/www_root/www/elcms/www/",
                    syncImport: true
                    //sourceMapAsFile: true
                },
                files: {
                    // compilation.css  :  source.less
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/main.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/main.less',
                    // AdminLTE without plugins
                    ////'../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.css' : '../www/Themes/admin/AdminLTE-2.4.2/build/less/AdminLTE-without-plugins.less',
                    // Separate plugins
                    ////'../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-select2.css'         : '../www/Themes/admin/AdminLTE-2.4.2/build/less/select2.less',
                    ////'../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-fullcalendar.css'    : '../www/Themes/admin/AdminLTE-2.4.2/build/less/fullcalendar.less',
                    ////'../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-bootstrap-social.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/bootstrap-social.less',
                    // Custom CSS
                    ////'../www/Themes/admin/AdminLTE-2.4.2/dist/css/custom.css'                       : '../www/Themes/admin/AdminLTE-2.4.2/build/less/custom.less'
                }
            },
            // Production compressed version
            production: {
                options: {
                    compress: true,
                    sourceMap: true,
                    sourceMapAsFile: true
                },
                files: {
                    // compilation.css  :  source.less
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/AdminLTE.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/AdminLTE.less',
                    // AdminLTE without plugins
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/AdminLTE-without-plugins.less',
                    // Separate plugins
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-select2.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/select2.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-fullcalendar.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/fullcalendar.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-bootstrap-social.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/bootstrap-social.less',
                    // Custom CSS
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/custom.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/custom.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/comments.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/comments.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/aceEditor.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/aceEditor.less'
                }
            },
            // Non minified skin files
            skins: {
                files: {
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-blue.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-black.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-yellow.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-green.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-red.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-purple.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue-light.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-blue-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black-light.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-black-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow-light.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-yellow-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green-light.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-green-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red-light.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-red-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple-light.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-purple-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/_all-skins.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/_all-skins.less'
                }
            },
            // Skins minified
            minifiedSkins: {
                options: {
                    compress: true
                },
                files: {
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-blue.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-black.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-yellow.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-green.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-red.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-purple.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue-light.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-blue-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black-light.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-black-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow-light.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-yellow-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green-light.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-green-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red-light.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-red-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple-light.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/skin-purple-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/_all-skins.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/skins/_all-skins.less'
                }
            }
        },
        // Uglify task info. Compress the js files.
        uglify: {
            options: {
                mangle: true,
                preserveComments: 'some'
            },
            production: {
                files: {
                    '../www/assets/admin/js/adminlte.min.js': ['../www/Themes/admin/AdminLTE-2.4.2/dist/js/adminlte.js']
                }
            }
        },

        // Concatenate JS Files
        concat: {
            options: {
                separator: ';',
                banner: '/*! AdminLTE app.js\n'
                + '* ================\n'
                + '* Main JS application file for AdminLTE v2. This file\n'
                + '* should be included in all pages. It controls some layout\n'
                + '* options and implements exclusive AdminLTE plugins.\n'
                + '*\n'
                + '* @Author  Almsaeed Studio\n'
                + '* @Support <https://www.almsaeedstudio.com>\n'
                + '* @Email   <abdullah@almsaeedstudio.com>\n'
                + '* @version <%= pkg.version %>\n'
                + '* @repository <%= pkg.repository.url %>\n'
                + '* @license MIT <http://opensource.org/licenses/MIT>\n'
                + '*/\n\n'
            },
            dist: {
                src: [
                    '../www/Themes/admin/AdminLTE-2.4.2/bower_components/jquery/dist/jquery.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/bower_components/datatables.net/js/jquery.dataTables.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/bower_components/fastclick/lib/fastclick.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/BoxWidget.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/ControlSidebar.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/DirectChat.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/Layout.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/PushMenu.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/TodoList.js',
                    //'../www/Themes/admin/AdminLTE-2.4.2/build/js/Tree.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/html5shiv/3.7.3/js/html5shiv.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/respond/1.4.2/js/respond.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/plugins/iCheck/icheck.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/netteForms.min.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/nette.ajax.2.3.0.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/nette.ajax.spinner.js',
                    '../vendor/vojtech-dobes/nette-ajax-history/client-side/history.ajax.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/confirm.dialog.js',
                    // to download jquery-ui-sortable.js, wee need to build ublaboo/datagrid with bower install
                    '../vendor/ublaboo/datagrid/bower_components/jquery-ui-sortable/jquery-ui-sortable.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/jquery.ui.sortable-animation.js',
                    //'../vendor/ublaboo/datagrid/assets/dist/datagrid.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/customUblabooDatagridTree.js', //my custom datagrid.js for this project
                    '../vendor/ublaboo/datagrid/assets/dist/datagrid-instant-url-refresh.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/bower_components/bootstrap/dist/js/bootstrap.min.js',
                    '../vendor/smahelos/ace-builds/src-noconflict/ace.js',
                    '../www/assets/admin/libs/templateEditorFactory/templateEditorFactory.js',
                    '../www/Themes/admin/AdminLTE-2.4.2/build/js/custom.js'
                ],
                dest: '../www/Themes/admin/AdminLTE-2.4.2/dist/js/adminAll.js'
            }
        },

        // Concatenate css files
        cssmin: {
            options: {
                root: 'c:/www_root/www/elcms/www/',
                shorthandCompacting: false,
                roundingPrecision: -1,
                sourceMap: true
            },
            target: {
                files: {
                    '../www/assets/admin/css/admin.min.css': [
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/main.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/bower_components/Ionicons/css/ionicons.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/bower_components/jvectormap/jquery-jvectormap.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/bower_components/bootstrap-daterangepicker/daterangepicker.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/plugins/iCheck/flat/blue.css',
                        '../www/assets/admin/libs/templateEditorFactory/css/templateEditor.css',
                        '../vendor/ublaboo/datagrid/assets/dist/datagrid.css',
                    ]
                }
            }
        },
        copy: {
            main: {
                files: [
                    // includes files within path
                    //{expand: true, src: ['path/*'], dest: 'dest/', filter: 'isFile'},
                    // includes files within path and its sub-directories
                    // {expand: true, src: ['path/**'], dest: 'dest/'},
                    // makes all src relative to cwd
                    {
                        expand: true,
                        cwd: '../www/Themes/admin/AdminLTE-2.4.2/dist/img/',
                        src: ['**'],
                        dest: '../www/assets/admin/img/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../www/Themes/admin/AdminLTE-2.4.2/bower_components/bootstrap/fonts/',
                        src: ['**'],
                        dest: '../www/assets/admin/fonts/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../www/Themes/admin/AdminLTE-2.4.2/bower_components/font-awesome/fonts/',
                        src: ['**'],
                        dest: '../www/assets/admin/fonts/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../www/assets/admin/css/',
                        src: ['**/admin.min.css.map'],
                        dest: '../www/assets/admin/temp/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/ublaboo/datagrid/assets/dist/',
                        src: ['**/datagrid.css'],
                        dest: '../www/Themes/admin/AdminLTE-2.4.2/dist/css/',
                        filter: 'isFile'
                    }
                    // flattens results to a single level
                    //{expand: true, flatten: true, src: ['path/**'], dest: 'dest/', filter: 'isFile'}
                ]
            },
            imagesICheck: {
                files: [
                    {
                        expand: true,
                        cwd: '../www/Themes/admin/AdminLTE-2.4.2/plugins/iCheck/flat/',
                        src: [
                            '**/blue.{png,jpg,svg}',
                            '**/blue@2x.{png,jpg,svg}',
                            '**/white_checkbox_checked.{png,jpg,svg}'
                        ],
                        dest: '../www/assets/admin/img/',
                        filter: 'isFile'
                    }
                ]
            },
            jsAce: {
                files: [
                    {
                        expand: true,
                        cwd: '../vendor/smahelos/ace-builds/src-noconflict/',
                        src: [
                            '**/theme-monokai.js',
                            '**/mode-javascript.js',
                            '**/mode-smarty.js',
                            '**/worker-javascript.js'
                        ],
                        dest: '../www/assets/admin/libs/ace',
                        filter: 'isFile'
                    }
                ]
            }
        },

        // Replace image paths in AdminLTE without plugins
        replace: {
            withoutPlugins: {
                src: ['../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.css'],
                dest: '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.css',
                replacements: [
                    {
                        from: '../img',
                        to: '../../img'
                    }
                ]
            },
            withoutPluginsMin: {
                src: ['../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.min.css'],
                dest: '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.min.css',
                replacements: [
                    {
                        from: '../img',
                        to: '../../img'
                    }
                ]
            },
            assetsCssMapsPaths: {
                //overwrite: true,
                src: ['../www/assets/admin/css/admin.min.css.map'],
                dest: '../www/assets/admin/css/admin.min.css.map',
                replacements: [
                    {
                        from: '"..\\\\www',
                        to: '"..\\\\..\\\\..'
                    },
                    {
                        from: '"..\\\\vendor\\\\ublaboo\\\\datagrid\\\\assets\\\\dist',
                        to: '"..\\\\..\\\\..\\\\Themes\\\\admin\\\\AdminLTE-2.4.2\\\\dist\\\\css'
                    }
                ]
            },
            tempCssMapsPaths: {
                src: ['../www/assets/admin/temp/admin.min.css.map'],
                dest: '../www/assets/admin/temp/admin.min.css.map',
                replacements: [
                    {
                        from: '"..\\\\www',
                        to: '"..\\\\..\\\\..'
                    },
                    {
                        from: '"..\\\\vendor\\\\ublaboo\\\\datagrid\\\\assets\\\\dist',
                        to: '"..\\\\..\\\\..\\\\Themes\\\\admin\\\\AdminLTE-2.4.2\\\\dist\\\\css'
                    }
                ]
            },
        },

        // Build the documentation files
        includes: {
            build: {
                src: ['*.html'], // Source files
                dest: '../www/Themes/admin/AdminLTE-2.4.2/documentation/', // Destination directory
                flatten: true,
                cwd: '../www/Themes/admin/AdminLTE-2.4.2/documentation/build',
                options: {
                    silent: true,
                    includePath: '../www/Themes/admin/AdminLTE-2.4.2/documentation/build/include'
                }
            }
        },

        // Optimize images
        image: {
            dynamic: {
                files: [
                    {
                        expand: true,
                        cwd: '../www/Themes/admin/AdminLTE-2.4.2/build/img/',
                        src: ['**/*.{png,jpg,gif,svg,jpeg}'],
                        dest: '../www/Themes/admin/AdminLTE-2.4.2/dist/img/'
                    }
                ]
            }
        },

        // Validate JS code
        jshint: {
            options: {
                jshintrc: '../www/Themes/admin/AdminLTE-2.4.2/build/js/.jshintrc'
            },
            grunt: {
                options: {
                    jshintrc: '../www/Themes/admin/AdminLTE-2.4.2/build/grunt/.jshintrc'
                },
                src: '../www/Themes/admin/AdminLTE-2.4.2/Gruntfile.js'
            },
            core: {
                src: '../www/Themes/admin/AdminLTE-2.4.2/build/js/*.js'
            },
            demo: {
                src: '../www/Themes/admin/AdminLTE-2.4.2/dist/js/demo.js'
            },
            pages: {
                src: '../www/Themes/admin/AdminLTE-2.4.2/dist/js/pages/*.js'
            }
        },

        jscs: {
            options: {
                config: '../www/Themes/admin/AdminLTE-2.4.2/build/js/.jscsrc'
            },
            core: {
                src: '<%= jshint.core.src %>'
            },
            pages: {
                src: '<%= jshint.pages.src %>'
            }
        },

        // Validate CSS files
        csslint: {
            options: {
                csslintrc: '../www/Themes/admin/AdminLTE-2.4.2/build/less/.csslintrc'
            },
            dist: [
                'dist/css/AdminLTE.css'
            ]
        },

        // Validate Bootstrap HTML
        bootlint: {
            options: {
                relaxerror: ['W005']
            },
            files: ['../www/Themes/admin/AdminLTE-2.4.2/pages/**/*.html', '*.html']
        },

        // Delete images in build directory
        // After compressing the images in the build/img dir, there is no need
        // for them
        clean: {
            build: ['../www/Themes/admin/AdminLTE-2.4.2/build/img/*'],
            removeMainLess: {
                options: {
                    force: true
                },
                src: ['../www/Themes/admin/AdminLTE-2.4.2/build/less/main.less']
            }
        },
        mergeSourceMaps: {
            admin: {
                options: {
                    inlineSources: true
                },
                files: [{
                    // src and dest are the same; merge-source-maps appends source map info to target file
                    src: [
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/AdminLTE.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/custom.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-without-plugins.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-select2.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-fullcalendar.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-bootstrap-social.min.css'
                    ],
                    expand: true
                }]
            }
        },


        // Configuration to be run (and then tested).
        concat_sourcemap: {
            css_files: {
                options: {},
                files: {
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/admin.min.css.map': [
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/AdminLTE.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/custom.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-without-plugins.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-select2.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-fullcalendar.min.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/alt/AdminLTE-bootstrap-social.min.css'
                    ]
                }
            }
        }
    });

    // Load all grunt tasks

    // LESS Compiler
    grunt.loadNpmTasks('grunt-contrib-less');
    // Watch File Changes
    grunt.loadNpmTasks('grunt-contrib-watch');
    // Compress JS Files
    grunt.loadNpmTasks('grunt-contrib-uglify');
    // Include Files Within HTML
    grunt.loadNpmTasks('grunt-includes');
    // Optimize images
    grunt.loadNpmTasks('grunt-image');
    // Validate JS code
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-jscs');
    // Delete not needed files
    grunt.loadNpmTasks('grunt-contrib-clean');
    // Lint CSS
    grunt.loadNpmTasks('grunt-contrib-csslint');
    // Lint Bootstrap
    grunt.loadNpmTasks('grunt-bootlint');
    // Concatenate JS files
    grunt.loadNpmTasks('grunt-contrib-concat');
    // Notify
    grunt.loadNpmTasks('grunt-notify');
    // Replace text in files using strings, regexs or functions
    grunt.loadNpmTasks('grunt-text-replace');
    // CSS Min
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    // Copy files
    grunt.loadNpmTasks('grunt-contrib-copy');
    // Merge source maps files
    //grunt.loadNpmTasks('grunt-concat-sourcemap');
    // Merge source maps files
    grunt.loadNpmTasks('grunt-merge-source-maps');
    // A grunt task that creates/writes to files from Javascript functions
    grunt.loadNpmTasks('grunt-file-creator');

    grunt.option('stack', true);

    // Linting task
    grunt.registerTask('lint', ['jshint', 'csslint', 'bootlint']);
    // JS task
    grunt.registerTask('js', ['concat', 'uglify']);
    // CSS Task
    grunt.registerTask('css', ['file-creator:createMainLess', 'less:development', 'less:production']);

    // The default task (running 'grunt' in console) is 'watch'
    grunt.registerTask('default', ['watch']);

    // Assets Task
    grunt.registerTask('assets', ['css', 'cssmin', 'concat', 'uglify', 'copy', 'replace']);

    // Concat Css Source Maps Task
    grunt.registerTask('concat_sourcemap', ['concat_sourcemap:css_files']);

    // Concat Css Source Maps Task
    grunt.registerTask('mergeSourceMaps', ['mergeSourceMaps:admin']);

    grunt.registerTask('testless', ["file-creator:createMainLess", 'less:development', "clean:removeMainLess"]);
};
