<?php

function showSidebarMenu($link)
{
    $query  = "SELECT * FROM sidebar";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    for($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row);


    echo "<sidebar class=\"sidebar\">
            <ul class=\"sidebar__menu\">";

        foreach($data as $elem) {
            $path = $elem['path'];
            $name = $elem['name'];

    echo "<li class=\"sidebar__menu-list\">
            <a href=\"{$path}\" class=\"sidebar__menu-link\">{$name}</a>
        </li>";
    }
    echo "</ul></sidebar>";
} 

showSidebarMenu($link);

