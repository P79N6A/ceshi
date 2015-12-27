@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="appEdit9234" class="pageName"></div>
    <div class="appEditContent">
        <iframe src="http://app.sina.com.cn/appmarket/appPutin.html#uid={{ UserInfo::getTargetUserId() }}&packageName={{ $package_name }}&apptype={{ $apptype }}&edited=1&domain=http://{{ $_SERVER['HTTP_HOST'] }}" width="1090" height="874"></iframe>
    </div>
</div>
@stop

@section('js')
<script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/appEdit.js?v={{ getenv('STATIC_VERSION') }}"></script>
@stop