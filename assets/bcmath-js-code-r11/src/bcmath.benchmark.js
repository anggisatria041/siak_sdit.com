if (typeof(bcmath) == 'undefined') {
    bcmath = {};
}

/**
 * Benchmarking tools to see if your changes make a difference or not.
 * Saving every ms counts when code is used heavily by large applications.
 */
bcmath.benchmark = {
    lastStart: null,
    lastStop: null,
    testCount: 100,
    
    doBenchmark: function() {

        var i, x, browserTime, bcTime;
        bcTime = bcmath.benchmark.start();
        for (i=0;i<bcmath.benchmark.testCount;i++) {
            x = bcdiv('2646693125139304345', '842468587426513207', i); // calculate pi to 5xi positions, technically formula is only accurate to 38 but it's just a test :)
            x = bcmul('1131231232321312' + i + '.3343', '3311231232123.339' + i + '.00', 2);
            x = bcsub('123123123215589810231' + i + '.3343', '9948123131' + i + '.314', 6);
            x = bcadd('9234232397842987' + i + '.342', '98432908432' + i + '.3242314', 6);
        }
        bcTime = bcmath.benchmark.stop();

        alert('Benchmark time: ' + bcTime);

        
    },

    start: function() {
        var d = new Date();
        bcmath.benchmark.lastStart = d.getTime();

    },
    
    stop: function() {
        var d=new Date();
        bcmath.benchmark.lastStop = d.getTime();
        return ((bcmath.benchmark.lastStop - bcmath.benchmark.lastStart) / 1000).toFixed(3) + ' seconds';
    }
};
