<?php

require __DIR__ . '/../db.php';

require_once __DIR__ . '/../config.php';
$logged_in = isLoggedIn(); ?>

<header class="container">
    <a href="/" class="logo">
        <svg
            width="40"
            height="40"
            viewBox="0 0 40 40"
            fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <rect
                x="2"
                y="2"
                width="36"
                height="36"
                rx="6"
                stroke="#FF006B"
                stroke-width="4" />
            <rect x="7" y="22" width="20" height="4" rx="2" fill="#FF006B" />
            <rect x="2" y="15" width="36" height="4" rx="2" fill="#FF006B" />
            <rect x="7" y="29" width="26" height="4" rx="2" fill="#FF006B" />
        </svg>
        <h1>Объявления</h1>
    </a>
    <?php if (!$logged_in): ?>
        <div class="header__buttons">
            <button open-popover commandfor="register" command="show-popover">
                Регистрация
            </button>
            <div popover id="register">
                <div class="top__wrapper">
                    <h2 class="register__heading">Регистрация</h2>
                    <button
                        commandfor="auth"
                        command="show-popover"
                        class="auth__button">
                        Авторизация
                    </button>
                </div>

                <button commandfor="register" command="hide-popover">
                    <img src="/assets/icons/close.svg" alt="" />
                </button>
                <form reg action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="reg__inputs">
                        <div class="input__wrapper">
                            <input type="text" id="reg-name" placeholder=" " required />
                            <label for="auth-email">Ваше имя</label>
                        </div>
                        <div class="input__wrapper">
                            <input type="email" id="reg-email" placeholder=" " required />
                            <label for="auth-email">Email</label>
                        </div>
                        <div class="input__wrapper">
                            <input
                                type="tel"
                                id="reg-tel"
                                placeholder=" "
                                required />
                            <label for="reg-tel">Телефон</label>
                        </div>
                        <div class="input__wrapper">
                            <input type="password" id="reg-pass" placeholder=" " required />
                            <label for="auth-email">Введите пароль</label>
                        </div>
                        <div class="input__wrapper">
                            <input
                                type="password"
                                id="reg-pass-repeat"
                                placeholder=" "
                                required />
                            <label for="auth-email">Повторите пароль</label>
                        </div>
                        <label class="privacy__checkbox" for="reg-check">
                            <input type="checkbox" id="reg-check" required />
                            <span class="box"></span>
                            <span>
                                Даю согласие на обработку <a href="">персональных данных</a>
                            </span>
                        </label>
                    </div>
                    <button pink type="submit">Зарегистрироваться</button>

                    <span alert>
                        <img src="/assets/icons/info.svg" alt="" />
                        Все поля обязательны для заполнения
                    </span>
                </form>
            </div>
            <button open-popover commandfor="auth" command="show-popover">
                Вход
            </button>
            <div popover id="auth">
                <div class="top__wrapper">
                    <button
                        commandfor="register"
                        command="show-popover"
                        class="register__button">
                        Регистрация
                    </button>
                    <h2 class="auth__heading">Авторизация</h2>
                </div>
                <button commandfor="auth" command="hide-popover">
                    <img src="/assets/icons/close.svg" alt="" />
                </button>
                <form auth action="">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div class="auth__inputs">
                        <div class="input__wrapper">
                            <input type="email" id="auth-email" placeholder=" " required />
                            <label for="auth-email">Email</label>
                        </div>
                        <div class="input__wrapper">
                            <input
                                type="password"
                                id="auth-pass"
                                placeholder=" "
                                required />
                            <label for="auth-pass">Пароль</label>
                        </div>
                    </div>
                    <button pink type="submit" disabled>Войти</button>
                    <span alert>
                        <img src="/assets/icons/info.svg" alt="" />
                        Все поля обязательны для заполнения
                    </span>
                </form>
            </div>
        </div>

    <?php else: ?>
        <div class="header__buttons">
            <p class="hello"><span>Здравствуйте,</span><?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <button class="logout" id="logout-btn">Выход</button>
        </div>
    <?php endif; ?>
</header>