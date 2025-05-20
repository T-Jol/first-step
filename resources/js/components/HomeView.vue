<template>
    <div class="main-panel">
        <header class="game-header">
            <h1 class="game-title">Fiction Interstellaire <span class="scanline"></span></h1>
            <p class="game-baseline">Histoires interactives à choix multiples</p>
            <div class="header-controls">
                <button v-if="lastChapterId && !error" @click="resumeStory" class="resume-btn">
                    Reprendre l'aventure
                </button>
            </div>
        </header>
        <section class="stories-section">
            <h2 class="stories-title">Liste des histoires</h2>
            <div v-if="stories && stories.length && !error" class="stories-grid">
                <div v-for="story in stories" :key="story.id" class="story-card">
                    <div class="story-content">
                        <h3 class="story-title">{{ story.title }}</h3>
                        <p class="story-description">{{ story.description }}</p>
                        <div class="story-stats">
                            <span class="stat">
                                <i class="fas fa-book"></i>
                                {{ story.chapters.length }} chapitres
                            </span>
                            <span class="stat">
                                <i class="fas fa-route"></i>
                                {{ countEndings(story) }} fins possibles
                            </span>
                        </div>
                        <button @click="startStory(story)" class="start-button">
                            Commencer l'aventure
                        </button>
                    </div>
                </div>
            </div>
            <div v-else-if="stories && !stories.length && !error" class="no-stories-container">
                <p class="error-message">Aucune histoire disponible pour le moment.</p>
            </div>
            <div v-else class="loading-container">
                <div class="loading-spinner"></div>
                <p>Chargement des histoires...</p>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    name: 'HomeView',
    props: {
        playing: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            stories: null, // Liste des histoires
            error: null,   // Message d'erreur éventuel
            lastChapterId: localStorage.getItem('currentChapterId'), // Progression sauvegardée
            currentStoryId: localStorage.getItem('currentStoryId') // Histoire en cours
        };
    },
    mounted() {
        this.fetchStories();
    },
    methods: {
        // Récupère la liste des histoires depuis l'API
        fetchStories() {
            fetch('http://127.0.0.1:8000/api/v1/stories')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Impossible de charger les histoires');
                    }
                    return response.json();
                })
                .then(data => {
                    // Supporte les deux formats
                    this.stories = Array.isArray(data) ? data : data.data;
                })
                .catch(err => {
                    this.error = err.message;
                });
        },
        // Compte le nombre de chapitres finaux pour une histoire
        countEndings(story) {
            return story.chapters.filter(chapter => chapter.is_ending).length;
        },
        // Démarre une histoire depuis le début
        startStory(story) {
            localStorage.setItem('currentStoryId', story.id);
            localStorage.removeItem('currentChapterId'); // On repart du début
            localStorage.removeItem(`journal_${story.id}`); // On efface le journal de cette histoire
            const firstChapterId = story.chapters[0]?.id;
            if (firstChapterId) {
                this.$router.push(`/chapter/${firstChapterId}`);
            }
        },
        // Reprend la progression sauvegardée
        resumeStory() {
            if (this.lastChapterId && this.currentStoryId) {
                this.$router.push(`/chapter/${this.lastChapterId}`);
            }
        }
    }
};
</script>

<style scoped>
.main-panel {
    background: rgba(20, 24, 38, 0.82);
    border-radius: 18px;
    border: 2px solid #3ec6ff88;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    backdrop-filter: blur(12px) saturate(1.2);
    margin: 2rem auto 1.5rem auto;
    max-width: 950px;
    padding: 2.5rem 2rem 2rem 2rem;
}
.game-header {
    text-align: center;
    margin-bottom: 1.5rem;
}
.game-title {
    font-family: 'Orbitron', 'Share Tech Mono', monospace;
    font-size: 2.5rem;
    color: #7fd6ff;
    text-shadow: 0 0 18px #3ec6ff, 0 0 32px #a3f0ff99;
    letter-spacing: 0.12em;
    margin-bottom: 0.2rem;
    position: relative;
    overflow: hidden;
}
.scanline {
    display: block;
    position: absolute;
    left: 0; right: 0; bottom: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, #7fd6ff, transparent);
    animation: scanline 2.5s linear infinite;
}
@keyframes scanline {
    0% { left: -100%; right: 100%; }
    50% { left: 0; right: 0; }
    100% { left: 100%; right: -100%; }
}
.game-baseline {
    font-family: 'Share Tech Mono', 'Orbitron', monospace;
    color: #a3f0ff;
    font-size: 1.1rem;
    margin-bottom: 1.2rem;
    letter-spacing: 0.08em;
    opacity: 0.85;
}
.header-controls {
    display: flex;
    justify-content: center;
    gap: 1.2rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}
.resume-btn {
    background: rgba(127,214,255,0.08);
    border: 1.5px solid #7fd6ff;
    color: #7fd6ff;
    border-radius: 20px;
    padding: 0.6rem 1.5rem;
    font-size: 1.08rem;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
    font-family: 'Orbitron', 'Share Tech Mono', monospace;
    margin-bottom: 0.5rem;
}
.resume-btn:hover {
    background: #7fd6ff;
    color: #0c0c1e;
}
.stories-section {
    margin-top: 1.2rem;
}
.stories-title {
    font-family: 'Orbitron', 'Share Tech Mono', monospace;
    color: #7fd6ff;
    font-size: 2rem;
    margin-bottom: 1.5rem;
    text-align: center;
    text-shadow: 0 0 10px #3ec6ff, 0 0 18px #a3f0ff99;
}
.stories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 1rem;
    width: 100%;
    box-sizing: border-box;
}
.story-card {
    background: rgba(20, 24, 38, 0.82);
    border-radius: 14px;
    border: 1.5px solid #3ec6ff44;
    box-shadow: 0 2px 16px 0 rgba(62, 198, 255, 0.10);
    padding: 1.5rem;
    transition: box-shadow 0.2s, transform 0.2s;
    position: relative;
    overflow: hidden;
}
.story-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #7fd6ff, transparent);
    animation: scan 2s linear infinite;
}
@keyframes scan {
    0% { left: -100%; right: 100%; }
    50% { left: 0; right: 0; }
    100% { left: 100%; right: -100%; }
}
.story-title {
    font-family: 'Orbitron', 'Share Tech Mono', monospace;
    color: #7fd6ff;
    font-size: 1.3rem;
    margin-bottom: 0.7rem;
    text-shadow: 0 0 10px #3ec6ff, 0 0 18px #a3f0ff99;
}
.story-description {
    color: #f0f4fa;
    font-size: 1.05rem;
    margin-bottom: 1.1rem;
}
.story-stats {
    display: flex;
    gap: 1.2rem;
    margin-bottom: 1.1rem;
    color: #a3f0ff;
    font-family: 'Share Tech Mono', monospace;
    font-size: 0.98rem;
}
.stat i {
    margin-right: 0.3em;
}
.start-button {
    background: transparent;
    border: 2px solid #7fd6ff;
    color: #7fd6ff;
    padding: 0.7rem 1.3rem;
    border-radius: 22px;
    cursor: pointer;
    font-size: 1.05rem;
    font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif;
    font-weight: 600;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px 0 rgba(127, 214, 255, 0.04);
    letter-spacing: 0.08em;
}
.start-button:hover {
    background: #7fd6ff;
    color: #181c24;
    box-shadow: 0 4px 16px 0 #7fd6ff99;
}
.loading-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    min-height: 200px;
}
.loading-spinner {
    width: 28px;
    height: 28px;
    border: 3px solid rgba(163, 240, 255, 0.3);
    border-radius: 50%;
    border-top-color: #a3f0ff;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.error-message {
    color: #ff6b6b;
    font-size: 1rem;
}
@media (max-width: 900px) {
    .main-panel {
        padding: 1.2rem 0.5rem 1.2rem 0.5rem;
        max-width: 99vw;
    }
    .stories-title {
        font-size: 1.2rem;
    }
}
@media (max-width: 600px) {
    .main-panel {
        padding: 0.7rem 0.2rem 0.7rem 0.2rem;
        max-width: 100vw;
    }
    .game-title {
        font-size: 1.2rem;
    }
    .stories-title {
        font-size: 1.05rem;
    }
}
</style> 