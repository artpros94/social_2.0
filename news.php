<?php

function showAllNews($link)
{
    $query  = "SELECT posts.text as posts_text, posts.date as posts_date, posts.user_id as posts_user_id, users.user_foto as users_foto, users.name as user_name, users.surname as user_surname FROM posts LEFT JOIN users ON users.id=posts.user_id ORDER BY `posts`.`date` DESC";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for($data= []; $row = mysqli_fetch_assoc($result); $data[] = $row)

    $item = '';
    foreach($data as $elem){
        
        $text   = $elem['posts_text'];
        $userId = $elem['posts_user_id'];
        $date   = $elem['posts_date'];
        $avatar = $elem['users_foto'];
        $name = $elem['user_name'] . ' ' . $elem['user_surname'];
        


        $item .= "<div class=\"news__item\">
                        <div class=\"news__item-header\">
                            <a href=\"?profiles={$userId}\" class=\"news__item-profile\">
                                <img src=\"images/{$avatar}\" alt=\"avatar\" class=\"news__item-img\">
                            </a>
                            <div class=\"news__item-headebox\">
                                <div class=\"news__item-name\">{$name}</div>
                                <div class=\"news__item-date\">{$date}</div>
                            </div>
                        </div>
                        <div class=\"news__item-content\">
                            <p class=\"news__item-text\">{$text}</p>
                        </div>
                </div>";

    }

    return $item;
}

?>
<div class="news">
    <div class="news__header">
        <h5 class="news__header-title">Все новости</h5>
    </div>
    <div class="news__inner">

        <?php echo showAllNews($link); ?>

    </div>
</div>