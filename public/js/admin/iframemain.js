/**
 * requirejs 入口文件
 *
 * @author overtrue <anzhengchao@gmail.com>
 */
requirejs.config({
    //默认情况下模块所在目录为 /js
    baseUrl: '/js',

    //这里设置的路径是相对与 baseUrl 的，不要包含.js
    paths: {
        // dirs
        admin: 'admin',
        plugins: 'plugins/',
        repos: 'admin/repos/',

        // base modules
        bootstrap:'bootstrap.min',
        jquery: '//cdn.bootcss.com/jquery/2.1.4/jquery.min',
        jqpager:'plugins/JqueryPagination/jquery.pagination-1.2.7',
        layer:'plugins/layer/layer',
        // tools
        util: 'admin/util',
        pager: 'pager',
    },
    shim: {
        jquery: {
            exports: 'jQuery'
        },
　　　　 underscore:{
            exports: '_'
　　　　 },
        jqpager:{
            exports: 'jqpager',
            deps: ['jquery']
        },
        layer:{
            exports: 'layer',
            deps: ['plugins/validator.js/i18n/zh_CN']
        },
        bootstrap: ['jquery'],
        sweetalertUtil: ['sweetalert'],
        selectpickerLang: ['selectpicker'],
        pager: ['jquery'],
        uploader: ['jquery'],
    }
});

define('jquery-private', ['jquery'], function (jq) {
    return jq.noConflict(true);
});

// 基础初始化
require(['admin/pages/material.imglib']);