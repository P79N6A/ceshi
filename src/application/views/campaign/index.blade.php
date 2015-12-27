@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="planManage" class="pageName"></div>
    <div class="adShowTable">
        <div class="adShowTableSearch">
            <label for="">时间：</label>
            <input id="startDate" type="text" name="date-from" class="adInputInput"/>
            <label for="search-date-to">到</label>
            <input id="endDate" type="text" name="date-to" class="adInputInput"/>
            <label for="" class="adShowTableSearchKeyword">关键字：</label>
            <input id="keywords" type="text" class="adInputInput search_key_JS"/>
            <input type="submit" value="搜索" class="adShowTableSearchBtn search_JS"/>
        </div>
        <div class="adShowTableShow planManage">
            <div class="adShowTableHeader clearfix">
                <div class="adShowTablePlan floatL ML20"><b class="FS14">计划管理</b></div>
            </div>
            <div class="adShowTableCell adShowTableCellHeight">
                <table class="adTable">
                    <tr class="adShowTableTitle">
                        <th class="adShowTableId">计划ID</th>
                        <th class="adShowTablePlan">计划名称</th>
                        <th class="adShowTableTime">推广起止时间</th>
                        <th class="adShowTableTime">出价(元)</th>
                        <th class="adShowTableDaycost">计划日限额</th>
                        <th class="adShowTableDaycost">今日消耗(元)</th>
                        <th class="adShowTableDaycost">总消耗</th>
                        <th class="adShowTableStatus">
                            <select name="" id="adShowTableStatusList" >
                                <option value="">状态</option>
                                <option value="">待投</option>
                                <option value="">在投</option>
                                <option value="">暂停</option>
                                <option value="">结束</option>
                                <option value="">草稿</option>
                                <option value="">异常</option>
                            </select>
                        </th>
                        <th class="adShowTablePerform">操作</th>
                    </tr>
                </table>
                <p class="adShowNoPlan">暂无数据</p>
            </div>
            <div id="Pagination" class="pagination floatR MT20"></div>
        </div>
    </div>
    <div class="alertBox_CSS openAlertBox_JS">
        <div class="alertBox">
            <div class="alertBoxTitle"><b>提示</b><a href="javascript:void(0);" class="alertBoxClose">X</a></div>
            <div class="alertBoxBody">
                <div class="alertBoxCon myb2">
                    <div class="alertBoxConText">
                        <p><b>即将恢复该计划投放</b><br/>确认开启？</p>
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
        <div class="alertBox">
            <div class="alertBoxTitle"><b>提示</b><a href="javascript:void(0);" class="alertBoxClose">X</a></div>
            <div class="alertBoxBody">
                <div class="alertBoxCon myb2">
                    <div class="alertBoxConText">
                        <p><b>即将暂停该计划投放。暂停后您可以在计划周期内再次开启计划，恢复投放</b><br/>确认暂停？</p>
                    </div>
                </div>
            </div>
            <div class="alertBoxFooter">
                <div class="btnConW150 centerT0">
                    <div class="myBtn redBtn sBtn myShadow floatL closeAlertSure_JS"  suda-uatrack="key=tblog_appllo_project&value=pause_plan_confirm">确认</div>
                    <div class="myBtn sBtn myShadow floatR alertBoxCancel"  suda-uatrack="key=tblog_appllo_project&value=pause_plan_cancel">取消</div>
                    <div class="blankBox"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="alertBox_CSS deleteAlertBox_JS">
        <div class="alertBox">
            <div class="alertBoxTitle"><b>提示</b><a href="javascript:void(0);" class="alertBoxClose">X</a></div>
            <div class="alertBoxBody">
                <div class="alertBoxCon myb2">
                    <div class="alertBoxConText">
                        <p><b>该计划正在投放中，删除操作将立即停止该计划的投放</b><br/>你确定要删除该计划？</p>
                    </div>
                </div>
            </div>
            <div class="alertBoxFooter">
                <div class="btnConW150 centerT0">
                    <div class="myBtn redBtn sBtn myShadow floatL deleteAlertSure_JS"  suda-uatrack="key=tblog_appllo_project&value=delete_plan_confirm">确认</div>
                    <div class="myBtn sBtn myShadow floatR alertBoxCancel" suda-uatrack="key=tblog_appllo_project&value=delete_plan_cancel">取消</div>
                    <div class="blankBox"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="mybox planManageError">
        <div class="TitleBar">
            <div class="floatL ML20"><b>计划管理</b></div>
            <div class="blankBox"></div>
        </div>
        <div class="noAPP">
            <div class="tanhao"></div><br/>暂无推广计划<br/><br/><a href="/app/?customer_id={{ \UserInfo::getTargetUserId() }}" >新建广告</a>
        </div>
    </div>
</div>
@stop