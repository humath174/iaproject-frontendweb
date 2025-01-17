<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Chat AI avec Synthèse Vocale</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6">

<div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
  <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Assistant IA avec Synthèse Vocale</h1>

  <div class="flex flex-col space-y-4">
    <input
      type="text"
      id="userQuestion"
      placeholder="Pose une question"
      class="border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:outline-none"
    >
    <button
      onclick="askQuestion()"
      class="bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-300 focus:outline-none"
    >
      Envoyer
    </button>
  </div>

  <p id="response" class="mt-4 text-gray-700"></p>

  <audio id="audioPlayer" controls class="w-full mt-4 hidden"></audio>
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
