<?php
//
// Definition of eZTemplate test suite
//
// Created on: <30-Jan-2004 11:53:06 >
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

$SuiteDefinition = array( 'name' => 'eZTemplate',
                          'tests' => array() );

$SuiteDefinition['tests'][] = array( 'name' => 'OutputHandling',
                                     'file' => 'eztesttemplateoutput.php',
                                     'class' => 'eZTestTemplateOutput' );

$SuiteDefinition['tests'][] = array( 'name' => 'Operator',
                                     'file' => 'eztesttemplateoperator.php',
                                     'class' => 'eZTestTemplateOperator' );

$SuiteDefinition['tests'][] = array( 'name' => 'ProcessedOperator',
                                     'file' => 'eztestprocessedtemplateoperator.php',
                                     'class' => 'eZTestProcessedTemplateOperator' );

$SuiteDefinition['tests'][] = array( 'name' => 'Function',
                                     'file' => 'eztesttemplatefunction.php',
                                     'class' => 'eZTestTemplateFunction' );

$SuiteDefinition['tests'][] = array( 'name' => 'ProcessedFunction',
                                     'file' => 'eztestprocessedtemplatefunction.php',
                                     'class' => 'eZTestProcessedTemplateFunction' );

?>
