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
        // Garder uniquement les histoires "Échos de l'Orbite", "Alunissage" et "Livraison Lunaire"

        // --- HISTOIRE 2 : ÉCHOS DE L'ORBITE ---
        $story2 = Story::create([
            'title' => "Échos de l'Orbite",
            'description' => "Une panne inexpliquée, une station orbitale déserte, un réveil en urgence. Vous êtes Kael, technicien à bord de la station Atlas. Votre mission : comprendre ce qui s'est passé, et faire des choix qui pourraient sauver une vie... ou condamner la vôtre."
        ]);

        // Chapitre 1
        $ch1 = Chapter::create([
            'story_id' => $story2->id,
            'title' => "Réveil d'urgence",
            'content' => "Vous ouvrez les yeux dans la capsule de stase. L'alarme retentit. La gravité est instable. Un écran clignote : 'Incident critique. Intervention requise.' Vous êtes seul, la station semble silencieuse.",
            'is_ending' => false,
        ]);
        $ch2A = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Sortir dans le couloir',
            'content' => "Vous ouvrez la porte. Le couloir est plongé dans la pénombre. Un bruit sourd résonne plus loin. Une odeur de brûlé flotte dans l'air.",
            'is_ending' => false,
        ]);
        $ch2B = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Tenter la radio',
            'content' => "Vous tentez d'activer la radio d'urgence. Un grésillement, puis une voix faible : '...Kael ?... Besoin d'aide... module médical...'. La communication coupe.",
            'is_ending' => false,
        ]);
        Choice::create(['chapter_id' => $ch1->id, 'text' => "Sortir dans le couloir", 'next_chapter_id' => $ch2A->id]);
        Choice::create(['chapter_id' => $ch1->id, 'text' => "Tenter la radio", 'next_chapter_id' => $ch2B->id]);

        // Couloir : choix explorer ou suivre le bruit
        $ch3A = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Explorer le couloir',
            'content' => "Vous avancez prudemment. Vous trouvez une trousse de secours et un panneau arraché. Soudain, un choc secoue la station.",
            'is_ending' => false,
        ]);
        $ch3B = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Suivre le bruit',
            'content' => "Vous suivez le bruit jusqu'au module médical. Lina, blessée, est allongée au sol. Elle murmure : 'Quelqu'un a saboté la station...'.",
            'is_ending' => false,
        ]);
        Choice::create(['chapter_id' => $ch2A->id, 'text' => "Explorer le couloir", 'next_chapter_id' => $ch3A->id]);
        Choice::create(['chapter_id' => $ch2A->id, 'text' => "Aller au module médical", 'next_chapter_id' => $ch3B->id]);

        // Radio : choix rejoindre la voix ou rester
        $ch4A = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Rejoindre le module médical',
            'content' => "Vous courez vers le module médical. Lina est là, blessée. Elle vous supplie de l'aider à réparer la station.",
            'is_ending' => false,
        ]);
        $ch4B = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Rester et sécuriser la capsule',
            'content' => "Vous restez dans la capsule, vérifiez les systèmes. L'oxygène baisse lentement. Le silence devient pesant.",
            'is_ending' => false,
        ]);
        Choice::create(['chapter_id' => $ch2B->id, 'text' => "Rejoindre la voix", 'next_chapter_id' => $ch4A->id]);
        Choice::create(['chapter_id' => $ch2B->id, 'text' => "Rester dans la capsule", 'next_chapter_id' => $ch4B->id]);

        // Module médical : choix aider Lina ou chercher le saboteur
        $ch5A = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Aider Lina',
            'content' => "Vous aidez Lina à se relever. Ensemble, vous tentez de relancer les systèmes. Elle vous fait confiance.",
            'is_ending' => false,
        ]);
        $ch5B = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Chercher le saboteur',
            'content' => "Vous laissez Lina et partez inspecter la station. Un bruit de pas précipités s'éloigne dans la coursive.",
            'is_ending' => false,
        ]);
        Choice::create(['chapter_id' => $ch3B->id, 'text' => "Aider Lina", 'next_chapter_id' => $ch5A->id]);
        Choice::create(['chapter_id' => $ch3B->id, 'text' => "Chercher le saboteur", 'next_chapter_id' => $ch5B->id]);
        Choice::create(['chapter_id' => $ch4A->id, 'text' => "Aider Lina", 'next_chapter_id' => $ch5A->id]);
        Choice::create(['chapter_id' => $ch4A->id, 'text' => "Chercher le saboteur", 'next_chapter_id' => $ch5B->id]);

        // Explorer le couloir : choix réparer ou attendre
        $ch6A = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Tenter une réparation',
            'content' => "Vous tentez de réparer le panneau arraché. L'énergie revient, mais un message d'alerte s'affiche : 'Fuite détectée'.",
            'is_ending' => false,
        ]);
        $ch6B = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Attendre',
            'content' => "Vous attendez, espérant un signe. L'oxygène baisse. Le froid s'installe.",
            'is_ending' => false,
        ]);
        Choice::create(['chapter_id' => $ch3A->id, 'text' => "Tenter une réparation", 'next_chapter_id' => $ch6A->id]);
        Choice::create(['chapter_id' => $ch3A->id, 'text' => "Attendre", 'next_chapter_id' => $ch6B->id]);

        // Fins
        $end1 = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Fin : Sauvetage',
            'content' => "Vous et Lina parvenez à relancer la station. Un signal de détresse est capté. Les secours arrivent. Vous êtes sauvés.",
            'is_ending' => true,
        ]);
        $end2 = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Fin : Isolement',
            'content' => "Vous restez seul dans la station, l'oxygène s'épuise. Personne ne répond à vos appels.",
            'is_ending' => true,
        ]);
        $end3 = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Fin : Saboteur',
            'content' => "Vous surprenez le saboteur, mais il s'échappe dans une capsule. Vous survivez, mais le mystère demeure.",
            'is_ending' => true,
        ]);
        $end4 = Chapter::create([
            'story_id' => $story2->id,
            'title' => 'Fin : Fuite',
            'content' => "Vous quittez la station dans une capsule de secours, laissant Lina derrière vous. Vous survivez, mais à quel prix ?",
            'is_ending' => true,
        ]);
        // Vers les fins
        Choice::create(['chapter_id' => $ch5A->id, 'text' => "Relancer la station avec Lina", 'next_chapter_id' => $end1->id]);
        Choice::create(['chapter_id' => $ch5A->id, 'text' => "Fuir seul", 'next_chapter_id' => $end4->id]);
        Choice::create(['chapter_id' => $ch5B->id, 'text' => "Poursuivre le saboteur", 'next_chapter_id' => $end3->id]);
        Choice::create(['chapter_id' => $ch5B->id, 'text' => "Revenir vers Lina", 'next_chapter_id' => $end2->id]);
        Choice::create(['chapter_id' => $ch6A->id, 'text' => "Envoyer un signal de détresse", 'next_chapter_id' => $end1->id]);
        Choice::create(['chapter_id' => $ch6A->id, 'text' => "Quitter la station", 'next_chapter_id' => $end4->id]);
        Choice::create(['chapter_id' => $ch6B->id, 'text' => "Attendre la fin", 'next_chapter_id' => $end2->id]);

        // --- HISTOIRE 4 : ALUNISSAGE ---
        $story4 = Story::create([
            'title' => "Alunissage",
            'description' => "2039. Vous êtes pilote de module pour une mission logistique internationale. Aujourd'hui, vous devez vous poser sur la Lune avec un container de matériel vital pour une base scientifique. Mais à l'approche, une série d'imprévus vous oblige à faire des choix. Chaque décision peut affecter votre survie, celle des autres… et la suite de la mission."
        ]);

        $ch1 = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Approche lunaire',
            'content' => "Votre module descend lentement vers la surface lunaire. Le sol gris et plat de la Mer de la Tranquillité s'étend devant vous. Le contact radio avec la base Galileo est intermittent. Un message brouillé vous parvient : '...chambre… pression… manuelle…'",
            'is_ending' => false,
        ]);

        $ch2A = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Suivre le protocole d\'atterrissage',
            'content' => "Vous enclenchez le protocole standard d'approche automatique. L'assistance gyroscopique stabilise la descente. Altitude : 300m. Mais un vent solaire perturbe soudain la navigation. Le module tangue légèrement.",
            'is_ending' => false,
        ]);

        $ch2B = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Passer en pilotage manuel',
            'content' => "Vous désactivez l'assistance automatique et prenez les commandes. La descente est plus lente mais stable. Vous devez choisir une zone d'atterrissage manuellement. Trois options : proche de la base, sur un plateau, ou en terrain dégagé mais plus éloigné.",
            'is_ending' => false,
        ]);

        Choice::create([
            'chapter_id' => $ch1->id,
            'text' => "Faire confiance au pilote automatique",
            'next_chapter_id' => $ch2A->id,
        ]);
        Choice::create([
            'chapter_id' => $ch1->id,
            'text' => "Prendre les commandes à la main",
            'next_chapter_id' => $ch2B->id,
        ]);

        $ch3A = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Turbulences et correction',
            'content' => "Le tangage s'amplifie. L'ordinateur vous suggère une correction d'urgence. Acceptez-vous l'intervention automatique ou tentez-vous de reprendre le contrôle à la main pour poser en sécurité ?",
            'is_ending' => false,
        ]);
        $ch3B = Chapter::create([
            'story_id' => $story4->id,
            'title' => "Choix du site d'atterrissage",
            'content' => "Vous voyez trois options :\n- Le plateau surélevé : plus stable mais exposé au vent solaire.\n- Le terrain proche de la base : rapide pour livrer, mais parsemé de roches.\n- Une zone plane plus au sud : sécurisée, mais 1,4 km à pied de la base.",
            'is_ending' => false,
        ]);

        Choice::create([
            'chapter_id' => $ch2A->id,
            'text' => "Laisser l'ordinateur corriger la trajectoire",
            'next_chapter_id' => $ch3A->id,
        ]);
        Choice::create([
            'chapter_id' => $ch2A->id,
            'text' => "Désactiver l'aide et piloter en manuel",
            'next_chapter_id' => $ch3B->id,
        ]);
        Choice::create([
            'chapter_id' => $ch2B->id,
            'text' => "Se poser sur le plateau",
            'next_chapter_id' => $ch3A->id,
        ]);
        Choice::create([
            'chapter_id' => $ch2B->id,
            'text' => "Choisir la zone proche de la base",
            'next_chapter_id' => $ch3B->id,
        ]);
        Choice::create([
            'chapter_id' => $ch2B->id,
            'text' => "Préférer la zone plane au sud",
            'next_chapter_id' => $ch3B->id,
        ]);

        $ch4A = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Atterrissage risqué',
            'content' => "Vous tentez de poser le module malgré les turbulences. L'atterrissage est brutal. Un pied du train d'atterrissage s'enfonce dans une cavité invisible. Le module penche, mais reste stable. L'électronique semble avoir pris un coup.",
            'is_ending' => false,
        ]);

        $ch4B = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Arrivée au sol',
            'content' => "Vous vous posez sans incident. Le paysage lunaire est immobile. La base Galileo n'est qu'à quelques centaines de mètres. Aucun signe d'activité visible. Vous préparez la sortie.",
            'is_ending' => false,
        ]);

        Choice::create([
            'chapter_id' => $ch3A->id,
            'text' => "Accepter la correction automatique",
            'next_chapter_id' => $ch4B->id,
        ]);
        Choice::create([
            'chapter_id' => $ch3A->id,
            'text' => "Tenter un atterrissage manuel d'urgence",
            'next_chapter_id' => $ch4A->id,
        ]);
        Choice::create([
            'chapter_id' => $ch3B->id,
            'text' => "Poser en zone proche de la base",
            'next_chapter_id' => $ch4B->id,
        ]);
        Choice::create([
            'chapter_id' => $ch3B->id,
            'text' => "Tenter le plateau malgré les risques",
            'next_chapter_id' => $ch4A->id,
        ]);

        $ch5A = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Entrée dans la base',
            'content' => "Vous atteignez la base Galileo. Deux accès : le sas secondaire (clignote en rouge) ou la porte principale (semble bloquée).",
            'is_ending' => false,
        ]);
        $ch5B = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Isolement sur la Lune',
            'content' => "Vous restez dans le module, hésitant à sortir. Les réserves d'oxygène baissent. Il faut agir vite.",
            'is_ending' => false,
        ]);

        Choice::create(['chapter_id' => $ch4A->id, 'text' => "Sortir et tenter d'entrer dans la base", 'next_chapter_id' => $ch5A->id]);
        Choice::create(['chapter_id' => $ch4A->id, 'text' => "Rester dans le module", 'next_chapter_id' => $ch5B->id]);
        Choice::create(['chapter_id' => $ch4B->id, 'text' => "Aller au sas secondaire", 'next_chapter_id' => $ch5A->id]);
        Choice::create(['chapter_id' => $ch4B->id, 'text' => "Attendre dans le module", 'next_chapter_id' => $ch5B->id]);

        $end1 = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Fin : Sauvetage',
            'content' => "Vous parvenez à entrer dans la base, à relancer un signal de détresse. Une mission de secours arrive. Vous survivez.",
            'is_ending' => true,
        ]);
        $end2 = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Fin : Isolement',
            'content' => "Vous restez trop longtemps dans le module. L'oxygène s'épuise. Vous laissez un dernier message à la Terre.",
            'is_ending' => true,
        ]);
        $end3 = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Fin : Accident',
            'content' => "En forçant la porte principale, un court-circuit déclenche une dépressurisation. Vous n'avez pas le temps de réagir.",
            'is_ending' => true,
        ]);
        $end4 = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Fin : Autonomie',
            'content' => "Vous réparez le module, survivez plusieurs jours, et finissez par rejoindre la base à pied. Vous devenez un héros lunaire.",
            'is_ending' => true,
        ]);
        $end5 = Chapter::create([
            'story_id' => $story4->id,
            'title' => 'Fin : Mystère',
            'content' => "En explorant la base, vous découvrez un laboratoire secret. Un message crypté laisse entendre que la mission n'était pas ce qu'elle semblait être…",
            'is_ending' => true,
        ]);

        Choice::create(['chapter_id' => $ch5A->id, 'text' => "Relancer un signal de détresse", 'next_chapter_id' => $end1->id]);
        Choice::create(['chapter_id' => $ch5A->id, 'text' => "Forcer la porte principale", 'next_chapter_id' => $end3->id]);
        Choice::create(['chapter_id' => $ch5A->id, 'text' => "Explorer le laboratoire", 'next_chapter_id' => $end5->id]);
        Choice::create(['chapter_id' => $ch5B->id, 'text' => "Réparer le module et tenter de survivre", 'next_chapter_id' => $end4->id]);
        Choice::create(['chapter_id' => $ch5B->id, 'text' => "Attendre les secours", 'next_chapter_id' => $end2->id]);

        // SUPPRESSION DES HISTOIRES 1 ET 3
        // AJOUT DE L'HISTOIRE "LIVRAISON LUNAIRE"
        $story = Story::create([
            'title' => "Livraison Lunaire",
            'description' => "2039. Vous êtes pilote de module cargo. Votre mission : livrer du matériel essentiel à une station scientifique sur la Lune. Une tâche simple ? Pas exactement."
        ]);

        // Introduction
        $ch1 = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Mission : Galileo-12',
            'content' => "Votre nom est Jules Narek. Vous pilotez le cargo lunaire Galileo-12. À votre bord : trois conteneurs de vivres, un générateur de secours et un drone d'exploration. Destination : la base scientifique Polaris, côté nuit de la Lune. Votre mission est censée durer 6h, aller-retour compris.",
            'is_ending' => false,
        ]);

        $ch2A = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Itinéraire direct',
            'content' => "Vous prenez le couloir direct balisé par les balises de surface. Cela vous fait passer au-dessus du cratère Demios. Avantage : c'est plus rapide. Risque : exposition aux radiations solaires en cas de pic imprévu.",
            'is_ending' => false,
        ]);

        $ch2B = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Itinéraire sécurisé',
            'content' => "Vous activez le plan B : longer la ligne d'ombre de l'horizon lunaire, à l'abri des radiations. Le vol est plus long et vous oblige à effectuer des corrections de trajectoire fréquentes.",
            'is_ending' => false,
        ]);

        Choice::create(['chapter_id' => $ch1->id, 'text' => "Suivre l'itinéraire direct", 'next_chapter_id' => $ch2A->id]);
        Choice::create(['chapter_id' => $ch1->id, 'text' => "Passer par l'itinéraire sécurisé", 'next_chapter_id' => $ch2B->id]);

        $ch3A = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Panne solaire',
            'content' => "Alors que vous approchez du cratère Demios, vos capteurs signalent une micro-tempête solaire. Trop tard pour rebrousser chemin. Vous avez le choix : activer le blindage d'urgence, ou tenter une accélération pour passer au travers.",
            'is_ending' => false,
        ]);

        $ch3B = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Dérive de navigation',
            'content' => "Les corrections de trajectoire fatiguent vos systèmes. Vous constatez une légère dérive. Polaris se trouve à 3,5 km de la trajectoire initiale. Vous pouvez recalculer, ou tenter un atterrissage approximatif à vue.",
            'is_ending' => false,
        ]);

        Choice::create(['chapter_id' => $ch2A->id, 'text' => "Activer le blindage d'urgence", 'next_chapter_id' => $ch3A->id]);
        Choice::create(['chapter_id' => $ch2A->id, 'text' => "Accélérer et traverser la tempête", 'next_chapter_id' => $ch3B->id]);

        Choice::create(['chapter_id' => $ch2B->id, 'text' => "Recalculer la trajectoire automatiquement", 'next_chapter_id' => $ch3A->id]);
        Choice::create(['chapter_id' => $ch2B->id, 'text' => "Tenter un atterrissage à vue", 'next_chapter_id' => $ch3B->id]);

        $ch4A = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Atterrissage difficile',
            'content' => "Le module tangue, l'altitude diminue trop vite. Vous enclenchez les rétropropulseurs. L'atterrissage est rude, mais vous êtes en un seul morceau. Le cargo a subi quelques dégâts.",
            'is_ending' => false,
        ]);

        $ch4B = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Atterrissage stable',
            'content' => "Grâce à vos ajustements constants, vous vous posez en douceur près de la base Polaris. La communication avec la base semble partiellement fonctionnelle. Aucun signe de mouvement à l'extérieur.",
            'is_ending' => false,
        ]);

        Choice::create(['chapter_id' => $ch3A->id, 'text' => "Atterrir en urgence malgré les alertes", 'next_chapter_id' => $ch4A->id]);
        Choice::create(['chapter_id' => $ch3B->id, 'text' => "Rejoindre la base à vue", 'next_chapter_id' => $ch4B->id]);

        $ch5A = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Livraison réussie',
            'content' => "Vous atteignez l'entrée de la base. Le sas fonctionne manuellement. À l'intérieur, deux scientifiques vous accueillent. Ils n'avaient plus de contact depuis 72h. Grâce à vous, leur mission peut reprendre. Vous redécollez, mission accomplie.",
            'is_ending' => true,
        ]);

        $ch5B = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Avarie critique',
            'content' => "En forçant l'atterrissage, une conduite d'oxygène s'est rompue. Vous ne pouvez pas redécoller sans aide. Vous survivez, mais restez bloqué en attendant une mission de secours.",
            'is_ending' => true,
        ]);

        $ch5C = Chapter::create([
            'story_id' => $story->id,
            'title' => 'Collision mortelle',
            'content' => "L'accélération au mauvais moment a été fatale. Vous percutez un affleurement rocheux non détecté. Le Galileo-12 s'écrase violemment. La mission est un échec tragique.",
            'is_ending' => true,
        ]);

        Choice::create(['chapter_id' => $ch4A->id, 'text' => "Rechercher une entrée dans la base malgré les dégâts", 'next_chapter_id' => $ch5A->id]);
        Choice::create(['chapter_id' => $ch4A->id, 'text' => "Rester dans le module et attendre", 'next_chapter_id' => $ch5B->id]);
        Choice::create(['chapter_id' => $ch4B->id, 'text' => "Trop confiant, vous accélérez vers la rampe d'amarrage", 'next_chapter_id' => $ch5C->id]);
        Choice::create(['chapter_id' => $ch4B->id, 'text' => "Descendre prudemment à pied avec les conteneurs", 'next_chapter_id' => $ch5A->id]);
    }
}
