// composables/useShowMore.js
import { nextTick, ref } from "vue";

export function useShowMore() {
  const mainContentRefs = ref([]);
  const cardContentRefs = ref([]);
  const showMoreIndexes = ref(new Set());

  const calculateHeights = () => {
    showMoreIndexes.value.clear();

    cardContentRefs.value.forEach((el, index) => {
      const mainContent = el?.querySelector(".main-contents");
      if (!el || !mainContent) return;

      const elHeight = el.offsetHeight;
      const elMainContentHeight = mainContent.offsetHeight;

      if (elHeight < elMainContentHeight) {
        showMoreIndexes.value.add(index);
      }
    });
  };

  const showMoreBtnVisible = (index) => {
    return showMoreIndexes.value.has(index);
  };

  const recalculateOnNextTick = async () => {
    await nextTick();
    calculateHeights();
  };

  return {
    mainContentRefs,
    cardContentRefs,
    showMoreIndexes,
    showMoreBtnVisible,
    recalculateOnNextTick,
  };
}
