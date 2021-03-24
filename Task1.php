<?php
error_reporting(E_ALL);

interface IChessmen{
    public function move($newX, $newY);
    public function getPosition();
}

abstract class AbstractChessmen implements IChessmen{
    protected $y;
    protected $x;

    // Вывод конечных позиций
    public function getPosition(){
        echo "Конечная позиция по Х = $this->y <br>";
        echo "Конечная позиция по Y = $this->x <br>";
    }

    // Проверка новых координат, находятся ли они в пределах доски
    public function IsOnBoard($newX, $newY){
        $flag = true;

        if($newX < 1 || $newX > 8 || $newY < 1 || $newY > 8){
            $flag = false;
        }

        return $flag;
    }

    // Проверка, осуществился ли ход
    public function IsMoveDone($newX, $newY){
        $flag = true;

        if($newX == $this->x && $newY == $this->y){
            $flag = false;
        }

        return $flag;
    }
}

class King extends AbstractChessmen{

    public function __construct($x, $y){
        $this->x = $x;
        $this->y = $y;
    }

    public function move($newX, $newY){

        // Массивы для записи всех перемещений по осям X и Y
        $movesX = [];
        $movesY = [];

        try{
            if(parent::IsOnBoard($newX, $newY) == false){
                throw new Exception("Выход за пределы доски");
            }

            if(parent::IsMoveDone($newX, $newY) == false){
                throw new Exception("Осуществите ход");
            }

            // Пространство допустимых перемещений короля
            $acceptableMove = [
                "leftX" => -1,
                "rightX" => 1,
                "downY" => -1,
                "upY" => 1
            ];

            // Определяем, в каком направлении смещается фигура по X (лево-право)
            if($newX < $this->x){
                $move = $acceptableMove["leftX"];
            }else{
                $move = $acceptableMove["rightX"];
            }

            // Определяем, в каком направлении смещается фигура по X (верх-низ)
            if($newY < $this->y){
                $move = $acceptableMove["downY"];
            }else{
                $move = $acceptableMove["upY"];
            }

            // Смещаем координату Х влево по направлению к конечной (X = 2)
            // пока не достигнем нужной
            while($this->x != $newX){
                $this->x = $this->x + $move;
                $movesX[] = $this->x; // Записываем промежуточные положения координаты X
            }

            // Смещаем координату Y вниз по направлению к конечной (Y = 2)
            // пока не достигнем нужной
            while($this->y != $newY){
                $this->y = $this->y + $acceptableMove["downY"];
                $movesY[] = $this->y; // Записываем промежуточные положения координаты Y
            }

            echo "Ходы фигуры King: <br>";
            for($i = 0; $i < sizeof($movesX); $i++){
                for($j = 0; $j < sizeof($movesY); $j++){
                    echo $i+1 . ". X = " . $movesX[$i] . " , Y =  " . $movesY[$j] . "<br>";
                }
            }

            parent::getPosition();

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}

class Queen extends AbstractChessmen{

    public function __construct($x, $y){
        $this->x = $x;
        $this->y = $y;
    }

    public function move($newX, $newY){

        $move = [];

        try{
            if(parent::IsOnBoard($newY, $newY) == false){
                throw new Exception("Выход за пределы доски");
            }
            if(parent::IsMoveDone($newX, $newY) == false){
                throw new Exception("Осуществите ход");
            }

            // Устанавливаем область допустимых ходов для Queen
            // (диагональ, горизонталь, вертикаль)
            if($newX > 1 && $newY > 1 && $newX == $newY){
                $diagonalMoves = true;
            }

            if($newX > 1 && $newY == $this->y){
                $horizontalMoves = true;
            }

            if($newY > 1 && $newX == $this->y){
                $verticalMoves = true;
            }

            // Переместим фигуру на промежуточное поле:
            $this->x = $newX;
            $this->y = 1;
            $move[] = $this->x;
            $move[] = $this->y;

            $this->y = $newY;

            echo "<br> Промежуточный ход Queen: <br>";
            echo " Х = " . $move[0];
            echo " , Y = " . $move[1] . "<br>";

            parent::getPosition();

        }catch(Exception $e){
            echo $e->getMessage();
        }
    }
}

// Изначальные координаты
$king = new King(4, 3);
$queen = new Queen(1,1);

// Координаты из условия
$king->move(2, 2);
$queen->move(7, 3);