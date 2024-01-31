<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coin Flip Game</title>
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
        .result {
            margin-top: 20px;
            font-size: 18px;
        }
        .coin {
            width: 100px;
            height: 100px;
            background-color: yellow;
            border: 2px solid #000;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            animation: flip-animation 0.5s ease-in-out;
        }
        .coin::before {
            content: "";
            width: 100%;
            height: 100%;
            background-image: url('https://cdn.pixabay.com/photo/2013/07/13/12/43/coin-159134_1280.png');
            background-size: cover;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 50%;
            transform: rotateY(0deg);
            transition: transform 0.5s;
            backface-visibility: hidden;
        }
        .coin.tails::before {
            transform: rotateY(180deg);
        }
        @keyframes flip-animation {
            0% { transform: rotateY(0deg); }
            100% { transform: rotateY(360deg); }
        }
    </style>
</head>
<body>
    <h1>Coin Flip Game</h1>

    <label for="betAmount">Enter your bet amount:</label>
    <input type="number" id="betAmount" min="1" value="1">
    <br><br>
    <label for="choice">Choose:</label>
    <select id="choice">
        <option value="heads">Heads</option>
        <option value="tails">Tails</option>
    </select>
    <br><br>
    <button onclick="flipCoin()">Flip Coin</button>

    <div id="resultContainer" class="result"></div>

    <script>
        var winCount = 0;
        var loseCount = 0;
        var totalPlays = 0;

        function flipCoin() {
            var betAmount = parseInt(document.getElementById("betAmount").value);
            var choice = document.getElementById("choice").value;
            var randomNumber = Math.random();
            var resultText = "";

            var coinResult = (randomNumber < 0.5) ? "heads" : "tails";

            var coinElement = document.createElement("div");
            coinElement.classList.add("coin", coinResult);
            document.body.appendChild(coinElement);

            setTimeout(function() {
                coinElement.remove();

                if (choice === coinResult) {
                    winCount++;
                    resultText = "You win! You flipped " + coinResult + ".";
                } else {
                    loseCount++;
                    resultText = "You lose! The coin landed on " + coinResult + ".";
                }

                totalPlays++;
                var winRate = (winCount / totalPlays) * 100;

                resultText += "<br>Wins: " + winCount;
                resultText += "<br>Loses: " + loseCount;
                resultText += "<br>Total plays: " + totalPlays;
                resultText += "<br>Win rate: " + winRate.toFixed(2) + "%";

                document.getElementById("resultContainer").innerHTML = resultText;
            }, 500); // Wait for the animation to finish (0.5s)
        }
    </script>
</body>
</html>
