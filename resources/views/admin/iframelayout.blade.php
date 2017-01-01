<!DOCTYPE html>
<!--[if lte IE 6 ]>
<html class="ie ie6 lte-ie7 lte-ie8" lang="zh-CN">
<![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 lte-ie7 lte-ie8" lang="zh-CN">
<![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 lte-ie8" lang="zh-CN">
<![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9" lang="zh-CN">
<![endif]-->
<!--[if (gt IE 9)|!(IE)]>
<!-->
<html lang="zh-CN">
  <!--<![endif]-->
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>后台管理</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="keywords" content="overtrue,bootstrap, bootstrap theme" />
  <meta name="description" content="a bootstrap theme made by overtrue." />
  @yield('css')
  @yield('pre_js')
</head>
<body>
  @yield('content')
  <script src="{{ asset('/js/require.js') }}" data-main="{{ asset('js/admin/iframemain') }}"></script>
  @yield('js')
</body>
</html>