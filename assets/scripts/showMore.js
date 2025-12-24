document.addEventListener("DOMContentLoaded", function () {
  const showMoreBtn = document.getElementById("showMoreBtn");
  const hiddenAds = document.querySelectorAll(".ads__grid__element.hidden");
  let adsToShow = 10;
  let currentIndex = 0;

  if (showMoreBtn && hiddenAds.length > 0) {
    showMoreBtn.addEventListener("click", function () {
      const endIndex = Math.min(currentIndex + adsToShow, hiddenAds.length);

      for (let i = currentIndex; i < endIndex; i++) {
        hiddenAds[i].classList.remove("hidden");
      }

      currentIndex = endIndex;

      if (currentIndex >= hiddenAds.length) {
        showMoreBtn.classList.add("hidden");
        showMoreBtn.innerHTML =
          '<img src="/assets/icons/done.svg" alt="" />Все объявления показаны';
      } else {
        const remaining = hiddenAds.length - currentIndex;
        showMoreBtn.innerHTML = `<img src="/assets/icons/show-more.svg" alt="" />Показать еще (осталось ${remaining})`;
      }
    });
  }
});
