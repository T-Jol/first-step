<script setup>
import { ref, onMounted } from 'vue';
const playing = ref(false);
const volume = ref(0.5);

function toggleMusic() {
  const audio = document.getElementById('bg-music');
  if (!audio) return;
  if (audio.paused) {
    audio.volume = volume.value;
    audio.play();
    playing.value = true;
  } else {
    audio.pause();
    playing.value = false;
  }
}

function setVolume(e) {
  const audio = document.getElementById('bg-music');
  volume.value = parseFloat(e.target.value);
  if (audio) {
    audio.volume = volume.value;
  }
}

onMounted(() => {
  const audio = document.getElementById('bg-music');
  if (audio) {
    audio.volume = volume.value;
    audio.pause();
    playing.value = false;
  }
});
</script>

<template>
  <header class="header-discret">
    <div class="music-controls">
      <button @click="toggleMusic" class="music-btn">
        <span v-if="playing">🔊 Couper la musique</span>
        <span v-else>🔈 Activer la musique</span>
      </button>
      <input
        type="range"
        min="0"
        max="1"
        step="0.01"
        :value="volume"
        @input="setVolume"
        class="volume-slider"
        :disabled="!playing"
      />
    </div>
  </header>
</template>

<style scoped>
.header-discret {
  padding-top: 0.5rem;
  margin-bottom: 0.5rem;
}
.music-controls {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 1rem;
}
.music-btn {
  background: rgba(127,214,255,0.08);
  border: 1px solid #7fd6ff;
  color: #7fd6ff;
  border-radius: 20px;
  padding: 0.4rem 1rem;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s, color 0.2s;
}
.music-btn:hover {
  background: #7fd6ff;
  color: #0c0c1e;
}
.volume-slider {
  width: 100px;
  accent-color: #7fd6ff;
  cursor: pointer;
  opacity: 1;
  transition: opacity 0.2s;
}
.volume-slider:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}
</style> 