import { createRouter, createWebHistory } from 'vue-router';
import HomeView from '@/components/HomeView.vue';
import ChapterView from '@/components/ChapterView.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView
        },
        {
            path: '/chapter',
            redirect: '/chapter/1'
        },
        {
            path: '/chapter/:id',
            name: 'chapter',
            component: ChapterView,
            props: true
        }
    ]
});

export default router; 