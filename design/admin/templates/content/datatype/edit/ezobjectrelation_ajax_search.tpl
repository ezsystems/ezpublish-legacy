{run-once}
{if ezmodule( 'ezjscore' )}{* Make sure ezjscore is installed before we try to enable the search code *}
<input type="hidden" id="ezobjectrelation-search-published-text" value="{'Yes'|i18n( 'design/standard/content/datatype' )}" />
<p id="ezobjectrelation-search-empty-result-text" class="hide ezobjectrelation-search-empty-result">{'No results were found when searching for "%1"'|i18n("design/standard/content/search",,array( '--search-string--' ))}</p>
{ezscript_require( array( 'ezjsc::jquery', 'ezjsc::jqueryio', 'ezajaxrelations_jquery.js' ) )}
{/if}
{/run-once}