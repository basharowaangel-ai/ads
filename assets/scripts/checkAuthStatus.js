document.addEventListener("DOMContentLoaded", function () {
  checkAuthStatus();

  const logoutBtn = document.getElementById("logout-btn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", async function () {
      if (!confirm("Вы уверены, что хотите выйти?")) {
        return;
      }

      try {
        const response = await fetch("/src/logout.php");

        const text = await response.text();
        let data;

        try {
          data = JSON.parse(text);
        } catch (e) {
          console.log("Ответ не JSON:", text);
          location.reload();
          return;
        }

        if (data.success) {
          alert(data.message || "Вы успешно вышли из системы");
          location.reload();
        } else {
          alert("Ошибка при выходе");
        }
      } catch (error) {
        console.error("Ошибка:", error);
        alert("Ошибка соединения с сервером");
      }
    });
  }
});

function checkAuthStatus() {
  fetch("/src/check_auth.php")
    .then((response) => response.json())
    .then((data) => {
      if (data.logged_in) {
        console.log("Пользователь авторизован:", data.user);
      } else {
        console.log("Пользователь не авторизован");
      }
    })
    .catch((error) => {
      console.log("Ошибка проверки авторизации:", error);
    });
}
