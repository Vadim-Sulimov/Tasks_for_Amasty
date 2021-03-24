<?php

include "simple_html_dom.php";

$team = $_POST['team']; // Получаем из формы название команды
$sitePath = "https://terrikon.com";
$seasons = [];
$places = [];
$result = [];

// Считывание страницы архивов
$html = file_get_html("https://terrikon.com/football/italy/championship/archive");
// Получаем сезоны и URL-ы их страниц и записываем в массив сезонов
foreach($html->find('div.maincol div[class=news] dd a') as $element){
    $seasonTitle[] = $element->plaintext;
    // Получаем ссылки страниц сезонов
    $seasonUrl[] = $sitePath . $element->getAttribute("href");
    $seasons = array_combine($seasonTitle, $seasonUrl);
}

// Очищаем и удаляем объект html
$html->clear();
unset($html);

// Проходим по циклу и вызываем функцию парсинга команд и их мест 12 раз (кол-во сезонов)
foreach($seasons as $seasonTitle => $seasonUrl){
    // Передаём URL-ы страниц и имя команды и формируем массив
    $places[] = parseSeasons($seasonUrl, $team);
}

foreach($places as $seasonIndex => $place){
    if($place == ""){
        exit("Введённой команды не найдено");
    }
    $resultPlaces[] = $place;
}

// Формируем конечный массив с результатами из названий сезонов и мест
$result = array_combine(array_keys($seasons), $resultPlaces);

echo "Результаты команды " . $team . " по сезонам: <br>";
foreach($result as $season => $place){
    echo "$season - $place место <br>";
}

function parseSeasons($seasonUrl, $searchedTeam){

    $teams = [];
    $place = "";

    $html = file_get_html($seasonUrl); // Создаём объект для каждой страницы сезонов

    // Парсим команды
    $teamsFound = $html->find("td a");
    foreach($teamsFound as $elem){
        // Очищаем элементы от мусора и выбираем 20 мест
        if($elem->plaintext != "#" && sizeof($teams) < 20){
            // Записываем названия команд в каждом сезоне в массив
            // (место команды - ключ массива + 1)
            $teams[] = $elem->plaintext;
            foreach($teams as $key => $team){
                // Находим введённую команду
                if($team == $searchedTeam){
                    $place = $key + 1;
                }
            }
        }
    }
    // Освобождаем память
    $html->clear();
    unset($html);

    return $place;
}