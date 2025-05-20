<script setup>
import { ref, onMounted } from 'vue';
import { RouterView, useRoute } from 'vue-router';
import TheHeader from '@/components/TheHeader.vue';
import StarBackground from '@/components/StarBackground.vue';

const playing = ref(false);
const volume = ref(0.5);
const route = useRoute();

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
    <div class="app">
        <StarBackground />
        <TheHeader v-if="route.name !== 'chapter' && route.name !== 'home'" />
        <main>
            <RouterView v-slot="{ Component }">
                <component
                  :is="Component"
                  v-bind="route.meta"
                  :playing="playing"
                  @toggle-music="toggleMusic"
                  @set-volume="(v) => { volume.value = v; const audio = document.getElementById('bg-music'); if(audio) audio.volume = v; }"
                  :volume="volume"
                />
            </RouterView>
        </main>
        <audio id="bg-music" loop>
            <source src="/audio/background.mp3" type="audio/mpeg">
        </audio>
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700&family=Share+Tech+Mono&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    background: linear-gradient(135deg, #0a192f 0%, #112240 100%);
    color: #f0f4fa;
    min-height: 100vh;
    overflow-x: hidden;
}

.app {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

main {
    flex: 1;
    display: flex;
    flex-direction: column;
}
</style>
