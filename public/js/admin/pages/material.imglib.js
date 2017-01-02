/**
 * 新建图片页面
 *
 * @author overtrue <anzhengchao@gmail.com>
 */
define(['jquery', 'jqpager', 'layer'], function ($) {
    $(function(){
        var obj =  $("#imglist");
        var content = obj.html();

        //ajax 分页
        $.fn.page.defaults = {
            pageSize: 10,
        };

        $("#page").page({
            remote: {
                url: '/admin/material/imglib',  //请求地址
                params: { ajax: "true"},       //自定义请求参数
                beforeSend: function(XMLHttpRequest){
                    //加载层
                    // var index = layer.load(1, {shade: false}); //0代表加载的风格，支持0-2
                },
                success: function (result, pageIndex) {
                    //回调函数
                    //result 为 请求返回的数据，呈现数据

                    console.log(result);
                    var data = result;
                    content='<div  class="row-icons ajax-page" style="padding: 30px;">';
                    for(var i=0;i<data['data'].length;i++) {
                        content +=
                            '<div class="imglist " style="margin-left:10px;margin-top:10px;float:left;position:relative">'+
                            '<img src ="'+data['data'][i]['source_url']+'" width="100px" height="100px" class="thisimg" onclick="selectImg(this)">'+
                            ' </div>';
                    }
                    content += '</div>';
                    $(".ajax-content").html(content);
                },
                complete: function(XMLHttpRequest, textStatu){
                    //...
                },
                pageIndexName: 'page',     //请求参数，当前页数，索引从0开始
                pageSizeName: 'pageSize',       //请求参数，每页数量
                totalName: 'total'              //指定返回数据的总数据量的字段名
            }
        });
    });
});

function selectImg(t){
    var tag = '<span style="position:absolute;top: 40px;width:50px;left:45px" class=" col-sm-4 tag">'+
        '<i class="fa fa-check-circle" style="color:#ffffff;font-size:24px"></i>'+
        '</span>';

    $(".tag").remove();
    $(t).after(tag);//点击加上标记
    $(".thisimg").removeClass("chose_icon");
    $(t).addClass("chose_icon");
}