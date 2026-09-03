import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useAppStore = defineStore('app', () => {
    const isSidebarOpen = ref(true);
    const activeBookingStep = ref(1);

    function toggleSidebar() {
        isSidebarOpen.value = !isSidebarOpen.value;
    }

    function setBookingStep(step: number) {
        activeBookingStep.value = step;
    }

    return {
        isSidebarOpen,
        activeBookingStep,
        toggleSidebar,
        setBookingStep,
    };
});
