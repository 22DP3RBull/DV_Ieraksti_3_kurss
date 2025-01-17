<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dienesta viesnīcas ieraksti</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @vite(['resources/sass/app.scss', 'resources/sass/main.scss', 'resources/js/main.js'])
</head>
<body>
    <header>
        <h1>Dienesta viesnīcas ieraksti</h1>
        <nav>
            <a href="{{ url('main') }}" class="button"><i class="fas fa-home"></i> Galvenā</a>
            <a href="{{ url('Par_mani') }}" class="button"><i class="fas fa-user"></i> Par mani</a>
            <div class="dropdown">
                <a href="#" class="button"><i class="fas fa-cogs"></i> Services</a>
                <div class="dropdown-content">
                    <a href="#">Service 1</a>
                    <a href="#">Service 2</a>
                    <a href="#">Service 3</a>
                </div>
            </div>
        </nav>
        <div class="menu">
            <i class="fas fa-bars"></i>
        </div>
    </header>
    <main>
        <h1>Laipni lūgti!</h1>
        <h2>Par mājaslapu</h2>
        <p>Šajā majaslapā būs iespējams ierakstīties un izrakstīties no dienesta viesnīcas ātri un ērti.</p>
        <div class="image-container">
            <img src="{{ asset('resources\images\RVT_kojas.png') }}" alt="rvt dienesta viesnica" class="animated-image">
        </div>
        <p><a href="{{ url('ierakstORizrakst') }}" class="start-work-button">Sākt darbu šeit!</a></p>
    </main>
    <footer>
        <p>&copy; 2024 Dienesta viesnīcas ieraksti</p>
        <p>
            <a href="{{ url('kontakti') }}" class="button">Kontakti</a>
        </p>
        <div id="dark-mode-toggle">
            <i class="fas fa-sun"></i>
            <i class="fas fa-moon"></i>
        </div>
    </footer>
</body>
</html>
