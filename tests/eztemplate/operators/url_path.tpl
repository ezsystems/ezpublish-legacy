Default:
{"content/view/full/4"|ezurl}
{"var/cache/images/root.png"|ezroot}
{"stylesheets/core.css"|ezdesign}
{"uml.png"|ezimage}
DoubleQuoted:
{"content/view/full/4"|ezurl( double )}
{"var/cache/images/root.png"|ezroot( double )}
{"stylesheets/core.css"|ezdesign( double )}
{"uml.png"|ezimage( double )}
SingleQuoted:
{"content/view/full/4"|ezurl( single )}
{"var/cache/images/root.png"|ezroot( single )}
{"stylesheets/core.css"|ezdesign( single )}
{"uml.png"|ezimage( single )}
NoQuote:
{"content/view/full/4"|ezurl( no )}
{"var/cache/images/root.png"|ezroot( no )}
{"stylesheets/core.css"|ezdesign( no )}
{"uml.png"|ezimage( no )}
Variable input:

{let url="content/view/full/4"
     root="var/cache/images/root.png"
     design="stylesheets/core.css"
     image="uml.png"}
{$url|ezurl}
{$root|ezroot}
{$design|ezdesign}
{$image|ezimage}
{/let}

