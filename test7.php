<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chess Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="chessboard" id="chessboard">
        <!-- Chessboard squares -->
        <div class="square" id="a8"></div><div class="square" id="b8"></div><div class="square" id="c8"></div><div class="square" id="d8"></div>
        <div class="square" id="e8"></div><div class="square" id="f8"></div><div class="square" id="g8"></div><div class="square" id="h8"></div>
        <div class="square" id="a7"></div><div class="square" id="b7"></div><div class="square" id="c7"></div><div class="square" id="d7"></div>
        <div class="square" id="e7"></div><div class="square" id="f7"></div><div class="square" id="g7"></div><div class="square" id="h7"></div>
        <div class="square" id="a6"></div><div class="square" id="b6"></div><div class="square" id="c6"></div><div class="square" id="d6"></div>
        <div class="square" id="e6"></div><div class="square" id="f6"></div><div class="square" id="g6"></div><div class="square" id="h6"></div>
        <div class="square" id="a5"></div><div class="square" id="b5"></div><div class="square" id="c5"></div><div class="square" id="d5"></div>
        <div class="square" id="e5"></div><div class="square" id="f5"></div><div class="square" id="g5"></div><div class="square" id="h5"></div>
        <div class="square" id="a4"></div><div class="square" id="b4"></div><div class="square" id="c4"></div><div class="square" id="d4"></div>
        <div class="square" id="e4"></div><div class="square" id="f4"></div><div class="square" id="g4"></div><div class="square" id="h4"></div>
        <div class="square" id="a3"></div><div class="square" id="b3"></div><div class="square" id="c3"></div><div class="square" id="d3"></div>
        <div class="square" id="e3"></div><div class="square" id="f3"></div><div class="square" id="g3"></div><div class="square" id="h3"></div>
        <div class="square" id="a2"></div><div class="square" id="b2"></div><div class="square" id="c2"></div><div class="square" id="d2"></div>
        <div class="square" id="e2"></div><div class="square" id="f2"></div><div class="square" id="g2"></div><div class="square" id="h2"></div>
        <div class="square" id="a1"></div><div class="square" id="b1"></div><div class="square" id="c1"></div><div class="square" id="d1"></div>
        <div class="square" id="e1"></div><div class="square" id="f1"></div><div class="square" id="g1"></div><div class="square" id="h1"></div>
    </div>
    <script>
 document.addEventListener("DOMContentLoaded", function() {
            const board = document.getElementById("chessboard");
            let selectedSquare = null;

            // Add click event listener to each square
            board.querySelectorAll(".square").forEach(square => {
                square.addEventListener("click", function() {
                    if (!selectedSquare) {
                        // If no square is selected, select the clicked square
                        if (square.querySelector(".piece")) {
                            selectedSquare = square;
                            selectedSquare.classList.add("selected");
                        }
                    } else {
                        // If a square is selected, move the selected piece to the clicked square
                        const piece = selectedSquare.querySelector(".piece");
                        square.appendChild(piece);
                        selectedSquare.classList.remove("selected");
                        selectedSquare = null;
                    }
                });
            });
        });
    </script>
</body>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0d9b5;
}

.chessboard {
    display: flex;
    flex-wrap: wrap;
    width: 400px;
    height: 400px;
    border: 2px solid #000;
}

.square {
    width: 50px;
    height: 50px;
    line-height: 50px;
    text-align: center;
    background-color: #f0d9b5;
    border: 1px solid #000;
    box-sizing: border-box;
}

.square.empty {
    background-color: transparent;
}

.square:nth-child(even) {
    background-color: #b58863;
}

.square:hover {
    background-color: #d6ab8c;
}

.piece {
    font-size: 40px;
}

.piece.black {
    color: #000;
}

.piece.white {
    color: #fff;
}
</style>
</html>
