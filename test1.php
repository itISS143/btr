<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Calculator</title>
</head>
<body>
    <div id="calculator">
        <a href="export.php" class="button">EXPORT TO EXCEL</a>
        <h2>Advanced Calculator</h2>
        <br>
        <form id="calculatorForm" action="" method="post">
            <input type="text" name="display" id="display" readonly>

            <div>
                <input type="button" value="7" onclick="appendToDisplay('7')">
                <input type="button" value="8" onclick="appendToDisplay('8')">
                <input type="button" value="9" onclick="appendToDisplay('9')">
                <input type="button" value="/" onclick="appendToDisplay('/')">
                <input type="button" value="⌫" onclick="backspace()" class="backspace-button">
            </div>

            <div>
                <input type="button" value="4" onclick="appendToDisplay('4')">
                <input type="button" value="5" onclick="appendToDisplay('5')">
                <input type="button" value="6" onclick="appendToDisplay('6')">
                <input type="button" value="-" onclick="appendToDisplay('-')">
            </div>

            <div>
                <input type="button" value="1" onclick="appendToDisplay('1')">
                <input type="button" value="2" onclick="appendToDisplay('2')">
                <input type="button" value="3" onclick="appendToDisplay('3')">
                <input type="button" value="+" onclick="appendToDisplay('+')">
            </div>

            <div>
                <input type="button" value="0" onclick="appendToDisplay('0')">
                <input type="button" value="." onclick="appendToDisplay('.')">
                <input type="button" value="=" onclick="calculate()">
                <input type="button" value="*" onclick="appendToDisplay('*')">
            </div>

            <div>
                <input type="button" value="C" onclick="clearDisplay()">
                <input type="button" value="√" onclick="calculateSquareRoot()">
                <input type="button" value="x^2" onclick="calculatePower(2)">
                <input type="button" value="x^y" onclick="appendOperator('^')">
                <input type="button" value="!" onclick="calculateFactorial()">
            </div>
        </form>
    </div>

    <script>
        function appendToDisplay(value) {
            document.getElementById('display').value += value;
        }

        function appendOperator(operator) {
            document.getElementById('display').value += operator;
        }

        function calculate() {
            try {
                const result = eval(document.getElementById('display').value);
                document.getElementById('display').value = result;
            } catch (error) {
                document.getElementById('display').value = 'Error';
            }
        }

        function clearDisplay() {
            document.getElementById('display').value = '';
        }

        function calculateSquareRoot() {
            const value = parseFloat(document.getElementById('display').value);
            if (!isNaN(value) && value >= 0) {
                document.getElementById('display').value = Math.sqrt(value);
            } else {
                document.getElementById('display').value = 'Error';
            }
        }

        function calculatePower(exp) {
            const value = parseFloat(document.getElementById('display').value);
            document.getElementById('display').value = Math.pow(value, exp);
        }

        function calculateFactorial() {
            const value = parseInt(document.getElementById('display').value);
            if (!isNaN(value) && value >= 0) {
                let result = 1;
                for (let i = 2; i <= value; i++) {
                    result *= i;
                }
                document.getElementById('display').value = result;
            } else {
                document.getElementById('display').value = 'Error';
            }
        }

        function backspace() {
        const display = document.getElementById('display');
        display.value = display.value.slice(0, -1);
    }
    </script>
     <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
        }

        #calculator {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            margin: 50px auto;
            width: 300px;
        }

        input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: right;
            outline: none;
        }

        .backspace-button {
            background-color: #ff7f7f; /* Change background color to red */
            color: #ff6464; /* Change text color to white */
            width: 100%; /* Make the width of the backspace button 100% */
        }

        .backspace-button:hover {
            background-color: #e03d3d; /* Darker red on hover */
        }

        input[type="button"] {
            width: 40px;
            height: 40px;
            margin: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            background-color: #f8f8f8;
        }

        input[type="button"]:hover {
            background-color: #e0e0e0;
        }

        div {
            display: flex;
            flex-wrap: wrap;
        }

    </style>
</body>
</html>
