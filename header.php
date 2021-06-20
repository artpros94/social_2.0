<header class="header">
            <div class="container">
                <div class="header__wrapper">
                    <a href="/" class="logo">NET 2.0</a>
                    <ul class="header__menu">

                       <?php if($_SESSION['auth'] == null) { ?>

                        <li class="header__menu-list">
                            <a href="/loginForm.php" class="header__menu-link">Войти</a>
                        </li>
                          <li class="header__menu-list">
                            <a href="/registerForm.php" class="header__menu-link">Регистрация</a>
                        </li> 

                        <?php } else { ?>
                       <li class="header__menu-list">
                            <a href="/logout.php" class="header__menu-link">Выйти</a>
                        </li> 
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </header>