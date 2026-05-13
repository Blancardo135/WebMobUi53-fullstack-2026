import { ref } from 'vue';

const currentView = ref('table');
const editingPoll = ref(null);

export function useRoute() {
    const showPollsTable = () => {
        currentView.value = 'table';
        editingPoll.value = null;
    };

    const showCreateForm = () => {
        currentView.value = 'create';
        editingPoll.value = null;
    };

    const showEditForm = (poll) => {
        currentView.value = 'edit';
        editingPoll.value = poll;
    };

    return { currentView, editingPoll, showPollsTable, showCreateForm, showEditForm };
}

/*me permet de mieux gérer mon routing et d'éviter le window.location.reload() que
j'avais avant. C'est le routing comme on a vu en cour. J'aurai pu utiliser une fonction navigateTo en
passant juste des paramètres view et poll, mais c'est + lisible pour moi.
Inconvénient ici, je dois rajouter chaque nouvelle vue manuellement. */