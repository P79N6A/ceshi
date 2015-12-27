<ul>
    <li><em><i class="arrow"></i><i class="icon icon_ad8"></i><b>博文推广</b></em>

        <p><a href="/bid/index/weibo?customer_id={{ UserInfo::getTargetUserId() }}">产品概览</a></p>

        <p><a href="/bid/group?customer_id={{ UserInfo::getTargetUserId() }}">广告组管理</a></p>

        <p><a href="/bid/weibo/manage?customer_id={{ UserInfo::getTargetUserId() }}">微博创意管理</a></p>
    </li>
    <li><em><i class="arrow"></i><i class="icon icon_ad9"></i><b>应用推广</b></em>

        <p><a href="/card/appdata/customer/report?customer_id={{ UserInfo::getTargetUserId() }}">产品概览</a></p>

        <p><a href="/card/app/campaign/show?customer_id={{ UserInfo::getTargetUserId() }}">计划管理</a></p>

        <p><a href="/card/app/overview?customer_id={{ UserInfo::getTargetUserId() }}">应用管理</a></p>
    </li>
    <li><em><i class="arrow"></i><i class="icon icon_ad11"></i><b>账号推广</b></em>

        <p><a href="/promotedaccounts/account/overview?customer_id={{ UserInfo::getTargetUserId() }}">产品概览</a></p>

        <p><a href="/promotedaccounts/campaign/list?customer_id={{ UserInfo::getTargetUserId() }}">计划管理</a></p>
    </li>
    <li class="expand"><em class="cur"><i class="arrow"></i><i class="icon_app"></i><b>应用家</b></em>

        <p class="{{ CSS::getCur('/app/customer/overview') }}"><a href="/app/customer/overview?customer_id={{ UserInfo::getTargetUserId() }}">产品概览</a></p>

        <p class="{{ CSS::getCur('/app/campaigns') }}"><a href="/app/campaigns?customer_id={{ UserInfo::getTargetUserId() }}">计划管理</a></p>

        <p class="{{ CSS::getCur('/app/apps') }}"><a href="/app/apps?customer_id={{ UserInfo::getTargetUserId() }}">应用管理</a></p>

        <p class="{{ CSS::getCur('/app/creatives') }}"><a href="/app/creatives?customer_id={{ UserInfo::getTargetUserId() }}">创意管理</a></p>
    </li>
</ul>