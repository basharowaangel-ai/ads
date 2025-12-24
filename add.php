<?php
require_once __DIR__ . '/src/config.php';
requireAuth();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/assets/styles/style.css" />
  <title>Добавить объявление</title>
</head>

<body>

  <?php require_once __DIR__ . '/src/components/header.php' ?>

  <main class="ad__main">
    <section class="container add__section">
      <form add action="" method="post" enctype="multipart/form-data">
        <div class="add__inputs">
          <div class="file__wrapper">
            <input type="file" id="add-file" name="image" accept="image/*" required />
            <label for="add-file" class="file-input-label">
              <img src="/assets/images/load.png" alt="Добавить файл" />
              <span style="display: block; margin-top: 10px; font-size: 14px; color: #666;">
                Нажмите для загрузки изображения
              </span>
            </label>
          </div>
          <div class="text__inputs">
            <input type="text" id="add-name" name="title" placeholder="Название" required />
            <input type="text" id="add-price" name="price" placeholder="Цена" required />
            <input type="text" id="add-desc" name="description" placeholder="Описание" required />
            <div class="add__button-and-alert">
              <button pink type="submit">Опубликовать объявление</button>
              <span alert>
                <img src="/assets/icons/info.svg" alt="" />
                Все поля обязательны для заполнения
              </span>
            </div>
          </div>
        </div>
      </form>
    </section>
  </main>

  <?php require_once __DIR__ . '/src/components/footer.php' ?>

  <script src="/assets/scripts/checkAuthStatus.js"></script>
  <script src="/assets/scripts/popover.js"></script>
  <script src="/assets/scripts/validation.js"></script>

  <script src="/assets/scripts/previewImage.js"></script>
</body>

</html>