eZSys:
wwwdir='{ezsys( 'wwwdir' )}'
sitedir='{ezsys( 'sitedir' )}'
indexfile='{ezsys( 'indexfile' )}'
indexdir='{ezsys( 'indexdir' )}'

eZHTTP:
ezhttp='{eq( get_class( ezhttp() ), 'ezhttptool' )}'
ezhttp( 'SearchText' )='{ezhttp( 'SearchText' )}'
ezhttp( 'SearchText', 'post' )='{ezhttp( 'SearchText', 'post' )}'
ezhttp( 'SearchText', 'get' )='{ezhttp( 'SearchText', 'get' )}'

eZINI:
ezini( 'ExtensionSettings', 'ExtensionDirectory' )='{ezini( 'ExtensionSettings', 'ExtensionDirectory' )}'
ezini( 'Base', 'meter', 'units.ini' )='{ezini( 'Base', 'meter', 'units.ini' )}'
