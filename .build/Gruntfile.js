// AdminLTE Gruntfile
'use strict';

module.exports = function (grunt) { // jshint ignore:line

    grunt.initConfig({
        pkg: grunt.file.readJSON('../vendor/almasaeed2010/adminlte/package.json'),
        "file-creator": {
            createMainLess: {
                "../www/Themes/admin/AdminLTE-2.4.2/build/less/main.less": function (fs, fd, done) {
                    var files = grunt.file.expand([
                        "../vendor/almasaeed2010/adminlte/build/less/AdminLTE.less",
                        "../vendor/almasaeed2010/adminlte/bower_components/bootstrap/less/bootstrap.less",
                        "../vendor/almasaeed2010/adminlte/bower_components/font-awesome/less/*.less",
                        "../vendor/almasaeed2010/adminlte/bower_components/morris.js/less/*.less",
                        "../vendor/almasaeed2010/adminlte/bower_components/bootstrap-datepicker/build/build_standalone.less",
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
        mkdir: {
            options: {
                mode: '0700',
                create: ['../log/']
            },
            your_target: {
                // Target-specific options go here.
            }
        },
        watch: {
            less: {
                // Compiles less files upon saving
                files: ['../vendor/almasaeed2010/adminlte/build/less/*.less'],
                tasks: ['less:development', 'less:production', 'replace', 'notify:less']
            },
            js: {
                // Compile js files upon saving
                files: ['../vendor/almasaeed2010/adminlte/build/js/*.js'],
                tasks: ['js', 'notify:js']
            },
            skins: {
                // Compile any skin less files upon saving
                files: ['../vendor/almasaeed2010/adminlte/build/less/skins/*.less'],
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
                    sourceMapFilename: '../www/Themes/admin/AdminLTE-2.4.2/dist/css/main.css.map',
                    sourceMapBasepath: '../www/Themes/admin/AdminLTE-2.4.2/dist/css/',
                    sourceMapRootpath: '',
                    syncImport: true
                    //sourceMapAsFile: true
                },
                files: {
                    // compilation.css  :  source.less
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/main.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/main.less'
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
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/AdminLTE.min.css': '../vendor/almasaeed2010/adminlte/build/less/AdminLTE.less',
                    // AdminLTE without plugins
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.min.css': '../vendor/almasaeed2010/adminlte/build/less/AdminLTE-without-plugins.less',
                    // Separate plugins
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-select2.min.css': '../vendor/almasaeed2010/adminlte/build/less/select2.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-fullcalendar.min.css': '../vendor/almasaeed2010/adminlte/build/less/fullcalendar.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-bootstrap-social.min.css': '../vendor/almasaeed2010/adminlte/build/less/bootstrap-social.less',
                    // Custom CSS
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/custom.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/custom.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/comments.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/comments.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/aceEditor.min.css': '../www/Themes/admin/AdminLTE-2.4.2/build/less/aceEditor.less'
                }
            },
            // Non minified skin files
            skins: {
                files: {
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue.css':         '../vendor/almasaeed2010/adminlte/build/less/skins/skin-blue.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black.css':        '../vendor/almasaeed2010/adminlte/build/less/skins/skin-black.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow.css':       '../vendor/almasaeed2010/adminlte/build/less/skins/skin-yellow.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green.css':        '../vendor/almasaeed2010/adminlte/build/less/skins/skin-green.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red.css':          '../vendor/almasaeed2010/adminlte/build/less/skins/skin-red.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple.css':       '../vendor/almasaeed2010/adminlte/build/less/skins/skin-purple.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue-light.css':   '../vendor/almasaeed2010/adminlte/build/less/skins/skin-blue-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black-light.css':  '../vendor/almasaeed2010/adminlte/build/less/skins/skin-black-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow-light.css': '../vendor/almasaeed2010/adminlte/build/less/skins/skin-yellow-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green-light.css':  '../vendor/almasaeed2010/adminlte/build/less/skins/skin-green-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red-light.css':    '../vendor/almasaeed2010/adminlte/build/less/skins/skin-red-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple-light.css': '../vendor/almasaeed2010/adminlte/build/less/skins/skin-purple-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/_all-skins.css':        '../vendor/almasaeed2010/adminlte/build/less/skins/_all-skins.less'
                }
            },
            // Skins minified
            minifiedSkins: {
                options: {
                    compress: true
                },
                files: {
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue.min.css':         '../vendor/almasaeed2010/adminlte/build/less/skins/skin-blue.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black.min.css':        '../vendor/almasaeed2010/adminlte/build/less/skins/skin-black.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow.min.css':       '../vendor/almasaeed2010/adminlte/build/less/skins/skin-yellow.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green.min.css':        '../vendor/almasaeed2010/adminlte/build/less/skins/skin-green.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red.min.css':          '../vendor/almasaeed2010/adminlte/build/less/skins/skin-red.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple.min.css':       '../vendor/almasaeed2010/adminlte/build/less/skins/skin-purple.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-blue-light.min.css':   '../vendor/almasaeed2010/adminlte/build/less/skins/skin-blue-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-black-light.min.css':  '../vendor/almasaeed2010/adminlte/build/less/skins/skin-black-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-yellow-light.min.css': '../vendor/almasaeed2010/adminlte/build/less/skins/skin-yellow-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-green-light.min.css':  '../vendor/almasaeed2010/adminlte/build/less/skins/skin-green-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-red-light.min.css':    '../vendor/almasaeed2010/adminlte/build/less/skins/skin-red-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/skin-purple-light.min.css': '../vendor/almasaeed2010/adminlte/build/less/skins/skin-purple-light.less',
                    '../www/Themes/admin/AdminLTE-2.4.2/dist/css/skins/_all-skins.min.css':        '../vendor/almasaeed2010/adminlte/build/less/skins/_all-skins.less'
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
                    '../www/assets/admin/js/adminlte.min.js': ['../vendor/almasaeed2010/adminlte/dist/js/adminlte.js']
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
                    '../vendor/almasaeed2010/adminlte/bower_components/jquery/dist/jquery.min.js',
                    '../vendor/almasaeed2010/adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
                    '../vendor/almasaeed2010/adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js',
                    // '../vendor/almasaeed2010/adminlte/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
                    // '../vendor/almasaeed2010/adminlte/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.cs.min.js',
                    '../vendor/moment/moment/min/moment-with-locales.min.js',
                    '../vendor/eonasdan/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                    '../vendor/almasaeed2010/adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js',
                    '../vendor/almasaeed2010/adminlte/bower_components/fastclick/lib/fastclick.js',
                    '../vendor/almasaeed2010/adminlte/bower_components/ckeditor/ckeditor.js',
                    //'../vendor/almasaeed2010/adminlte/bower_components/ckeditor/config.js',
                    '../vendor/almasaeed2010/adminlte/build/js/BoxWidget.js',
                    '../vendor/almasaeed2010/adminlte/build/js/ControlSidebar.js',
                    '../vendor/almasaeed2010/adminlte/build/js/DirectChat.js',
                    '../vendor/almasaeed2010/adminlte/build/js/Layout.js',
                    '../vendor/almasaeed2010/adminlte/build/js/PushMenu.js',
                    '../vendor/almasaeed2010/adminlte/build/js/TodoList.js',
                    //'../www/Themes/admin/AdminLTE-2.4.2/build/js/Tree.js',
                    '../vendor/almasaeed2010/adminlte/html5shiv/3.7.3/js/html5shiv.min.js',
                    '../vendor/almasaeed2010/adminlte/respond/1.4.2/js/respond.min.js',
                    '../vendor/almasaeed2010/adminlte/plugins/iCheck/icheck.min.js',
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
                    '../vendor/almasaeed2010/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js',
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
                root: 'c:/www_root/www/elcms4/',
                shorthandCompacting: false,
                roundingPrecision: -1,
                sourceMap: true
            },
            target: {
                files: {
                    '../www/assets/admin/css/admin.min.css': [
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/main.css',
                        '../vendor/almasaeed2010/adminlte/dist/css/skins/skin-blue.css',
                        '../vendor/almasaeed2010/adminlte/bower_components/Ionicons/css/ionicons.css',
                        '../vendor/almasaeed2010/adminlte/bower_components/jvectormap/jquery-jvectormap.css',
                        '../vendor/almasaeed2010/adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css',
                        '../vendor/eonasdan/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
                        '../vendor/almasaeed2010/adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.css',
                        '../vendor/almasaeed2010/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css',
                        '../vendor/almasaeed2010/adminlte/plugins/iCheck/flat/blue.css',
                        '../www/assets/admin/libs/templateEditorFactory/css/templateEditor.css',
                        '../vendor/ublaboo/datagrid/assets/dist/datagrid.css'
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
                        cwd: '../vendor/almasaeed2010/adminlte/dist/img/',
                        src: ['**'],
                        dest: '../www/assets/admin/img/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/bootstrap/fonts/',
                        src: ['**'],
                        dest: '../www/assets/admin/fonts/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/font-awesome/fonts/',
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
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/ckeditor/',
                        src: ['config.js'],
                        dest: '../www/assets/admin/libs/ckeditor/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/ckeditor/',
                        src: ['styles.js'],
                        dest: '../www/assets/admin/libs/ckeditor/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/ckeditor/',
                        src: ['contents.css'],
                        dest: '../www/assets/admin/libs/ckeditor/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/ckeditor/skins/',
                        src: ['**'],
                        dest: '../www/assets/admin/libs/ckeditor/skins/',
                        filter: 'isFile'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/ckeditor/lang/',
                        src: ['**'],
                        dest: '../www/assets/admin/libs/ckeditor/lang/'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/bower_components/ckeditor/plugins/',
                        src: ['**'],
                        dest: '../www/assets/admin/libs/ckeditor/plugins/'
                    },
                    {
                        expand: true,
                        cwd: '../vendor/sunhater/kcfinder/',
                        src: ['**'],
                        dest: '../www/assets/admin/libs/kcfinder/'
                    }

                    // flattens results to a single level
                    //{expand: true, flatten: true, src: ['path/**'], dest: 'dest/', filter: 'isFile'}
                ]
            },
            imagesICheck: {
                files: [
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/plugins/iCheck/flat/',
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
                src: ['../vendor/almasaeed2010/adminlte/dist/css/alt/AdminLTE-without-plugins.css'],
                dest: '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.css',
                replacements: [
                    {
                        from: '../img',
                        to: '../../img'
                    }
                ]
            },
            withoutPluginsMin: {
                src: ['../vendor/almasaeed2010/adminlte/dist/css/alt/AdminLTE-without-plugins.min.css'],
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
            updateKcFinderConfig: {
                src: ['../www/assets/admin/libs/kcfinder/conf/config.php'],
                dest: '../www/assets/admin/libs/kcfinder/conf/config.php',
                replacements: [
                    {
                        from: '\'disabled\' => true,',
                        to: '\'disabled\' => false,'
                    },
                    {
                        from: '\'uploadURL\' => "upload",',
                        to: '\'uploadURL\' => "/assets/uploads/",'
                    },
                    {
                        from: '\'uploadDir\' => "",',
                        to: '\'uploadDir\' => __DIR__ . "/../../../../uploads/",'
                    }
                ]
            },
            // Remove imports of mixins and variables, because we are mixing bootstrap.less with AdminLTE.less
            removeMixinsAndVariablesImports: {
                src: ['../vendor/almasaeed2010/adminlte/build/less/AdminLTE.less'],
                dest: '../vendor/almasaeed2010/adminlte/build/less/AdminLTE.less',
                replacements: [
                    {
                        from: '@import (reference) "../bootstrap-less/mixins";',
                        to: '//@import (reference) "../bootstrap-less/mixins";'
                    },
                    {
                        from: '@import (reference) "../bootstrap-less/variables";',
                        to: '//@import (reference) "../bootstrap-less/variables";'
                    }
                ]
            }
        },

        // Build the documentation files
        includes: {
            build: {
                src: ['*.html'], // Source files
                dest: '../www/Themes/admin/AdminLTE-2.4.2/documentation/', // Destination directory
                flatten: true,
                cwd: '../vendor/almasaeed2010/adminlte/documentation/build',
                options: {
                    silent: true,
                    includePath: '../vendor/almasaeed2010/adminlte/documentation/build/include'
                }
            }
        },

        // Optimize images
        image: {
            dynamic: {
                files: [
                    {
                        expand: true,
                        cwd: '../vendor/almasaeed2010/adminlte/build/img/',
                        src: ['**/*.{png,jpg,gif,svg,jpeg}'],
                        dest: '../www/Themes/admin/AdminLTE-2.4.2/dist/img/'
                    }
                ]
            }
        },

        // Validate JS code
        jshint: {
            options: {
                jshintrc: '../vendor/almasaeed2010/adminlte/build/js/.jshintrc'
            },
            grunt: {
                options: {
                    jshintrc: '../vendor/almasaeed2010/adminlte/build/grunt/.jshintrc'
                },
                src: '../vendor/almasaeed2010/adminlte/Gruntfile.js'
            },
            core: {
                src: '../vendor/almasaeed2010/adminlte/build/js/*.js'
            },
            demo: {
                src: '../vendor/almasaeed2010/adminlte/dist/js/demo.js'
            },
            pages: {
                src: '../vendor/almasaeed2010/adminlte/dist/js/pages/*.js'
            }
        },

        jscs: {
            options: {
                config: '../vendor/almasaeed2010/adminlte/build/js/.jscsrc'
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
                csslintrc: '../vendor/almasaeed2010/adminlte/build/less/.csslintrc'
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
            files: ['../vendor/almasaeed2010/adminlte/pages/**/*.html', '*.html']
        },

        // Delete images in build directory
        // After compressing the images in the build/img dir, there is no need
        // for them
        clean: {
            build: ['../vendor/almasaeed2010/adminlte/build/img/*'],
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
                        '../vendor/almasaeed2010/adminlte/dist/css/AdminLTE.css',
                        '../vendor/almasaeed2010/adminlte/dist/css/custom.css',
                        '../vendor/almasaeed2010/adminlte/dist/alt/AdminLTE-without-plugins.min.css',
                        '../vendor/almasaeed2010/adminlte/dist/alt/AdminLTE-select2.min.css',
                        '../vendor/almasaeed2010/adminlte/dist/alt/AdminLTE-fullcalendar.min.css',
                        '../vendor/almasaeed2010/adminlte/dist/alt/AdminLTE-bootstrap-social.min.css'
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
                        '../vendor/almasaeed2010/adminlte/dist/css/AdminLTE.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/custom.css',
                        '../www/Themes/admin/AdminLTE-2.4.2/dist/css/alt/AdminLTE-without-plugins.min.css',
                        '../vendor/almasaeed2010/adminlte/dist/alt/AdminLTE-select2.min.css',
                        '../vendor/almasaeed2010/adminlte/dist/alt/AdminLTE-fullcalendar.min.css',
                        '../vendor/almasaeed2010/adminlte/dist/alt/AdminLTE-bootstrap-social.min.css'
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
    // Create directories with Grunt
    grunt.loadNpmTasks('grunt-mkdir');

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
    //grunt.registerTask('assets', ['replace:', 'css', 'cssmin', 'concat', 'uglify', 'copy', 'replace']);
    grunt.registerTask('assets', ['mkdir', 'replace:removeMixinsAndVariablesImports', 'css', 'cssmin', 'concat', 'uglify', 'copy', 'replace:withoutPlugins', 'replace:withoutPluginsMin', 'replace:assetsCssMapsPaths', 'replace:tempCssMapsPaths', 'replace:updateKcFinderConfig']);

    // Concat Css Source Maps Task
    grunt.registerTask('concat_sourcemap', ['concat_sourcemap:css_files']);

    // Concat Css Source Maps Task
    grunt.registerTask('mergeSourceMaps', ['mergeSourceMaps:admin']);

    grunt.registerTask('testless', ["file-creator:createMainLess", 'less:development', "clean:removeMainLess"]);
};
