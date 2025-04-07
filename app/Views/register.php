<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | WeatherNow</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        html, body {
            margin: 0; padding: 0;
            height: 100%; width: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }
        #vanta-bg {
            width: 100%;
            height: 100%;
            position: absolute;
            z-index: 0;
        }
        .form-container {
            position: relative;
            z-index: 1;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(10px);
            color: #fff;
            max-width: 400px;
            margin: auto;
            top: 50%;
            transform: translateY(-50%);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 20px #4ecca3;
        }
        input, button, .link-btn {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            border: none;
            border-radius: 8px;
        }
        button {
            background-color: #4ecca3;
            color: #000;
            font-weight: bold;
            cursor: pointer;
        }
        .link-btn {
            background-color: rgba(255,255,255,0.2);
            color: #fff;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .link-btn:hover {
            background-color: rgba(255,255,255,0.4);
        }
        a {
            color: #4ecca3;
        }
    </style>
</head>
<body>
<div id="vanta-bg"></div>
<div class="form-container">
    <h2>Create your WeatherNow account</h2>
    <?php if (session()->getFlashdata('msg')): ?>
    <p><?= session()->getFlashdata('msg') ?></p>
    <?php endif; ?>
    <form method="post" action="/registerSubmit">
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Login</button>
    </form>
    <p class="mt-3">Already have an account? <a href="/login">Login</a></p>
    <a href="/" class="link-btn mt-3">← Back to Home</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.waves.min.js"></script>
<script>
    VANTA.WAVES({
        el: "#vanta-bg",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        color: 0x1e90ff,
        shininess: 50,
        waveHeight: 20,
        waveSpeed: 1.5,
        zoom: 0.85
    });
</script>
</body>
</html>
