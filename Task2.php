<?php

class StroopTest
{
    public $result = [];

    public function getData($colours, $words)
    {
        shuffle($colours); // Перемешивание массивов
        shuffle($words);

        $arr = array_combine($words, $colours); //Соединение массивов. Значения одного массива становятся ключами другого.
        $this->result = array_slice($arr, 5); // Приведение значений массива к заданному количеству
        foreach ($arr as $key => $value) {//Перебор массива.
            if ($key == $value) {//Если ключ и значение совпадают
                $part[$key] = $value;// Пара сохраняется в переменной $part
                unset($arr[$key]);//и удаляется из общего массива
                $rand = array_rand($arr, 1); //Поиск случайного ключа в исходном массиве
                $part[$value] = $rand;//Замена значения в отделенной паре на случайное
                $this->result = array_slice($part + $arr, 5);//Соединение $part с общим массивом
            }
        }
    }

    public function view() //Отображение результата
    {

        for ($i = 0; $i < 5; $i++) {
            foreach ($this->result as $key => $value) {
                echo '<label style="color:' . $key . '">' . $value . "  " . '</label>';
            }
            echo "<br>";
        }
    }

}

$colours = ["red", "blue", "green", "yellow", "lime", "magenta", "black", "gold", "gray", "tomato"];//Массив цветов
$words = $colours;//Массив слов

$stroop = new StroopTest();
$stroop->getData($colours, $words);
$stroop->view();