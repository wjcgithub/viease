/**
 * 新建图片页面
 *
 * @author overtrue <anzhengchao@gmail.com>
 */
define(['jquery', 'uploader', 'util', 'repos/article-store', 'admin/common', 'layer'], function ($, Uploader, Util, Article) {
    $(function () {
        var $ue = UE.getEditor('container');
        var $form = $('.article-form');
        var $previewItemTemplate = _.template($('#preview-item-template').html());
        var $firstItem = $('.article-preview-item.first');
        $ue.ready(function () {
            // $ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
            // 初始化第一张图片
            $firstItem.find('a.edit').click();
        });
        //检查是否显示添加按钮
        function performAddBtn() {
            var $addBtnBox = $('.add-new-item').closest('.article-preview-item');

            if ($('.articles-preview-container .article-preview-item').length - 1 >= 8) {
                $addBtnBox.slideUp(100);
            } else {
                $addBtnBox.slideDown(100);
            }
        }

        // 根据属性渲染 form
        function renderForm($attributes) {
            console.log($attributes);
            // 必须从表单字段开始遍历
            var $keys = Util.parseForm($form);

            for ($attribute in $keys) {
                $form.find('[name=' + $attribute + ']').val($attributes[$attribute]);
            }

            if ($attributes['content']) {
                $ue.setContent($attributes['content']);
            } else {
                $ue.setContent('');
            }

            previewItem($attributes);
        }

        // 渲染预览框
        function previewItem($attributes) {
            var $item = $('.article-preview-item.active');

            $item.find('.attr-title').html($attributes['title'] || '标题');

            if ($attributes['cover_url']) {
                $('.form-group-div').find('img').attr('src', $attributes['cover_url']).slideDown(100);
            } else {
                $('.form-group-div').find('img').slideUp(100);
            }

            if ($attributes['cover_url']) {
                //修改实时预览
                if ($('.article-preview-item.active').hasClass('first')) {
                    $('.article-preview-item.active').find('.article-preview-item-cover-placeholder').css("backgroundImage", "url('" + $attributes['cover_url'] + "')");
                } else {
                    $('.article-preview-item.active').find('.article-preview-item-thumb-img img').attr("src", $attributes['cover_url']);
                }
            }
        }

        // 保存form
        function saveForm() {
            var $id = $('.article-preview-item.active').prop('id');
            var $attributes = Util.parseForm($($form));
            $attributes.content = $ue.getContent();
            Article.put($id, $attributes);

            previewItem($attributes);
        }

        function saveAll(arg1) {
            if(!arguments[0]) arg1 = "check";
            //先保存当前激活的文章
            saveForm();
            //循环判断每一个
            var $articles = Article.all();
            $('.alert-warning').hide();
            var validateNum = 0;
            for ($id in $articles) {
                if ($articles[$id].title == '') {
                    $("#" + $id).find('a.edit').click();
                    scroll(0, 0);
                    $('.article-title').slideDown(1000);
                    break;
                }
                if ($articles[$id].cover_url == '') {
                    $("#" + $id).find('a.edit').click();
                    scroll(0, 0);
                    $('.article-cover_url').slideDown(1000);
                    break;
                }
                if ($articles[$id].content == '') {
                    $("#" + $id).find('a.edit').click();
                    scroll(0, 0);
                    $('.article-content').slideDown(1000);
                    break;
                }
                validateNum++;
            }
            if (validateNum == Article.getLength()) {
                if(typeof arg1 == 'object'){
                    alert('保存成功')
                }
                return true;
            }

            return false;
        }


        /**
         * 弹出选择图片提示框
         */
        function showChoseImageDialog(obj, content) {
            var _this = obj;
            layer.open({
                title: '选择图片',
                type: 2,//1：字符串；2:content填URL
                area: ['650px', '450px'],
                content: content,//这里content是一个普通的String
                zIndex: layer.zIndex,
                btn: ['确认', '取消'],
                yes: function (layero, index) {
                    var body = layer.getChildFrame('body', 0);
                    var size = body.find('.chose_icon').size();//选中数量
                    if (size <= 0) {
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
                cancel: function (layero, index) {
                    layer.closeAll()
                }
            });
        }

        //预览图文消息
        function previewImgMsg(e) {
            switchPreviewItem(e);

            var $tmplate = _.template($('#wx-preview-mutilarticle-bd-template').html());
            var articles = Util.reverseObject(Article.all());
            var articleLength = Article.getLength();
            if(articleLength>1){
                //多图文消息preview
                var articlesArr = new Array();
                var i=0;
                for (item in articles) {
                    if(!isNaN(item)){
                        articlesArr[i] = articles[item]
                        i++;
                    }
                }
                var articleHtml = $tmplate({
                    firstTitle:articles['article-first'].title,
                    coverUrl:articles['article-first'].cover_url,
                    articlesArr:articlesArr});
            }else{
                //单图文消息preview
                var $tmplate = _.template($('#wx-preview-article-bd-template').html());
                var d = new Date();
                var articles = Article.get('article-first');
                articles.ctime = parseInt(d.getUTCMonth()+1)+'月'+d.getDate()+'日';
                var articleHtml = $tmplate({item:articles});
            }

            $('.wx_name').text($('.current_wx_name .dropdown .dropdown-toggle').text());
            $('.wx_phone_bd_replace').html(articleHtml);
        }

        //显示正文消息
        function previewMsgBd(e) {
            switchPreviewItem(e)
            var $item = Article.get('article-first')
            showArticle($item)
            switchArticle();
        }

        //显示某个正文
        function showArticle($item) {
            var $tmplate = _.template($('#wx-phone-bd-template').html());
            var d = new Date();
            $item.ctime = d.getUTCFullYear()+'-'+parseInt(d.getUTCMonth()+1)+'-'+d.getDate();
            $item.wxname=$('.current_wx_name .dropdown .dropdown-toggle').text();
            var article = $tmplate($item);
            $('.wx_phone_bd_replace').html(article);
        }

        //上一篇下一篇文章的切换
        function switchArticle() {
            //绑定上一篇,下一篇
            $('.wx_phone_preview .wx_article_crtl .crtl_pre_btn').on('click', function (e){
                if(!$(this).hasClass('disabled')){
                    var key = Article.getPreById($('#currentArticle').val());
                    if(key!=''){
                        $('.wx_phone_preview .wx_article_crtl .crtl_next_btn').removeClass('disabled')
                        $('#currentArticle').val(key)
                        showArticle(Article.get(key))
                    }else{
                        $(this).addClass('disabled')
                    }
                }
            });

            $('.wx_phone_preview .wx_article_crtl .crtl_next_btn').on('click', function (e){
                if(!$(this).hasClass('disabled')) {
                    var key = Article.getNextById($('#currentArticle').val());
                    console.log($('#currentArticle').val())
                    if (key != '') {
                        $('.wx_phone_preview .wx_article_crtl .crtl_pre_btn').removeClass('disabled')
                        $('#currentArticle').val(key)
                        showArticle(Article.get(key))
                    } else {
                        $(this).addClass('disabled')
                    }
                }
            });
        }

        //切换预览按钮
        function switchPreviewItem(e) {
            if(e){
                $(e.target).addClass('selected').siblings().removeClass('selected');
                var articleLength = Article.getLength();
                if(articleLength>1){
                    $('.currentNum').val(articleLength)
                    if($(e.target).hasClass('msgbd')){
                        $('.wx_view_container .wx_article_crtl').slideDown(100);
                    }else{
                        $('.wx_view_container .wx_article_crtl').slideUp(100);
                    }
                }
            }
        }

        //微信预览
        function wexPreview() {
            if(saveAll('preview')){
                //示范一个公告层
                layer.open({
                    type: 1
                    ,
                    title: false //不显示标题栏
                    ,
                    closeBtn: false
                    ,
                    area: '300px;'
                    ,
                    shade: 0.8
                    ,
                    id: 'LAY_layuipro' //设定一个id，防止重复弹出
                    ,
                    moveType: 1 //拖拽模式，0或者1
                    ,
                    content: (function (){
                        return $('#wex-preview').html()
                    })()
                    ,
                    success: function (layero) {
                        // var btn = layero.find('.layui-layer-btn');
                        // btn.css('text-align', 'center');
                        // btn.find('.layui-layer-btn0').attr({
                        //     href: 'http://www.layui.com/'
                        //     , target: '_blank'
                        // });
                    }
                });
                previewImgMsg();
                //绑定图文消息
                $('.wx_view_list .imgmsg').on('click', previewImgMsg);
                $('.wx_view_list .msgbd').on('click', previewMsgBd);
            }
        }

        // 添加项目
        $('.articles-preview-container').on('click', '.add-new-item', function () {
            var $parentItem = $(this).closest('.article-preview-item');
            var $id = (new Date).getTime();
            var $item = $($previewItemTemplate({item: {'cover_url': '/image/slt.png'}})).prop('id', $id);

            $parentItem.before($item);

            Article.add($id)

            performAddBtn();
        });

        // 编辑项目
        $('.articles-preview-container').on('click', 'a.edit', function () {

            var $item = $(this).closest('.article-preview-item');

            if ($item.hasClass('active')) {
                return;
            }
            ;

            var $article = Article.get($item.prop('id'));
            console.log('form' + $item.prop('id') + "被编辑，内容为" + $article);
            console.log($article);
            $item.addClass('active').siblings().removeClass('active');

            renderForm($article);
        });

        // 删除项目
        $('.articles-preview-container').on('click', 'a.delete', function () {
            var $item = $(this).closest('.article-preview-item');

            Article.delete($item.prop('id'));

            $item.slideUp(200, function () {
                $(this).remove();

                performAddBtn();
            });

            window.location.reload();
        });

        var $articles = Article.all();

        //初始化除了第一张的其他图片
        for ($id in $articles) {
            console.log($id)
            if ($id == 'article-first') {
                continue;
            }

            if (!$articles[$id].cover_url) {
                $articles[$id].cover_url = '/image/slt.png';
            }
            $firstItem.after($($previewItemTemplate({item: $articles[$id]})).prop('id', $id));
        }

        $form.on('keyup', saveForm);
        $ue.addListener('keyup', saveForm);
        $('.btn-save').on('click', saveAll);
        $('.btn-preview').on('click', wexPreview);

        $('.btn-open-imglib').on('click', function () {
            showChoseImageDialog("", "/admin/material/imglib");
        });

    });
});