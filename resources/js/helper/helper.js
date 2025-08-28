import useUserStore from "@/store/useUserStore";

export const hasPermission = (permission) => {
  const permissions = useUserStore().permissions;
  return permissions.includes(permission);
}

export const hasPermissions = (permissions) => {
  const userPermissions = useUserStore().permissions;
  return userPermissions.some((permission) => permissions.includes(permission))
}

export const showFullText = (e) => {
  let card = e.target.closest(".main-card");
  let cardContent;
  let showMoreBtn;
  if (card) {
    cardContent = card.querySelector(".card-contents");
    showMoreBtn = card.querySelector(".show-more-btn");

    cardContent.classList.toggle("h-100");
    showMoreBtn.textContent = cardContent.classList.contains("h-100")
      ? "Show less"
      : "Show more";
  }
};
