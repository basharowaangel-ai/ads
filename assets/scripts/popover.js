document.addEventListener("DOMContentLoaded", () => {
  const popovers = document.querySelectorAll("[popover]");

  // Функция закрытия всех поповеров
  const closeAll = () => popovers.forEach((p) => p.hidePopover());

  // Обработка всех кликов на кнопках с command
  document.addEventListener("click", (e) => {
    const btn = e.target.closest("[command]");
    if (!btn) return;

    const target = document.getElementById(btn.getAttribute("commandfor"));
    if (!target) return;

    e.preventDefault();
    e.stopPropagation();

    if (btn.getAttribute("command") === "show-popover") {
      closeAll();
      target.showPopover();
      document.body.style.overflow = 'hidden';
    } else {
      target.hidePopover();
      document.body.style.overflow = 'auto';
    }
  });

  // Клик вне поповера
  document.addEventListener("click", (e) => {
    if (
      !e.target.closest("[popover]") &&
      !e.target.closest('[command="show-popover"]')
    ) {
      closeAll();
    }
  });

  // ESC
  document.addEventListener("keydown", (e) => e.key === "Escape" && closeAll());
});
