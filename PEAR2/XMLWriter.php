<?php
/**
 * Process an array, and serialize it into XML
 * @package PEAR_SimpleChannelServer
 * @subpackage XML
 */
class PEAR2_XMLWriter
{
    function toString($array)
    {
        $w = new XMLWriter;
        $w->openMemory();
        return $this->_serializeArray($array, $w);
    }

    function toFile($array, $file)
    {
        $w = new XMLWriter;
        $w->openUri($file);
        return $this->_serializeArray($array, $w);
    }

    private function _serializeArray($array, $w)
    {
        $namespaces = array();
        $stackPointer = 0;
        $namespacesStack = array();
        $w->setIndent(true);
        $w->setIndentString(' ');
        $w->startDocument('1.0', 'UTF-8');
        if (count($array) != 1) {
            throw new PEAR2_XMLWriter_Exception('Cannot serialize array to' .
                'XML, array must have exactly 1 element');
        }
        $depth = $curdepth = 0;
        $attribs = false;
        foreach ($i = new RecursiveIteratorIterator(new RecursiveArrayIterator($array),
                     RecursiveIteratorIterator::SELF_FIRST) as $key => $values) {
            if ($key == '_content') {
                $w->text($values);
                continue;
            }
            $curdepth = $i->getDepth();
            if ($curdepth != $depth) {
                if ($curdepth < $depth) {
                    for (; $depth != $curdepth; $depth--) {
                        $w->endElement();
                        if (isset($namespacesStack[$depth])) {
                            // restore previous namespaces/alias association
                            $namespaces = $namespacesStack[$depth];
                            unset($namespacesStack[$depth]);
                        }
                    }
                } elseif (!$attribs) {
                    $depth = $curdepth;
                }
            } elseif ($attribs) {
                $attribs = false;
            }
            if ($attribs) {
                // xmlwriter converts these to &#10; and &#13;.  Bad.
                $values = str_replace(array("\n","\r"), array('', ''), $values);
                if (strpos($key, ':')) {
                    // namespaced
                    list($ns, $attr) = explode(':', $key);
                    if ($ns == 'xmlns') {
                        // new namespace declaration
                        if (isset($namespaces[$attr]) && !isset($namespacesStack[$depth])) {
                            // save the current namespace, will restore
                            // at element end
                            $namespacesStack[$depth] = $namespaces;
                        }
                        $namespaces[$attr] = $values;
                        $w->writeAttribute($key, $values);
                    } else {
                        $w->writeAttributeNS($ns, $attr, $namespaces[$ns], $values);
                    }
                } else { // default namespace
                    $w->writeAttribute($key, $values);
                }
                continue;
            }
            if ($key === 'attribs') {
                // attributes
                $attribs = true;
                continue;
            }
            $depth = $curdepth;
            // new element
            if (strpos($key, ':')) {
                // namespaced element
                list($ns, $element) = explode(':', $key);
                if (is_string($values)) {
                    $w->writeElementNs($ns, $element, $namespaces[$ns], $values);
                } else {
                    $w->startElementNs($ns, $element, $namespaces[$ns]);
                }
            } else {
                if (is_string($values)) {
                    $w->writeElement($key, $values);
                } else {
                    $w->startElement($key);
                }
            }
        }
        $w->endDocument();
        return $w->flush();
    }
}