<?php
class PEAR_PackageFile_Generator_PHP5_Common
{
    protected $pf;
    protected $options = array(
            'formatfilelist' => true,
            'flatfilelist' => false,
        );
    public function __construct(PEAR_PackageFile_PHP5_v1 $pf, $options = array())
    {
        $this->pf = $pf;
        $this->options = array_merge($this->options, $options);
    }

    public function toXml()
    {
        if ($this->options['flatfilelist']) {
            $this->pf->flattenFilelist();
        } else {
            $this->pf->recursiveFilelist();
        }
        $this->beautify();
        return $this->pf->dom->saveXml();
    }

    public function beautify()
    {
        $result = $this->pf->dom->createElement('package');
        $package = $this->pf->dom->replaceChild($result, $this->pf->dom->documentElement);
        $this->_recurbeautify($result, $package, '');
    }

    private function _recurbeautify($result, $package, $depth)
    {
        $echo = $haschildnodes = false;
        if ($package->nodeName == 'filelist') {
            if ($this->options['formatfilelist']) {
                $result->parentNode->replaceChild($package, $result);
                return;
            }
            foreach ($package->childNodes as $node) {
                echo $node->nodeName . "\n";
            }
            $echo = true;
        }
        foreach ($package->childNodes as $node) {
            if ($echo) echo $node->nodeName . "\n";
            if ($node->nodeType == XML_COMMENT_NODE) {
                $result->appendChild($this->pf->dom->createTextNode("\n $depth"));
                $result->appendChild($node->cloneNode());
            }
            if ($node->nodeType != XML_ELEMENT_NODE) {
                continue;
            }
            $haschildnodes = true;
            $result->appendChild($this->pf->dom->createTextNode("\n$depth "));
            $newnode = $node->cloneNode();
            $result->appendChild($newnode);
            $text = $this->_getElementText($node);
            foreach ($text as $i => $val) {
                if ($i == count($text) - 1) {
                    if (is_string($val)) {
                        if (count($text) > 1 || strpos($val, "\n")) {
                            $val = trim($val) . "\n$depth ";
                        } else {
                            $val = trim($val);
                        }
                    }
                }
                if (!is_string($val)) {
                    if ($val->nodeType == XML_ELEMENT_NODE) {
                        $another = $val->cloneNode();
                        $newnode->appendChild($this->pf->dom->createTextNode("\n$depth  "));
                        $newnode->appendChild($another);
                        $this->_recurbeautify($another, $val, "$depth  ");
                        if ($i == count($text) - 1) {
                            $newnode->appendChild($this->pf->dom->createTextNode("\n$depth "));
                        }
                    } else {
                        $result->appendChild($val);
                    }
                } else {
                    if (!trim($val)) {
                        continue;
                    }
                    $newnode->appendChild($this->pf->dom->createTextNode($val));
                }
            }
        }
        if (!$haschildnodes) {
            $res = $this->_getElementText($package);
            foreach ($res as $text) {
                if (is_string($text)) {
                    $result->appendChild($this->pf->dom->createTextNode($text));
                } else {
                    $result->appendChild($text);
                }
            }
        } else {
            $result->appendChild($this->pf->dom->createTextNode("\n$depth"));
        }
    }

    private function _getElementText($node)
    {
        $text = array();
        $index = 0;
        foreach ($node->childNodes as $cnode) {
            if ($cnode->nodeType == XML_TEXT_NODE) {
                if (!trim($cnode->nodeValue)) {
                    continue;
                }
                if (!isset($text[$index])) {
                    $text[$index] = '';
                }
                $text[$index] .= $cnode->nodeValue;
            } elseif ($cnode->nodeType == XML_ENTITY_NODE ||
                       $cnode->nodeType == XML_CDATA_SECTION_NODE ||
                       $cnode->nodeType == XML_ENTITY_REF_NODE ||
                       $cnode->nodeType == XML_ELEMENT_NODE) {
                if (isset($text[$index])) {
                    $index++;
                }
                $text[$index] = $cnode;
            }
        }
        return $text;
    }
}
?>