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
function bcadd(b,d,f){var e,c,a;if(typeof(f)=="undefined"){f=libbcmath.scale}f=((f<0)?0:f);e=libbcmath.bc_init_num();c=libbcmath.bc_init_num();a=libbcmath.bc_init_num();e=libbcmath.php_str2num(b.toString());c=libbcmath.php_str2num(d.toString());if(e.n_scale>c.n_scale){c.setScale(e.n_scale)}if(c.n_scale>e.n_scale){e.setScale(c.n_scale)}a=libbcmath.bc_add(e,c,f);if(a.n_scale>f){a.n_scale=f}return a.toString()}function bcsub(b,d,f){var e,c,a;if(typeof(f)=="undefined"){f=libbcmath.scale}f=((f<0)?0:f);e=libbcmath.bc_init_num();c=libbcmath.bc_init_num();a=libbcmath.bc_init_num();e=libbcmath.php_str2num(b.toString());c=libbcmath.php_str2num(d.toString());if(e.n_scale>c.n_scale){c.setScale(e.n_scale)}if(c.n_scale>e.n_scale){e.setScale(c.n_scale)}a=libbcmath.bc_sub(e,c,f);if(a.n_scale>f){a.n_scale=f}return a.toString()}function bccomp(a,c,e){var d,b;if(typeof(e)=="undefined"){e=libbcmath.scale}e=((e<0)?0:e);d=libbcmath.bc_init_num();b=libbcmath.bc_init_num();d=libbcmath.bc_str2num(a.toString(),e);b=libbcmath.bc_str2num(c.toString(),e);return libbcmath.bc_compare(d,b,e)}function bcscale(a){a=parseInt(a,10);if(isNaN(a)){return false}if(a<0){return false}libbcmath.scale=a;return true}function bcdiv(b,d,f){var e,c,a;if(typeof(f)=="undefined"){f=libbcmath.scale}f=((f<0)?0:f);e=libbcmath.bc_init_num();c=libbcmath.bc_init_num();a=libbcmath.bc_init_num();e=libbcmath.php_str2num(b.toString());c=libbcmath.php_str2num(d.toString());if(e.n_scale>c.n_scale){c.setScale(e.n_scale)}if(c.n_scale>e.n_scale){e.setScale(c.n_scale)}a=libbcmath.bc_divide(e,c,f);if(a===-1){throw new Error(11,"(BC) Division by zero")}if(a.n_scale>f){a.n_scale=f}return a.toString()}function bcmul(b,d,f){var e,c,a;if(typeof(f)=="undefined"){f=libbcmath.scale}f=((f<0)?0:f);e=libbcmath.bc_init_num();c=libbcmath.bc_init_num();a=libbcmath.bc_init_num();e=libbcmath.php_str2num(b.toString());c=libbcmath.php_str2num(d.toString());if(e.n_scale>c.n_scale){c.setScale(e.n_scale)}if(c.n_scale>e.n_scale){e.setScale(c.n_scale)}a=libbcmath.bc_multiply(e,c,f);if(a.n_scale>f){a.n_scale=f}return a.toString()}function bcround(d,b){var a,c;a="0."+Array(b+1).join("0")+"5";if(d.toString().substring(0,1)=="-"){a="-"+a}c=bcadd(d,a,b);return c};