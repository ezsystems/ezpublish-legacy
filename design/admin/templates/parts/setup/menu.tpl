{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Setup'|i18n( 'design/admin/parts/setup/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=eq( $ui_context, 'edit' )}
<ul>
    <li><div><span class="disabled">{'Cache management'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Classes'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Collected information'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Extensions'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Global settings'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Ini settings'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Languages'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
<!-- Removed from 3.5. Enable if you have special requirements.   <li><div><span class="disabled">{'Notification'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li> -->
    <li><div><span class="disabled">{'PDF export'|i18n( 'design/admin/parts/setup/menu' ,'PDF export')}</span></div></li>
    <li><div><span class="disabled">{'Packages'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'RAD'|i18n( 'design/admin/parts/setup/menu' ,'Rapid Application Development')}</span></div></li>
    <li><div><span class="disabled">{'Roles and policies'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'RSS'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Search statistics'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Sections'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'States'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Sessions'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'System information'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Upgrade check'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Triggers'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'URL management'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'URL translator'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'URL wildcards'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
    <li><div><span class="disabled">{'Workflows'|i18n( 'design/admin/parts/setup/menu' )}</span></div></li>
</ul>

{section-else}

<ul>
    <li><div><a href={'/setup/cache/'|ezurl}>{'Cache management'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/class/grouplist/'|ezurl}>{'Classes'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/infocollector/overview/'|ezurl}>{'Collected information'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/setup/extensions/'|ezurl}>{'Extensions'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/content/edit/52/'|ezurl}>{'Global settings'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/settings/view'|ezurl}>{'Ini settings'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/content/translations/'|ezurl}>{'Languages'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
<!-- Removed from 3.5. Enable if you have special requirements.   <li><div><a href={'/notification/runfilter/'|ezurl}>{'Notification'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li> -->
    <li><div><a href={'/pdf/list/'|ezurl}>{'PDF export'|i18n( 'design/admin/parts/setup/menu' ,'PDF export')}</a></div></li>
    <li><div><a href={'/package/list/'|ezurl}>{'Packages'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/setup/rad/'|ezurl}>{'RAD'|i18n( 'design/admin/parts/setup/menu' ,'Rapid Application Development')}</a></div></li>
    <li><div><a href={'/role/list/'|ezurl}>{'Roles and policies'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/rss/list'|ezurl}>{'RSS'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/search/stats/'|ezurl}>{'Search statistics'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/section/list/'|ezurl}>{'Sections'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/state/groups/'|ezurl}>{'States'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/setup/session'|ezurl}>{'Sessions'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/setup/info/'|ezurl}>{'System information'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/setup/systemupgrade'|ezurl}>{'Upgrade check'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/trigger/list/'|ezurl}>{'Triggers'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/url/list/'|ezurl}>{'URL management'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/content/urltranslator/'|ezurl}>{'URL translator'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/content/urlwildcards/'|ezurl}>{'URL wildcards'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/workflow/grouplist/'|ezurl}>{'Workflows'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
    <li><div><a href={'/workflow/processlist/'|ezurl}>{'Workflow processes'|i18n( 'design/admin/parts/setup/menu' )}</a></div></li>
</ul>

{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>