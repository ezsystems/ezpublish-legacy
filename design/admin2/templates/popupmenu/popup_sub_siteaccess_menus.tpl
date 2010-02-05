{def $siteAccessList = ezini('SiteAccessSettings','RelatedSiteAccessList')|unique}

<!-- Site access for override popup menu -->
<script type="text/javascript">
<!--
menuArray['OverrideSiteAccess'] = {ldelim} 'depth': 1 {rdelim};
// -->
</script>

<div class="popupmenu" id="OverrideSiteAccess">
    <div class="popupmenuheader"><h3 class="override-site-access-menu-header">{"Choose siteaccess"|i18n("design/admin/popupmenu")}</h3>
        <div class="break"></div>
    </div>

    {section var=siteAccess loop=$:siteAccessList}
        {let link=concat("visual/templatecreate/node/view/full.tpl/(siteAccess)/",$siteAccess)|ezurl}
            <a id="menu-override-siteAccess-{$siteAccess}" onclick='ezpopmenu_hideAll(); ezpopup_SubstituteAndRedirect({$link}); return true;' onmouseover="ezpopmenu_mouseOver( 'OverrideSiteAccess' )">{$siteAccess}</a>
        {/let}
    {/section}
</div>



<!-- Site access for override by class popup menu -->
<script type="text/javascript">
<!--
menuArray['OverrideByClassSiteAccess'] = {ldelim} 'depth': 1 {rdelim};
// -->
</script>
<div class="popupmenu" id="OverrideByClassSiteAccess">
    <div class="popupmenuheader"><h3 class="override-site-access-menu-header">{"Choose siteaccess"|i18n("design/admin/popupmenu")}</h3>
        <div class="break"></div>
    </div>

    {section var=siteAccess loop=$:siteAccessList}
        {let link=concat('visual/templatecreate/node/view/full.tpl/(siteAccess)/', $siteAccess, '/(classID)/%classID%')|ezurl}
            <a id="menu-override-by-class-siteAccess-{$siteAccess}" onclick='ezpopmenu_hideAll(); ezpopup_SubstituteAndRedirect({$link}); return true;' onmouseover="ezpopmenu_mouseOver( 'OverrideByClassSiteAccess' )">{$siteAccess}</a>
        {/let}
    {/section}

</div>



<!-- Site access for override by node popup menu -->
<script type="text/javascript">
<!--
menuArray['OverrideByNodeSiteAccess'] = {ldelim} 'depth': 1 {rdelim};
// -->
</script>
<div class="popupmenu" id="OverrideByNodeSiteAccess">
    <div class="popupmenuheader"><h3 class="override-site-access-menu-header">{"Choose siteaccess"|i18n("design/admin/popupmenu")}</h3>
        <div class="break"></div>
    </div>

    {section var=siteAccess loop=$:siteAccessList}
        {let link=concat('visual/templatecreate/node/view/full.tpl/(siteAccess)/', $siteAccess, '/(nodeID)/%nodeID%')|ezurl}
            <a id="menu-override-by-node-siteAccess-{$siteAccess}" onclick='ezpopmenu_hideAll(); ezpopup_SubstituteAndRedirect({$link}); return true;' onmouseover="ezpopmenu_mouseOver( 'OverrideByNodeSiteAccess' )">{$siteAccess}</a>
        {/let}
    {/section}
</div>