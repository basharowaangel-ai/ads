<?php
require __DIR__ . '/src/config.php';
require __DIR__ . '/src/db.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
  http_response_code(404);
  exit('Not found');
}

$stmt = $pdo->prepare("
    SELECT a.id, a.title, a.price, a.description, a.image, 
           a.user_id, u.name, u.phone, u.email
    FROM Ads a
    JOIN Users u ON a.user_id = u.id
    WHERE a.id = :ad_id
    LIMIT 1
");

$stmt->execute(['ad_id' => $id]);
$ad = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ad) {
  http_response_code(404);
  exit('Not found');
}

$descr = htmlspecialchars($ad['description']);
$title = htmlspecialchars($ad['title']);
$price = number_format($ad['price'], 0, '.', ' ');
$image = base64_encode($ad['image']);

$user_name = htmlspecialchars($ad['name']);
$user_id = $ad['user_id'];

function formatPhone($phone)
{
  $phone = preg_replace('/[^0-9]/', '', $phone);

  if (substr($phone, 0, 1) === '8') {
    $phone = '7' . substr($phone, 1);
  }

  if (substr($phone, 0, 1) === '7' && strlen($phone) === 11) {
    $code = substr($phone, 1, 3);
    $part1 = substr($phone, 4, 3);
    $part2 = substr($phone, 7, 2);
    $part3 = substr($phone, 9, 2);
    return "+7 $code-$part1-$part2-$part3";
  }

  return $phone;
}

$user_phone = formatPhone($ad['phone']);

// Проверяем, откликнулся ли текущий пользователь
$current_user_responded = false;
if (isLoggedIn()) {
  $check_stmt = $pdo->prepare("SELECT id FROM Responses WHERE ad_id = ? AND user_id = ?");
  $check_stmt->execute([$id, $_SESSION['user_id']]);
  $current_user_responded = $check_stmt->fetch() ? true : false;
}

$rspns = $pdo->prepare("
  SELECT r.id, r.user_id, r.created_at, 
           u.name, u.phone, u.email
    FROM Responses r
    JOIN Users u ON r.user_id = u.id
    WHERE r.ad_id = :ad_id
    ORDER BY r.created_at DESC
");

$rspns->execute(['ad_id' => $id]);
$responses = $rspns->fetchAll(PDO::FETCH_ASSOC);

// Проверяем, является ли текущий пользователь автором объявления
$is_author = isLoggedIn() && ($_SESSION['user_id'] == $user_id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="/assets/styles/style.css" />
  <title><?= htmlspecialchars($title) ?> - Объявления</title>
</head>

<body>
  <?php require_once __DIR__ . '/src/components/header.php' ?>

  <main class="ad__main">
    <section class="container ad__section">
      <div half left>
        <img main src="data:image/jpeg;base64,<?= $image ?>" alt="<?= htmlspecialchars($title) ?>" />

        <?php if (!empty($responses)): ?>
          <div desktop class="responses">
            <h3>Откликнулись<sup><?= count($responses) ?></sup></h3>
            <ul class="responses__list">
              <?php foreach ($responses as $rsp): ?>
                <li>
                  <span name><?= htmlspecialchars($rsp['name']) ?></span>
                  <span phone><?= formatPhone($rsp['phone']) ?></span>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

      </div>
      <div half right>
        <div class="price__wrapper">
          <h2><?= $price ?> ₽</h2>
          <a href="/" non-mobile class="back__button">
            <img src="/assets/icons/arrow-back.svg" alt="" />Назад
          </a>
        </div>
        <p title><?= $title ?></p>
        <p class="seller__info">
          <span phone><?= $user_phone ?></span>
          <span name><?= $user_name ?></span>
        </p>
        <div class="buttons__wrapper-mobile">
          <?php if ($is_author): ?>
            <div class="author__notice">
              Это ваше объявление. Вы не можете откликаться на него.
            </div>
          <?php else: ?>
            <button class="apply__button <?= $current_user_responded ? 'hidden' : '' ?>"
              id="responseBtn"
              data-ad-id="<?= $id ?>">
              Откликнуться
            </button>

            <button class="success__button <?= !$current_user_responded ? 'hidden' : '' ?>"
              id="successBtn"
              data-ad-id="<?= $id ?>">
              <img src="/assets/icons/done.svg" alt="" />Вы откликнулись
            </button>
          <?php endif; ?>

          <a href="/" mobile class="back__button">
            <img src="/assets/icons/arrow-back.svg" alt="" />Назад
          </a>
        </div>

        <?php if (!empty($descr)): ?>
          <p desc desktop>
            <?= $descr ?>
          </p>
        <?php endif; ?>
      </div>

      <?php if (!empty($descr)): ?>
        <p desc mobile>
          <?= $descr ?>
        </p>
      <?php endif; ?>

      <?php if (!empty($responses)): ?>
        <div mobile class="container responses">
          <h3>Откликнулись<sup><?= count($responses) ?></sup></h3>
          <ul class="responses__list">
            <?php foreach ($responses as $rsp): ?>
              <li>
                <span name><?= htmlspecialchars($rsp['name']) ?></span>
                <span phone><?= formatPhone($rsp['phone']) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
    </section>
  </main>

  <?php require_once __DIR__ . '/src/components/footer.php' ?>

  <script src="/assets/scripts/checkAuthStatus.js"></script>
  <script src="/assets/scripts/popover.js"></script>
  <script src="/assets/scripts/validation.js"></script>
  <script src="/assets/scripts/toggleResponse.js"></script>
</body>

</html>