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

        add: function ($id) {
            var $newItem = {
                title:'',
                author:'',
                content:'',
                cover_url:'',
                description:'',
                source_url:''
            }

            this.put($id, $newItem);
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
        }
    };

    return $article;
});