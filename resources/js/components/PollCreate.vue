<script setup>
import {ref} from 'vue';
import { useFetchApi } from '../composables/useFetchApi';

const {fetchApi} = useFetchApi();

const title = ref('');
const question = ref('');
const options = ref([{ label: '' }, { label: '' }]);
const isDraft = ref(true);
const allowMultipleChoices = ref(false);
const allowVoteChange = ref(false);
const resultsPublic = ref(false);
const duration = ref(null);

const loading = ref(false);
const error = ref(null);

const addOption = () => options.value.push({label: ''});
const removeOption = (index) => options.value.splice(index,1);

const createPoll = async()=>{
    loading.value = true; //en lien avec l'état du btn
    error.value = null;
    try{
        await fetchApi({
            url: '/polls',
            method: 'POST',
            data: {
                question: question.value,
                options: options.value,
                is_draft: isDraft.value,
                allow_multiple_choices: allowMultipleChoices.value,
                allow_vote_change: allowVoteChange.value,
                results_public: resultsPublic.value,
                duration: duration.value,
                title: title.value,
      },
        });
        window.location.reload();
    } catch(err){
        error.value = 'Erreur de création.';
        loading.value = false;
    }
}
</script>

<template>
<div class="max-w-lg space-y-4">
    <!-- espace pr mon titre -->
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
        <input v-model="title" type="text" placeholder="Titre du sondage (optionnel)" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
        
    </div>

    <!-- espace pr ma question -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Question</label>
      <input v-model="question" type="text" placeholder="Quelle est votre question ?" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
    </div>

    <!-- espace pr definir mes options -->
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Options</label>
        <!-- utile davoir le index pr supprimer au cas ou et pr la key -->
        <div v-for="(option, index) in options" :key="index" class="flex gap-2">
            <input
            v-model="option.label"
            type="text"
            :placeholder="`Option ${index+1}`"
            class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
            <button
            v-if="options.length > 2"
            @click="removeOption(index)"
             class="text-red-400 hover:text-red-600 px-2 text-sm"
            >Supprimer l'option</button>
        </div>
        <button
        @click="addOption"
        class="text-sm text-blue-600 hover:underline">
        Ajouter une option
        </button>
        </div>

        <!-- parametres de mon poll -->
        <div class="space-y-2 rounded-lg border border-gray-200 p-4">
        <p class="text-sm font-medium text-gray-700 mb-2">Paramètres</p>
        
        <div class="flex items-center gap-2">
            <input type="checkbox" id="isDraft" v-model="isDraft" />
            <label for="isDraft" class="text-sm text-gray-700">Brouillon</label>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" id="allowMultipleChoices" v-model="allowMultipleChoices" />
            <label for="allowMultipleChoices" class="text-sm text-gray-700">Choix multiples</label>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" id="allowVoteChange" v-model="allowVoteChange" />
            <label for="allowVoteChange" class="text-sm text-gray-700">Changement de vote possible</label>
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" id="resultsPublic" v-model="resultsPublic" />
            <label for="resultsPublic" class="text-sm text-gray-700">Résultats publics</label>
        </div>
        <div class="flex items-center gap-2 pt-1">
            <input type="number" id="duration" v-model="duration" min="1"
            class="w-24 rounded-lg border border-gray-300 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            <label for="duration" class="text-sm text-gray-700">Durée du sondage (jours)</label>
        </div>
        
        </div>
        <p v-if="error" class="text-sm text-red-500">{{ error }}</p>

        <!-- Btn pr creer -->
        <button
        @click="createPoll"
        :disabled="loading"
        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
        >
        {{ loading ? 'En cours de création...' : 'Créer le sondage' }}
        </button>
</div>
</template>