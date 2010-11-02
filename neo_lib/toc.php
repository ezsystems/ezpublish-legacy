<?php

class ezpTOC
{
    /**
     * Keeps track of header values.
     * @var array(int => int)
     */
    private $HeaderCounter = array();

    /**
     * Keeps track of last header level.
     * @var int
     */
    private $LastHeaderLevel = 0;

    /**
     * The content attribute ID which is being processed.
     * @var int
     */
    public $ObjectAttributeId;

    /**
     * The content attribute to generate a TOC for.
     * @var eZContentObjectAttribute
     */
    public $Attribute;

    public function __construct( $attr )
    {
        if ( !$attr instanceof eZContentObjectAttribute )
        {
            throw new Exception( "Invalid type for \$attr parameter, must be of type eZContentObjectAttribute" );
        }
        $this->Attribute = $attr;
    }

    /**
     * Generates the TOC for the attribute as HTML and returns it.
     * @return string
     */
    public function generate()
    {
        $this->ObjectAttributeId = $this->Attribute->id;
        $content = $this->Attribute->content;
        $xmlData = $content->xml_data;

        $domTree = new DOMDocument( '1.0', 'utf-8' );
        $domTree->preserveWhiteSpace = false;
        $success = $domTree->loadXML( $xmlData );

        $tocText = '';
        if ( $success )
        {
            $this->HeaderCounter = array();
            $this->LastHeaderLevel = 0;

            $rootNode = $domTree->documentElement;
            $tocText .= $this->handleSection( $rootNode );

            while ( $this->LastHeaderLevel > 0 )
            {
                $tocText .= "</li>\n</ul>\n";
                $this->LastHeaderLevel--;
            }
        }
        return $tocText;
    }

    private function handleSection( $sectionNode, $level = 0 )
    {
        // Reset next level counter
        $this->HeaderCounter[$level + 1] = 0;

        $tocText = '';
        $children = $sectionNode->childNodes;
        foreach ( $children as $child )
        {
            if ( $child->nodeName == 'section' )
            {
                $tocText .= $this->handleSection( $child, $level + 1 );
            }

            if ( $child->nodeName == 'header' )
            {
                if ( $level > $this->LastHeaderLevel )
                {
                    while ( $level > $this->LastHeaderLevel )
                    {
                        $tocText .= "\n<ul><li>";
                        $this->LastHeaderLevel++;
                    }
                }
                elseif ( $level == $this->LastHeaderLevel )
                {
                    $tocText .= "</li>\n<li>";
                }
                else
                {
                    $tocText .= "</li>\n";
                    while ( $level < $this->LastHeaderLevel )
                    {
                        $tocText .= "</ul></li>\n";
                        $this->LastHeaderLevel--;
                    }
                    $tocText .= "<li>";
                }
                $this->LastHeaderLevel = $level;

                $this->HeaderCounter[$level] += 1;
                $i = 1;
                $headerAutoName = "";
                while ( $i <= $level )
                {
                    if ( $i > 1 )
                        $headerAutoName .= "_";

                    $headerAutoName .= $this->HeaderCounter[$i];
                    $i++;
                }
                $tocText .= '<a href="#eztoc' . $this->ObjectAttributeId . '_' . $headerAutoName . '">' . $child->textContent . '</a>';
            }
        }

        return $tocText;
    }
}

?>
