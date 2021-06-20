<?php

$id = $_SESSION['id'];

function clear($value = '')
{
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    
    return $value;
}


if(!empty($_POST)){

    $name       = clear($_POST['name']);
    $surname    = clear($_POST['surname']);
    $patronimic = clear($_POST['patronimic']);
    $email      =  filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $birthday   = $_POST['birthday'];
    $aboutUser  = clear($_POST['about_user']);
    $country    = $_POST['country'];
    $status    = $_POST['status'];
    $city       = clear($_POST['city']);
    $genus      = $_POST['genus'];
    if(is_numeric($_POST['phone'])){
        $phone      = clear($_POST['phone']);
    }

    $query  = "UPDATE users SET name='$name', surname='$surname', patronimic='$patronimic', email='$email', birthday='$birthday', status='$status', about_user='$aboutUser', country='$country', city='$city', genus='$genus', phone='$phone' WHERE id='$id'";
    mysqli_query($link, $query) or die(mysqli_error($link));

}






$query  = "SELECT name, surname, patronimic, email, phone, birthday, status, about_user, country, city, genus FROM users WHERE id='$id'";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

foreach($data as $elem){

    $name       = $elem['name'];
    $surname    = $elem['surname'];
    $patronimic = $elem['patronimic'];
    $email      = $elem['email'];
    $phone      = $elem['phone'];
    $birthday   = $elem['birthday'];
    $aboutUser  = $elem['about_user'];
    $country    = $elem['country'];
    $city       = $elem['city'];
    $genus      = $elem['genus'];

}


?>
<div class="editpages">
    <form class="editpages__form" action="" method="POST">
        <h3 class="editpages__title">Редактировать профиль</h3>

        <label  class="editpages_label">Имя</label>
        <input class="editpages__input" name="name" type="text" value="<?php echo $name ?>">

        <label  class="editpages_label">Фамилия</label>
        <input class="editpages__input" name="surname" type="text" value="<?php echo $surname ?>">

        <label  class="editpages_label">Отчество</label>
        <input class="editpages__input" name="patronimic" type="text" value="<?php if(!empty($patronimic)){ echo $patronimic; } ?>" >

        <div class="register-form__genus-box editform__genus-box">
            <p class="register-form__genus-text editform__genus-text">Ваш пол:</p>
        
            <?php 
                if($genus == 'man') {
                    $checkedMen   = 'checked';
                } else {
                    $checkedMen   = '';
                }

                if($genus == 'women'){  
                    $checkedWomen = 'checked';
                }else {
                    $checkedWomen   = '';
                }

                if($genus == 'other') {
                    $checkedOther = 'checked';
                }else {
                    $checkedOther   = '';
                }
            ?>

            <label class="register-form__genus-label">Мужской</label>
            <input class="register-form__genus-input" type="radio" name="genus" value="man" <?php echo $checkedMen ?>>

            <label class="register-form__genus-label">Женский</label>
            <input class="register-form__genus-input" type="radio" name="genus" value="women" <?php echo $checkedWomen ?>>

            <label class="register-form__genus-label">Другое</label>
            <input class="register-form__genus-input" type="radio" name="genus" value="other" <?php echo $checkedOther ?>>
        </div>

        <label  class="editpages_label">Email</label>
        <input class="editpages__input" name="email" type="email" value="<?php if(!empty($email)){ echo $email; } ?>">

        <label  class="editpages_label">Телефон</label>
        <input class="editpages__input" name="phone" type="tel" value="<?php if(!empty($phone)){ echo $phone; } ?>">

        <label  class="editpages_label">Семейное положение</label>
        <select class="editpages__input" name="status">
            <option value="">Не выбрано</option>
            <option value="Не женат">Не женат</option>
            <option value="Есть подруга">Есть подруга</option>
            <option value="Помолвлен">Помолвлен</option>
            <option value="Женат">Женат</option>
            <option value="Все сложно">Все сложно</option>
            <option value="В активном поиске">В активном поиске</option>
            <option value="Влюблен">Влюблен</option>
        </select>

        <label  class="editpages_label">Страна</label>
        <?php include 'country.php'; ?>

        <label  class="editpages_label">Город</label>
        <input class="editpages__input" name="city" type="text" value="<?php if(!empty($city)){ echo $city; } ?>">

        <label  class="editpages_label">Дата рождения</label>
        <input class="editpages__input" name="birthday" type="date" value="<?php if(!empty($birthday)){ echo $birthday; } ?>">

        <label  class="editpages_label">Про себя</label>
        <textarea class="editpages__text" name="about_user"><?php if(!empty($aboutUser)){ echo $aboutUser; } ?></textarea>

        <input class="register-form__btn" type="submit">

    </form>
</div>








