<?php

$id = $_SESSION['id'];

function clear($value = '', $link)
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    $value = mysqli_real_escape_string($link, $value);
    
    return $value;
}
function showToAddFormPost()
{
    echo "<form action=\"\" class=\"profile__post-form\" method=\"POST\">
    <textarea class=\"profiles__post-formtext\" name=\"post-text\" ></textarea>
    <input class=\"profiles__post-formbtn\" type=\"submit\">
    </form>";
}
// Delete Post 
if(isset($_GET['deletePost'])){

    $deletePostId = $_GET['deletePost'];
    
    $query = "DELETE FROM posts WHERE id='$deletePostId'";
    mysqli_query($link, $query) or die(mysqli_error($link));

}

// Insert new Post to Data Base 
if(!empty($_GET['eddNewPost']) && $_GET['eddNewPost'] == 'new') 
{ 
    if(!empty($_POST['post-text'])) {
    
        $text = clear($_POST['post-text'], $link);
        $date = date("Y-m-d H:i:s");
    
        $query  = "INSERT INTO posts SET text='$text', user_id='$id', date='$date'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
        $_GET['eddNewPost'] == 'send';
    
    }
} 

function showAllUserPosts($link, $id)
{

    $query  = "SELECT posts.id as post_id, posts.text as posts_text, posts.date as posts_date, users.user_foto as users_foto FROM posts LEFT JOIN users ON users.id=posts.user_id WHERE user_id='$id' ORDER BY `posts`.`date` DESC";
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
                        <a href=\"?deletePost={$elem['post_id']}\" class=\"profiles__post-delete\">Удалить пост</a>
                    </div>
                </div>";
    }
    return $post;
}

function showMyFriends($link, $id)
{
    $query  = "SELECT users.id as user_id, users.name as user_name, users.user_foto as user_avatar
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
        $userAvatar  = $elem['user_avatar'];
        
        $result .= " <a href=\"?profiles={$userId}\" class=\"profiles__friends-item\">
                        <img src=\"images/{$userAvatar}\" alt=\"avatar\" class=\"profiles__friends-images\">
                        <p class=\"profiles__friends-name\">{$userName}</p>
                    </a>";
        

    }
    return $result;

}

function getUserData($link, $id)
{
    $query    = "SELECT name, surname, user_foto, phone, patronimic, birthday, about_user, country, city, genus, status FROM users WHERE id='$id'";
    $result   = mysqli_query($link, $query) or die($link);
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    return $data;
}

function updateFotoPath($link, $id, $fileName)
{
    $query  = "UPDATE users SET user_foto='$fileName' WHERE id='$id'";
    $result = mysqli_query($link, $query) or die(mysqli_erro($link));

    return 'Фото успешно измененно!';
}

if(isset($_FILES['foto'])){

    $fileName    = $_FILES['foto']['name'];
    $fileType    = $_FILES['foto']['type'];
    $fileSize    = $_FILES['foto']['size'];
    $fileTmpName = $_FILES['foto']['tmp_name'];

    if(move_uploaded_file($fileTmpName, 'images/' . $fileName)){
        $confirm = updateFotoPath($link, $id, $fileName); 
    } else {
        $confirm = 'Ошибка при загрузке файла!!!';
    }
}

$data = getUserData($link, $id);
// var_dump($data);
foreach($data as $elem) {

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
                <a href="?editFoto=true" class="profiles__colum-loadimages">Сменить картинку</a>

                <p class="upload-file"><?php if(isset($confirm)){ echo $confirm; } ?></p>
                <?php if(isset($_GET['editFoto']) && $_GET['editFoto'] == true) { ?>
                <form class="profiles__upload" action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="MAX_FILE_SIZE" value="30000">
                    <input class="profiles__upload-file" type="file" name="foto">
                    <input class="profiles__upload-btn" type="submit">
                </form>
                <?php } ?>
                <div class="profiles__friends-box">

                    <?php echo showMyFriends($link, $id); ?>
                    

                </div>
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
                        <a href="?eddNewPost=new" class="profiles__post-newpost">Новый пост</a>
                    </div>

                    <?php
                     if(!empty($_GET['eddNewPost']) && $_GET['eddNewPost'] == 'new') 
                    { 
                        showToAddFormPost();
                    } 
                    ?>

                    <div class="profiles__post-inner">

                       <?php echo showAllUserPosts($link, $id); ?>

                    </div>
            </div>
        </div>
    </div>
</div>