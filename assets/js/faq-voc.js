// Limites de caractères et de durée d'enregistrement
const MAX_CHARS = 150;
const MAX_DURATION = 30;

// Variables globales pour gérer l'audio, la reco vocale et le timer
let mediaRecorder, recognition, timerInterval;
let isRecording = false;
let seconds = 0;
let textBeforeRecording = "";

// Récupération des éléments du DOM
const micBtn = document.getElementById("micBtn");
const micIcon = document.getElementById("micIcon");
const questionInput = document.getElementById("questionInput");
const categorySelect = document.getElementById("categorySelect");
const timerDisplay = document.getElementById("timer");
const wavesDisplay = document.querySelector(".waves");
const stopHint = document.getElementById("stopHint");
const deleteBtn = document.getElementById("deleteBtn");

// Compatibilité SpeechRecognition (Chrome / autres navigateurs)
const SpeechRecognition =
  window.SpeechRecognition || window.webkitSpeechRecognition;

if (SpeechRecognition) {
  recognition = new SpeechRecognition();
  recognition.lang = "fr-FR";
  recognition.continuous = true;
  recognition.interimResults = true;

  // Quand la reconnaissance vocale renvoie du texte
  recognition.onresult = (event) => {
    let transcript = Array.from(event.results)
      .slice(event.resultIndex)
      .map((result) => result[0].transcript)
      .join("");

    // On concatène avec le texte déjà présent
    let finalText =
      (textBeforeRecording ? textBeforeRecording + " " : "") + transcript;

    // Sécurité sur la longueur du texte
    if (finalText.length > MAX_CHARS) {
      finalText = finalText.substring(0, MAX_CHARS);
      stopEverything();
    }

    questionInput.value = finalText;
    updateUI();
  };

  // Relance automatique si l'enregistrement est toujours actif
  recognition.onend = () => {
    if (isRecording) recognition.start();
  };
}

// Lance le timer + animation visuelle
function startTimer() {
  seconds = 0;
  clearInterval(timerInterval);

  timerInterval = setInterval(() => {
    seconds += 0.1;
    timerDisplay.innerText = seconds.toFixed(1);

    // Progression visuelle dans les vagues
    let percentage = Math.min((seconds / MAX_DURATION) * 100, 100);
    wavesDisplay.style.background = `linear-gradient(to right, #ff4d4d ${percentage}%, #999 ${percentage}%)`;
    wavesDisplay.style.backgroundClip = "text";
    wavesDisplay.style.webkitBackgroundClip = "text";

    // Stop automatique à la durée max
    if (seconds >= MAX_DURATION) stopEverything();
  }, 100);
}

// Stoppe proprement tout (audio, reco, timer)
function stopEverything() {
  isRecording = false;
  clearInterval(timerInterval);
  if (mediaRecorder?.state !== "inactive") mediaRecorder.stop();
  if (recognition) recognition.stop();
  updateUI();
}

// Clic sur le bouton micro
micBtn.onclick = async () => {
  if (!isRecording) {
    textBeforeRecording = questionInput.value.trim();

    try {
      // Demande d'accès au micro
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      mediaRecorder = new MediaRecorder(stream);
      mediaRecorder.start();

      if (recognition) recognition.start();
      isRecording = true;
      startTimer();
      updateUI();
    } catch (err) {
      alert("Accès micro refusé");
    }
  } else {
    stopEverything();
  }
};

// Mise à jour de l'UI à chaque saisie manuelle
questionInput.addEventListener("input", updateUI);

// Bouton suppression du texte
deleteBtn.onclick = () => {
  questionInput.value = "";
  textBeforeRecording = "";
  seconds = 0;
  timerDisplay.innerText = "0.0";
  wavesDisplay.style.background = "none";
  wavesDisplay.style.webkitTextFillColor = "initial";
  wavesDisplay.style.color = "#999";
  updateUI();
};

// Gère l'état visuel de l'interface
function updateUI() {
  const active = isRecording;

  micBtn.classList.toggle("recording", active);
  micIcon.className = active ? "fa-solid fa-stop" : "fa-solid fa-microphone";
  stopHint.innerText = active
    ? "Appuies pour arrêter."
    : "Appuies pour enregistrer.";

  deleteBtn.style.visibility =
    questionInput.value.trim().length > 0 ? "visible" : "hidden";
}

// ENVOI VERS LE CONTROLEUR PHP
document.getElementById("sendBtn").onclick = async () => {
  const text = questionInput.value.trim();
  const category = categorySelect ? categorySelect.value : "Question ouverte";

  if (!text) return alert("Question vide !");
  if (isRecording) stopEverything();

  try {
    const response = await fetch("controller/save-question.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ transcription: text, category: category }),
    });

    const result = await response.json();

    if (response.ok && result.status === "success") {
      showSuccessPopup(
        "Question envoyée ! Elle sera visible après validation.",
      );
      setTimeout(() => location.reload(), 2500);
    } else {
      alert("Erreur : " + result.message);
    }
  } catch (err) {
    console.error(err);
  }
};

// Petite popup de confirmation
function showSuccessPopup(message) {
  const popup = document.createElement("div");
  popup.className = "success-popup";
  popup.textContent = message;
  document.body.appendChild(popup);

  setTimeout(() => popup.classList.add("show"), 10);
  setTimeout(() => popup.classList.remove("show"), 2400);
}
