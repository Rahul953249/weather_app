<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🌤️ Weather Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #ffffff;
        }

        #vanta-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .content-container {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            padding: 20px;
        }

        .card {
            background: rgba(0, 0, 0, 0.6);
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(255,255,255,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        .card h2 {
            font-size: 2rem;
        }

        .card p {
            font-size: 1.1rem;
            margin: 10px 0;
        }

        .sun-info {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .btn-back, .btn-save {
            margin-top: 20px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-back:hover, .btn-save:hover {
            background: rgba(255, 255, 255, 0.4);
        }
    </style>
</head>
<body>
<div id="vanta-bg"></div>
<div class="content-container">
    <div class="card" id="detailsCard">
        <h2 class="mb-3">🔄 Loading Weather Info...</h2>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
<script>
    VANTA.WAVES({
        el: "#vanta-bg",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        color: 0x888888,
        shininess: 30,
        waveHeight: 18,
        waveSpeed: 1.3,
        zoom: 0.85
    });

    const urlParams = new URLSearchParams(window.location.search);
    const city = urlParams.get('city');
    const apiKey = "ebb26c5e7fe37c3d77a0a87ee9770299";

    let weatherData = {};

    if (city) {
        fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`)
            .then(res => res.json())
            .then(data => {
                const sunrise = new Date(data.sys.sunrise * 1000).toLocaleTimeString();
                const sunset = new Date(data.sys.sunset * 1000).toLocaleTimeString();

                weatherData = {
                    city: data.name,
                    temperature: data.main.temp,
                    humidity: data.main.humidity,
                    wind_speed: data.wind.speed,
                    condition: data.weather[0].main,
                    sunrise: sunrise,
                    sunset: sunset
                };

                const html = `
                    <h2>${data.name} - ${data.weather[0].main}</h2>
                    <h4 class="mb-3">${data.weather[0].description}</h4>
                    <p><strong>Temperature:</strong> ${data.main.temp} °C</p>
                    <p><strong>Humidity:</strong> ${data.main.humidity}%</p>
                    <p><strong>Wind Speed:</strong> ${data.wind.speed} m/s</p>
                    <div class="sun-info">
                        <div>🌅 Sunrise<br><strong>${sunrise}</strong></div>
                        <div>🌇 Sunset<br><strong>${sunset}</strong></div>
                    </div>
                    <button class="btn-save" onclick="saveToDatabase()">💾 Save to Log</button><br>
                    <a href="/" class="btn-back mt-4">← Back to Home</a>
                `;
                document.getElementById("detailsCard").innerHTML = html;
            });
    } else {
        document.getElementById("detailsCard").innerHTML = "<h3>❌ No city specified!</h3><a href='/' class='btn-back mt-4'>Back</a>";
    }

    function saveToDatabase() {
        fetch('/save-weather-details', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(weatherData)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert("✔️ Weather details saved to database!");
            } else {
                alert("⚠️ Failed to save weather details.");
            }
        });
    }
</script>
</body>
</html>
