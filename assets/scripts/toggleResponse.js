document.addEventListener("DOMContentLoaded", function () {
  const responseBtn = document.getElementById("responseBtn");
  if (responseBtn) {
    responseBtn.addEventListener("click", async function () {
      await toggleResponse(this.dataset.adId, "add");
    });
  }

  const successBtn = document.getElementById("successBtn");
  if (successBtn) {
    successBtn.addEventListener("click", async function () {
      await toggleResponse(this.dataset.adId, "remove");
    });
  }

  async function toggleResponse(adId, action) {
    try {
      // Проверка авторизации
      const authCheck = await fetch("/src/check_auth.php");
      const authData = await authCheck.json();

      if (!authData.logged_in) {
        alert("Для отклика необходимо авторизоваться");
        document.querySelector('[commandfor="auth"]')?.click();
        return;
      }

      const formData = new FormData();
      formData.append("ad_id", adId);

      const response = await fetch("/src/toggle_response.php", {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        alert(result.message);

        if (result.action === "added") {
          responseBtn.classList.add("hidden");
          successBtn.classList.remove("hidden");
        } else if (result.action === "removed") {
          successBtn.classList.add("hidden");
          responseBtn.classList.remove("hidden");
        }
      } else {
        alert(result.message || "Ошибка");
      }
    } catch (error) {
      console.error("Ошибка:", error);
      alert("Ошибка соединения");
    }
  }
});
