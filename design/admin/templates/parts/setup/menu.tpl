{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h4>{'Setup'|i18n( 'design/admin/parts/setup/menu' )}</h4>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{section show=eq( $ui_context, 'edit' )}
<ul>
    <li><span class="disabled">{'Cache management'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Classes'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Extensions'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Global settings'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Ini settings'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
<!-- Removed from 3.5. Enable if you have special requirements.   <li><span class="disabled">{'Notification'|i18n( 'design/admin/parts/setup/menu' )}</span></li> -->
    <li><span class="disabled">{'PDF export'|i18n( 'design/admin/parts/setup/menu' ,'PDF export')}</span></li>
    <li><span class="disabled">{'Packages'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'RAD'|i18n( 'design/admin/parts/setup/menu' ,'Rapid Application Development')}</span></li>
    <li><span class="disabled">{'Roles and policies'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'RSS'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Search statistics'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Sections'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Sessions'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'System information'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Upgrade check'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Translations'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Triggers'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'URL management'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'URL translator'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
    <li><span class="disabled">{'Workflows'|i18n( 'design/admin/parts/setup/menu' )}</span></li>
</ul>

{section-else}

<ul>
    <li><a href={'/setup/cache/'|ezurl}>{'Cache management'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/class/grouplist/'|ezurl}>{'Classes'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/setup/extensions/'|ezurl}>{'Extensions'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/content/edit/52/'|ezurl}>{'Global settings'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/settings/view'|ezurl}>{'Ini settings'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
<!-- Removed from 3.5. Enable if you have special requirements.   <li><a href={'/notification/runfilter/'|ezurl}>{'Notification'|i18n( 'design/admin/parts/setup/menu' )}</a></li> -->
    <li><a href={'/pdf/list/'|ezurl}>{'PDF export'|i18n( 'design/admin/parts/setup/menu' ,'PDF export')}</a></li>
    <li><a href={'/package/list/'|ezurl}>{'Packages'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/setup/rad/'|ezurl}>{'RAD'|i18n( 'design/admin/parts/setup/menu' ,'Rapid Application Development')}</a></li>
    <li><a href={'/role/list/'|ezurl}>{'Roles and policies'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/rss/list'|ezurl}>{'RSS'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/search/stats/'|ezurl}>{'Search statistics'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/section/list/'|ezurl}>{'Sections'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/setup/session'|ezurl}>{'Sessions'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/setup/info/'|ezurl}>{'System information'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/setup/systemupgrade'|ezurl}>{'Upgrade check'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/content/translations/'|ezurl}>{'Translations'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/trigger/list/'|ezurl}>{'Triggers'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/url/list/'|ezurl}>{'URL management'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/content/urltranslator/'|ezurl}>{'URL translator'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
    <li><a href={'/workflow/grouplist/'|ezurl}>{'Workflows'|i18n( 'design/admin/parts/setup/menu' )}</a></li>
</ul>

{/section}

{* DESIGN: Content END *}</div></div></div></div></div></div>