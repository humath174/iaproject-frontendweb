<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Chat AI avec Synthèse Vocale</title>
</head>
<body>

<nav class="bg-white border-gray-200 dark:bg-gray-900">
  <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
    <a href="https://flowbite.com/" class="flex items-center space-x-3 rtl:space-x-reverse">
      <img src="https://flowbite.com/docs/images/logo.svg" class="h-8" alt="Flowbite Logo" />
      <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Flowbite</span>
    </a>
    <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-default" aria-expanded="false">
      <span class="sr-only">Open main menu</span>
      <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
      </svg>
    </button>
    <div class="hidden w-full md:block md:w-auto" id="navbar-default">
      <ul class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
        <li>
          <a href="/dashboard/acceuilsansvoix.php" class="block py-2 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 dark:text-white md:dark:text-blue-500" aria-current="page">Acceuil</a>
        </li>
        <li>
          <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">About</a>
        </li>
        <li>
          <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Services</a>
        </li>
        <li>
          <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Pricing</a>
        </li>
        <li>
          <a href="#" class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Contact</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="bg-gray-50 min-h-screen flex flex-col items-center p-6">
<!-- Conteneur principal -->
<div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-4xl">
  <!-- Titre -->
  <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Assistant IA avec Synthèse Vocale</h1>

  <!-- Section 2 : Entrée du script -->
  <div class="mb-8">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">2. Saisissez votre script</h2>
    <textarea
      id="userQuestion"
      placeholder="Posez votre question ici..."
      rows="4"
      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    ></textarea>

    <div class="flex items-center justify-between mt-4">
      <div class="flex items-center space-x-2">
        <label for="language" class="text-gray-600">Langue :</label>
        <select id="language" class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
          <option value="fr">French (FR)</option>
          <option value="en">English (EN)</option>
        </select>
      </div>
      <div class="flex items-center space-x-2">
        <label for="speed" class="text-gray-600">Vitesse :</label>
        <input type="number" id="speed" value="1.0" step="0.1" min="0.5" max="2.0" class="w-20 border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>
    </div>
  </div>

  <!-- Bouton générer -->
  <div class="text-center">
    <button
      onclick="askQuestion()"
      class="bg-blue-500 text-white font-semibold py-2 px-6 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-300 focus:outline-none"
    >
      Générer une voix IA
    </button>
  </div>

  <!-- Section 3 : Résultat -->
  <div class="mt-8">
    <p id="response" class="text-gray-700 text-lg mb-4"></p>
    <audio id="audioPlayer" controls class="w-full hidden"></audio>
  </div>
</div>
</div>
<script>
  async function askQuestion() {
    const question = document.getElementById('userQuestion').value;
    const responseText = document.getElementById('response');
    const audioPlayer = document.getElementById('audioPlayer');

    try {
      // Envoie la question à l'API Flask
      const response = await fetch('http://192.168.30.10:5010/ask', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ question })
      });

      const data = await response.json();
      responseText.innerText = `Réponse : ${data.response}`;

      // Envoie la réponse texte à l'API TTS
      const ttsResponse = await fetch('http://192.168.30.10:5011/tts', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ text: data.response })
      });

      if (!ttsResponse.ok) {
        throw new Error('Erreur lors de la génération audio');
      }

      const audioBlob = await ttsResponse.blob();
      const audioUrl = URL.createObjectURL(audioBlob);

      // Lecture audio
      audioPlayer.src = audioUrl;
      audioPlayer.classList.remove('hidden');
      audioPlayer.play();

    } catch (error) {
      console.error('Erreur:', error);
      responseText.innerText = 'Une erreur est survenue.';
    }
  }
</script>
</body>
</html>
