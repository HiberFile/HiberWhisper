<!DOCTYPE html>
<html class="h-screen">

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

<body class="text-lg">
  <!-- Header (only logo on the top left) (anchered) -->
  <div class="min-h-screen bg-blue-200 flex items-center justify-center">
    <div class="bg-white p-8 ml-4 mr-4 rounded-2xl shadow-sm w-full sm:max-w-screen-md md:max-w-md">
      <h1 class="text-4xl mb-4" style="font-family: ohno-blazeface">
        HiberWhisper
      </h1>
      <hr />
      <p class="mb-4 mt-4">
        Service de transcription <u>gratuit</u> d'audio qui convertit
        n'importe quel fichier audio MP3 en texte grâce à l'API d'OpenAI
        (Whisper).
      </p>
      <form id="upload-form" class="mb-4" action="traitement.php" method="post" enctype="multipart/form-data">
        <div class="mb-4">
          <label for="audio-file" class="block text-gray-700 font-bold mb-2">Fichier audio (mp3) :</label>
          <div class="relative rounded-md shadow-sm">
            <input type="file" name="audio-file" id="audio-file" class="opacity-0 absolute inset-0 z-50" required />
            <div class="flex items-center justify-center px-4 py-2 bg-white border border-gray-300 rounded-md font-medium text-gray-700 hover:bg-gray-50 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500 cursor-pointer">
              <span class="custom-file-label">Choisir un fichier</span>
            </div>
          </div>
        </div>
        <button id="submit-button" type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          HiberWhisperiser !
        </button>
      </form>
      <div id="loading-container" class="hidden flex items-center justify-center">
        <div class="rounded-full p-2 border-blue-600 border-4">
          <div class="w-4 h-4 bg-blue-600 rounded-full animate-ping"></div>
        </div>
        <div class="ml-4 text-gray-700 font-medium">HiberWhisperisation en cours...</div>
      </div>
      <div id="result-container" class="hidden">
        <h2 class="text-lg font-bold mb-2">
          Résultat de la HiberWhisperisation :
        </h2>
        <div id="result-text" class="bg-blue-100 p-4 rounded max-h-96 overflow-y-auto"></div>
        <button id="copy-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">
          Copier
        </button>
        <button id="return-button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-4">
          Retour
        </button>
      </div>
    </div>
  </div>
  <footer class="flex flex-col md:flex-row items-start w-full flex-none self-start p-6 md:p-8 font-medium text-xs text-gray-600 md:items-center justify-between fixed bottom-0">
    <ul class="flex flex-col md:flex-row items-start md:items-center md:justify-start">
      <li class="m-2">HiberFile Team</li>
    </ul>
  </footer>
  <script>
    const input = document.getElementById('audio-file');
    const label = document.querySelector('.custom-file-label');
    input.addEventListener('change', () => {
      const fileName = input.files[0].name;
      label.textContent = fileName;
    });

    // Show loading screen when form is submitted
    document.getElementById("upload-form").addEventListener("submit", function(e) {
      e.preventDefault();
      document.getElementById("upload-form").classList.add("hidden");
      document.getElementById("loading-container").classList.remove("hidden");
      this.submit();
    });
  </script>
</body>

</html>