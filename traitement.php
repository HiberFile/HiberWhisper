<?php

// Do not show any errors
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);

$result = "";

// Check if POST content length is too large
if ($_SERVER['CONTENT_LENGTH'] > 10000000) {
    http_response_code(400);
    // Show error message in $result and exit
    $result = "Le fichier audio est trop volumineux. La taille maximale est de 10 Mo.";
}

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Récupération de la clé d'API depuis une variable d'environnement
$api_key = $_ENV['API_KEY'];

// Vérification de la présence d'un fichier audio
if (!isset($_FILES['audio-file'])) {
    http_response_code(400);
    $result = "Aucun fichier audio n'a été envoyé.";
}

// Récupération du fichier audio et vérification de son type
$file = $_FILES['audio-file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    $result = "Erreur lors de l'envoi du fichier : " . $file['error'];
}
if ($file['type'] !== 'audio/mpeg') {
    http_response_code(400);
    $result = "Le type de fichier " . $file['type'] . " n'est pas supporté. Le fichier doit être de type audio/mpeg.";
}

// Préparation de la requête API
$client = new Client(['headers' => ['Authorization' => 'Bearer ' . $api_key]]);
$url = 'https://api.openai.com/v1/audio/transcriptions';
$options = [
    'multipart' => [
        [
            'name' => 'file',
            'contents' => fopen($file['tmp_name'], 'r'),
            'filename' => $file['name'],
        ],
        [
            'name' => 'model',
            'contents' => 'whisper-1',
        ],
    ],
];

// Envoi de la requête API et récupération du résultat (mais seulement si le fichier audio est valide)
if ($result === "") {
    try {
        $result = json_decode($client->post($url, $options)->getBody(), true)['text'];
    } catch (RequestException $e) {
        http_response_code(400);
        $result = "Erreur lors de la transcription de l'audio : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title>HiberWhisper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Chargement de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chargement de SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Chargement de la police Ohno Blazeface -->
    <link rel="stylesheet" href="https://use.typekit.net/ieo2idj.css" />
</head>

<body>
    <!-- Header (only logo on the top left) (anchered) -->
    <header class="fixed">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <img src="logo.png" alt="HiberWhisper" class="h-12 w-auto" />
        </div>
    </header>
    <div class="min-h-screen bg-blue-200 flex items-center justify-center">
        <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
            <h1 class="text-2xl mb-4" style="font-family: ohno-blazeface">
                HiberWhisper
            </h1>
            <hr />
            <p class="mb-4 mt-4">
                Service de transcription <u>gratuit</u> d'audio qui convertit
                n'importe quel fichier audio MP3 en texte grâce à l'API d'OpenAI
                (Whisper).
            </p>
            <div id="result-container">
                <h2 class="text-lg font-bold mb-2">
                    Résultat de la HiberWhisperisation :
                </h2>
                <div id="result-text" class="bg-blue-100 p-4 rounded max-h-96 overflow-y-auto">
                    <?php echo $result; ?>
                </div>
                <button id="copy-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
                    Copier
                </button>
                <button id="return-button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">
                    Retour
                </button>
            </div>
        </div>
    </div>
    <footer class="fixed bottom-8 left-8 h-8 w-full text-white text-sm flex items-center justify-start">
        <div class="px-4">© 2023 HiberFile Team</div>
    </footer>
    <script>
        // Copie le texte transcrit dans le presse-papier
        const copyButton = document.getElementById("copy-button");
        copyButton.addEventListener("click", () => {
            const resultText = document.getElementById("result-text");
            const text = resultText.innerText;
            navigator.clipboard.writeText(text);
            Swal.fire({
                title: "Copié !",
                text: "Le texte a été copié dans le presse-papiers",
                icon: "success",
                confirmButtonText: "OK",
            });
        });
        // Retour à la page d'accueil
        const returnButton = document.getElementById("return-button");
        returnButton.addEventListener("click", () => {
            window.location.href = "index.php";
        });
    </script>
</body>

</html>