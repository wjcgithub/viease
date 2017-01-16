/**
 * 文章本地存储数据仓库
 *
 * @author overtrue <anzhengchao@gmail.com>
 */
define(['jquery', 'util', 'store'], function($, Util, Store){
    var $article = {
        put: function ($id, $attributes) {
            var $articles = Store.get('articles') || {};
            $articles[$id] = $attributes;
            Store.set('articles', $articles);
        },

        get: function ($id) {
            var $articles = Store.get('articles') || {};

            return $articles[$id] || {};
        },

        delete: function ($id) {
            var $articles = Store.get('articles') || {};

            delete $articles[$id];

            Store.set('articles', $articles);
        },

        all: function() {
            var $articles = Store.get('articles') || {};

            return Util.reverseObject($articles);
        },

        clean: function () {
            Store.set('articles', {});
        },

        add: function ($id, item) {
            if(!arguments[1]) item = null;

            var $newItem = {
                title:'',
                author:'',
                content:'',
                cover_url:'',
                cover_media_id:'',
                description:'',
                source_url:''
            }

            $newItem = item || $newItem;

            this.put($id, $newItem);
        },

        fillArticle: function (item) {
            var $attributes = new Object();
            $attributes.title = item.title;
            $attributes.author = item.author;
            $attributes.content = item.content;
            $attributes.cover_media_id = item.cover_media_id;
            $attributes.cover_url = item.cover_url;
            $attributes.description = item.description;
            $attributes.show_cover_pic = item.show_cover_pic;
            $attributes.source_url = item.source_url;
            return $attributes;
        },

        getLength: function () {
            var o = this.all()
            var t = typeof o;
            if(t == 'string'){
                return o.length;
            }else if(t == 'object'){
                var n = 0;
                for(var i in o){
                    n++;
                }
                return n;
            }
            return false;
        },

        getNextById: function (id) {
            if(id!=''){
                var tmp='';
                var next = '';
                var articles = this.all();
                var o = Util.reverseObject(articles);
                for(var i in o){
                    next=i;
                    if(tmp=='yes'){
                        return next;
                    }
                    if(i==id){
                        tmp='yes';
                    }
                }
            }
            return '';
        },

        getPreById: function (id) {
            if(id!=''){
                var tmp='';
                var pre = '';
                var o = Util.reverseObject(this.all());
                for(var i in o){
                    if(i==id){
                        break;
                    }
                    pre=i;
                }

                return pre;
            }

            return '';
        }

    };

    return $article;
});