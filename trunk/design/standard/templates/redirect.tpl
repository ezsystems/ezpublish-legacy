<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
<head>
    <title>eZ publish redirection - {$redirect_uri}</title>
</head>
<body>
  <form action="{$redirect_uri}" method="post" name="Redirect">
  {"Redirecting to %1"|i18n("design/standard/layout",,array(concat("<b>",$redirect_uri,"</b>")))} <br/>
  <input class="stdbutton" type="submit" Name="RedirectButton" value="{'Redirect'|i18n('design/standard/layout')}"/>
  </form>

<!--DEBUG_REPORT-->

</body>
</html>
