@extends('admin.layout')
@include('vendor.ueditor.assets')
@section('content')
    <div class="console-content">
        <div class="page-header">
            <h2 id="nav">素材管理 - 新建图文 <a href="{{ admin_url('material') }}" class="btn btn-success btn-sm">返回素材列表</a>
            </h2>
        </div>
        <div class="well row">
            <div class="col-md-4">
                <div class="articles-preview-container">
                    <div class="article-preview-item first" id="article-first">
                        <div class="article-preview-item-cover-placeholder">封面图片</div>
                        <div class="article-preview-item-title attr-title">标题</div>
                        <div class="article-preview-item-edit-links"><a href="javascript:;" class="edit"><i
                                        class="ion-edit"></i></a></div>
                    </div>

                    <div class="article-preview-item button-box">
                        <a href="javascript:;" class="add-new-item"><i class="ion-plus"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <form action="" method="POST" role="form" class="article-form">
                    <div class="form-group">
                        <label>标题</label>
                        <div class="alert alert-warning alert-dismissible article-title" role="alert"
                             style="display: none">
                            <button type="button" class="close" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Warning!</strong> 请添加文章标题
                        </div>
                        <input type="text" name="title" id="input" class="form-control" value="" required="required"
                               title="">
                    </div>
                    <div class="form-group">
                        <label>作者
                            <small>（选填）</small>
                        </label>
                        <input type="text" name="author" id="input" class="form-control" value="" required="required"
                               title="">
                    </div>

                    <div class="form-group">
                        <label>摘要
                            <small>（选填，该摘要只在发送图文消息为单条时显示）</small>
                        </label>
                        <textarea name="description" id="inputContent" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group form-group-div">
                        <div class="alert alert-warning alert-dismissible article-cover_url" role="alert"
                             style="display: none">
                            <button type="button" class="close" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Warning!</strong> 请选择一个封面
                        </div>
                        <label>封面
                            <small>（小图片建议尺寸：200像素 * 200像素）</small>
                        </label>
                        <div>
                            {{--<button type="button" class="btn btn-light">上传</button>--}}
                            <input type="input" name="cover_url" id="cover_url" value="">
                            <button type="button" class="btn btn-open-imglib btn-light">从图片库选择</button>
                            <label>
                                <input type="checkbox" name="show_cover_pic" value="" class="js-switch"
                                       data-size="small">
                                封面图片显示在正文中
                            </label>
                        </div>
                        <img src="" alt="">
                    </div>

                    <div class="form-group">
                        <div class="alert alert-warning alert-dismissible article-content" role="alert"
                             style="display: none">
                            <button type="button" class="close" data-dismiss="alert"><span
                                        aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <strong>Warning!</strong> 请编写文章内容
                        </div>
                        <label>文章内容
                            <small></small>
                        </label>
                        <script id="container" name="content" style="width:100%;height:350px;"
                                type="text/template"></script>
                    </div>

                    <div class="form-group">
                        <label>原文链接
                            <small></small>
                        </label>
                        <input type="text" name="source_url" id="input" class="form-control" value=""
                               required="required" title="">
                    </div>
                </form>
            </div>
            <div class="col-md-12 text-center">
                <hr>
                <input type="hidden" name="cover_media_id">
                <input type="hidden" name="cover_url">
                <button type="submit" class="btn btn-primary btn-save">保 存</button>
                <button type="submit" class="btn btn-default btn-preview">预 览</button>
                <button type="submit" class="btn btn-default btn-save-send btn-success">保存并发送</button>
            </div>
        </div>
    </div>
    <div id="current_"></div>

    <div id="wex-preview">
        <div class="wx_phone_preview">
            <div class="wx_phone">
                <div class="wx_phone_hd">
                    王继超
                </div>
                <div class="wx_phone_bd">
                    <div class="wx_phone_preview_appmsg appmsg_wap">
                        <div class="rich_media">
                            <div class="rich_media_area_primary">
                                <h2 class="rich_media_title" title="那一年，你还记得吗">那一年，你还记得吗</h2>
                                <div class="rich_media_meta_list">
                                    <!-- <span class="rich_media_meta meta_original_tag dn">原创</span>
                                    <a class="rich_media_meta meta_enterprise_tag" href="javascript:;"><img src="http://mmbiz.qpic.cn/mmbiz/sHCMyzurWv7hBXKI1vsotnuSWZxSu2QicqJy5ygBCWRSktDVbfnUrfg6A6fJIc8M0hORTWibPFKMHibKKMaBibQKTg/0"></a> -->
                                    <em class="rich_media_meta rich_media_meta_text">2015-04-16</em>
                                    <em class="rich_media_meta rich_media_meta_text"></em>
                                    <span class="rich_media_meta rich_media_meta_link" title="请发送到手机查看完整效果">王继超</span>
                                </div>

                                <div class="rich_media_thumb_wrp">
                                    <img src="http://mmbiz.qpic.cn/mmbiz/sHCMyzurWv7hBXKI1vsotnuSWZxSu2QicqJy5ygBCWRSktDVbfnUrfg6A6fJIc8M0hORTWibPFKMHibKKMaBibQKTg/0"
                                         class="rich_media_thumb" onerror="this.parentNode.removeChild(this)">
                                </div>

                                <div class="rich_media_content">
                                    <p>这个多雨的城市，留下了什么？你还记得吗？</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wx_view_container jsPhoneViewPlugin">
                <div>
                    <ul class="wx_view_list">
                        <li class="wx_view_item jsPhoneViewLink" data-id="card">图文消息</li>
                        <li class="wx_view_item jsPhoneViewLink selected" data-id="appmsg">消息正文</li>
                        <li class="wx_view_item jsPhoneViewLink" data-id="moments">分享到朋友圈</li>
                        <li class="wx_view_item jsPhoneViewLink" data-id="chat">发送给朋友</li>
                    </ul>

                    <ul class="wx_article_crtl">
                        <li class="crtl_btn crtl_pre_btn  jsPhoneViewCard" data-index="0">上一篇</li>
                        <li class="crtl_btn crtl_next_btn  jsPhoneViewCard" data-index="2">下一篇</li>
                    </ul>

                    <div class="btn_wx_phone_preview_wrp">
                        <a class="btn btn_default btn_wx_phone_preview jsPhoneViewPub">发送到手机预览</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/template" id="preview-item-template">
        <div class="article-preview-item deleteable">
            <div class="article-preview-item-thumb-title attr-title"><%= item.title || '标题' %></div>
        <div class="article-preview-item-thumb-img"><img src="<%= item.cover_url %>" alt="<%= item.title %>"></div>
        <div class="article-preview-item-edit-links"><a href="javascript:;" class="edit"><i class="ion-edit"></i></a><a href="javascript:;" class="delete"><i class="ion-trash-a"></i></a></div>
    </div>
</script>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('js/plugins/ueditor/themes/viease/css/ueditor.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/layer/skin/default/layer.css') }}">
@stop

@section('pre_js')
<script>window.UEDITOR_HOME_URL = "/js/plugins/ueditor/";</script>
<script type="text/javascript" src="{{ asset('js/plugins/ueditor/third-party/zeroclipboard/ZeroClipboard.js') }}"></script>
@stop
@section('js')
<script>
    require(['pages/material.new-article']);
</script>
@stop