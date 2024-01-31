<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Clock with Alarm</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            text-align: center;
            margin-top: 100px;
            background-color: #f4f4f4;
        }
        #clock {
            font-size: 48px;
            font-weight: bold;
            color: #333;
        }
        #alarmInput {
            margin-top: 20px;
            font-size: 16px;
        }
        #alarmButton {
            margin-top: 10px;
            font-size: 16px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>

<div id="clock"><?php echo date("H:i:s A"); ?></div>
<input type="time" id="alarmInput">
<button onclick="setAlarm()" id="alarmButton">Set Alarm</button>

<audio id="alarmSound" src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" preload="auto"></audio>

<script>
    var alarmTime;

    // Function to update the clock every second
    function updateClock() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var seconds = now.getSeconds();
        var ampm = (hours >= 12) ? "PM" : "AM";

        hours = hours % 12;
        hours = (hours == 0) ? 12 : hours;

        // Add leading zeros to minutes and seconds
        minutes = (minutes < 10) ? "0" + minutes : minutes;
        seconds = (seconds < 10) ? "0" + seconds : seconds;

        var timeString = hours + ":" + minutes + ":" + seconds + " " + ampm;
        document.getElementById("clock").innerHTML = timeString;

        // Check if the current time matches the alarm time
        if (alarmTime && now.getHours() === alarmTime.getHours() && now.getMinutes() === alarmTime.getMinutes()) {
            ringAlarm();
            alarmTime = null; // Reset the alarm time after it rings
        }

        // Call the function again after 1000 milliseconds (1 second)
        setTimeout(updateClock, 1000);
    }

    function setAlarm() {
        var alarmInput = document.getElementById("alarmInput");
        if (alarmInput.value) {
            alarmTime = new Date();
            var inputTime = alarmInput.value.split(":");
            alarmTime.setHours(inputTime[0]);
            alarmTime.setMinutes(inputTime[1]);
            alarmTime.setSeconds(0);
            alert("Alarm set for " + alarmInput.value);
        } else {
            alert("Please enter a valid time for the alarm.");
        }
    }

    function ringAlarm() {
        var alarmSound = document.getElementById("alarmSound");
        alarmSound.play();
    }

    // Initial call to the function
    updateClock();
</script>

</body>
</html>
