{let current_memory=$test_result[2].current_memory
     required_memory=$test_result[2].required_memory}

<h3>{$result_number}. {"Insufficient memory allocated to install eZ publish"|i18n("design/standard/setup/tests")}</h3>
<p>{"eZ publish will not work correctly with a memory limit of %1."|i18n("design/standard/setup/tests",,array($current_memory))}
{"It's highly recommended that you fix this."|i18n("design/standard/setup/tests")}</p>
<p>{"Locate the php.ini settings file for your PHP installation. On unix systems, this is normally located at /etc/php.ini, on windows systems check the PHP installation path."|i18n("design/standard/setup/tests")}</p>
{"Open the php.ini file and change the memory_limit value to at least %1, and press %2"|i18n("design/standard/setup/tests",,array($required_memory, "Next"|i18n("design/standard/setup/tests")))}</p>
<p>{"If you are running eZ publish in a shared host environment, contant your ISP to perform the changes"|i18n("design/standard/setup/tests")}</p>

{/let}
