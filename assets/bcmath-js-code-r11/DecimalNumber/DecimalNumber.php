<?php
    /**
     * Decimal Number Object - BC Math Wrapper
     *
     * Usage: $x = new DecimalNumber('22.3', 2);
     *        $x->add('4')->sub('5')->mul('4')->div('3.3')->toString();
     *
     * Replicates the math used in
     */
    class DecimalNumber {
        protected $_result;
        protected $_precision;
        
        public function __construct($num, $precision=0) {
            $this->_precision = intval($precision);
            $this->_result = bcround($num, $this->_precision);
        }

        public function add($operand) {
            $this->_result = bcround(bcadd($this->_result, $operand, $this->_precision+2), $this->_precision);
            return $this;
        }
        
        public function sub($operand) {
            return $this->subtract($operand);
        }

        public function subtract($operand) {
            $this->_result = bcround(bcsub($this->_result, $operand, $this->_precision+2), $this->_precision);
            return $this;
        }
        
        public function mul($operand) {
            return $this->multiply($operand);
        }

        public function multiply($operand) {
            $this->_result = bcround(bcmul($this->_result, $operand, $this->_precision+2), $this->_precision);
            return $this;
        }
        
        public function div($operand) {
            return $this->divide($operand);
        }

        public function divide($operand) {
            $this->_result = bcround(bcdiv($this->_result, $operand, $this->_precision+2), $this->_precision);
            return $this;
        }
        
        public function round($precision) {
            $this->_result = bcround($this->_result, $precision);
            return $this;
        }
        
        public function setPrecision($precision) {
            $this->_precision = intval($precision);
            return $this;
        }
        
        public function toString() {
            return $this->_result;
        }
    }
    
if (!function_exists('bcround')) {
    function bcround($strval, $precision = 0) {
        if (false !== ($pos = strpos($strval, '.')) && (strlen($strval) - $pos - 1) > $precision) {
            $zeros = str_repeat("0", $precision);
            return bcadd($strval, "0.{$zeros}5", $precision);
        } else {
            return $strval;
        }
    }
}
