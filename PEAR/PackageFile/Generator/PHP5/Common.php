<?php
class PEAR_PackageFile_Generator_PHP5_Common
{
    protected $pf;
    protected $options = array(
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
            $this->recursiveFilelist();
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
            if (!$this->options['flatfilelist']) {
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

    private function _cloneFile($file, $indent)
    {
        $new = $file->cloneNode(true);
        $remove = array();
        foreach ($new->childNodes as $node) {
            if ($node->nodeType != XML_ELEMENT_NODE) {
                $remove[] = $node;
            }
        }
        foreach ($remove as $node) {
            $new->removeChild($node);
        }
        $kid = $new->firstChild;
        while ($kid) {
            $new->insertBefore($this->pf->dom->createTextNode("\n$indent"), $kid);
            $kid = $kid->nextSibling;
        }
        if ($new->hasChildNodes()) {
            $new->appendChild($this->pf->dom->createTextNode("\n" . substr($indent, 1)));
        }
        return $new;
    }

    public function recursiveFilelist($cmp = null, $depth = '  ')
    {
        $oldfiles = $this->pf->dom->documentElement->getElementsByTagname('file');
        $files = array();
        foreach ($oldfiles as $file) {
            array_unshift($files, $file);
        }
        $d = $this->pf->dom;
        if ($cmp === null) {
            $cmp = $d->documentElement->getElementsByTagname('filelist')->item(0);
        }
        $sx = $d->createElement('filelist');
        $cmp->parentNode->replaceChild($sx, $cmp);
        $sx->appendChild($d->createTextNode("\n$depth"));
        $sx->appendChild($dirnode = $d->createElement('dir'));
        $dirnode->setAttribute('name', '/');
        $sx->appendChild($d->createTextNode("\n$depth"));
        $dirs = $mainfiles = array();
        foreach ($files as $file) {
            $name = explode('/', $file->getAttribute('name'));
            $filename = array_pop($name);
            $path = '';
            if (count($name)) {
                foreach ($name as $i => $dir) {
                    if ($path != '') {
                        $path .= '/';
                    }
                    $path .= $dir;
                    if (!isset($dirs[$path])) {
                        $dirs[$path] = $this->pf->dom->createElement('dir');
                        $dirs[$path]->setAttribute('name', $dir);
                    }
                    if ($i == count($name) - 1) {
                        $newfile = $this->_cloneFile($file, "   $depth" .
                            str_repeat(' ', count($name)));
                        $dirs[$path]->appendChild($this->pf->dom->createTextNode("\n  $depth" .
                            str_repeat(' ', count($name))));
                        $dirs[$path]->appendChild($newfile);
                        $newfile->setAttribute('name', $filename);
                    }
                }
            } else {
                $mainfiles[$file->getAttribute('name')] = $this->_cloneFile($file,
                    '     ');
            }
        }
        uksort($dirs, 'strnatcmp');
        $dirs = array_reverse($dirs);
        $maindirs = array();
        foreach ($dirs as $path => $cdir) {
            if (basename($path) != $path && isset($dirs[dirname($path)])) {
                $cdir->appendChild($this->pf->dom->createTextNode("\n  $depth" .
                        str_repeat(' ', count(explode('/', dirname($path))))));
                $parent = $dirs[dirname($path)];
                $parent->insertBefore($cdir, $parent->firstChild);
                $parent->insertBefore(
                    $this->pf->dom->createTextNode("\n  $depth" .
                        str_repeat(' ', count(explode('/', dirname($path))))),
                    $parent->firstChild);
                $parent->insertBefore(
                    $co = $this->pf->dom->createTextNode(' '),
                    $cdir->nextSibling);
                $parent->insertBefore(
                    $this->pf->dom->createComment($path),
                    $co->nextSibling);
            } else {
                $cdir->appendChild($this->pf->dom->createTextNode("\n  $depth"));
                array_unshift($maindirs, array($path, $cdir));
            }
        }
        foreach ($maindirs as $cdir) {
            $dirnode->appendChild($this->pf->dom->createTextNode("\n  $depth"));
            $dirnode->appendChild($cdir[1]);
            $dirnode->appendChild($this->pf->dom->createTextNode(" "));
            $dirnode->appendChild($this->pf->dom->createComment(" $cdir[0] "));
        }
        uksort($mainfiles, 'strnatcmp');
        foreach ($mainfiles as $file) {
            $dirnode->appendChild($this->pf->dom->createTextNode("\n  $depth"));
            $dirnode->appendChild($file);
        }
        $dirnode->appendChild($this->pf->dom->createTextNode("\n $depth"));
    }
}
?>