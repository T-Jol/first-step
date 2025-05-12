<template>
    <div class="chapter">
        <div v-if="error">
            <p class="error-message">Erreur : {{ error }}</p>
        </div>

        <div v-else-if="chapter">
            <h1>{{ chapter.title }}</h1>

            <p>{{ chapter.content }}</p>

            <div class="choices">
                <button
                    v-for="choice in chapter.choices"
                    :key="choice.id"
                    class="choice-button"
                    @click="goToChapter(choice.next_chapter_id)"
                >
                    {{ choice.text }}
                </button>
            </div>

            <!-- 🔁 Bouton de redémarrage -->
            <div class="restart">
                <button @click="restartStory" class="restart-button">🔁 Recommencer l'histoire</button>
            </div>
        </div>

        <div v-else>
            <p>Chargement...</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ChapterView',
    data() {
        return {
            chapter: null,
            error: null,
        };
    },
    mounted() {
        const savedChapterId = localStorage.getItem('currentChapterId');
        const id = savedChapterId ? parseInt(savedChapterId) : 1;
        this.goToChapter(id);
    },
    methods: {
        goToChapter(id) {
            this.chapter = null;
            this.error = null;

            fetch(`http://127.0.0.1:8000/api/v1/chapters/${id}`)
                .then(response => response.json())
                .then(response => {
                    if (response.status === 'error') {
                        throw new Error(response.message);
                    }
                    this.chapter = response.data;
                    localStorage.setItem('currentChapterId', id);
                })
                .catch(err => {
                    console.error('Erreur:', err);
                    this.error = err.message;
                    // Si on est pas au chapitre 1, on y retourne
                    if (id !== 1) {
                        this.goToChapter(1);
                    }
                });
        },
        restartStory() {
            localStorage.removeItem('currentChapterId');
            this.goToChapter(1);
        }
    }
};
</script>

<style scoped>
.chapter {
    min-height: 100vh;
    padding: 2rem;
    width: 100%;
    max-width: none;
    margin: auto;
    display: flex;
    flex-direction: column;
    justify-content: center;
    font-family: 'Segoe UI', Roboto, sans-serif;
    color: #f0f0f0;
    background: linear-gradient(to bottom, #0c0c1e, #1b1b2f);
    text-align: center;
}

h1 {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #a3f0ff;
    text-shadow: 0 0 8px rgba(163, 240, 255, 0.2);
}

.chapter-image {
    margin: 1rem 0;
    max-width: 100%;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
}

.chapter-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 8px;
}

p {
    font-size: 1.1rem;
    line-height: 1.6;
}

.choices {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.choice-button {
    background-color: rgba(42, 43, 61, 0.8);
    border: 1px solid #3f3f5a;
    color: #ffffff;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    margin-top: 1rem;
    width: 80%;
    cursor: pointer;
    transition: all 0.3s ease;
}

.choice-button:hover {
    background-color: #3c3c5c;
    transform: scale(1.05);
}

.restart-button {
    margin-top: 1rem;
    background: none;
    border: none;
    color: #aaa;
    font-size: 0.9rem;
    cursor: pointer;
    text-decoration: underline;
    text-align: center;
}

.error-message {
    color: red;
    font-weight: bold;
}

@media (max-width: 600px) {
    .chapter {
        padding: 1rem;
    }

    h1 {
        font-size: 1.5rem;
    }

    .choice-button {
        font-size: 0.95rem;
    }
}
</style>
