@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="appManage" class="pageName"></div>
    <div class="adShowTable">
        <div class="adShowTableShow appManage">
            <div class="adShowTableHeader clearfix">
                <div class="adShowTablePlan floatL ML20"><b class="FS14">应用管理</b></div>
                <div class="floatR MR20">
                    <div class="miniBtn MT12"><a href="/app/?customer_id={{ \UserInfo::getTargetUserId() }}" class="adShowTableLink_JS" target="_blank"  suda-uatrack="key=tblog_appllo_project&value=app_management_add">添加应用</a></div>
                </div>
            </div>
            <div class="adShowTableCell">
                <table class="adTable">
                    <tr class="adShowTableTitle">
                        <th></th>
                        <th class="adShowTablePlan">应用名称</th>
                        <th class="adShowTableTime">应用大小</th>
                        <th class="adShowTableDaycost">应用分类</th>
                        <th class="adShowTableTodaycost">应用版本</th>
                        <th class="adShowTableTotalcost">包名</th>
                        <th class="adShowTableTotalcost">应用状态</th>
                        <th class="adShowTableTotalcost">平台</th>
                        <th class="adShowTablePerform">操作</th>
                    </tr>
                </table>
            </div>
            <div id="Pagination" class="pagination floatR MT20"></div>
        </div>
    </div>
    <div class="mybox appManageError">
        <div class="TitleBar">
            <div class="floatL ML20"><b>应用管理</b></div>
            <div class="floatR MR20">
                <div class="miniBtn MT12"> <a href="/app/?customer_id={{ \UserInfo::getTargetUserId() }}" class="adShowTableLink_JS" >添加应用</a></div>
            </div>
            <div class="blankBox"></div>
        </div>
        <div class="noAPP">
            <div class="tanhao"></div><br/>暂无提交应用<br/><br/><a href="/app/?customer_id={{ \UserInfo::getTargetUserId() }}" >添加应用</a>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/JWindow.js?v={{ getenv('STATIC_VERSION') }}"></script>
<script src="http://js.t.sinajs.cn/weiboad/apps/app/js/libs/tuiAppEdit.js?v={{ getenv('STATIC_VERSION') }}"></script>
@stop