<?php

require __DIR__ . '/src/db.php';
require_once __DIR__ . '/src/config.php';
$logged_in = isLoggedIn();

// Получаем все объявления
$stmt = $pdo->query("
    SELECT id, title, price, description, image
    FROM Ads
    ORDER BY id DESC
");
$all_ads = $stmt->fetchAll(PDO::FETCH_ASSOC);

$visible_ads = array_slice($all_ads, 0, 10);
$hidden_ads = array_slice($all_ads, 10);
$has_more_ads = count($all_ads) > 10;

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/assets/styles/style.css" />
  <title>Объявления</title>
</head>

<body>
  <?php require_once __DIR__ . '/src/components/header.php' ?>

  <main>
    <section class="container section__heading">
      <h1>Новые объявления</h1>
      <a href="/add/" class="add__ad">
        <img src="/assets/icons/+.svg" alt="" /><span>Добавить объявление</span>
      </a>
    </section>

    <section class="container ads__grid">
      <?php if (empty($all_ads)): ?>
        <p style="grid-column: 1 / -1; text-align: center; color: #666;">
          Нет объявлений. Будьте первым, кто добавит объявление!
        </p>
      <?php endif; ?>

      <?php foreach ($visible_ads as $ad): ?>
        <?php
        $id = htmlspecialchars($ad['id']);
        $title = htmlspecialchars($ad['title']);
        $price = number_format($ad['price'], 0, '.', ' ');
        $image = base64_encode($ad['image']);
        ?>
        <a href="/ad/<?= $id ?>" class="ads__grid__element">
          <img src="data:image/jpeg;base64,<?= $image ?>" alt="<?= $title ?>" />
          <div>
            <span><?= $price ?> ₽</span>
            <p><?= $title ?></p>
          </div>
        </a>
      <?php endforeach; ?>

      <?php foreach ($hidden_ads as $index => $ad): ?>
        <?php
        $id = htmlspecialchars($ad['id']);
        $title = htmlspecialchars($ad['title']);
        $price = number_format($ad['price'], 0, '.', ' ');
        $image = base64_encode($ad['image']);
        ?>
        <a href="/ad/<?= $id ?>" class="ads__grid__element hidden"
          data-ad-index="<?= $index + 10 ?>">
          <img src="data:image/jpeg;base64,<?= $image ?>" alt="<?= $title ?>" />
          <div>
            <span><?= $price ?> ₽</span>
            <p><?= $title ?></p>
          </div>
        </a>
      <?php endforeach; ?>

      <?php if ($has_more_ads): ?>
        <button class="show-more__button" id="showMoreBtn">
          <img src="/assets/icons/show-more.svg" alt="" />Показать еще
        </button>
      <?php endif; ?>
    </section>
  </main>

  <?php require_once __DIR__ . '/src/components/footer.php' ?>

  <script src="/assets/scripts/checkAuthStatus.js"></script>
  <script src="/assets/scripts/popover.js"></script>
  <script src="/assets/scripts/validation.js"></script>

  <script src="/assets/scripts/showMore.js"></script>
</body>

</html>