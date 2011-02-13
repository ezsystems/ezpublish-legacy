{*
  To be able to define menu entries in ini at the same time as letting i18n
  system translate the names, we use a lookup table for translatable strings.
  If the ini name is not defined here (key), then LinkNames ini value is used.
  For extensions that need to extend this, you either have to override this
  template or let translations use LinkNames as described in menu.ini.
*}

{include uri='design:parts/ini_menu.tpl' ini_section='Leftmenu_setup' i18n_hash=hash(
    'setup',              'Setup'|i18n( 'design/admin/parts/setup/menu' ),
    'cache',              'Cache management'|i18n( 'design/admin/parts/setup/menu' ),
    'classes',            'Classes'|i18n( 'design/admin/parts/setup/menu' ),
    'collected',          'Collected information'|i18n( 'design/admin/parts/setup/menu' ),
    'extensions',         'Extensions'|i18n( 'design/admin/parts/setup/menu' ),
    'global_setting',     'Global settings'|i18n( 'design/admin/parts/setup/menu' ),
    'ini',                'Ini settings'|i18n( 'design/admin/parts/setup/menu' ),
    'languages',          'Languages'|i18n( 'design/admin/parts/setup/menu' ),
    'notification',       'Notification'|i18n( 'design/admin/parts/setup/menu' ),
    'pdf_export',         'PDF export'|i18n( 'design/admin/parts/setup/menu', 'PDF export'),
    'packages',           'Packages'|i18n( 'design/admin/parts/setup/menu' ),
    'rad',                'RAD'|i18n( 'design/admin/parts/setup/menu', 'Rapid Application Development'),
    'roles_and_policies', 'Roles and policies'|i18n( 'design/admin/parts/setup/menu' ),
    'rss',                'RSS'|i18n( 'design/admin/parts/setup/menu' ),
    'search_statistics',  'Search statistics'|i18n( 'design/admin/parts/setup/menu' ),
    'sections',           'Sections'|i18n( 'design/admin/parts/setup/menu' ),
    'states',             'States'|i18n( 'design/admin/parts/setup/menu' ),
    'sessions',           'Sessions'|i18n( 'design/admin/parts/setup/menu' ),
    'system_information', 'System information'|i18n( 'design/admin/parts/setup/menu' ),
    'upgrade_check',      'Upgrade check'|i18n( 'design/admin/parts/setup/menu' ),
    'triggers',           'Triggers'|i18n( 'design/admin/parts/setup/menu' ),
    'url_management',     'Link management'|i18n( 'design/admin/parts/setup/menu' ),
    'url_translator',     'URL translator'|i18n( 'design/admin/parts/setup/menu' ),
    'url_wildcards',      'URL wildcards'|i18n( 'design/admin/parts/setup/menu' ),
    'workflows',          'Workflows'|i18n( 'design/admin/parts/setup/menu' ),
    'workflow_processes', 'Workflow processes'|i18n( 'design/admin/parts/setup/menu' ),
    'look_and_feel',      'Look and feel'|i18n( 'design/admin/parts/visual/menu' ),
    'menu_management',    'Menu management'|i18n( 'design/admin/parts/visual/menu' ),
    'toolbar_management', 'Toolbar management'|i18n( 'design/admin/parts/visual/menu' ),
    'templates',    'Templates'|i18n( 'design/admin/parts/visual/menu' ),
)}

{* Left menu width control. *}
<div id="widthcontrol-links" class="widthcontrol">
<p>
{switch match=ezpreference( 'admin_left_menu_size' )}
    {case match='medium'}
    <a href={'/user/preferences/set/admin_left_menu_size/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/user/menu' )}">{'Small'|i18n( 'design/admin/parts/user/menu' )}</a>
    <span class="current">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</span>
    <a href={'/user/preferences/set/admin_left_menu_size/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/user/menu' )}">{'Large'|i18n( 'design/admin/parts/user/menu' )}</a>
    {/case}

    {case match='large'}
    <a href={'/user/preferences/set/admin_left_menu_size/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/user/menu' )}">{'Small'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/user/menu' )}">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</a>
    <span class="current">{'Large'|i18n( 'design/admin/parts/user/menu' )}</span>
    {/case}

    {case in=array( 'small', '' )}
    <span class="current">{'Small'|i18n( 'design/admin/parts/user/menu' )}</span>
    <a href={'/user/preferences/set/admin_left_menu_size/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/user/menu' )}">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/user/menu' )}">{'Large'|i18n( 'design/admin/parts/user/menu' )}</a>
    {/case}

    {case}
    <a href={'/user/preferences/set/admin_left_menu_size/small'|ezurl} title="{'Change the left menu width to small size.'|i18n( 'design/admin/parts/user/menu' )}">{'Small'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/medium'|ezurl} title="{'Change the left menu width to medium size.'|i18n( 'design/admin/parts/user/menu' )}">{'Medium'|i18n( 'design/admin/parts/user/menu' )}</a>
    <a href={'/user/preferences/set/admin_left_menu_size/large'|ezurl} title="{'Change the left menu width to large size.'|i18n( 'design/admin/parts/user/menu' )}">{'Large'|i18n( 'design/admin/parts/user/menu' )}</a>
    {/case}
{/switch}
</p>
</div>

{* This is the border placed to the left for draging width, js will handle disabling the one above and enabling this *}
<div id="widthcontrol-handler" class="hide">
<div class="widthcontrol-grippy"></div>
</div>
