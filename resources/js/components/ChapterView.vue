<template>
    <div class="chapter" @click="skipTypewriter">
        <!-- Affichage d'une erreur si besoin -->
        <div v-if="error" class="error-container">
            <p class="error-message">Erreur : {{ error }}</p>
            <div class="error-actions">
                <button class="back-home-btn neon-btn" @click.stop="goHome">
                    <span class="back-icon">←</span> Retour à l'accueil
                </button>
            </div>
        </div>

        <!-- Loader uniquement au tout premier chargement -->
        <div v-else-if="!chapter && !loadingChoice" class="loading-container">
            <div class="loading-spinner"></div>
            <p>Chargement...</p>
        </div>

        <!-- Panneau principal toujours affiché -->
        <div v-else class="chapter-content">
            <!-- Bouton retour accueil -->
            <button class="back-home-btn neon-btn" @click.stop="goHome">
                <span class="back-icon">←</span> Retour à l'accueil
            </button>
            <div class="chapter-music-controls-compact">
                <button @click="$emit('toggle-music')" class="music-btn-compact">
                    <span>{{ playing ? 'Couper la musique' : 'Activer la musique' }}</span>
                </button>
                <input
                    type="range"
                    min="0"
                    max="1"
                    step="0.01"
                    :value="volume"
                    @input="$emit('set-volume', parseFloat($event.target.value))"
                    class="volume-slider-compact"
                    :disabled="!playing"
                />
            </div>
          
            <div class="chapter-panel glass-panel">
                <div class="scan-effect"></div>
                <!-- Transition fade uniquement sur le contenu du chapitre -->
                <transition name="fade" mode="out-in" @after-leave="onFadeOutDone">
                    <div v-if="!isFading" :key="chapter.id" class="chapter-inner">
                        <!-- Titre du chapitre -->
                        <h1 class="chapter-title glow">{{ chapter.title }}</h1>
                        <div class="chapter-separator"></div>
                        <!-- Texte du chapitre, effet machine à écrire -->
                        <div class="chapter-text">
                            <p v-for="(paragraph, index) in displayedParagraphs" :key="index" class="paragraph" :class="{'proto9-dialogue': paragraph.startsWith('PROTO-9')}">
                                {{ paragraph }}<span v-if="isTypewriting && index === displayedParagraphs.length - 1 && !typewriterDone" class="type-cursor">▋</span>
                            </p>
                        </div>
                        <!-- Choix de navigation (affichés seulement à la fin de l'effet) -->
                        <div class="choices-container" v-if="typewriterDone && chapter.choices && chapter.choices.length">
                            <button
                                v-for="choice in chapter.choices"
                                :key="choice.id"
                                class="choice-button neon-btn"
                                :class="{ 'active-choice': loadingChoice && loadingChoiceId === choice.id }"
                                :disabled="loadingChoice"
                                @click="handleChoice(choice.next_chapter_id, choice.id, choice.text)"
                            >
                                <span v-if="loadingChoice && loadingChoiceId === choice.id" class="loader-btn"></span>
                                <span v-else>{{ choice.text }}</span>
                            </button>
                        </div>
                        <!-- Bouton pour recommencer l'histoire (affiché à la fin de l'effet) -->
                        <div class="restart-container" v-if="typewriterDone && (!chapter.choices || !chapter.choices.length)">
                            <button @click="restartStory" class="restart-button neon-btn">
                                <span class="restart-icon">⟳</span>
                                Recommencer l'histoire
                            </button>
                            <!-- Bouton pour afficher/masquer le journal -->
                            <button @click="showJournal = !showJournal" class="journal-btn neon-btn">
                                <span class="journal-icon">📖</span>
                                {{ showJournal ? 'Masquer' : 'Voir' }} mon parcours
                            </button>
                        </div>
                        <!-- Journal des choix : timeline stylée et narrative -->
                        <div v-if="showJournal && (!chapter.choices || !chapter.choices.length)" class="journal-timeline-container">
                            <h2 class="journal-title">Journal de votre aventure</h2>
                            <div class="journal-intro">Voici le chemin que vous avez parcouru :</div>
                            <div class="journal-timeline">
                                <div v-for="(entry, idx) in journal" :key="idx" class="timeline-step">
                                    <div class="timeline-dot">
                                        <span class="timeline-num">{{ idx + 1 }}</span>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="timeline-chapter">{{ entry.chapterTitle }}</div>
                                        <div class="timeline-choice">→ {{ entry.choiceText }}</div>
                                        <div v-if="getConsequence(entry)" class="timeline-consequence">{{ getConsequence(entry) }}</div>
                                    </div>
                                    <div v-if="idx < journal.length - 1" class="timeline-line"></div>
                                </div>
                            </div>
                            <div class="journal-summary">
                                <span class="journal-end">{{ getEndingSummary(chapter.title) }}</span>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ChapterView',
    props: {
        playing: {
            type: Boolean,
            default: false
        },
        volume: {
            type: Number,
            default: 0.5
        }
    },
    data() {
        return {
            chapter: null,
            error: null,
            loadingChoice: false,
            loadingChoiceId: null,
            isFading: false,
            nextChapterId: null,
            displayedParagraphs: [],
            isTypewriting: false,
            typewriterDone: false,
            typewriterTimeout: null,
            journal: [],
            showJournal: false,
            currentStoryId: null,
            consequences: window.CHOICE_CONSEQUENCES || {},
            endings: window.STORY_ENDINGS || {},
            lastPlayedChapterId: null
        };
    },
    computed: {
        // Découpe le contenu du chapitre en paragraphes (pour affichage stylé)
        formattedContent() {
            if (!this.chapter?.content) return [];
            return this.chapter.content.split('\n\n').filter(p => p.trim());
        }
    },
    mounted() {
        // Vérifie la présence de l'ID dans l'URL (via $route)
        const idParam = this.$route?.params?.id;
        let id = parseInt(idParam);
        if (!id || isNaN(id)) {
            // Redirige vers le chapitre 1 si l'ID est absent ou invalide
            this.$router.replace('/chapter/1');
            return;
        }
        this.goToChapter(id);
    },
    watch: {
        // Si l'ID change dans l'URL (navigation directe), recharge le chapitre
        '$route.params.id'(newId) {
            let id = parseInt(newId);
            if (!id || isNaN(id)) {
                this.$router.replace('/chapter/1');
                return;
            }
            this.goToChapter(id);
        }
    },
    methods: {
        // Récupère la conséquence narrative pour une entrée du journal
        getConsequence(entry) {
            // Si pas de table, ou pas de correspondance, retourne ''
            if (!this.consequences) return '';
            return this.consequences[`${entry.chapterTitle}|${entry.choiceText}`] || '';
        },
        // Récupère le résumé de fin personnalisé
        getEndingSummary(chapterTitle) {
            if (!this.endings) return `Fin de l'aventure : ${chapterTitle}`;
            for (const key in this.endings) {
                if (chapterTitle.toLowerCase().includes(key.toLowerCase())) {
                    return this.endings[key];
                }
            }
            // Fallback générique
            return `Fin de l'aventure : ${chapterTitle}`;
        },
        // Charge un chapitre donné (par son id)
        goToChapter(id) {
            this.error = null;
            this.loadingChoice = false;
            this.loadingChoiceId = null;
            this.isFading = false;
            this.nextChapterId = null;
            this.displayedParagraphs = [];
            this.isTypewriting = false;
            this.typewriterDone = false;
            clearTimeout(this.typewriterTimeout);
            this.showJournal = false;

            fetch(`http://127.0.0.1:8000/api/v1/chapters/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Chapitre introuvable');
                    return response.json();
                })
                .then(data => {
                    this.chapter = data.data ? data.data : data;
                    this.currentStoryId = this.chapter.story_id;
                    
                    // Récupère le dernier chapitre joué pour cette histoire
                    this.lastPlayedChapterId = localStorage.getItem(`last_played_${this.currentStoryId}`);
                    
                    // Si on charge un chapitre différent de la dernière partie jouée, on réinitialise le journal
                    if (this.lastPlayedChapterId && this.lastPlayedChapterId !== id.toString()) {
                        this.journal = [];
                        localStorage.setItem(`journal_${this.currentStoryId}`, JSON.stringify(this.journal));
                    } else {
                        this.journal = JSON.parse(localStorage.getItem(`journal_${this.currentStoryId}`) || '[]');
                    }
                    
                    localStorage.setItem('currentChapterId', id);
                    localStorage.setItem('currentStoryId', this.currentStoryId);
                    
                    this.startTypewriter();
                })
                .catch(err => {
                    this.error = err.message;
                });
        },
        // Effet machine à écrire sur tous les paragraphes
        startTypewriter() {
            this.displayedParagraphs = [];
            this.isTypewriting = true;
            this.typewriterDone = false;
            const paragraphs = this.formattedContent;
            let currentParagraph = 0;
            let currentChar = 0;
            let buffer = '';
            const speed = 18; // ms par lettre
            const nextChar = () => {
                if (!this.isTypewriting) return; // Si skip
                if (currentParagraph >= paragraphs.length) {
                    this.isTypewriting = false;
                    this.typewriterDone = true;
                    return;
                }
                const para = paragraphs[currentParagraph];
                buffer += para[currentChar];
                this.displayedParagraphs = [
                    ...paragraphs.slice(0, currentParagraph),
                    buffer
                ];
                if (currentChar < para.length - 1) {
                    currentChar++;
                    this.typewriterTimeout = setTimeout(nextChar, speed);
                } else {
                    // Paragraphe fini, passe au suivant
                    currentParagraph++;
                    currentChar = 0;
                    buffer = '';
                    this.typewriterTimeout = setTimeout(nextChar, 200); // Petite pause entre paragraphes
                }
            };
            nextChar();
        },
        // Permet de skip l'effet et d'afficher tout le texte instantanément
        skipTypewriter() {
            if (this.isTypewriting) {
                clearTimeout(this.typewriterTimeout);
                this.displayedParagraphs = this.formattedContent;
                this.isTypewriting = false;
                this.typewriterDone = true;
            }
        },
        // Gère le choix de l'utilisateur
        handleChoice(nextChapterId, choiceId, choiceText) {
            this.loadingChoice = true;
            this.loadingChoiceId = choiceId;
            
            const journalEntry = {
                chapterTitle: this.chapter.title,
                choiceText: choiceText
            };
            
            this.journal.push(journalEntry);
            localStorage.setItem(`journal_${this.currentStoryId}`, JSON.stringify(this.journal));
            localStorage.setItem(`last_played_${this.currentStoryId}`, nextChapterId);
            
            this.isFading = true;
            this.nextChapterId = nextChapterId;
        },
        // Appelé quand le fade-out est terminé
        onFadeOutDone() {
            if (this.nextChapterId) {
                this.goToChapter(this.nextChapterId);
            }
        },
        // Remet l'histoire à zéro
        restartStory() {
            this.journal = [];
            localStorage.setItem(`journal_${this.currentStoryId}`, JSON.stringify(this.journal));
            localStorage.removeItem(`last_played_${this.currentStoryId}`);
            this.$router.push('/chapter/1');
        },
        goHome() {
            this.$router.push('/');
        }
    }
};
</script>

<style scoped>
html, body {
    height: 100vh;
    overflow: hidden;
}
.chapter {
    width: 100vw;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    overflow: hidden;
    box-sizing: border-box;
    background: transparent;
    z-index: 1;
    padding-top: 2.5vh;
}
.chapter-content {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    min-height: 80vh;
}
.hud-panel {
    font-family: 'Share Tech Mono', 'Fira Mono', 'Consolas', monospace;
    color: #7fd6ff;
    font-size: 1.05rem;
    opacity: 0.92;
    display: flex;
    gap: 1.5rem;
    margin-bottom: 0.5rem;
    margin-top: 0.2rem;
    letter-spacing: 0.04em;
    text-shadow: 0 2px 8px #0a2236;
    background: rgba(18, 34, 54, 0.55);
    border-radius: 8px;
    padding: 0.3rem 1.2rem;
    box-shadow: 0 2px 12px 0 rgba(127, 214, 255, 0.08);
    border: 1.5px solid #7fd6ff33;
    align-self: center;
}
.glass-panel {
    background: rgba(20, 24, 38, 0.72);
    border-radius: 14px;
    border: 1.5px solid #3ec6ff88;
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
    backdrop-filter: blur(12px) saturate(1.2);
    padding: 2.2rem 2.5rem 1.5rem 2.5rem;
    margin: 0 auto;
    max-width: 600px;
    transition: box-shadow 0.3s;
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px);}
    to { opacity: 1; transform: translateY(0);}
}
.chapter-title {
    font-family: 'Orbitron', 'Rajdhani', 'Segoe UI', Arial, sans-serif;
    color: #3ec6ff;
    font-size: 2.1rem;
    letter-spacing: 0.13em;
    text-align: center;
    margin-bottom: 1.2rem;
    font-weight: 700;
    text-shadow: 0 2px 24px #3ec6ff33, 0 0 8px #0a2236;
}
.glow {
    text-shadow: 0 0 16px #7fd6ff, 0 0 32px #a3f0ff99;
}
.chapter-separator {
    width: 60%;
    height: 2px;
    margin: 0 auto 0.7rem auto;
    background: linear-gradient(90deg, transparent 0%, #7fd6ff 50%, transparent 100%);
    border-radius: 2px;
    opacity: 0.8;
    box-shadow: 0 0 8px #7fd6ff55;
}
.chapter-text {
    font-size: 1.08rem;
    line-height: 1.6;
    color: #f0f4fa;
    font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
    letter-spacing: 0.01em;
    text-align: left;
    z-index: 3;
    overflow-wrap: break-word;
    margin-bottom: 1.2rem;
    width: 100%;
    max-width: 800px;
}
.paragraph {
    margin-bottom: 0.7rem;
    text-align: justify;
}
.proto9-dialogue {
    font-family: 'Share Tech Mono', 'Fira Mono', 'Consolas', monospace;
    color: #7fd6ff;
    background: rgba(127, 214, 255, 0.07);
    border-left: 3px solid #7fd6ff;
    padding-left: 0.6rem;
    margin-left: 0.1rem;
    font-size: 1.05rem;
    letter-spacing: 0.03em;
    font-style: italic;
}
.choices-container {
    display: flex;
    flex-direction: column;
    gap: 1.1rem;
    margin-bottom: 1.2rem;
    width: 100%;
    max-width: 700px;
}
.neon-btn {
    background: rgba(127, 214, 255, 0.13);
    border: 2px solid #7fd6ff;
    color: #a3f0ff;
    padding: 1.1rem 1.5rem;
    border-radius: 12px;
    cursor: pointer;
    font-size: 1.13rem;
    font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif;
    font-weight: 600;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, border 0.2s, filter 0.2s;
    text-align: center;
    box-shadow: 0 2px 16px 0 rgba(127, 214, 255, 0.10);
    margin-bottom: 0.1rem;
    letter-spacing: 0.08em;
    filter: drop-shadow(0 0 8px #7fd6ff33);
}
.neon-btn:hover {
    background: #7fd6ff;
    color: #181c24;
    border: 2px solid #fff;
    box-shadow: 0 4px 32px 0 #7fd6ff99;
    filter: drop-shadow(0 0 16px #a3f0ff);
}
.restart-container {
    text-align: center;
    margin-top: 1.2rem;
}
.restart-button {
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
.restart-button:hover {
    background: #7fd6ff;
    color: #181c24;
    box-shadow: 0 4px 16px 0 #7fd6ff99;
}
.restart-icon {
    font-size: 1.1rem;
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
.error-container {
    text-align: center;
    padding: 1rem;
    background: rgba(255, 0, 0, 0.1);
    border-radius: 10px;
    border: 1px solid rgba(255, 0, 0, 0.3);
}
.error-message {
    color: #ff6b6b;
    font-size: 1rem;
}
.error-actions {
    display: flex;
    justify-content: center;
    margin-top: 1.2rem;
}
@media (max-width: 1200px) {
    .glass-panel {
        max-width: 99vw;
        padding: 1.2rem 0.5rem;
    }
    .choices-container {
        max-width: 99vw;
    }
    .chapter {
        min-height: 50vh;
        padding: 0.5rem;
    }
    .chapter-title {
        font-size: 1.2rem;
    }
    .chapter-text {
        font-size: 1rem;
    }
}
@media (max-width: 900px) {
    .glass-panel {
        max-width: 100vw;
        padding: 0.7rem 0.2rem;
    }
    .choices-container {
        max-width: 100vw;
    }
    .chapter-title {
        font-size: 1.05rem;
    }
    .chapter-text {
        font-size: 0.95rem;
    }
}
@media (max-width: 600px) {
    .glass-panel {
        padding: 0.4rem 0.1rem;
        max-width: 100vw;
    }
    .choices-container {
        max-width: 100vw;
    }
    .chapter-title {
        font-size: 0.95rem;
    }
    .chapter-text {
        font-size: 0.85rem;
    }
    .hud-panel {
        font-size: 0.92rem;
        padding: 0.2rem 0.5rem;
    }
    .journal-timeline-container {
        padding: 0.7rem 0.2rem;
    }
    .timeline-content {
        min-width: 0;
        padding: 0.5rem 0.5rem;
    }
    .back-home-btn {
        position: static !important;
        display: block;
        margin: 0.5rem auto 1.2rem auto;
        width: 90vw;
        max-width: 340px;
        font-size: 1.08rem;
        padding: 0.9rem 0.5rem;
        border-radius: 12px;
        text-align: center;
        background: rgba(127, 214, 255, 0.22);
        box-shadow: 0 2px 16px 0 rgba(127, 214, 255, 0.13);
    }
}
/* Transition fade entre les chapitres */
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.4s cubic-bezier(.4,0,.2,1);
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
.chapter-inner {
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.loader-btn {
    display: inline-block;
    width: 1.2em;
    height: 1.2em;
    border: 2px solid #7fd6ff44;
    border-top: 2px solid #7fd6ff;
    border-radius: 50%;
    animation: spin-btn 0.7s linear infinite;
    margin-right: 0.5em;
    vertical-align: middle;
}
@keyframes spin-btn {
    to { transform: rotate(360deg); }
}
.active-choice {
    filter: brightness(1.3) drop-shadow(0 0 8px #a3f0ff);
    background: #7fd6ff;
    color: #181c24;
    border: 2px solid #fff;
    box-shadow: 0 4px 32px 0 #7fd6ff99;
}
.type-cursor {
    color: #7fd6ff;
    font-weight: bold;
    animation: blink 1s steps(1) infinite;
    font-size: 1.1em;
}
@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0; }
}
.back-home-btn {
    position: absolute;
    top: 1.2rem;
    right: 2.2rem;
    z-index: 10;
    background: rgba(127, 214, 255, 0.13);
    border: 2px solid #7fd6ff;
    color: #a3f0ff;
    padding: 0.6rem 1.2rem;
    border-radius: 12px;
    font-size: 1.05rem;
    font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif;
    font-weight: 600;
    letter-spacing: 0.08em;
    box-shadow: 0 2px 16px 0 rgba(127, 214, 255, 0.10);
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, border 0.2s, filter 0.2s;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.back-home-btn:hover {
    background: #7fd6ff;
    color: #181c24;
    border: 2px solid #fff;
    box-shadow: 0 4px 32px 0 #7fd6ff99;
    filter: drop-shadow(0 0 16px #a3f0ff);
}
.back-icon {
    font-size: 1.2em;
    margin-right: 0.2em;
}
.journal-btn {
    margin-top: 1.2rem;
    margin-left: 1.2rem;
    font-size: 1.05rem;
    padding: 0.6rem 1.2rem;
    border-radius: 12px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}
.journal-icon {
    font-size: 1.2em;
}
.journal-timeline-container {
    background: rgba(20, 24, 38, 0.82);
    border-radius: 14px;
    border: 1.5px solid #3ec6ff44;
    box-shadow: 0 2px 16px 0 rgba(62, 198, 255, 0.10);
    padding: 1.5rem 2rem;
    max-width: 600px;
    margin: 2rem auto 0 auto;
}
.journal-title {
    color: #7fd6ff;
    font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif;
    font-size: 1.3rem;
    margin-bottom: 1.1rem;
    text-align: center;
    letter-spacing: 0.08em;
}
.journal-intro {
    color: #a3f0ff;
    font-size: 1.05rem;
    margin-bottom: 1.2rem;
    text-align: center;
    font-style: italic;
}
.journal-timeline {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    position: relative;
    margin-left: 1.5rem;
}
.timeline-step {
    display: flex;
    align-items: flex-start;
    position: relative;
    margin-bottom: 1.2rem;
}
.timeline-dot {
    width: 2.1rem;
    height: 2.1rem;
    background: #0a2236;
    border: 2.5px solid #7fd6ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 12px #7fd6ff55;
    position: relative;
    z-index: 2;
}
.timeline-num {
    color: #7fd6ff;
    font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
}
.timeline-content {
    margin-left: 1.1rem;
    background: rgba(127, 214, 255, 0.07);
    border-radius: 10px;
    padding: 0.7rem 1.2rem;
    min-width: 180px;
    box-shadow: 0 2px 8px 0 rgba(127, 214, 255, 0.04);
}
.timeline-chapter {
    color: #7fd6ff;
    font-weight: 600;
    font-size: 1.05rem;
    margin-bottom: 0.2rem;
}
.timeline-choice {
    color: #a3f0ff;
    font-style: italic;
    font-size: 1.05rem;
    margin-left: 0.2rem;
}
.timeline-consequence {
    color: #f0f4fa;
    font-size: 0.98rem;
    margin-top: 0.2rem;
    font-style: italic;
    opacity: 0.85;
}
.timeline-line {
    position: absolute;
    left: 1.05rem;
    top: 2.1rem;
    width: 2px;
    height: 1.2rem;
    background: linear-gradient(180deg, #7fd6ff 0%, transparent 100%);
    z-index: 1;
}
.journal-summary {
    margin-top: 2.2rem;
    text-align: center;
}
.journal-end {
    color: #7fd6ff;
    font-size: 1.1rem;
    font-family: 'Orbitron', 'Segoe UI', Arial, sans-serif;
    letter-spacing: 0.06em;
}
.choice-button {
    background: rgba(62, 198, 255, 0.08);
    border: 1.5px solid #3ec6ff;
    color: #eaf6ff;
    padding: 1.1rem 1.5rem;
    border-radius: 7px;
    font-size: 1.13rem;
    font-family: 'Rajdhani', 'Orbitron', 'Segoe UI', Arial, sans-serif;
    font-weight: 600;
    margin-bottom: 0.5rem;
    transition: background 0.18s, color 0.18s, box-shadow 0.18s, border 0.18s;
    box-shadow: 0 2px 12px 0 rgba(62, 198, 255, 0.08);
}
.choice-button:hover {
    background: rgba(62, 198, 255, 0.18);
    color: #3ec6ff;
    border: 1.5px solid #fff;
    box-shadow: 0 4px 24px 0 #3ec6ff44;
    filter: brightness(1.1);
}
.chapter-music-controls-compact {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1.1rem;
    margin: 1.1rem 0 1.7rem 0;
}
.music-btn-compact {
    background: rgba(127,214,255,0.13);
    border: 2px solid #7fd6ff;
    color: #7fd6ff;
    border-radius: 10px;
    padding: 0.45rem 1.1rem;
    font-size: 0.98rem;
    font-family: 'Orbitron', 'Share Tech Mono', monospace;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    box-shadow: 0 2px 8px 0 rgba(127, 214, 255, 0.08);
    letter-spacing: 0.08em;
}
.music-btn-compact:hover {
    background: #7fd6ff;
    color: #181c24;
    box-shadow: 0 4px 16px 0 #7fd6ff99;
}
.volume-slider-compact {
    width: 80px;
    accent-color: #7fd6ff;
    cursor: pointer;
    opacity: 1;
    transition: opacity 0.2s;
    margin-left: 0.2rem;
}
.volume-slider-compact:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}
</style>
