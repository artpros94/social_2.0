<?php

$id = $_SESSION['id'];

if(isset($_GET['message'])){
    $userId = $_GET['message'];
}


function sendNewMessage($link, $id, $userId)
{
    if(!empty($_POST['message'])){
        $mess     = $_POST['message'];
        $dateSend = date("Y-m-d H:i:s");

        $query  = "INSERT INTO messages SET id_sender='$id', id_recipient='$userId', message='$mess', date='$dateSend'";
        $result = mysqli_query($link, $query) or die(mysqli_error($link));
    }
}
sendNewMessage($link, $id, $userId);

function showAllUsers($link, $id) 
{
    $query  = "SELECT id, name, surname,  user_foto FROM users WHERE id!='$id'";
    $result = mysqli_query($link, $query) or die($link);
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    $users = '';
    foreach($data as $elem){

        $name   = $elem['name'] . ' ' . $elem['surname'];
        $avatar = $elem['user_foto'];
        $userId = $elem['id'];

        $users .= "<div class=\"mess__users-item\">
                    <img src=\"images/{$avatar}\" alt=\"avatar\" class=\"mess__users-itemimg\">
                    <div class=\"mess__users-item-box\">
                        <p class=\"mess__users-itemname\">{$name}</p>
                        <a href=\"?message={$userId}\" class=\"mess__users-itemsensmess\">Send new Message</a>
                    </div>
                </div>";

    }

    return $users;
}


function showMessages($link, $id, $userId)
{
    $query  = "SELECT messages.id_sender as mess_id_sender, messages.id_recipient as mess_id_recipient, messages.message as mess_message, messages.date as mess_date, users.user_foto as user_avatar FROM messages LEFT JOIN users ON users.id=messages.id_sender WHERE id_sender='$id' AND id_recipient='$userId'  OR id_recipient='$id' AND id_sender='$userId' ORDER BY `messages`.`date` ASC";
    $result = mysqli_query($link, $query) or die($link);
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);

    
    $item = '';
    foreach($data as $elem){

        $avatar  = $elem['user_avatar'];
        $message = $elem['mess_message'];
        $date    = $elem['mess_date'];
        
        if($elem['mess_id_sender'] == $id) {
            $item .= "<div class=\"mess__message-left\">
                <div class=\"mess__message-leftcontent\">
                    <img src=\"images/{$avatar}\" alt=\"avatar\" class=\"mess__message-leftimg\">
                    <div class=\"mess_message-lefttext\">{$message}</div>
                </div>
                <div class=\"mess_message-leftdate\">{$date}</div>
        </div>";
            
        } 
        if($elem['mess_id_sender'] == $userId) {
            $item .= "<div class=\"mess__message-right\">
            <div class=\"mess__message-rightcontent\">
                <img src=\"images/{$avatar}\" alt=\"avatar\" class=\"mess__message-rightimg\">
                <div class=\"mess_message-righttext\">{$message}</div>
            </div>
            <div class=\"mess_message-rightdate\">{$date}</div>
        </div>";
          
        }
    
    }
    return $item;
}

    

   
?>
<div class="mess">
    <div class="mess__header">
        <div class="mess__header-title">MESSAGE</div>
    </div>
    <div class="mess__inner">

        <div class="mess__message">
            <div class="mess_message-header">
                <h4 class="mess_message-headername">Ivan Ivanov</h4>
            </div>
            <div class="mess__message-inner">

            <?php

                    if(isset($_GET['message'])){
                        $userId = $_GET['message'];
                        echo showMessages($link, $id, $userId);

                    }
            ?>

            </div>
            <form class="mess__message-footer" method="POST">
                <textarea name="message" class="mess__message-newmessage"></textarea>
                <input type="submit" class="mess__message-btn">
            </form>
        </div>

        <div class="mess__users">

            <div class="mess__users-header">
                <h6 class="mess__user-headertitle">All Users</h6>
            </div>

            <div class="mess__users-inner">

                <?php echo showAllUsers($link, $id); ?>

            </div>
        </div>
    </div>
</div>