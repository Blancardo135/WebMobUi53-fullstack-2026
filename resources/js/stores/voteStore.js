import { ref } from 'vue';
import { useFetchApi } from '../composables/useFetchApi';

export function voteStore(poll) {
    const { fetchApi } = useFetchApi();

    const selectedOptionIds = ref([]);
    const loading = ref(false);
    const error = ref(null);
    const success = ref(false); // deviendra true ensuite

    const changeOption = (optionId) => {
        if (poll.allow_multiple_choices) {
            //deja selectionée
            if (selectedOptionIds.value.includes(optionId)) {
                //deselectionne
                selectedOptionIds.value = selectedOptionIds.value.filter(id => id !== optionId);
            } else {
                selectedOptionIds.value.push(optionId);
            }
        } else {
            //cas unique
            selectedOptionIds.value = [optionId];
        }
    };

    const submitVote = async (onSuccess) => {
        loading.value = true;
        error.value = null;
        try {
            await fetchApi({
                url: `polls/${poll.id}/vote`,
                method: 'POST',
                data: {
                    option_ids: selectedOptionIds.value
                },
            });
            success.value = true;
            //mon callback
            if (onSuccess) onSuccess();
        } catch (err) {
            error.value = 'Erreur lors du vote'
            loading.value = false;
        }
    };
    return { selectedOptionIds, loading, error, success, changeOption, submitVote }
}