<?php

$userId = $_GET['profiles'];



function showAllUserPosts($link, $userId)
{
    $query  = "SELECT posts.id as post_id, posts.text as posts_text, posts.date as posts_date, users.user_foto as users_foto FROM posts LEFT JOIN users ON users.id=posts.user_id WHERE user_id='$userId' ORDER BY `posts`.`date` DESC";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for($data= []; $row = mysqli_fetch_assoc($result); $data[] = $row)

    $post = '';
    foreach($data as $elem){
        $post .= "<div class=\"profiles__post-item\">
                    <div class=\"profiles__post-content\">
                        <img class=\"profiles__post-avatar\" src=\"images/{$elem['users_foto']}\" alt=\"avatar\" >
                        <p class=\"profile__post-text\">{$elem['posts_text']}</p>
                    </div>
                    <div class=\"profiles__post-footer\">
                        <p class=\"podiles__post-datecreate\">{$elem['posts_date']}</p>
                    </div>
                </div>";
    }
    return $post;
}

function getUserData($link, $userId)
{
    $query    = "SELECT *  FROM users WHERE id='$userId'";
    $result   = mysqli_query($link, $query) or die($link);
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    return $data;
}



$data = getUserData($link, $userId);

foreach($data as $elem) {

    $userId      = $elem['id'];
    $name        = $elem['name'];
    $surname     = $elem['surname'];
    $patronimic  = $elem['patronimic'];
    $phone       = $elem['phone'];
    $fotoUser    = $elem['user_foto'];
    $birthday    = $elem['birthday'];
    $status      = $elem['status'];
    $aboutUser   = $elem['about_user'];
    $country     = $elem['country'];
    $city        = $elem['city'];
    $genus       = $elem['genus'];
}
    if($genus == 'man'){
        $genus = 'Мужской';
    } else if ($genus == 'women'){
        $genus = 'Женский';
    } else if ($genus == 'other'){
        $genus = 'Другое';
    }

?>
<div class="profiles">
    <div class="profiles__header">
        <h6 class="profiles__header-tite"><?php echo $surname . ' ' . $name ?></h6>
    </div>
    <div class="profiles__inner">
        <div class="prifiles__column">
            <div class="profiles__column-images">
                <img src="images/<?php echo $fotoUser; ?>" alt="avatar" class="profiles__column-img">
                <a href="?message=<?php echo $userId ?>" class="profiles__add-friends">Написать сообщение</a>
                <a href="#" class="profiles__add-friends">Добавить в друзья</a>
            </div>
     
        </div>
        <div class="profiles__about">
            <div class="profiles__about-desc">
                <dl class="profiles__about-list">
                    <dt class="profiles__about-title">ФИО</dt>
                    <dd class="profiles__about-text"><?php echo $surname . ' ' . $name . ' ' . $patronimic ?></dd>

                    <?php if(!empty($birthday)) { ?>
                    <dt class="profiles__about-title">Дата рождения</dt>
                    <dd class="profiles__about-text"><?php echo $birthday ?></dd>
                    <?php } ?>

                    <?php if(!empty($genus)) { ?>
                    <dt class="profiles__about-title">Пол</dt>
                    <dd class="profiles__about-text"><?php echo $genus ?></dd>
                    <?php } ?>

                    <?php if(!empty($status)) { ?>
                    <dt class="profiles__about-title">Статус</dt>
                    <dd class="profiles__about-text"><?php echo $status ?></dd>
                    <?php } ?>

                    <?php if(!empty($phone)) { ?>
                    <dt class="profiles__about-title">Телефон</dt>
                    <dd class="profiles__about-text"><?php echo $phone ?></dd>
                    <?php } ?>

                    <?php if(!empty($country)) { ?>
                    <dt class="profiles__about-title">Страна</dt>
                    <dd class="profiles__about-text"><?php echo $country ?></dd>
                    <?php } ?>

                    <?php if(!empty($city)) { ?>
                    <dt class="profiles__about-title">Город</dt>
                    <dd class="profiles__about-text"><?php echo $city ?></dd>
                    <?php } ?>

                    <?php if(!empty($aboutUser)) { ?>
                    <dt class="profiles__about-title">Про меня</dt>
                    <dd class="profiles__about-text"><?php echo $aboutUser ?></dd>
                    <?php } ?>

                </dl>
            </div>
            <div class="profiles__posts">
                    <div class="profiles__post-header">
                        Все посты
                    </div>


                    <div class="profiles__post-inner">

                       <?php echo showAllUserPosts($link, $userId); ?>

                    </div>
            </div>
        </div>
    </div>
</div>