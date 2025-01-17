<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chat AI avec Synthèse Vocale</title>
</head>
<body>
<h1>Assistant IA avec Synthèse Vocale</h1>
<input type="text" id="userQuestion" placeholder="Pose une question" disabled>
<button onclick="askQuestion()">Envoyer</button>
<button onclick="startListening()">Parler</button> <!-- Nouveau bouton pour parler -->
<p id="response"></p>
<audio id="audioPlayer" controls></audio>

<script>
  // Fonction de reconnaissance vocale
  const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
  recognition.lang = 'fr-FR';
  recognition.interimResults = false;

  recognition.onstart = () => {
    console.log("Reconnaissance vocale commencée...");
  };

  recognition.onresult = async (event) => {
    const transcript = event.results[0][0].transcript;
    document.getElementById('userQuestion').value = transcript;
    await askQuestion();
  };

  recognition.onerror = (event) => {
    console.error("Erreur de reconnaissance vocale :", event.error);
  };

  // Fonction pour démarrer la reconnaissance vocale
  function startListening() {
    recognition.start();
  }

  // Fonction pour envoyer la question à l'API et recevoir la réponse
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
      audioPlayer.play();

    } catch (error) {
      console.error('Erreur:', error);
      responseText.innerText = 'Une erreur est survenue.';
    }
  }
</script>
</body>
</html>
