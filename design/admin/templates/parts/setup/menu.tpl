{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Setup'|i18n( 'design/admin/parts/setup/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<ul>
    <li><a href={'/setup/cache/'|ezurl}>{'Cache management'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/search/stats/'|ezurl}>{'Search statistics'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/setup/info/'|ezurl}>{'System information'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/url/list/'|ezurl}>{'URL management'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/content/urltranslator/'|ezurl}>{'URL translator'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    {section show=ezpreference( 'admin_setup_advanced' )}
    <li><a href={'/user/preferences/set/admin_setup_advanced/0'|ezurl}>{'Advanced'|i18n( 'design/admin/parts/setup/menu' )}</a>
    <a href={'/user/preferences/set/admin_setup_advanced/0'|ezurl}><img src={'down.gif'|ezimage} alt="" width="11" height="6" /></a>
        <ul>
        <li><a href={'/class/grouplist/'|ezurl}>{'Classes'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/setup/extensions/'|ezurl}>{'Extensions'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/settings/view'|ezurl}>{'Ini settings'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/notification/runfilter/'|ezurl}>{'Notification'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/pdf/list/'|ezurl}>{'PDF export'|i18n( 'design/admin/parts/setup/menu' ,'PDF export')}</a></li>
        <li><a href={'/package/list/'|ezurl}>{'Packages'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/setup/rad/'|ezurl}>{'RAD'|i18n( 'design/admin/parts/setup/menu' ,'Rapid Application Development')}</a></li>
        <li><a href={'/rss/list'|ezurl}>{'RSS'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/section/list/'|ezurl}>{'Sections'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/setup/session'|ezurl}>{'Sessions'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/setup/systemupgrade'|ezurl}>{'System upgrade'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/content/translations/'|ezurl}>{'Translations'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/trigger/list/'|ezurl}>{'Triggers'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        <li><a href={'/workflow/grouplist/'|ezurl}>{'Workflows'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
        </ul>
    </li>
    {section-else}
    <li><a href={'/user/preferences/set/admin_setup_advanced/1'|ezurl}>{'Advanced'|i18n( 'design/admin/parts/setup/menu' )}</a>
    <a href={'/user/preferences/set/admin_setup_advanced/1'|ezurl}><img src={'up.gif'|ezimage} alt="" width="11" height="6" /></a></li>
    {/section}
</ul>

{* DESIGN: Content END *}</div></div></div></div></div></div>