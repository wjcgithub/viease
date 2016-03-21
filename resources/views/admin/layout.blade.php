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
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.css') }}" media="screen">
  <link rel="stylesheet" href="{{ asset('/css/ionicons.css') }}" media="screen">
  <link rel="stylesheet" href="{{ asset('/js/plugins/switchery/dist/switchery.min.css') }}" media="screen">
  <link rel="stylesheet" href="{{ asset('/js/plugins/sweetalert/lib/sweet-alert.css') }}" media="screen">
  <link rel="stylesheet" href="{{ asset('/js/plugins/bootstrap-select/dist/css/bootstrap-select.min.css') }}" media="screen">
  <link rel="stylesheet" href="{{ asset('/js/plugins/magnific-popup/dist/magnific-popup.css') }}" media="screen">
  <link rel="stylesheet" href="{{ asset('/css/app.css') }}" media="screen">
  <script src="{{ asset('/js/plugins/switchery/dist/switchery.min.js') }}"></script>
  @yield('css')
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="{{ asset('/plugin/html5shiv/dist/html5shiv.js') }}"></script>
  <script src="{{ asset('/plugin/respond/dest/respond.min.js') }}></script>
  <![endif]-->
  @yield('pre_js')
</head>
<body>
  <header class="console-header">
    <div class="container">
      <div class="header-inner table-box">
        <div class="table-row">
          <div class="left table-cell">
            <div class="logo">
              <a href="{{ admin_url('/') }}"><img src="{{asset('img/logo.svg')}}" alt=""></a>
            </div>
          </div>
          <div class="right table-cell">
            <div class="top-nav">
              <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                </button>
              </div>
              <ul class="nav navbar-nav navbar-main">
                @foreach($global->menus as $group)
                <li>
                  <a href="javascript:;" data-group="{{ $group['group'] }}">{{ $group['label'] }}</a>
                </li>
                @endforeach
              </ul>

              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                          <i class="ion-ios-person icon-md"></i> Admin
                          <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                          <li>
                              <a href="{{ admin_url('user/edit/' . $global->user->id) }}">账号设置</a>
                          </li>
                          <li class="divider"></li>
                          <li>
                              <a href="{{ admin_url('auth/logout') }}">注销</a>
                          </li>
                      </ul>
                  </li>
              </ul>
              @if(!$global->accounts->isEmpty() && !empty($global->current_account))
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                          @if($global->current_account) <i class="ion-chatbubble icon-md"></i>  {{ $global->current_account->name }} @endif
                          <span class="caret"></span>
                      </a>
                      <ul class="dropdown-menu">
                        @foreach($global->accounts as $account)
                          @if($global->current_account && $account->id != $global->current_account->id)
                          <li>
                            <a href="{{ admin_url('account/change-account/'.$account->id)}}" data-toggle="tooltip" data-placement="right" title="" data-original-title="切换到 “{{ $account->name }}”">{{ $account->name}}</a>
                          </li>
                          @endif
                        @endforeach
                        @if($global->accounts->count() > 1)
                        <li role="presentation" class="divider"></li>
                        @endif
                        <li>
                          <a href="{{ admin_url('account')}}">公众号管理</a>
                        </li>
                        <li>
                          <a href="{{ admin_url('account/create')}}">添加公众号</a>
                        </li>
                      </ul>
                  </li>
              </ul>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <div id="wrap">
  <div class="container main-container">
    <div class="console-wrapper table-box">
      <section class="console-container table-row">
        <aside class="console-sidebar-wrapper table-cell">
        @include('admin.partials.sidebar')
        </aside>
        <section class="console-content-wrapper table-cell">
          @yield('content')
        </section>
      </section>
    </div>
  </div>
      </div>

  <div class="console-footer" id="footer">
      <div class="clearfix text-center">
        <ul class="list-unstyled list-inline">
        <li>POWERED BY <a href="http://www.viease.com" target="_blank">viease {{ VIEASE_VERSION }}</a> &copy;  2015</li>
        </ul>
        <button class="pull-right hidden-print  back-to-top" onclick="window.scrollTo(0,0)"> <i class="ion-android-arrow-dropup"></i>
        </button>
      </div>
    </div>
  <div class="loading text-center" style="display:none;">
      <span class="plus-loader"></span>
      <span class="message">网络加载中...</span>
  </div>

  <script src="{{ asset('/js/require.js') }}" data-main="{{ asset('js/admin/main') }}"></script>
  @yield('js')
</body>
</html>