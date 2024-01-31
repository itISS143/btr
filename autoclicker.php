<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Clicker</title>
    <style>
        /* CSS styles */
    </style>
</head>
<body>
    <h1>Auto Clicker</h1>

    <button id="startButton" onclick="startAutoClick()">Start Auto Click</button>
    <button id="stopButton" onclick="stopAutoClick()">Stop Auto Click</button>

    <!-- This is the element that will be automatically clicked -->
    <button id="clickTarget" onclick="console.log('Clicked!')">Click Me</button>
    
    <script>
        let intervalId;

        function startAutoClick() {
            intervalId = setInterval(function() {
                // Simulate a click event on a specific element (e.g., a button)
                document.getElementById("clickTarget").click();
            }, 1000); // Adjust the interval (in milliseconds) between each click
        }

        function stopAutoClick() {
            clearInterval(intervalId);
        }
    </script>
</body>
</html>
