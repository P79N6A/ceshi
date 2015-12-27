@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="ideaManage" class="pageName"></div>
    <div class="adShowTable">
        <div class="adShowTableSearch">
            <label for="">时间：</label>
            <input id="startDate" type="text" name="date-from" data-date="minDate" class="adInputInput">
            <label for="search-date-to">到</label>
            <input id="endDate" type="text" name="date-to" data-date="maxDate" class="adInputInput">
            <label for="" class="adShowTableSearchKeyword">关键字：</label>
            <input id="keywords" type="text" class="adInputInput search_key_JS">
            <input type="submit" value="搜索" class="adShowTableSearchBtn search_JS">
        </div>
        <div class="adShowTableShow ideaManage">
            <div class="adShowTableHeader clearfix">
                <div class="adShowTablePlan floatL ML20"><b class="FS14">创意管理</b></div>
                <div class="floatR MR20">
                    <div class="miniBtn MT12"> <a class="adShowTableLink_JS" href="/app/creatives/create?customer_id={{ UserInfo::getTargetUserId() }}" target="_blank"  suda-uatrack="key=tblog_appllo_project&value=creative_managemen_new" >新建创意</a></div>
                </div>
            </div>
            <div class="adShowTableCell adShowTableCellHeight">
                <table class="adTable">
                    <tr class="adShowTableTitle">
                        <th class="adShowTableId">创意ID</th>
                        <th class="adShowTablePlan">创意名称</th>
                        <th class="adShowTableTime">创意时间</th>
                        <th class="adShowTableAllApp">
                            <select name="" id="" class="ideaManageSelectApp_JS ideaManageAllApp_JS">
                                <option value="全部关联应用" data-index="">关联应用</option>
                            </select>
                        </th>
                        <th class="adShowTableTotalcost">创意</th>
                        <th class="adShowTableStatus">
                            <select name="" id="" class="ideaManageSelectRank_JS">
                                <option value="全部审核状态" data-index="">审核状态</option>
                                <option value="待审核" data-index="0">待审核</option>
                                <option value="审核通过" data-index="1">审核通过</option>
                                <option value="未通过" data-index="2">未通过</option>
                            </select>
                        </th>
                        <th class="adShowTableStatus">
                            <select name="" id="" class="ideaManageSelectComment_JS">
                                <option value="全部评论状态" data-index="">评论状态</option>
                                <option value="已开启" data-index="0">已开启</option>
                                <option value="已关闭" data-index="1">已关闭</option>
                            </select>
                        </th>
                        <th class="adShowTablePerform">操作</th>
                    </tr>
                </table>
                <p class="adShowNoIdea">暂无数据</p>
                <div class="mybox adShowNoTable">
                        <div class="noAPP">
                            <div class="tanhao"></div><br/>您目前没有添加任何创意<br/><br/><a href="/app/creatives/create?customer_id={{ UserInfo::getTargetUserId() }}">添加创意</a>
                        </div>
                </div>
            </div>
           
            <div id="Pagination" class="pagination floatR MT20"><span class="current prev">Prev</span><span
                    class="current">1</span><span class="current next">Next</span></div>
        </div>
    </div>
    <div class="alertBox_CSS removeAlertBox_JS">
        <div class="alertBox floatL ML20">
            <div class="alertBoxTitle"><b>提示</b><a href="javascript:void(0);" class="alertBoxClose">X</a></div>
            <div class="alertBoxBody">
                <div class="alertBoxCon myb2">
                    <div class="alertBoxConText">
                        <p><b>移除只会将该创意微博从创意库列表中删除，不会删除原微博。</b><br/>确定要从列表中移除该创意？</p>
                    </div>
                </div>
            </div>
            <div class="alertBoxFooter">
                <div class="btnConW150 centerT0">
                    <div class="myBtn redBtn sBtn myShadow floatL removeAlertSure_JS">确认</div>
                    <div class="myBtn sBtn myShadow floatR alertBoxCancel">取消</div>
                    <div class="blankBox"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="alertBox_CSS openAlertBox_JS">
        <div class="alertBox floatL ML20">
            <div class="alertBoxTitle"><b>提示</b><a href="javascript:void(0);" class="alertBoxClose">X</a></div>
            <div class="alertBoxBody">
                <div class="alertBoxCon myb2">
                    <div class="alertBoxConText">
                        <p><b>开启评论，将对所有正在使用该创意的计划生效</b><br/>确认开启评论？</p>
                    </div>
                </div>
            </div>
            <div class="alertBoxFooter">
                <div class="btnConW150 centerT0">
                    <div class="myBtn redBtn sBtn myShadow floatL openAlertSure_JS">确认</div>
                    <div class="myBtn sBtn myShadow floatR alertBoxCancel">取消</div>
                    <div class="blankBox"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="alertBox_CSS closeAlertBox_JS">
        <div class="alertBox floatL ML20 closeAlertBox_JS">
            <div class="alertBoxTitle"><b>提示</b><a href="javascript:void(0);" class="alertBoxClose">X</a></div>
            <div class="alertBoxBody">
                <div class="alertBoxCon myb2">
                    <div class="alertBoxConText">
                        <p><b>关闭评论，将对所有正在使用该创意的计划生效</b><br/>确认关闭评论？</p>
                    </div>
                </div>
            </div>
            <div class="alertBoxFooter">
                <div class="btnConW150 centerT0">
                    <div class="myBtn redBtn sBtn myShadow floatL closeAlertSure_JS">确认</div>
                    <div class="myBtn sBtn myShadow floatR alertBoxCancel">取消</div>
                    <div class="blankBox"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="mybox ideaManageError">
        <div class="TitleBar">
            <div class="floatL ML20"><b>创意管理</b></div>
            <div class="floatR MR20">
                <div class="miniBtn MT12"><a class="adShowTableLink_JS"
                                             href="/app/creatives/create?customer_id={{ UserInfo::getTargetUserId() }}" suda-uatrack="key=tblog_appllo_project&value=creative_managemen_new" >新建创意</a>
                </div>
            </div>
            <div class="blankBox"></div>
        </div>
        <div class="noAPP">
            <div class="tanhao"></div>
            <br>创意需要和所推广的应用关联，您目前没有添加任何应用，无法新建创意<br><br><a href="/app/?customer_id={{ UserInfo::getTargetUserId() }}"  >添加应用</a>
        </div>
    </div>
    <div
        style="width: 364px; position: absolute; top: 383px; left: 600px; display: none; background: rgb(255, 255, 255);"
        class="adShowApp myHide">
        <div class="adShowAppBox">
            <div class="adShowAppBody">
                <div class="MB10"><img src="http://p17.qhimg.com/t01cf3401fcf64b7af3.png" alt="" width="40" height="40"
                                       class="icon_JS1">

                    <div>
                        <p class="adShowAppName color000 FS14 name_JS">-</p>

                        <p class="adShowAppSource"><span class="adShowAppTime"></span>1分钟前<span
                                class="adShowAppLocation">来自weibo.com</span></p>
                    </div>
                </div>
                <p class="FS16 color000 MB10 text_JS">-</p>

                <div class="imgs_JS"><img src="http://ww4.sinaimg.cn/bmiddle/8c803935jw1eu9e3ox8raj20e80d83yx.jpg"
                                          width="321px" height="160px"></div>
                <div class="adShowAppDownload MT10 FS14">
                    <div class="adShowAppDownloadIcon"></div>
                    <p class="color000 imgs_JS2">-</p>

                    <p class="src_JS" style="display: block;">-</p>

                    <div data-rateit-value="2.5" data-rateit-ispreset="true" data-rateit-readonly="true" class="src2_JS myHide rateit" style="display: none;"></div>
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
@stop