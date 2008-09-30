<?php
/**
 * File containing the ezpLoremIpsum class
 *
 * @copyright Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/gnu_gpl GNU GPLv2
 * @package tests
 */

PHPUnit_Util_Filter::addFileToFilter( __FILE__ );

class ezpLoremIpsum
{

    private static $dictionary = array(
                                        'a', 'ab', 'ac', 'accumsan', 'ad', 'adipiscing', 'aenean', 'aliquam',
                                        'aliquet', 'amet', 'ante', 'aptent', 'arcu', 'as', 'at', 'auctor', 'augue',
                                        'bibendum', 'blandit', 'class', 'commodo', 'condimentum', 'congue',
                                        'consectetuer', 'consequat', 'conubia', 'convallis', 'cras', 'crlorem',
                                        'cubilia', 'cum', 'curabitur', 'curae', 'cursus', 'dapibus', 'diam',
                                        'dictum', 'dictumst', 'dignissim', 'dis', 'dolor', 'donec', 'dui', 'duis',
                                        'egestas', 'eget', 'eleifend', 'elementum', 'elit', 'enim', 'erat', 'eros',
                                        'est', 'et', 'etiam', 'eu', 'euismod', 'facilisi', 'facilisis', 'fames',
                                        'faucibus', 'felis', 'fermentum', 'feugiat', 'fringilla', 'fusce',
                                        'gravida', 'habitant', 'habitasse', 'hac', 'hendrerit', 'hymenaeos',
                                        'iaculis', 'id', 'imperdiet', 'in', 'inceptos', 'integer', 'interdum',
                                        'ipsum', 'justo', 'lacinia', 'lacus', 'laoreet', 'lectus', 'leo', 'libero',
                                        'ligula', 'litora', 'lobortis', 'lorem', 'luctus', 'maecenas', 'magna',
                                        'magnis', 'malesuada', 'massa', 'mattis', 'mauris', 'metus', 'mi',
                                        'molestie', 'mollis', 'montes', 'morbi', 'mus', 'nam', 'nascetur',
                                        'natoque', 'nec', 'neque', 'netus', 'nibh', 'nisi', 'nisl', 'non',
                                        'nonummy', 'nostra', 'nulla', 'nullam', 'nunc', 'odio', 'orci', 'ornare',
                                        'parturient', 'pede', 'pellentesque', 'penatibus', 'per', 'pharetra',
                                        'phasellus', 'placerat', 'platea', 'porta', 'porttitor', 'posuere',
                                        'potenti', 'praesent', 'pretium', 'primis', 'proin', 'pulvinar', 'purus',
                                        'quam', 'quis', 'quisque', 'rhoncus', 'ridiculus', 'risus', 'rutrum',
                                        'sagittis', 'sapien', 'scelerisque', 'sed', 'sem', 'semper', 'senectus',
                                        'sit', 'sociis', 'sociosqu', 'sodales', 'sollicitudin', 'suscipit',
                                        'suspendisse', 'taciti', 'tellus', 'tempor', 'tempus', 'tincidunt',
                                        'torquent', 'tortor', 'tristique', 'turpis', 'ullamcorper', 'ultrices',
                                        'ultricies', 'urna', 'ut', 'varius', 'vehicula', 'vel', 'velit',
                                        'venenatis', 'vestibulum', 'vitae', 'vivamus', 'viverra', 'volutpat',
                                        'vulputate'
                                    );


    public static function generateWord()
    {
        $dictionary = self::$dictionary;
        $dictionarySize = count( $dictionary );
        $randomIndex = mt_rand( 0, $dictionarySize - 1 );
        return $dictionary[$randomIndex];
    }

    public static function generateString( $minWords = 4, $maxWords = 6, $capitalize = true )
    {
        $string = '';
        $numberOfWords = mt_rand( $minWords, $maxWords );
        for ( $index = 0; $index < $numberOfWords; $index++ )
        {
            $word = ezpLoremIpsum::generateWord();
            if ( !$string )
            {
                if ( $capitalize )
                {
                    $string = ucfirst( $word );
                }
                else
                {
                    $string = $word;
                }
            }
            else
            {
                $string .= ' '.$word;
            }
        }

        return $string;
    }

    public static function generateSentence()
    {
        $sentence = '';

        // TODO: make parameters be configurable
        // with probablity of 0.8 the sentence will be single
        $numberOfParts = ( mt_rand( 0, 100 ) < 80 )? 1: 2;
        for( $part = 0; $part < $numberOfParts; $part++ )
        {
            // single sentence has from 5 to 9 words
            $sentence .= ezpLoremIpsum::generateString( 5, 9, ( $part == 0 )? true: false );
            if ( $part < $numberOfParts - 1 )
            {
                $sentence .= ', ';
            }
        }
        // 10% of sentences wil be finished with exclamation mark
        $sentence .= ( mt_rand( 0, 100 ) < 90 )? '.': '!';

        return $sentence;
    }

    /*!
     Create objects

     \param $parameters.

     \returm $parameters, with updated counts.
    */
    public static function createObjects( $parameters )
    {
        if ( !isset( $parameters['structure'] ) )
        {
            $parameters['structure'] = array();
            $totalCount = 0;
            $count = $parameters['count'];

            foreach ( $parameters['nodes'] as $nodeID )
            {
                $nodeID = ( int ) $nodeID;
                if ( $nodeID )
                {
                    $parameters['structure'][$nodeID] = $count;
                    $totalCount += $count;
                }
            }

            $parameters['total_count'] = $totalCount;
            $parameters['created_count'] = 0;
            $parameters['start_time'] = time();
        }

        $cli = false;
        if ( isset( $parameters['cli'] ) && $parameters['cli'] )
            $cli = $parameters['cli'];

        $classID = $parameters['class'];

        if ( !$class = eZContentClass::fetch( $classID ) )
        {
            // TODO
            return;
        }

        if ( !$attributes = eZContentClassAttribute::fetchListByClassID( $classID, eZContentClass::VERSION_STATUS_DEFINED, false ) )
        {
            // TODO
            return;
        }

        foreach ( $attributes as $attribute )
        {
            if ( $attribute['is_required'] && !isset( $parameters['attributes'][$attribute['id']] ) )
            {
                // TODO
                return;
            }
        }

        $db = eZDB::instance();
        $db->setIsSQLOutputEnabled( false );

        $startTime = time();
        foreach ( array_keys( $parameters['structure'] ) as $nodeID )
        {
            $node = eZContentObjectTreeNode::fetch( $nodeID );
            if ( !$node )
            {
                // TODO
                continue;
            }
            if ( isset( $parameters['quick'] ) && $parameters['quick'] )
            {
                $parentObject = $node->attribute( 'object' );
                $sectionID = $parentObject->attribute( 'section_id' );
            }
            while ( $parameters['structure'][$nodeID] > 0 )
            {
                // create object
                $object = $class->instantiate();
                if ( $object )
                {
                    $db->begin();

                    $objectID = $object->attribute( 'id' );

                    if ( $cli )
                        $cli->output( "Creating object #$objectID" );

                    $nodeAssignment = eZNodeAssignment::create( array( 'contentobject_id' => $objectID,
                                                                       'contentobject_version' => 1,
                                                                       'parent_node' => $nodeID,
                                                                       'is_main' => 1 ) );
                    $nodeAssignment->store();
                    $dataMap = $object->dataMap();

                    foreach( array_keys( $dataMap ) as $key )
                    {
                        $attribute = $dataMap[$key];
                        $classAttributeID = $attribute->attribute( 'contentclassattribute_id' );
                        if ( isset( $parameters['attributes'][$classAttributeID] ) )
                        {
                            $attributeParameters = $parameters['attributes'][$classAttributeID];
                            $dataType = $attribute->attribute( 'data_type_string' );

                            switch ( $dataType )
                            {
                                case 'ezstring':
                                {
                                    $attribute->setAttribute( 'data_text',
                                                              eZLoremIpsum::generateString( $attributeParameters['min_words'], $attributeParameters['max_words'] ) );
                                } break;

                                case 'ezxmltext':
                                {
                                    $xml = '<?xml version="1.0" encoding="utf-8"?>'."\n".
                                           '<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"'."\n".
                                           '         xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"'."\n".
                                           '         xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">'."\n".
                                           '  <section>'."\n";
                                    $numPars = mt_rand( ( int ) $attributeParameters['min_pars'], ( int ) $attributeParameters['max_pars'] );
                                    for ( $par = 0; $par < $numPars; $par++ )
                                    {
                                        $xml .= '    <paragraph>';
                                        $numSentences = mt_rand( ( int ) $attributeParameters['min_sentences'], ( int ) $attributeParameters['max_sentences'] );
                                        for ( $sentence = 0; $sentence < $numSentences; $sentence++ )
                                        {
                                            if ( $sentence != 0 )
                                            {
                                                $xml .= ' ';
                                            }
                                            $xml .= eZLoremIpsum::generateSentence();
                                        }
                                        $xml .= "</paragraph>\n";
                                    }
                                    $xml .= "  </section>\n</section>\n";

                                    $attribute->setAttribute( 'data_text', $xml );
                                } break;

                                case 'eztext':
                                {
                                    $numPars = mt_rand( ( int ) $attributeParameters['min_pars'], ( int ) $attributeParameters['max_pars'] );
                                    for ( $par = 0; $par < $numPars; $par++ )
                                    {
                                        if ( $par == 0 )
                                        {
                                            $text = '';
                                        }
                                        else
                                        {
                                            $text .= "\n";
                                        }
                                        $numSentences = mt_rand( ( int ) $attributeParameters['min_sentences'], ( int ) $attributeParameters['max_sentences'] );
                                        for ( $sentence = 0; $sentence < $numSentences; $sentence++ )
                                        {
                                            $text .= eZLoremIpsum::generateSentence();
                                        }
                                        $text .= "\n";
                                    }

                                    $attribute->setAttribute( 'data_text', $text );
                                } break;

                                case 'ezboolean':
                                {
                                    $rnd = mt_rand( 0, 99 );
                                    $value = 0;
                                    if ( $rnd < $attributeParameters['prob'] )
                                    {
                                        $value = 1;
                                    }

                                    $attribute->setAttribute( 'data_int', $value );
                                } break;

                                case 'ezinteger':
                                {
                                    $integer = mt_rand( ( int ) $attributeParameters['min'], ( int ) $attributeParameters['max'] );
                                    $attribute->setAttribute( 'data_int', $integer );
                                } break;

                                case 'ezfloat':
                                {
                                    $power = 100;
                                    $float = mt_rand( $power * ( int ) $attributeParameters['min'], $power * ( int ) $attributeParameters['max'] );
                                    $float = $float / $power;
                                    $attribute->setAttribute( 'data_float', $float );
                                } break;

                                case 'ezprice':
                                {
                                    $power = 10;
                                    $price = mt_rand( $power * ( int ) $attributeParameters['min'], $power * ( int ) $attributeParameters['max'] );
                                    $price = $price / $power;
                                    $attribute->setAttribute( 'data_float', $price );
                                } break;

                                case 'ezuser':
                                {
                                    $user = $attribute->content();
                                    if ( $user === null )
                                    {
                                        $user = eZUser::create( $objectID );
                                    }

                                    $user->setInformation( $objectID,
                                                           md5( mktime() . '-' . mt_rand() ),
                                                           md5( mktime() . '-' . mt_rand() ) . '@ez.no',
                                                           'publish',
                                                           'publish' );
                                    $user->store();
                                } break;

                                case 'ezuser':
                                {
                                    $user = $attribute->content();
                                    if ( $user === null )
                                    {
                                        $user = eZUser::create( $objectID );
                                    }

                                    $user->setInformation( $objectID,
                                                           md5( mktime . '-' . mtrand() ),
                                                           md5( mktime . '-' . mtrand() ) . '@ez.no',
                                                           'publish',
                                                           'publish' );
                                    $user->store();
                                } break;
                            }

                            $attribute->store();
                        }
                    }

                    if ( isset( $parameters['quick'] ) && $parameters['quick'] )
                    {
                        $version = $object->version( 1 );

                        $version->setAttribute( 'status', 3 );
                        $version->store();

                        $object->setAttribute( 'status', 1 );
                        $objectName = $class->contentObjectName( $object );

                        $object->setName( $objectName, 1 );
                        $object->setAttribute( 'current_version', 1 );
                        $time = time();
                        $object->setAttribute( 'modified', $time );
                        $object->setAttribute( 'published', $time );
                        $object->setAttribute( 'section_id', $sectionID );
                        $object->store();

                        $newNode = $node->addChild( $objectID, true );
                        $newNode->setAttribute( 'contentobject_version', 1 );
                        $newNode->setAttribute( 'contentobject_is_published', 1 );
                        $newNode->setName( $objectName );
                        $newNode->setAttribute( 'main_node_id', $newNode->attribute( 'node_id' ) );
                        $newNode->setAttribute( 'sort_field', $nodeAssignment->attribute( 'sort_field' ) );
                        $newNode->setAttribute( 'sort_order', $nodeAssignment->attribute( 'sort_order' ) );

                        $newNode->updateSubTreePath();
                        $newNode->store();

                        $db->commit();
                    }
                    else
                    {
                        $db->commit();
                        if ( !eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $objectID, 'version' => 1 ) ) )
                        {
                            // TODO:
                            // add to the list of errors
                        }
                    }
                }
                else
                {
                    // TODO:
                    // add to the list of errors
                }

                $parameters['structure'][$nodeID]--;
                $parameters['created_count']++;

                if ( !$cli && ( time() - $startTime > 15 ) )
                {
                    break;
                }
            }
        }

        if ( isset( $parameters['quick'] ) && $parameters['quick'] )
        {
            include_once( 'kernel/classes/ezcontentcachemanager.php' );
            eZContentCacheManager::clearAllContentCache();
        }

        $parameters['used_time'] = time() - $parameters['start_time'];
        return $parameters;
    }
}

?>
