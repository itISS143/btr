<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blinking Body</title>
    <style>
        .blink-animation {
            background-color: white; /* Set initial background color */
        }

        /* Apply black color when the class is added */
        .blink-animation.blink {
            background-color: black;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Blinking Body Joke!</h1>
    <p>This is just a silly joke to make the body of the webpage blink.</p>

    <script>
        // Function to generate a random number between min and max (inclusive)
        function getRandomNumber(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        // Randomly decide whether to apply the blinking animation
        if (getRandomNumber(1, 10) === 1) { // Adjust the probability as needed
            document.body.classList.add('blink-animation');
            // After a delay, add the 'blink' class to change the background to black
            setTimeout(function() {
                document.body.classList.add('blink');
            }, 0.01); // Adjust delay as needed
        }
    </script>
</body>
</html>
