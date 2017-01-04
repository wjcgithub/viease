/**
 * 新建图片页面
 *
 * @author overtrue <anzhengchao@gmail.com>
 */
define(['jquery', 'uploader', 'util', 'repos/article-store', 'admin/common','layer'], function ($, Uploader, Util, Article) {
    $(function(){
        var $ue = UE.getEditor('container');
        var $form = $('.article-form');
        var $previewItemTemplate = _.template($('#preview-item-template').html());
        var $firstItem = $('.article-preview-item.first');

        //检查是否显示添加按钮
        function performAddBtn () {
            var $addBtnBox = $('.add-new-item').closest('.article-preview-item');

            if ($('.articles-preview-container .article-preview-item').length -1 >= 8) {
                $addBtnBox.slideUp(100);
            } else {
                $addBtnBox.slideDown(100);
            }
        }

        // 根据属性渲染 form
        function renderForm ($attributes) {
            console.log($attributes);
            // 必须从表单字段开始遍历
            var $keys = Util.parseForm($form);

            for ($attribute in $keys) {
                $form.find('[name='+$attribute+']').val($attributes[$attribute]);
            }

            if ($attributes['content']) {
                $ue.addListener("ready", function () {
                    // editor准备好之后才可以使用
                    $ue.setContent($attributes['content']);
                });
            }else{
                $ue.setContent('');
            }

            previewItem($attributes);
        }

        // 渲染预览框
        function previewItem ($attributes) {
            var $item = $('.article-preview-item.active');

            $item.find('.attr-title').html($attributes['title'] || '标题');

            if($attributes['cover_url']){
                $('.form-group-div').find('img').attr('src', $attributes['cover_url']).slideDown(100);
            }else{
                $('.form-group-div').find('img').slideUp(100);
            }

            if($attributes['cover_url']){
                //修改实时预览
                if($('.article-preview-item.active').hasClass('first')){
                    $('.article-preview-item.active').find('.article-preview-item-cover-placeholder').css("backgroundImage","url('"+$attributes['cover_url']+"')");
                }else{
                    $('.article-preview-item.active').find('.article-preview-item-thumb-img img').attr("src",$attributes['cover_url']);
                }
            }
        }

        // 保存form
        function saveForm () {
            console.log('save-form');
            var $id = $('.article-preview-item.active').prop('id');
            var $attributes = Util.parseForm($($form));
            console.log($attributes);
            console.log('ue-content:'+$ue.getContent());
            $attributes.content = $ue.getContent();
            Article.put($id, $attributes);

            previewItem($attributes);
        }

        $form.on('keyup', saveForm);
        $ue.addListener('keyup', saveForm);

        // 添加项目
        $('.articles-preview-container').on('click', '.add-new-item', function(){
            var $parentItem = $(this).closest('.article-preview-item');
            var $item = $($previewItemTemplate({item:{'cover_url':'/image/slt.png'}})).prop('id', (new Date).getTime());

            $parentItem.before($item);

            performAddBtn();
        });

        // 编辑项目
        $('.articles-preview-container').on('click', 'a.edit', function(){
            var $item = $(this).closest('.article-preview-item');

            if ($item.hasClass('active')) { return; };

            var $article = Article.get($item.prop('id'));

            $item.addClass('active').siblings().removeClass('active');

            renderForm($article);
        });

        // 删除项目
        $('.articles-preview-container').on('click', 'a.delete', function(){
            var $item = $(this).closest('.article-preview-item');

            Article.delete($item.prop('id'));

            $item.slideUp(200, function(){
                $(this).remove();

                performAddBtn();
            });

            window.location.reload();
        });

        var $articles = Article.all();

        //初始化除了第一张的其他图片
        for($id in $articles){
            if ($id == 'article-first') {continue;}

            if(!$articles[$id].cover_url){
                $articles[$id].cover_url = '/image/slt.png';
            }
            $firstItem.after($($previewItemTemplate({item: $articles[$id]})).prop('id', $id));
        }

        // 初始化第一张图片
        $firstItem.find('a.edit').click();

        /**
         * 弹出选择图片提示框
         */
        function showChoseImageDialog(obj, content){
            var _this = obj;
            layer.open({
                title:'选择图片',
                type: 2,//1：字符串；2:content填URL
                area: ['650px', '450px'],
                content: content ,//这里content是一个普通的String
                zIndex: layer.zIndex,
                btn: ['确认', '取消'],
                yes: function(layero, index){
                    var body = layer.getChildFrame('body', 0);
                    var size = body.find('.chose_icon').size();//选中数量
                    if(size <= 0){
                        layer.alert("请选择图片");
                        return;
                    }

                    //设置图片
                    var imagePath = body.find(".chose_icon").attr("src");
                    //设置input值
                    $('.form-group-div').find('input[name=cover_url]').val(imagePath);
                    layer.closeAll()
                    saveForm();
                },
                cancel: function(layero, index){
                    layer.closeAll()
                }
            });
        }

        $('.btn-open-imglib').on('click', function(){
            showChoseImageDialog("","/admin/material/imglib");
        });

    });
});