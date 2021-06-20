<?php

$id = $_SESSION['id'];
if(!empty($_POST['add-friend'])) {
    $idFriend = $_POST['add-friend'];
    $statusFriend = $_POST['status-friend'];

    $query = "SELECT * FROM friendship WHERE id_first_user='$id' AND id_second_user='$idFriend' OR id_first_user='$idFriend' AND id_second_user='$id'";
    $friendQuery = mysqli_fetch_assoc(mysqli_query($link, $query));

    if(!empty($friendQuery)){
        $query = "UPDATE friendship SET status='$statusFriend' WHERE id_first_user='$id' AND id_second_user='$idFriend' OR id_first_user='$idFriend' AND id_second_user='$id'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));

    } else {
        $query = "INSERT INTO friendship SET id_first_user='$id', id_second_user='$idFriend', status='$statusFriend'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));

    }
    
    

}

function showAllPeople($link, $id)
{
    // $query = "SELECT users.id as user_id, users.name as user_name, users.surname as user_surname, users.country as users_country, users.user_foto as user_avatar";
    $query  = "SELECT users.id as user_id, users.name as user_name, users.surname as user_surname, users.country as user_country, users.user_foto as user_avatar, friendship.status as friend_status 
    FROM users 
    LEFT JOIN friendship 
    ON users.id=friendship.id_second_user AND friendship.id_first_user='$id' OR users.id=friendship.id_first_user AND friendship.id_second_user='$id' 
    WHERE users.id!='$id'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    $result = '';
    foreach($data as $elem){
        $userId      = $elem['user_id'];
        $userName    = $elem['user_name'];
        $userSurname = $elem['user_surname'];
        $userCountry = $elem['user_country'];
        $userAvatar  = $elem['user_avatar'];
        $frienStatus = $elem['friend_status'];

        $result .= "<div class=\"people__item\">
                        <a href=\"?profiles={$userId}\" class=\"people__img\">
                            <img src=\"images/{$userAvatar}\" alt=\"avatar\" class=\"people__avatar\">
                        </a>
                        <div class=\"people__about\">
                <div class=\"people__about-name\">{$userName}  {$userSurname}</div>";

        if(!empty($userCountry)) {

            $result .= "<p class=\"people__about-country\">Страна <span>$userCountry</span></p>";

        }

        $result .= "<div class=\"people__about-btns\">
                <a href=\"?message={$userId}\" class=\"people__about-btn\">Send Message</a>";

        if($frienStatus == "1") {
            $result .= "<button form=\"edd_friend{$userId}\" type=\"submit\" class=\"people__about-btn\">Запрос отправлен</button>
            <form action=\"\" method=\"POST\" id=\"edd_friend{$userId}\"> 
                <input type=\"hidden\" name=\"add-friend\" value=\"{$userId}\">
                <input type=\"hidden\" name=\"status-friend\" value=\"1\">
            </form>";

        } else if($frienStatus == "2") {
            $result .= "<button form=\"edd_friend{$userId}\" type=\"submit\" class=\"people__about-btn\">Дрзья</button>
            <form action=\"\" method=\"POST\" id=\"edd_friend{$userId}\"> 
                <input type=\"hidden\" name=\"add-friend\" value=\"{$userId}\">
                <input type=\"hidden\" name=\"status-friend\" value=\"0\">
            </form>";

        } else if($frienStatus == null) {
            $result .= "<button form=\"edd_friend{$userId}\" type=\"submit\" class=\"people__about-btn\">Добавить в друзья</button>
            <form action=\"\" method=\"POST\" id=\"edd_friend{$userId}\"> 
                <input type=\"hidden\" name=\"add-friend\" value=\"{$userId}\">
                <input type=\"hidden\" name=\"status-friend\" value=\"1\">
            </form>";

        } else {
            $result .= "<button form=\"edd_friend{$userId}\" type=\"submit\" class=\"people__about-btn\">Добавить в друзья</button>
            <form action=\"\" method=\"POST\" id=\"edd_friend{$userId}\"> 
                <input type=\"hidden\" name=\"add-friend\" value=\"{$userId}\">
                <input type=\"hidden\" name=\"status-friend\" value=\"1\">
            </form>";

        }
               

        $result .= "</div></div></div>";
    }
    return $result;

}

function showAllPeopleRequest($link, $id)
{
    $query  = "SELECT users.id as user_id, users.name as user_name, users.surname as user_surname, users.country as user_country, users.user_foto as user_avatar, friendship.status as friend_status FROM users LEFT JOIN friendship ON users.id=friendship.id_first_user WHERE friendship.id_second_user='$id' AND friendship.status=1 ";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    $result = '';
    foreach($data as $elem){
        $userId      = $elem['user_id'];
        $userName    = $elem['user_name'];
        $userSurname = $elem['user_surname'];
        $userCountry = $elem['user_country'];
        $userAvatar  = $elem['user_avatar'];
        $frienStatus = $elem['friend_status'];
        
        $result .= "<div class=\"people__item\">
                        <a href=\"?profiles={$userId}\" class=\"people__img\">
                            <img src=\"images/{$userAvatar}\" alt=\"avatar\" class=\"people__avatar\">
                        </a>
                        <div class=\"people__about\">
                <div class=\"people__about-name\">{$userName}  {$userSurname}</div>";

        if(!empty($userCountry)) {

            $result .= "<p class=\"people__about-country\">Страна <span>$userCountry</span></p>";

        }

        $result .= "<div class=\"people__about-btns\">
                <a href=\"?message={$userId}\" class=\"people__about-btn\">Send Message</a>";

        
            $result .= "<button form=\"edd_friend{$userId}\" type=\"submit\" class=\"people__about-btn\">Добавить в друзья</button>
            <form action=\"\" method=\"POST\" id=\"edd_friend{$userId}\"> 
                <input type=\"hidden\" name=\"add-friend\" value=\"{$userId}\">
                <input type=\"hidden\" name=\"status-friend\" value=\"2\">
            </form>";

        
               

        $result .= "</div></div></div>";
    }
    return $result;
}


function showMyFriends($link, $id)
{
    $query  = "SELECT users.id as user_id, users.name as user_name, users.surname as user_surname, users.country as user_country, users.user_foto as user_avatar, friendship.status as friend_status 
    FROM users 
    LEFT JOIN friendship 
    ON users.id=friendship.id_first_user AND friendship.id_second_user='$id' OR users.id=friendship.id_second_user AND friendship.id_first_user='$id'
    WHERE friendship.status=2 ";
    
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    $result = '';
    foreach($data as $elem){
        $userId      = $elem['user_id'];
        $userName    = $elem['user_name'];
        $userSurname = $elem['user_surname'];
        $userCountry = $elem['user_country'];
        $userAvatar  = $elem['user_avatar'];
        $frienStatus = $elem['friend_status'];
        
        $result .= "<div class=\"people__item\">
                        <a href=\"?profiles={$userId}\" class=\"people__img\">
                            <img src=\"images/{$userAvatar}\" alt=\"avatar\" class=\"people__avatar\">
                        </a>
                        <div class=\"people__about\">
                <div class=\"people__about-name\">{$userName}  {$userSurname}</div>";

        if(!empty($userCountry)) {

            $result .= "<p class=\"people__about-country\">Страна <span>$userCountry</span></p>";

        }

        $result .= "<div class=\"people__about-btns\">
                <a href=\"?message={$userId}\" class=\"people__about-btn\">Send Message</a>";

        
            $result .= "<button form=\"edd_friend{$userId}\" type=\"submit\" class=\"people__about-btn\">Дрзья</button>
            <form action=\"\" method=\"POST\" id=\"edd_friend{$userId}\"> 
                <input type=\"hidden\" name=\"add-friend\" value=\"{$userId}\">
                <input type=\"hidden\" name=\"status-friend\" value=\"0\">
            </form>";

        
               

        $result .= "</div></div></div>";
    }
    return $result;

}

?>
<div class="people">
        <div class="people__header">
            <a href="?people" class="people__all-people">Запросы в друзья</a>
            <a href="?people=allPeople" class="people__all-people">Все пользователи</a>
            <a href="?people=friens" class="people__friend">Мои Друзья</a>
        </div>
        <div class="people__wrapper">
           
            <?php

                if($_GET['people'] == 'allPeople') {
                    echo showAllPeople($link, $id);
                    
                } else if($_GET['people'] == 'friens'){
                    echo showMyFriends($link, $id);
                } else {
                    echo showAllPeopleRequest($link, $id);
                }
                
            ?>

        </div>
      
</div>