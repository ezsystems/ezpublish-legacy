{set-block variable=subject}
eZ publish {$version.text} {"site registration"|i18n("design/standard/setup")} - {$site_templates.0.title}
{/set-block}
{"Site info:"|i18n("design/standard/setup")}
{section name=SiteTemplate loop=$site_templates }

  {"Template"|i18n("design/standard/setup")} - {$:item.name}
  {"Title"|i18n("design/standard/setup")} - {$:item.title}
  {"URL"|i18n("design/standard/setup")} - {$:item.url}
  {"Access type"|i18n("design/standard/setup")} - {$:item.access_type}
  {"Access value"|i18n("design/standard/setup")} - {$:item.access_type_value}
  {"E-mail"|i18n("design/standard/setup")} - {$:item.email}

{/section}

{"PHP info:"|i18n("design/standard/setup")}
{"Version"|i18n("design/standard/setup")} - {$phpversion.found}

{"OS info:"|i18n("design/standard/setup")}
{"Name"|i18n("design/standard/setup")} - {$os.name}

{"Database info:"|i18n("design/standard/setup")}
{"Type"|i18n("design/standard/setup")} - {$database_info.info.name}
{"Driver"|i18n("design/standard/setup")} - {$database_info.info.driver}
{"Unicode"|i18n("design/standard/setup")} - {section show=$database_info.info.supports_unicode}{"Supported"|i18n("design/standard/setup")}{section-else}{"Unsupported"|i18n("design/standard/setup")}{/section}


{"Demo data:"|i18n("design/standard/setup")}

{section show=$demo_data.use}
{"Demo data was installed."|i18n("design/standard/setup")}
{section-else}
{"Demo data was not installed."|i18n("design/standard/setup")}
{/section}


{"Email info:"|i18n("design/standard/setup")}
{"Transport"|i18n("design/standard/setup")} - {section show=eq($email_info.type,1)}{"sendmail"|i18n("design/standard/setup")}{section-else}{"SMTP"|i18n("design/standard/setup")}{/section}


{"Image conversion:"|i18n("design/standard/setup")}

{section show=$imagemagick_program.result}
{"ImageMagick was found and used."|i18n("design/standard/setup")}
{"Path"|i18n("design/standard/setup")} - {$imagemagick_program.path}
{"Executable"|i18n("design/standard/setup")} - {$imagemagick_program.program}
{/section}


{section show=$imagegd_extension.result}
{"ImageGD extension was found and used."|i18n("design/standard/setup")}
{/section}


{"Regional info:"|i18n("design/standard/setup")}
{"Type"|i18n("design/standard/setup")} - {switch match=$regional_info.language_type}{case match=1}{"Monolingual"|i18n("design/standard/setup")}{/case}{case match=2}{"Multilingual"|i18n("design/standard/setup")}{/case}{case match=3}{"Unicode"|i18n("design/standard/setup")}{/case}{case/}{/switch}

{"Primary"|i18n("design/standard/setup")} - {$regional_info.primary_language}

{section show=$regional_info.languages}
{"Additional"|i18n("design/standard/setup")} - {section name=Language loop=$regional_info.languages}{$:item}{delimiter}, {/delimiter}{/section}
{/section}


{"Critical tests:"|i18n("design/standard/setup")}

{section name=Critical loop=$tests_run}

{$:key} - {section show=$:item}{"Success"|i18n("design/standard/setup")}{section-else}{"Failure"|i18n("design/standard/setup")}{/section}

{/section}


{"Other tests:"|i18n("design/standard/setup")}

{section name=Other loop=$optional_tests}

{$:item[1]} - {section show=eq($:item[0],1)}{"Success"|i18n("design/standard/setup")}{section-else}{"Failure"|i18n("design/standard/setup")}{/section}

{/section}


{"Comments:"|i18n("design/standard/setup")}
{$comments}
