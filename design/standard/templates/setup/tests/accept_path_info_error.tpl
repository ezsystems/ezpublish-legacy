<h3>{$result_number}. {"AcceptPathInfo disabled"|i18n("design/standard/setup/tests")}</h3>
<p>
  {"You need enable AcceptPathInfo in your Apache config file."|i18n("design/standard/setup/tests")}
</p>
<p>
  Check the Apache <a
  href="http://httpd.apache.org/docs-2.0/mod/core.html">documentation</a>,
  or {"enter the following into your httpd.conf file."|i18n("design/standard/setup/tests")}
</p>
{literal}
  AcceptPathInfo On
{/literal}
<p>
  {"Remember to restart your web server afterwards."|i18n("design/standard/setup/tests")}
</p>