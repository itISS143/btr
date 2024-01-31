<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Typing Game</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        #wordContainer {
            font-size: 24px;
            margin-top: 20px;
        }
        #inputBox {
            margin-top: 20px;
            padding: 10px;
            font-size: 18px;
        }
        #message {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Typing Game - 10 Finger Challenge</h1>

    <div id="wordContainer">Type the word: <span id="word"></span></div>
    <input type="text" id="inputBox" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
    <div id="message"></div>

    <script>
        const words = ["keyboard", "mouse", "monitor", "computer", "screen", "desktop", "laptop", "software", "hardware", "internet"];
        let currentWordIndex = 0;
        let startTime, endTime;

        function startGame() {
            currentWordIndex = 0;
            displayWord();
            document.getElementById("inputBox").value = "";
            document.getElementById("inputBox").focus();
            startTime = new Date();
        }

        function displayWord() {
            document.getElementById("word").innerText = words[currentWordIndex];
        }

        function checkWord() {
            const typedWord = document.getElementById("inputBox").value.trim();
            const targetWord = words[currentWordIndex];

            if (typedWord === targetWord) {
                currentWordIndex++;
                if (currentWordIndex === words.length) {
                    endGame();
                } else {
                    displayWord();
                    document.getElementById("inputBox").value = "";
                }
            }
        }

        function endGame() {
            endTime = new Date();
            const totalTime = (endTime - startTime) / 1000; // in seconds
            const wpm = Math.round(words.join("").length / 5 / (totalTime / 60));
            const accuracy = calculateAccuracy();
            const message = `Game Over! Your typing speed: ${wpm} WPM, Accuracy: ${accuracy}%`;
            document.getElementById("message").innerText = message;
        }

        function calculateAccuracy() {
            const typedText = document.getElementById("inputBox").value;
            const targetText = words.join("");
            let correctCount = 0;
            for (let i = 0; i < typedText.length; i++) {
                if (typedText[i] === targetText[i]) {
                    correctCount++;
                }
            }
            return ((correctCount / targetText.length) * 100).toFixed(2);
        }

        document.getElementById("inputBox").addEventListener("input", checkWord);

        startGame(); // Start the game initially
    </script>
</body>
</html>
