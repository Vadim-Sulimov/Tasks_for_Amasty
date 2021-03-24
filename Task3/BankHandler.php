<?php
// Считывание данных из формы
$data = json_decode(file_get_contents("php://input"));

$nominalBuf = $data[0];
$sum = $data[1];
$sumValidator = $sum;

// Преобразование полученной строки номиналов в массив
$nominalArray = explode(",", $nominalBuf);
$result = [];
$underSum = 0;
$afterSum = 0;

// Проверка на ввод значения суммы, не кратной 5
if ($sum % 10 != 5 && $sum % 10 != 0) {
    while ($sum % 5 != 0) {
        $sum += 1;
        $afterSum = $sum;
    }

    $sum = $data[1];

    while ($sum % 5 != 0) {
        $sum -= 1;
        $underSum = $sum;
    }

    exit("Некорректная сумма. Введите значение " . $underSum . " или " . $afterSum);

} else {
    getNominalAndCount($sum, $nominalArray, $result);
}

// Алгоритм подбора наименьшего количества купюр
function getNominalAndCount($sum, $nominalArray, &$result)
{

    $nominal = array_pop($nominalArray);
    if (!($sum >= $nominal))
        $nominal = array_pop($nominalArray);

    if ($sum % $nominal)
        list($total, $rest) = explode('.', $sum / $nominal);
    else
        $total = $sum / $nominal;

    array_push($result, [$nominal => $total]);

    if (isset($rest)) {
        $rest = $sum - $total * $nominal;
        getNominalAndCount($rest, $nominalArray, $result);
    }
}

// Вывод результата в табличном виде
echo '<table>';
echo '<tr><td>Номинал</td><td>Количество</td></tr>';
foreach ($result as $elem) {
    echo '<tr>';
    foreach ($elem as $key => $value) {
        echo '<td>' . $key . '</td>' . '<td>' . $value . '</td>';
    }
    echo '</tr>';
}
echo '</table>';
