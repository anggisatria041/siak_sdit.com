@echo off
del *.js
echo Building Files...
type ..\src\libbcmath.js > .\libbcmath-std-src.js
type ..\src\libbcmath.add.js >> .\libbcmath-std-src.js
type ..\src\libbcmath.compare.js >> .\libbcmath-std-src.js
type ..\src\libbcmath.div.js >> .\libbcmath-std-src.js
type ..\src\libbcmath.doaddsub.js >> .\libbcmath-std-src.js
type ..\src\libbcmath.recmul.js >> .\libbcmath-std-src.js
type ..\src\libbcmath.sub.js >> .\libbcmath-std-src.js

type .\libbcmath-std-src.js > .\bcmath-full-src.js
type ..\src\bcmath.js >> .\bcmath-full-src.js

type ..\src\bcmath.js > .\bcmath-std-src.js

type .\bcmath-full-src.js > .\DecimalNumber-full-src.js
type ..\DecimalNumber\DecimalNumber.js >> .\DecimalNumber-full-src.js

type ..\DecimalNumber\DecimalNumber.js > .\DecimalNumber-std-src.js

echo Compressing...
java -jar yuicompressor-2.4.2.jar -o "tmp-libbcmath-std-min.js" "libbcmath-std-src.js"
java -jar yuicompressor-2.4.2.jar -o "tmp-bcmath-full-min.js" "bcmath-full-src.js"
java -jar yuicompressor-2.4.2.jar -o "tmp-bcmath-std-min.js" "bcmath-std-src.js"
java -jar yuicompressor-2.4.2.jar -o "tmp-DecimalNumber-std-min.js" "DecimalNumber-std-src.js"
java -jar yuicompressor-2.4.2.jar -o "tmp-DecimalNumber-full-min.js" "DecimalNumber-full-src.js"

echo Adding Headers
type ".\header-libbcmath.txt" > ."\libbcmath-std-min.js"
type ".\tmp-libbcmath-std-min.js" >> ."\libbcmath-std-min.js"

type ".\header-bcmath.txt" > ."\bcmath-std-min.js"
type ".\tmp-bcmath-std-min.js" >> ."\bcmath-std-min.js"

type ".\header-bcmath.txt" > ."\bcmath-full-min.js"
type ".\tmp-bcmath-full-min.js" >> ."\bcmath-full-min.js"

type ".\header-bcmath.txt" > ."\DecimalNumber-std-min.js"
type ".\tmp-DecimalNumber-std-min.js" >> ."\DecimalNumber-std-min.js"

type ".\header-DecimalNumber.txt" > ."\DecimalNumber-full-min.js"
type ".\tmp-DecimalNumber-full-min.js" >> ."\DecimalNumber-full-min.js"

echo Cleaning up...
del tmp-*
dir *.js
echo Done
pause
