@extends('layouts.default')

@section('content')
<div id="Page" class="main_cont">
    <!-- 版心内容区域-->
    <div page-name="totalityData" class="pageName"></div>
    <div class="adShowTable">
        <div class="adShowTableSearch">
            <label for="">时间：</label>
            <input id="startDate" type="text" name="date-from" class="adInputInput"/>
            <label for="search-date-to">到</label>
            <input id="endDate" type="text" name="date-to" class="adInputInput"/>
            <input type="submit" value="搜索" class="adShowTableSearchBtn search_JS"/>
        </div>
        <div class="adShowTableShow">
            <div class="adShowTableHeader clearfix">
                <div class="adShowTablePlan floatL ML20"><b class="FS14">总体数据</b><span title="总体数据为该应用版本所统计到的相关数据汇总。其中：&lt;br&gt;互动量，为有效互动量，包含点击、关注、转发、赞、收藏的总量，不包含评论量；&lt;br&gt;互动率，为曝光中的互动情况比率；&lt;br&gt;千次曝光成本，为每千次曝光的花费；&lt;br&gt;单次互动成本，为每一次互动行为的花费;  &lt;br&gt;激活量，为对于已在TalkingData添加应用监控，并在应用家填写其监控地址的情况下，所统计到的所推广应用版本激活量。" class="questionMark tips_hover_JS"></span></div>
                <div class="floatR MR20">
                    <div class="miniBtn MT12"><a href="javascript:void(0)" class="export_JS">

                            导出数据</a></div>
                </div>
                <div class="floatR MR20 adTitleTipsMark">

                    数据中心为您提供的是截止到昨日的广告数据情况
                </div>
            </div>
            <div class="adShowTableCell adShowTableCellWidth adShowTableCellHeight">
                <table>
                    <tr class="adShowTableTitle">
                        <th class="adShowTableTotalcost">日期</th>
                        <th class="adShowTableStatus">消耗/元</th>
                        <th class="adShowTableStatus">曝光量</th>
                        <th class="adShowTablePerform">互动量<b title="互动量为有效互动量，包含图文区点击、下载区点击、短链点击、转发、关注、赞、收藏的总量，不包含评论量" class="tips_hover_JS">i</b></th>
                        <th class="adShowTablePerform">互动率<b title="互动率，为曝光中的互动情况比率" class="tips_hover_JS">i</b></th>
                        <!-- <th class="adShowTablePerform">点击量</th>  -->
                        <th class='adShowTablePerform'>图文区点击量</th>  
                        <th class='adShowTablePerform'>下载区点击量</th>
                        <th class='adShowTablePerform'>短链点击量</th>  
                        <th class="adShowTablePerform">转发量</th>
                        <th class="adShowTablePerform">关注量</th>
                        <th class="adShowTablePerform">赞量</th>
                        <th class="adShowTablePerform">收藏量</th>
                        <th class="adShowTablePerform">评论量</th>
                        <th class="adShowTablePerform">
                               激活量<b title="已经在第三方添加了应用监控，并在应用家填写了监控地址，系统将自动统计应用激活量。目前已支持<a href=&quot;https://www.talkingdata.com/product-market.jsp?languagetype=zh_cn&quot;  target = &quot;_blank&quot;>TalkingData</a>和<a href=&quot;http://www.admaster.com.cn&quot;  target = &quot;_blank&quot;>AdMaster</a>转化大师的监控地址" class="tips_hover_JS">i</b> 
                        </th>
                        <th class="adShowTablePerform">千次曝光成本<b title="千次曝光成本，为每千次曝光的花费" class="tips_hover_JS">i</b></th>
                        <th class="adShowTablePerform">单次互动成本<b title="单次互动成本，为每一次互动行为的花费" class="tips_hover_JS">i</b></th>
                    </tr>
                    <tr>
                        <td colspan="2"><img src="http://e.sinajs.cn/bpbid/images/common/loading.gif"/>请稍等,正在加载......</td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="Pagination" class="pagination floatR MT20"></div>
    </div>
</div>
@stop