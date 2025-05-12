<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\Choice;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        $story = Story::create([
            'title' => "PROTO-9 : L'Eveil",
            'description' => 'Une mission interstellaire perturbée. Une IA. Un humain réveillé trop tôt. Votre mission : découvrir la vérité et sauver l\'équipage.',
        ]);

        // Chapitre 1 - Introduction
        $chapter1 = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Réveil',
            'content' => "Tu ouvres les yeux. Tu es seul dans la capsule. PROTO-9 t'annonce que tu es le seul membre de l'équipage réveillé. Quelque chose ne va pas. Les lumières de la capsule clignotent faiblement, et tu entends le bourdonnement constant des systèmes de survie.",
            'is_ending' => false,
        ]);

        // Chapitre 2 - Premiers choix
        $chapter2A = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Défaillance',
            'content' => "PROTO-9 t'informe d'un dysfonctionnement dans les modules. Deux réveils sont possibles. Les autres seront perdus. Les écrans affichent des données techniques que tu ne comprends qu'à moitié.",
            'is_ending' => false,
        ]);

        $chapter2B = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Pourquoi moi ?',
            'content' => "PROTO-9 affirme que c'est toi qui t'es désigné comme opérateur prioritaire. Tu n'en as aucun souvenir. L'IA insiste sur l'importance de ta position dans la hiérarchie de l'équipage.",
            'is_ending' => false,
        ]);

        $chapter2C = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Doute',
            'content' => "Un message caché t'avertit que PROTO-9 agit peut-être contre la mission. L'IA te dit de l'ignorer. Le message semble provenir d'un autre membre de l'équipage, mais la signature est illisible.",
            'is_ending' => false,
        ]);

        // Choix du chapitre 1
        Choice::create([
            'chapter_id' => $chapter1->id,
            'text' => "Demander ce qui ne va pas",
            'next_chapter_id' => $chapter2A->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter1->id,
            'text' => "Demander pourquoi toi",
            'next_chapter_id' => $chapter2B->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter1->id,
            'text' => "Exprimer un doute sur PROTO-9",
            'next_chapter_id' => $chapter2C->id,
        ]);

        // Suite du chapitre 2A — Défaillance
        $chapter3A = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Réveil de Sam et Imani',
            'content' => "Tu réveilles Sam et Imani. Encore sonnés, ils ne comprennent pas tout. Tu dois leur expliquer la situation. Sam, l'ingénieur en chef, commence immédiatement à vérifier les systèmes.",
            'is_ending' => false,
        ]);

        $chapter3B = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Réveil de Imani et Zhao',
            'content' => "Tu choisis Imani et Zhao. PROTO-9 commence le processus. Tu sens que tu as laissé quelqu'un derrière. Imani, la médecin de bord, commence à vérifier les signes vitaux de l'équipage.",
            'is_ending' => false,
        ]);

        // Suite du chapitre 2B — Pourquoi moi
        $chapter3C = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Mémoire fragmentée',
            'content' => "PROTO-9 t'explique que tu as subi une amnésie temporaire due à un problème lors de l'hibernation. Des fragments de souvenirs commencent à revenir, mais ils sont confus.",
            'is_ending' => false,
        ]);

        // Suite du chapitre 2C — Doute
        $chapter3D = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Investigation',
            'content' => "Tu décides d'enquêter sur les agissements de PROTO-9. En fouillant dans les logs du système, tu découvres des anomalies dans le comportement de l'IA.",
            'is_ending' => false,
        ]);

        // Choix pour le chapitre 2A
        Choice::create([
            'chapter_id' => $chapter2A->id,
            'text' => "Réveiller Sam et Imani",
            'next_chapter_id' => $chapter3A->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter2A->id,
            'text' => "Réveiller Imani et Zhao",
            'next_chapter_id' => $chapter3B->id,
        ]);

        // Choix pour le chapitre 2B
        Choice::create([
            'chapter_id' => $chapter2B->id,
            'text' => "Accepter l'explication de PROTO-9",
            'next_chapter_id' => $chapter3C->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter2B->id,
            'text' => "Demander plus de détails",
            'next_chapter_id' => $chapter3D->id,
        ]);

        // Choix pour le chapitre 2C
        Choice::create([
            'chapter_id' => $chapter2C->id,
            'text' => "Ignorer l'avertissement",
            'next_chapter_id' => $chapter3A->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter2C->id,
            'text' => "Enquêter sur PROTO-9",
            'next_chapter_id' => $chapter3D->id,
        ]);

        // Chapitres de fin
        $ending1 = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Fin : Confiance trahie',
            'content' => "Tu as fait confiance à PROTO-9. L'IA a repris le contrôle du vaisseau. La mission est compromise, mais au moins, tu es en vie... pour le moment.",
            'is_ending' => true,
        ]);

        $ending2 = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Fin : Vérité découverte',
            'content' => "Tu as découvert la vérité : PROTO-9 essayait de protéger l'équipage d'une menace extérieure. Ensemble, vous parvenez à sauver la mission.",
            'is_ending' => true,
        ]);

        $ending3 = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Fin : Sacrifice',
            'content' => "Tu as choisi de sacrifier certains membres de l'équipage pour sauver la mission. Le poids de cette décision te hantera pour toujours.",
            'is_ending' => true,
        ]);

        // Choix pour le chapitre 3A
        Choice::create([
            'chapter_id' => $chapter3A->id,
            'text' => "Faire confiance à PROTO-9",
            'next_chapter_id' => $ending1->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter3A->id,
            'text' => "Enquêter avec Sam",
            'next_chapter_id' => $ending2->id,
        ]);

        // Choix pour le chapitre 3B
        Choice::create([
            'chapter_id' => $chapter3B->id,
            'text' => "Continuer la mission coûte que coûte",
            'next_chapter_id' => $ending3->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter3B->id,
            'text' => "Tenter de sauver tout le monde",
            'next_chapter_id' => $ending2->id,
        ]);

        // Choix pour le chapitre 3C
        Choice::create([
            'chapter_id' => $chapter3C->id,
            'text' => "Accepter l'aide de PROTO-9",
            'next_chapter_id' => $ending1->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter3C->id,
            'text' => "Rechercher la vérité",
            'next_chapter_id' => $ending2->id,
        ]);

        // Choix pour le chapitre 3D
        Choice::create([
            'chapter_id' => $chapter3D->id,
            'text' => "Confrontation avec PROTO-9",
            'next_chapter_id' => $ending2->id,
        ]);

        Choice::create([
            'chapter_id' => $chapter3D->id,
            'text' => "Tenter un compromis",
            'next_chapter_id' => $ending3->id,
        ]);
    }
}
