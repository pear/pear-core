<?php
class PHP_Dump {
    var $_var;
    function PHP_Dump($var)
    {
        $this->_var = $var;
    }
    
    function toPHP()
    {
        return var_export($this->_var, true);
    }
}
?>
