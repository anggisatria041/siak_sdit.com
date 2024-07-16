/**
 * Binary Calculator (BC) Arbitrary Precision Mathematics Lib v0.10  (LGPL)
 * Copy of libbcmath included in PHP5 src
 * supports bcadd, bcsub, bcmul, bcdiv, bccomp, bcscale, and new function bcround(val, precision)
 * See PHP Manual for parameters.. functions work identical to the PHP5 funcions
 * Feel free to use how-ever you want, just email any bug-fixes/improvements to the sourceforge project:
 *      https://sourceforge.net/projects/bcmath-js/
 *
 * Ported from the PHP5 bcmath extension source code,
 * which uses the libbcmath package...
 *    Copyright (C) 1991, 1992, 1993, 1994, 1997 Free Software Foundation, Inc.
 *    Copyright (C) 2000 Philip A. Nelson
 *     The Free Software Foundation, Inc.
 *     59 Temple Place, Suite 330
 *     Boston, MA 02111-1307 USA.
 *      e-mail:  philnelson@acm.org
 *     us-mail:  Philip A. Nelson
 *               Computer Science Department, 9062
 *               Western Washington University
 *               Bellingham, WA 98226-9062
 */
function DecimalNumber(b,a){if(typeof(a)=="undefined"){a=0}if(typeof(b)=="undefined"){b="0"}this.getPi=function(c){if(c>37){alert("Note: this approximation is not accurate above 37 decimal places")}return bcdiv("2646693125139304345","842468587426513207",c)};this.toString=function(){return this._result};this.floor=function(c){this._result=bcadd(this._result,"0",0);return this};this.ceil=function(c){if(this._result.substr(0,1)=="-"){this._result=bcround(bcadd(this._result,"-0.5",1),0)}else{this._result=bcround(bcadd(this._result,"0.5",1),0)}return this};this.toFixed=function(c){return bcround(this._result,c)};this.valueOf=function(){return this._result};this.abs=function(){if(this._result.substr(0,1)=="-"){this._result=this._result.substr(1,this._result.length-1)}return this};this.toInt=function(){return parseInt(this.toFixed(0))};this.toFloat=function(){return parseFloat(this._result)};this.add=function(c){this._result=bcround(bcadd(this._result,this._parseNumber(c),this._precision+2),this._precision);return this};this.sub=function(c){return this.subtract(c)};this.subtract=function(c){this._result=bcround(bcsub(this._result,this._parseNumber(c),this._precision+2),this._precision);return this};this.mul=function(c){return this.multiply(c)};this.multiply=function(c){this._result=bcround(bcmul(this._result,this._parseNumber(c),this._precision+2),this._precision);return this};this.div=function(c){return this.divide(c)};this.divide=function(c){this._result=bcround(bcdiv(this._result,this._parseNumber(c),this._precision+2),this._precision);return this};this.round=function(c){this._result=bcround(this._result,c);return this};this.setPrecision=function(c){this._precision=c;this.round(c);return this};this._parseNumber=function(c){var d,e;d=c.toString().replace(/[^0-9\-\.]/g,"");if(d===""){return"0"}return d};this.reset=function(c){if(typeof(c)=="undefined"){c=0}this._result=bcround(c,this._precision);return this};this._precision=a;this._result=bcround(this._parseNumber(b),this._precision)}function TestFloatingPointProblems(){var a=0;a+=0.1;a+=0.7;a=a*10;a=Math.floor(a).toString();if(a==="8"){alert("Wow! Result is correct, your browser doesn't have the floating point problems... cool :)")}else{alert("Well, apparently your browser can't work out Floor((0.1 + 0.7) * 10).. it thinks the answer is: "+a+", the correct answer is of course 8.")}var d=new DecimalNumber(0,1).add("0.1").add("0.7").multiply("10").floor().toString();if(d==="8"){alert("Howver, The DecimalNumber Library worked it out fine.. it figured out the maths as expected :)")}else{alert("Odd, apparently the DecimalNumber library can't work it out either.. must be a problem somewhere :/")}var b=(2646693125139304400/842468587426513200).toFixed(20);if(b=="3.14159265358979323846"){alert("Your browser calculates PI correctly to 20dp.. well done")}else{alert("Your browser calculates PI WRONG.. it is.. "+bcsub("3.14159265358979323846",b,20)+" off at 20dp")}var c=new DecimalNumber(0,20);c=c.reset("2646693125139304345").divide("842468587426513207").toString();if(c=="3.14159265358979323846"){alert("The DecimalNumber Library calculates PI correctly to 20dp... of course :)")}else{alert("Odd, the DecimalNumber Library calculated PI WRONG.. it is.."+bcsub("3.14159265358979323846",b,20)+" off at 20dp")}var c=new DecimalNumber(0,38);c=c.reset("2646693125139304345").divide("842468587426513207").toString();if(c=="3.14159265358979323846264338327950288418"){alert("The DecimalNumber Library calculates PI correctly to 38dp... of course :)")}else{alert("Odd, the DecimalNumber Library calculated PI WRONG.. it is.."+bcsub("3.14159265358979323846264338327950288418",c,38)+" off at 38dp")}var c=new DecimalNumber();c.getPi(1000000);c=null;alert("done")};