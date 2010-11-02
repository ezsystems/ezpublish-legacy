#!/bin/bash

export EZC_TEST_INTERACTIVE=1
export EZC_TEST_TEMPLATE_SORT=mtime

phpunit MainSuite tests/main_suite.php
