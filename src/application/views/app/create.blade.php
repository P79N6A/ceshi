@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="appEdit9234" class="pageName"></div>
    <div class="appEditContent">
        <iframe src="http://app.sina.com.cn/appmarket/appPutin.html#uid={{ UserInfo::getTargetUserId() }}&packageName={{ $package_name }}&apptype={{ $apptype }}&edited=0&domain=http://{{ $_SERVER['HTTP_HOST'] }}" width="1090" height="874"></iframe>
    </div>
</div>
@stop

@section('css')
<script type="text/javascript" src="http://e.sinajs.cn/p/scripts/jquery-1.8.2.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/appEdit.js?v={{ getenv('STATIC_VERSION') }}"></script>
@stop