<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Dice Gambling Game</title>
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
        form {
            margin-top: 20px;
        }
        label {
            font-size: 16px;
            color: #333;
        }
        input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .result {
            margin-top: 20px;
            font-size: 18px;
        }
        .dice-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .dice {
            width: 100px;
            height: 100px;
            background-color: white;
            border: 2px solid black;
            border-radius: 10px;
            margin: 10px;
            position: relative;
        }
        .dot {
            width: 12px;
            height: 12px;
            background-color: black;
            border-radius: 50%;
            position: absolute;
            transform: translate(-50%, -50%);
        }
    </style>
</head>
<body>
    <h1>Simple Dice Gambling Game</h1>

    <form id="gamblingForm">
        <label for="bet">Place your bet (1 to 10): </label>
        <input type="number" id="bet" name="bet" min="1" max="10" required>
        <br>
        <label for="chosenNumber">Choose a number between 1 and 6: </label>
        <input type="number" id="chosenNumber" name="chosenNumber" min="1" max="6" required>
        <br>
        <input type="submit" value="Roll the Dice">
    </form>

    <div id="resultContainer" class="result"></div>

    <script>
        var totalPoints = 100; // Initial total points
        var winCount = 0; // Counter for number of wins
        var sameFaceCount = 0; // Counter for number of times getting three dice with the same face
        var loseCount = 0; // Counter for number of losses
        var playCount = 0; // Counter for number of plays

        document.getElementById("gamblingForm").addEventListener("submit", function(event) {
            event.preventDefault();
            var bet = parseInt(document.getElementById("bet").value);
            var chosenNumber = parseInt(document.getElementById("chosenNumber").value);

            var resultText = "";

            rollAnimation(function(dice1, dice2, dice3) {
                resultText += "Rolling the dice...<br>";
                resultText += "<div class='dice-container'>";
                resultText += "<div class='dice'>" + generateDiceDots(dice1) + "</div>";
                resultText += "<div class='dice'>" + generateDiceDots(dice2) + "</div>";
                resultText += "<div class='dice'>" + generateDiceDots(dice3) + "</div>";
                resultText += "</div>";

                var pointsChange = 0; // Points change from the bet

                if (chosenNumber == dice1 && dice1 == dice2 && dice2 == dice3) {
                    pointsChange = bet * 5;
                    resultText += "Congratulations! You matched all three dice. You win " + pointsChange + " points.";
                    sameFaceCount++;
                } else if (chosenNumber == dice1 || chosenNumber == dice2 || chosenNumber == dice3) {
                    pointsChange = bet;
                    resultText += "You matched one die. You win " + pointsChange + " points.";
                    winCount++;
                } else {
                    pointsChange = -bet;
                    resultText += "Sorry, you didn't match any dice. You lose " + Math.abs(pointsChange) + " points.";
                    loseCount++;
                }

                totalPoints += pointsChange; // Update total points
                playCount++; // Increment the play count

                var winRate = (winCount / playCount) * 100; // Calculate win rate

                resultText += "<br>Your current points: " + totalPoints;
                resultText += "<br>Wins: " + winCount;
                resultText += "<br>Three same face: " + sameFaceCount;
                resultText += "<br>Loses: " + loseCount;
                resultText += "<br>Number of plays: " + playCount;
                resultText += "<br>Win Rate: " + winRate.toFixed(2) + "%";

                document.getElementById("resultContainer").innerHTML = resultText;
            });
        });

        function rollAnimation(callback) {
            var rollCount = 10;
            var delay = 100; // milliseconds

            var rollInterval = setInterval(function() {
                if (rollCount-- <= 0) {
                    clearInterval(rollInterval);
                    var dice1 = rollDice();
                    var dice2 = rollDice();
                    var dice3 = rollDice();
                    callback(dice1, dice2, dice3);
                }
            }, delay);
        }

        function rollDice() {
            return Math.floor(Math.random() * 6) + 1;
        }

        function generateDiceDots(number) {
            var dots = '';
            if (number == 1) {
                dots += "<div class='dot' style='top: 50%; left: 50%;'></div>";
            } else if (number == 2) {
                dots += "<div class='dot' style='top: 20%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 80%;'></div>";
            } else if (number == 3) {
                dots += "<div class='dot' style='top: 20%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 50%; left: 50%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 80%;'></div>";
            } else if (number == 4) {
                dots += "<div class='dot' style='top: 20%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 20%; left: 80%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 80%;'></div>";
            } else if (number == 5) {
                dots += "<div class='dot' style='top: 20%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 20%; left: 80%;'></div>";
                dots += "<div class='dot' style='top: 50%; left: 50%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 80%;'></div>";
            } else if (number == 6) {
                dots += "<div class='dot' style='top: 20%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 50%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 20%;'></div>";
                dots += "<div class='dot' style='top: 20%; left: 80%;'></div>";
                dots += "<div class='dot' style='top: 50%; left: 80%;'></div>";
                dots += "<div class='dot' style='top: 80%; left: 80%;'></div>";
            }
            return dots;
        }
    </script>
</body>
</html>
