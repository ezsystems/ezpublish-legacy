Language Switcher
=================

The language switcher is used to switch from viewing one translation of a site
to another. This is today done by changing to another translation siteaccess
(We are not talking about using the locale user param here).

**Problem**: Today this poses a challenge with the multilingual url alias system
where the request uri cannot be reused in another translation site access, as
the URL will be different there, that is, translated into another language, for
a specific node.

Scope
-----

Several approaches are listed below. The first one, shows an example where the
URLs are virtually created manually, only helped by the URL transformation.
The later approaches show a much more automated approach, where the system
automatically generates all the required data for the user.

Both approaches have merit, in the sense, that for certain setups full control
on how URLs look like are needed. In many other cases, ease of use is more
important.

This feature will be implemented, in at least two phases.

Phase 1
~~~~~~~

In the first phase
focus is on extending the API of eZURLAliasML, so that an URL alias can be
fetched for user selected locales. The second part of this phase, is to create
a module, which a developer can use in templates, to redirect to the
destination, namely the other translation siteaccess with a translated URL alias.

A module is useful in this setting, as it allows for the most efficient lookup
of data, only when they are needed. This avoids extra queries, and
potential superfluous caches being required per page. After you would need one
unique link per URL, per translation siteaccess otherwise. This we can avoid
with a simple module.

Phase 1.5
~~~~~~~~~

A method to enquire URL aliases depending on translation, should be
added to the templates as well. Being able to refer to URLs in different
translations is a need many multilingual sites share.

Phase 2
~~~~~~~

The final phase will seek to automate the process of setting up links to
translation siteaccesses, so that the there will be less manual input required
(ref. the translation setup in eZWebin today). An example could be seen in
`Approach 3`_.

Approach 1
~~~~~~~~~~

::

    {if $curr_siteaccess|eq( "eng")}
        <span>English</span>
    {else}
        <a href=concat( "/switchlanguage/to/", "eng/", choose( $module_result.node_id, $site.url ))>English</a>
    {/if}


Approach 2
~~~~~~~~~~

::

    {def $class="lang"}
    {foreach $languages as $lang}
        {set $class="lang"}
        {if $current_siteaccess|eq( $lang.sa )}
            {set $class="selected"}
        {/if}
        <a class="{$lang}" href=lang_url( $module_result.node_id, {$lang.sa}, {$lang.locale} ) )|ezurl>{$lang.text}</a>
    {/foreach}
    

Approach 3
~~~~~~~~~~

::

    {def $langSwitcher=switchLang()}

    {foreach $langSwitcher as $lang}
        <a href={$lang.url}>{$lang.text}</a>
    {/foreach}


Updates to eZURLAliasML
-----------------------

Introduce a new parameter *$locale* in relevant methods. When this new param
is omitted the current behaviour of the methods is triggered. If the new param
is provided then results filtered for that particularly locale will be
considered.

Updated methods:
~~~~~~~~~~~~~~~~

**eZURLAliasML:**

- fetchByAction

  For this method the already existing parameter *$maskLanguages* have been
  reused. For boolean values the old logic will be triggered, for string
  values entries will be filtered with the locale's language mask.
  
  *$onlyprioritized* will not have any effect, when a locale is specified in
  *$maskLanguages*.

- getPath

  New $locale parameter introduced. When $locale is used, it will override the
  default filtering in the choosePriortizedRow() method.

- fetchPathByActionList

  $locale parameter added, with fallback to normal behaviour.

Methods to be considered:
~~~~~~~~~~~~~~~~~~~~~~~~~

- fetchByPath

  Can be skipped since, the method here is fetching an eZURLAliasML object for
  a given path.

- fetchNodeIDByPath

  Can also be skipped, locale will follow implicitly from the given path here.

- fetchByParentID

  Can be skipped. The idea is not fetching arbitrary URL elements of specified
  locale, but to provide means to fetch selected URL aliases in different
  translations/locales.

- getChildren

  Skipped, see comment for fetchByParentID

**eZContentObjectTreeNode: (might be considered)**

- urlAlias
- pathWithNames

Tasks
-----

- Calculate siteaccess url for given SA

  This is complicated by the fact that there is no good way to calculate full
  URLs for siteaccess, espcially across different matching rules.

- Translate request URL to URL in desired translation

  Done via updated API.

- redirect to final destination url SA

- Setup the mapping between SA and translation locale.
  Used for automatic aproaches such as listed in `Approach 3`_

- Check for problems in dependent classes after updates
  - eZURLAliasML
  - eZURLAliasQuery
  - eZPathElement
  
Tweaks
------

It's possible to do some optimisations. One is to *node id* where available
from templates directly, this will save a lookup of the path/ request uri.

(Update: this is now done)
         
Limitations
-----------

Initially only a subset of the possible site access types will be supported,
namely *URI* and *host*.
