@extends('layouts.default')

@section('css')
<script type="text/javascript" src="http://e.sinajs.cn/p/scripts/jquery-1.8.2.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/tuiAppEdit.js?v={{ getenv('STATIC_VERSION') }}"></script>
@stop

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="tuiAPP" class="pageName"></div>
    <div class="appCon tuiAPP">
        <div class="mybox">
            <div class="myboxCon">
                <div class="H20"></div>
                <div class="appToExtendBox2 floatL FCenter" suda-uatrack="key=tblog_appllo_project&value=new_app_add">
                    <div class="LH160"><span class="ori">+ </span><span>添加应用</span></div>
                </div>
                <div class="H20"></div>
                <div class="blankBox"></div>
            </div>
        </div>
        <div class="modelBox_CSS modelAlertBox_JS">

        </div>

        <div id="addApp">
            <iframe src="http://app.sina.com.cn/appmarket/spreadApp.html#uid={{ UserInfo::getTargetUserId() }}&domain=http://{{ $_SERVER['HTTP_HOST'] }}"></iframe>
            <div class="upload-dem">
                <div class="dem-in">
                    <p>
                        请您将以下内容按要求<span class="CRed">发送到邮箱app158@sina.com</span>，我们会有专人帮您将应用信息添加至微博应用中心。添加成功后，您可以回到这里，直接推广应用。
                    </p>
                    <p class="CRed">1．邮件正文，请写明以下内容（*为必填项） </p>
                    <p class="emailText"> <span class="CRed">*</span>微博UID：</p>
                    <p class="emailText"> <span class="CRed">*</span>应用名称：</p>
                    <p class="emailText"> <span class="CRed">*</span>应用分类：</p>
                    <p class="emailText"> 作者名称:（要求7个字符以内）</p>
                    <p class="emailText"> <span class="CRed">*</span>应用简介：(500字以内，不能包含空行，版本号信息及特殊字符)</p>
                    <p class="emailText"> 更新内容：(300字以内，不能包含空行，版本号信息及特殊字符)</p>
                    <p class="emailText"> <span class="CRed">*</span>应用ICON：(png格式，72*72)，1张，以邮件附件方式提交</p>
                    <p class="emailText"> <span class="CRed">*</span>应用截图：(png格式，要求尺寸不小于480*800)，最少3张，最多5张</p>
                    <p class="CRed">2．邮件附件，请上传要推广的应用安装包（.apk格式）</p>
                    <p>

                        以上内容填写好后，邮件至 <span class="underline">app158@sina.com </span>即可。
                    </p>
                    <p>如果您有疑问，请致电客服400-1234-1234</p>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/JWindow.js?v={{ getenv('STATIC_VERSION') }}"></script>
@stop