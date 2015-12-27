
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ empty($title) ? '应用家' : $title}}</title>
    @yield('css')
    <!-- 公用部分css-->
    <link rel="stylesheet" type="text/css" href="http://img.t.sinajs.cn/t4/appstyle/e/css/module/base/frame.css?v={{ getenv('STATIC_VERSION') }}"/>
    <link rel="stylesheet" type="text/css" href="http://img.t.sinajs.cn/t4/appstyle/e_ad/css/pages/fanstong.css?v={{ getenv('STATIC_VERSION') }}"/>
    <link rel="stylesheet" type="text/css" href="http://img.t.sinajs.cn/t4/appstyle/e_ad/css/module/ead_usually.css?v={{ getenv('STATIC_VERSION') }}"/>
    <link rel="stylesheet" type="text/css" href="http://img.t.sinajs.cn/t4/appstyle/e_ad/css/module/ead_table.css?v={{ getenv('STATIC_VERSION') }}"/>
    <link rel="stylesheet" type="text/css" href="http://img.t.sinajs.cn/t4/appstyle/e_ad/skin/skin.css?v={{ getenv('STATIC_VERSION') }}"/>
    <link rel="stylesheet" type="text/css" href="http://e.sinajs.cn/p/styles/jquery-ui-1.9.1.css?v={{ getenv('STATIC_VERSION') }}"/>
    <!-- APP推广部分css-->
    <link rel="stylesheet" href="http://js.t.sinajs.cn/weiboad/apps/app//css/main.min.css?v={{ getenv('STATIC_VERSION') }}"/>
    
</head>
<body class="B_fanstong">
<!--顶栏-通用-->
<div class="header">
    <div class="inner">
        <div class="logo"><a href="http://bp.biz.weibo.com/bid/" title="新浪微博 | 广告中心"></a></div>
        <div class="fr"><a href="">{{ \UserInfo::getTargetUserName() }}</a><i>|</i><a href="http://bp.biz.weibo.com/bid/logout?customer_id={{ UserInfo::getTargetUserId() }}">退出并返回微博</a></div>
    </div>
    <div class="main_nav"><a href="http://bp.biz.weibo.com/bid/">首页</a><a href="/app/customer/overview?customer_id={{ UserInfo::getTargetUserId() }}" class="{{ (strpos(get_current_page_uri(), 'reports') == false) ? 'cur':'' }}">广告中心</a><a href="/app/customer-reports?customer_id={{ UserInfo::getTargetUserId() }}" class="{{ (strpos(get_current_page_uri(), 'reports') == false) ? '':'cur' }}">数据中心</a><a href="http://bp.biz.weibo.com/bid/account?customer_id={{ UserInfo::getTargetUserId() }}">账户管理</a><a href="http://bp.biz.weibo.com/bid/packet?customer_id={{ UserInfo::getTargetUserId() }}">自定义投放管理</a></div>
</div>
<!--左侧栏目-通用-->
<div id="mainWrap" class="main_wrap full_side">
    <div class="main_int clearfix">
        <div id="left" class="left_nav"><a id="indicator" href="javascript:;" class="icon icon_ad0"></a>
        @if (!strpos(get_current_page_uri(), 'reports'))
            @include('layouts.ad_left')
        @else
            @include('layouts.data_left')
        @endif
        </div>
        @yield('content')
    </div>
</div>
<!--底栏-通用-->
<div class="global_footer">
    <div class="other_link clearfix">
        <div class="help_link">
            <p>微博广告咨询热线：4000-980-980</p>
            <p>北京微梦创科网络技术有限公司 <a href="http://weibo.com/aj/static/jww.html" target="_blank">京网文[2012]0398-130号</a><a href="http://www.miibeian.gov.cn" target="_blank">京ICP证100780号</a></p>
        </div>
        <div class="copy">
            <p>Copyright © 1996-2015 SINA</p>
        </div>
    </div>
</div>
<!--原页面中的JS-通用-->
<script type="text/javascript">
    var BASEURL = '/bid/';
    var CDN = 'http://e.sinajs.cn/bpbid/';
    var BPCDN = 'http://e.sinajs.cn/bp/';
    var ORDERSTEP = Number('30');
</script>
<script type="text/javascript" src="http://e.sinajs.cn/p/scripts/jquery-1.8.2.js?v=150326X01"></script>
<script type="text/javascript" src="http://e.sinajs.cn/p/scripts/jquery-ui-1.9.1.js?v=150326X01"></script>
<script type="text/javascript" src="http://e.sinajs.cn/bpbid/js/common.js?v=150326X01"></script>
<script type="text/javascript">
    hushc.baseurl = BASEURL;
    hushc.uid = Number(0);
    hushc.customer = {"id":"1259879347","crm_id":"FD14224086901146","email":"","weibo_name":"\\u6d59\u5c0f\u5b69","name":"\u6d59\u5c0f\u5b69","contact":"\u6d59\u5c0f\u5b69","website":null,"website_url":null,"addr":null,"zip":null,"tel":"","fax":null,"create_time":"2015-01-28 09:31:30","status":"2","industry1":"0","industry2":"0","image":"http:\/\/tp4.sinaimg.cn\/1259879347\/50\/5703638937\/1"};
</script>
<script type="text/javascript" src="http://e.sinajs.cn/bpbid/js/pages/common/sidebar.js?v=150326X01"></script>
<script type="text/javascript">
    hushc.feedId = 0;
    hushc.isTop = 0;
    hushc.groupId = 0;
    hushc.expandedAll = 0;
</script>
<script type="text/javascript" src="http://e.sinajs.cn/bpbid/js/pages/groupindex/index.js?v=150326X01"></script>
<!--输出各种-->
<script type="text/javascript">
    var bee = {};
    bee.apiBathUrl = 'http://{{ $_SERVER['HTTP_HOST'] }}';
    bee.imgUploadApiBathUrl = 'http://{{ $_SERVER['HTTP_HOST'] }}';
    bee.customerId = {{ \UserInfo::getTargetUserId() }};
    bee.customerAvatar = '{{ \Api\WeiboApi::getUserAvatarByUId(\UserInfo::getTargetUserId()) }}';
    bee.customerName = '{{ \UserInfo::getTargetUserName() }}';
    @yield('data')
</script>
<div class="myMask">
    <img src="http://js.t.sinajs.cn/weiboad/apps/app//images/loading.gif"/>
</div>
<div class="bottom_mark bottom_mark_JS">
    <div class="slide_mark_box slide_mark_JS"><span class="slide_mark"></span></div>
    <a target="_blank" href="/app/?customer_id={{ \UserInfo::getTargetUserId() }}" class="creative_ad myBtn redBtn"  suda-uatrack="key=tblog_appllo_project&value=new_ad">新建广告</a>
</div>


<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/rome.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/jquery.tmpl.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/jquery.form.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/jquery.rateit.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/jquery.pagination.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/jquery.powertip.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/jquery.scrollTo.js?v={{ getenv('STATIC_VERSION') }}"></script>
@yield('js')
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/apps/ideaManage-createPage.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/apps/bee.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/apps/appJs.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script type="text/javascript" src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/highcharts.js?v={{ getenv('STATIC_VERSION') }}"></script>
<!-- SUDA_CODE_START -->
<script type="text/javascript" charset="utf-8" src="http://www.sinaimg.cn/unipro/pub/suda_s_v851c.js?v={{ getenv('STATIC_VERSION') }}"></script>
<!-- SUDA_CODE_END -->
<script type="text/javascript">
    SUDA.log('pid', 'ext1', 'ext2', {
        'callback': function (result) {
            //alert('haha');	//回调语句
        }
    })
</script>
</body>
</html>