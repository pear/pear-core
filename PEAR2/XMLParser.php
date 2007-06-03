<?php
/**
 * Process an XML file, convert it to an array
 * @package PEAR_SimpleChannelServer
 * @subpackage XML
 */
class PEAR2_XMLParser
{
    /**
     * Recursively merge in new XML values
     *
     * @param array $arr
     * @param array $depth array of tag names in depth-last order
     * @param mixed $value the content to merge in
     */
    static function mergeValue(&$arr, $depth, $value)
    {
        if (!count($depth)) {
            if (is_string($arr) && strlen($arr)) {
                $arr = array('_content' => $arr);
                $arr[] = $value;
                return;
            }
            if (is_array($arr)) {
                if (is_string($value)) {
                    $arr['_content'] = $value;
                    return;
                }
                $arr[] = $value;
                return;
            }
            $arr = $value;
            return;
        }
        $key = array_shift($depth);
        if (!isset($arr[$key])) {
            $arr[$key] = count($depth) ? array() : null;
        } else {
            if (is_string($arr[$key]) && strlen($arr)) {
                $arr[$key] = array('_content' => $arr[$key]);
            }
        }
        self::mergeValue($arr[$key], $depth, $value);
    }

    /**
     * Using XMLReader, unserialize XML into an array
     *
     * This unserializer has limitations on the XML it can parse, for simplicity:
     * 
     *  - Only a single text node (the last one) will be processed, so this code:
     *    <pre>
     *     <?xml version="1.0" ?><test>hi<tag/>there</test>
     *    </pre>
     *    results in <code>array('test' => array('tag' => '', '_content' => 'there'))</code>
     *  - tag ordering is not preserved in all cases:
     *    <pre>
     *     <?xml version="1.0" ?><test><tag /><another /> <tag /></test>
     *    </pre>
     *    results in
     *    <code>array('test' => array('tag' => array('', ''), 'another' => ''))</code>
     * @param string $file file URI to process
     * @return array
     */
    function parse($file)
    {
        $a = new XMLReader;
        $a->open($file);
        $tagStack = array();
        $arr = array();
        while ($a->read()) {
            if ($a->nodeType == XMLReader::ELEMENT) {
                if ($a->isEmptyElement) {
                    if ($a->hasAttributes) {
                        $attrs = array();
                        $attr = $a->moveToFirstAttribute();
                        while ($attr) {
                            $attrs[$a->name] = $a->value;
                            $attr = $a->moveToNextAttribute();
                        }
                        self::mergeValue($arr, 
                            array_merge($tagStack, array($a->name, 'attribs')),
                            $attrs);
                        continue;
                    }
                    self::mergeValue($arr,
                        array_merge($tagStack, array($a->name)), '');
                    continue;
                }
                $tagStack[] = $a->name;
                if ($a->hasAttributes) {
                    $attrs = array();
                    $attr = $a->moveToFirstAttribute();
                    while ($attr) {
                        $attrs[$a->name] = $a->value;
                        $attr = $a->moveToNextAttribute();
                    }
                    self::mergeValue($arr,
                        array_merge($tagStack, array('attribs')), $attrs);
                }
                continue;
            }
            if ($a->nodeType == XMLReader::END_ELEMENT) {
                array_pop($tagStack);
                continue;
            }
            if ($a->nodeType == XMLReader::TEXT || $a->nodeType == XMLReader::CDATA) {
                self::mergeValue($arr,
                    $tagStack, $a->value);
            }
        }
        return $arr;
    }
}