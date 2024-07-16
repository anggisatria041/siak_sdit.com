/**
 * Decimal Number Object
 *
 * Designed to be an OO Number Object to replace basic mathematics in JavaScript
 * Requires the "bcmath" javascript library (https://sourceforge.net/projects/bcmath-js  - see "bcmath-min.js")
 * Licence is BSD licence, bcmath is LGPL
 *
 * Example: var x=new DecimalNumber('123.456', 3); // create 3dp number, any operations will be calculated then rounded to 3dp
 *          x.add('5'); // add 5 to our number
 *          x.sub('5').mul('5');    // subtract 5, then multiply by 5
 *          alert(x);               // display the number (can also use x.toString() if needed)
 *
 *          Arguements can be passed in formula method too (but are applicable to std floating point errors).. ie:
 *          x = new DecimalNumber('5+5', 2);
 *          x.add('3/4');
 *          x.toString(); // returns 10.75
 */
function DecimalNumber(num, precision) {
    if (typeof(precision) == 'undefined') {
        precision = 0;
    }
    
    if (typeof(num) == 'undefined') {
        num = '0';
    }

    this.getPi = function(precision) {
        if (precision > 37) {
            alert('Note: this approximation is not accurate above 37 decimal places');
        }
        return bcdiv('2646693125139304345', '842468587426513207', precision);
    };

    this.toString = function() {
        return this._result;
    };
    
    this.floor = function(precision) {
        this._result = bcadd(this._result, '0', 0);
        return this;
    };
    
    this.ceil = function(precision) {
        if (this._result.substr(0, 1) == '-') {
            this._result = bcround(bcadd(this._result, '-0.5', 1), 0);
        } else {
            this._result = bcround(bcadd(this._result, '0.5', 1), 0);
        }
        return this;
    };
    
    this.toFixed = function(precision) {
        return bcround(this._result, precision);
    };

    this.valueOf = function() {
        return this._result;
    };
    
    this.abs = function() {
        if (this._result.substr(0, 1) == '-') {
            this._result = this._result.substr(1, this._result.length-1);
        };
        return this;
    };
    
    this.toInt = function() {
        return parseInt(this.toFixed(0));
    };
    
    this.toFloat = function() {
        return parseFloat(this._result);
    };

    this.add = function(operand) {
        this._result = bcround(bcadd(this._result, this._parseNumber(operand), this._precision+2), this._precision);
        return this;
    };
    
    this.sub = function(operand) {
        return this.subtract(operand);
    };
    
    this.subtract = function(operand) {
        this._result = bcround(bcsub(this._result, this._parseNumber(operand), this._precision+2), this._precision);
        return this;
    };
    
    this.mul = function(operand) {
        return this.multiply(operand);
    };

    this.multiply = function(operand) {
        this._result = bcround(bcmul(this._result, this._parseNumber(operand), this._precision+2), this._precision);
        return this;
    };

    this.div = function(operand) {
        return this.divide(operand);
    };
    
    this.divide = function(operand) {
        this._result = bcround(bcdiv(this._result, this._parseNumber(operand), this._precision+2), this._precision);
        return this;
    };

    this.round = function(precision) {
        this._result = bcround(this._result, precision);
        return this;
    };
    
    this.setPrecision=function(precision) {
        this._precision = precision;
        this.round(precision);
        return this;
    };
    
    this._parseNumber=function(num) {
        var tmp, r;
        tmp = num.toString().replace(/[^0-9\-\.]/g,'');
        if (tmp === '') {
            return '0';
        }
        return tmp;
    };

    this.reset = function(num) {
        if (typeof(num) == 'undefined') {
            num = 0;
        }
        this._result = bcround(num, this._precision);
        return this;
    }

    // construct
    this._precision = precision;
    this._result = bcround(this._parseNumber(num), this._precision);


};

/**
 * So why do we need DecimalNumber?
 * Answer: Math.floor((0.1+0.7)*10) being one example.. (returns 7, should be 8)
 *         As the PHP Manual says... "http://nz.php.net/manual/en/language.types.float.php"
 *               "This is due to the fact that it is impossible to express some
 *                fractions in decimal notation with a finite number of digits.
 *                For instance, 1/3 in decimal form becomes 0.3."
 */
function TestFloatingPointProblems() {

    // First, lets try JavaScripts Built-in maths...
    var x=0;
    x += 0.1;
    x += 0.7;
    x = x * 10;
    x = Math.floor(x).toString();

    if (x === '8') {
        alert("Wow! Result is correct, your browser doesn't have the floating point problems... cool :)");
    } else {
        alert("Well, apparently your browser can't work out Floor((0.1 + 0.7) * 10).. it thinks the answer is: " + x + ", the correct answer is of course 8.");
    }

    var y=new DecimalNumber(0, 1)
        .add('0.1').add('0.7')
        .multiply('10')
        .floor()
        .toString()
    ;
    if (y === '8') {
        alert('Howver, The DecimalNumber Library worked it out fine.. it figured out the maths as expected :)');
    } else {
        alert("Odd, apparently the DecimalNumber library can't work it out either.. must be a problem somewhere :/");
    }

    
    // now let's test PI using a magic number...   http://qin.laya.com/tech_projects_approxpi.html
    var browserPi=(2646693125139304345/842468587426513207).toFixed(20); // as high as it goes
    if (browserPi == '3.14159265358979323846') {//264338327950288418
        alert('Your browser calculates PI correctly to 20dp.. well done');
    } else {
        alert('Your browser calculates PI WRONG.. it is.. ' + bcsub('3.14159265358979323846', browserPi, 20) + ' off at 20dp');
    }
    var decNumPi=new DecimalNumber(0, 20);
    decNumPi = decNumPi.reset('2646693125139304345').divide('842468587426513207').toString(); //getPi(20);
    if (decNumPi == '3.14159265358979323846') {//264338327950288418
        alert('The DecimalNumber Library calculates PI correctly to 20dp... of course :)');
    } else {
        alert('Odd, the DecimalNumber Library calculated PI WRONG.. it is..' + bcsub('3.14159265358979323846', browserPi, 20) + ' off at 20dp');
    }

    // heck, lets go all out.. test at 38dp (limit of the accuracy of the 2 numbers to PI)..
    var decNumPi=new DecimalNumber(0, 38);
    decNumPi = decNumPi.reset('2646693125139304345').divide('842468587426513207').toString(); //getPi(20);
    if (decNumPi == '3.14159265358979323846264338327950288418') {
        alert('The DecimalNumber Library calculates PI correctly to 38dp... of course :)');
    } else {
        alert('Odd, the DecimalNumber Library calculated PI WRONG.. it is..' + bcsub('3.14159265358979323846264338327950288418', decNumPi, 38) + ' off at 38dp');
    }

    var decNumPi=new DecimalNumber();
    decNumPi.getPi(1000000);
    decNumPi = null;
    alert('done');

};

