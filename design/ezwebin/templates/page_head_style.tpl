{if is_unset( $load_css_file_list )}
    {def $load_css_file_list = true()}
{/if}

{if $load_css_file_list}
  {ezcss_load( array( 'core.css',
                      'debug.css',
                      'pagelayout.css',
                      'content.css',
                      'websitetoolbar.css',
                      ezini( 'StylesheetSettings', 'CSSFileList', 'design.ini' ),
                      ezini( 'StylesheetSettings', 'FrontendCSSFileList', 'design.ini' ) ))}
{else}
  {ezcss_load( array( 'core.css',
                      'debug.css',
                      'pagelayout.css',
                      'content.css',
                      'websitetoolbar.css' ))}
{/if}

<link rel="stylesheet" type="text/css" href={ezini('StylesheetSettings','ClassesCSS','design.ini')|ezroot()} />
<link rel="stylesheet" type="text/css" href={ezini('StylesheetSettings','SiteCSS','design.ini')|ezroot()} />
<link rel="stylesheet" type="text/css" href={"stylesheets/print.css"|ezdesign} media="print" />
<!--[if lte IE 7]><style type="text/css"> @import url({"stylesheets/browsers/ie7lte.css"|ezdesign(no)});</style> <![endif]-->