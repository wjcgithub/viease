/**
 * 素材管理页 js
 *
 * @author overtrue <anzhengchao@gmail.com>
 */
define(['jquery', 'repos/material', 'uploader', 'pager', 'admin/common'], function ($, Material, Uploader, Pager) {
    $(function(){
        var $emptyContentTemplate = _.template($('#no-content-template').html());

        var $templates = {
            image: _.template($('#image-item-template').html()),
            video: _.template($('#video-item-template').html()),
            voice: _.template($('#voice-item-template').html()),
            article: _.template($('#article-item-template').html()),
        };

        var $containers = {
            image: $('.images-container'),
            video: $('.videos-container'),
            voice: $('.voices-container'),
            article: $('.articles-container')
        };

        var $pagers = {};

        var $imageUploader = Uploader.make('.upload-image', 'image', function(material, file){
            var $container = $containers['image'];
            var $template = $templates['image'];
            console.log(material)
            $container.append($template(material));
        });

        // 当无内容时显示“无内容”提示
        $('.panel-body.empty-listener').ifEmpty(function($el){
            $el.html($emptyContentTemplate()).addClass('no-content');;
        });

        /**
         * 通用加载资源
         *
         * @param {String} $type
         * @param {Int} $page
         *
         * @return {Void}
         */
        function load($type, $page) {
            console.log($type);
            var $request = {
                type: $type,
                page: $page,
            };

            Material.lists($request, function($items){
                var $template = $templates[$type];
                var $container = $containers[$type];

                $container.html('');

                _.each($items, function($item) {
                    $container.append($template($item));
                });
                $pagers[$type].display({
                    total: window.last_response.last_page,
                    current: window.last_response.current_page,
                });
                if ($type == 'image') {
                    $('.media-card img').relocate('.media-card');
                };
            });
        }

        /**
         * 生成分页器
         *
         * @param {String} $type
         *
         * @return {Pager}
         */
        function getPager ($type) {
            return new Pager('#' + $type + ' .pagination-bar', {
                                classes: 'border-top',
                                onChange: function($page){
                                    load($type, $page);
                                }
                            })
        }

        // 加载总数
        Material.summary(function($summary){
            _.mapObject($summary, function($count, $type) {
                $('#' + $type + ' .count').html($count);
            });
        });

        $pagers['image'] = getPager('image');
        load('image', 1);

        var $loaded = {
            image: true
        };

        $('.nav-tabs a').on('show.bs.tab', function(){
            var $type = $(this).attr('href').substring(1);

            if(typeof $loaded[$type] == 'undefined'){
                $pagers[$type] = getPager($type);
                load($type, 1);
                $loaded[$type] = true;
            }
        });

    })
});