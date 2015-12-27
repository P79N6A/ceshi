@extends('layouts.default')

@section('content')

<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="planDetail" class="pageName"></div>
    <div class="appCon">
        <div class="mybox">
            <div class="TitleBar">
                <div class="floatL ML20"><b class="ori">计划管理 </b><span>&gt; 计划详情</span></div>
                <div class="floatR MR20">
                    <div class="miniBtn MT12"> <a href="/app/campaigns/{{$campaign_id}}/edit?customer_id={{ \UserInfo::getTargetUserId() }}" suda-uatrack="key=tblog_appllo_project&value=plan_detail_modify">修改</a></div>
                </div>
                <div class="blankBox"></div>
            </div>
            <div class="myboxCon width1066">
                <div style="vertical-align:top;width:340px;" class="myboxConL2">
                    <div class="myboxConLCon2 myb2">
                        <div class="adShowApp">
                            <div class="adShowAppBox">
                                <div class="adShowAppBody">
                                    <div class="MB10"><img src="http://js.t.sinajs.cn/weiboad/apps/app/images/icon_user_logo.jpeg" alt="" width="40" height="40" class="icon_JS1"/>
                                        <div>
                                            <p class="adShowAppName color000 FS14 name_JS">Loading...</p>
                                            <p class="adShowAppSource"><span class="adShowAppTime">

                                  1分钟前</span><span class="adShowAppLocation">来自weibo.com</span></p>
                                        </div>
                                    </div>
                                    <p class="FS16 color000 MB10 text_JS">Loading...</p>
                                    <div class="imgs_JS"><img src="http://js.t.sinajs.cn/weiboad/apps/app/images/icon_app_text.png" width="321px" height="160px"/></div>
                                    <div class="adShowAppDownload MT10 FS14">
                                        <div class="adShowAppDownloadIcon"></div>
                                        <p class="color000 imgs_JS2">Loading...</p>
                                        <p class="src_JS">Loading...</p>
                                        <div data-rateit-value="2.5" data-rateit-ispreset="true" data-rateit-readonly="true" class="src2_JS myHide rateit"></div>
                                    </div>
                                </div>
                                <div class="adShowAppContact">
                                    <div>
                                        <p class="adShowAppContactImg"><span class="adShowAppContactForward FS14">0</span></p>
                                        <p class="adShowAppContactImg"><span class="adShowAppContactComment FS14">0</span></p>
                                        <p class="adShowAppContactLast"><span class="adShowAppContactLike FS14">0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="vertical-align:top;" class="myboxConR2 ML20">
                    <div class="myboxConCon2">
                        <div style="vertical-align:top;" class="myboxConCon2L LH30 myb2">
                            <div class="FS14">推广信息</div>
                            <div class="logoAndText2"><img src="http://js.t.sinajs.cn/weiboad/apps/app/images/app-name.png" alt="" class="logoAndTextImgR appImg_JS"/><span class="appName_JS"></span></div>
                            <div>版本：<b class="C000 appVersion_JS"></b></div>
                            <div>平台：<b class="C000 platform_JS"> </b></div>
                            <div>提交时间：<b class="C000 smDate_JS"></b></div>
                            <div><span class="detailLeftName">计划名称：</span><b class="C000 planName_JS detailLeftCon"> </b></div>
                            <div>竞价方式：<b class="C000 comStyle_JS"> </b></div>
                            <div>计划日消耗上限：<b class="C000 planLimit_JS"></b></div>
                            <!-- 日限额消耗提示开始-->
                            <div class="changePriceDetailBox_JS" style="display:none"><span class="changePricetips2">日限额修改次日生效，生效限额为<span class="tomPriceDetail_JS"></span>元</span></div>
                            <!-- 日限额消耗提示结束-->
                            <div><span class="detailLeftName">投放排期：</span><b class="C000 planData_JS detailLeftCon"> </b></div>
                        </div>
                        <div style="vertical-align:top;" class="myboxConCon2R LH30 myb2">
                            <div class="FS14">广告受众</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('data')
bee.detailPlanId = {{ $campaign_id }};
@stop