{*
  To be able to define menu entries in ini at the same time as letting i18n
  system translate the names, we use a lookup table for translatable strings.
  If the ini name is not defined here (key), then the ini name value is used.
  For extensions that need to extend this, you either have to override this
  template or let translations be handled by ini overrides instead. 
*}

{include uri='design:parts/ini_menu.tpl' ini_section='Leftmenu_setup' i18n_hash=hash(
    '_setup',              'Setup'|i18n( 'design/admin/parts/setup/menu' ),
    '_cache',              'Cache management'|i18n( 'design/admin/parts/setup/menu' ),
    '_classes',            'Classes'|i18n( 'design/admin/parts/setup/menu' ),
    '_collected',          'Collected information'|i18n( 'design/admin/parts/setup/menu' ),
    '_extensions',         'Extensions'|i18n( 'design/admin/parts/setup/menu' ),
    '_global_setting',     'Global settings'|i18n( 'design/admin/parts/setup/menu' ),
    '_ini',                'Ini settings'|i18n( 'design/admin/parts/setup/menu' ),
    '_languages',          'Languages'|i18n( 'design/admin/parts/setup/menu' ),
    '_notification',       'Notification'|i18n( 'design/admin/parts/setup/menu' ),
    '_pdf_export',         'PDF export'|i18n( 'design/admin/parts/setup/menu', 'PDF export'),
    '_packages',           'Packages'|i18n( 'design/admin/parts/setup/menu' ),
    '_rad',                'RAD'|i18n( 'design/admin/parts/setup/menu', 'Rapid Application Development'),
    '_roles_and_policies', 'Roles and policies'|i18n( 'design/admin/parts/setup/menu' ),
    '_rss',                'RSS'|i18n( 'design/admin/parts/setup/menu' ),
    '_search_statistics',  'Search statistics'|i18n( 'design/admin/parts/setup/menu' ),
    '_sections',           'Sections'|i18n( 'design/admin/parts/setup/menu' ),
    '_states',             'States'|i18n( 'design/admin/parts/setup/menu' ),
    '_sessions',           'Sessions'|i18n( 'design/admin/parts/setup/menu' ),
    '_system_information', 'System information'|i18n( 'design/admin/parts/setup/menu' ),
    '_upgrade_check',      'Upgrade check'|i18n( 'design/admin/parts/setup/menu' ),
    '_triggers',           'Triggers'|i18n( 'design/admin/parts/setup/menu' ),
    '_url_management',     'URL management'|i18n( 'design/admin/parts/setup/menu' ),
    '_url_translator',     'URL translator'|i18n( 'design/admin/parts/setup/menu' ),
    '_url_wildcards',      'URL wildcards'|i18n( 'design/admin/parts/setup/menu' ),
    '_workflows',          'Workflows'|i18n( 'design/admin/parts/setup/menu' ),
    '_workflow_processes', 'Workflow processes'|i18n( 'design/admin/parts/setup/menu' ),
    '_look_and_feel',      'Look and feel'|i18n( 'design/admin/parts/visual/menu' ),
    '_menu_management',    'Menu management'|i18n( 'design/admin/parts/visual/menu' ),
    '_toolbar_management', 'Toolbar management'|i18n( 'design/admin/parts/visual/menu' ),
    '_templates',    'Templates'|i18n( 'design/admin/parts/visual/menu' ),
)}