<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaprosy</title>
    <style>
        table {
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #dddddd;
            border-collapse: collapse;
        }

        th {
            font-weight: bold;
            padding: 5px;
            background: #efefef;
            border: 1px solid #dddddd;
        }

        td {
            border: 1px solid #dddddd;
            padding: 5px;
            text-align: center;
        }

        caption {
            font-weight: bold;
        }
        .a {
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>
<body>
<?php
$host = 'localhost';
$user = 'root';
$password = 'root';
$db_name = 'dump5';

$link = mysqli_connect($host, $user, $password, $db_name);
mysqli_connect($host, $user, $password, $db_name) or die(mysqli_error($link));
mysqli_query($link, "SET NAMES 'utf8'");
$query = "select p.fullname, 100 + sum(
case p.id
when t.from_person_id then - t.amount
else t.amount
end) as balance
from transactions t, persons p
where p.id = t.from_person_id or p.id = t.to_person_id
group by p.fullname
 ";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

echo '<table>';
echo '<caption>Полное имя и баланс человека на данный момент.</caption>';
echo '<tr><td>Имя</td><td>Баланс</td></tr>';
{
    foreach ($data as $key => $value) {
        echo '<tr>';
        foreach ($value as $key => $item)
            echo '<td>' . $item . '</td>';
    }
    echo '</tr>';
}
echo '</table>';
echo '<br>';

$query = "select p.fullname, count(*)
from persons p, transactions t
where p.id = t.from_person_id or p.id = t.to_person_id
order by count(*)";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$row = mysqli_fetch_assoc($result);
echo '<div class="a">'."Человек, который участвовал в передаче денег наибольшее количество раз - $row[fullname]".'</div>';
echo '<br>';
echo '<br>';
$query = "select t.transaction_id, p1.fullname as from_person, p2.fullname as to_person, t.amount
from transactions t join persons p1 on t.from_person_id = p1.id
join persons p2 on t.to_person_id = p2.id
where p1.city_id = p2.city_id
 ";

$result = mysqli_query($link, $query) or die(mysqli_error($link));

for ($data = []; $row = mysqli_fetch_assoc($result); $data[] = $row) ;

echo '<table>';
echo '<caption>Транзакции, где передача денег осуществлялась между представителями одного города.</caption>';
echo '<tr><td>Номер транзакции</td><td>Плательщик</td><td>Бенефициар</td><td>Сумма</td></tr>';
{
    foreach ($data as $key => $value) {
        echo '<tr>';
        foreach ($value as $key => $item)
            echo '<td>' . $item . '</td>';
    }
    echo '</tr>';
}
echo '</table>';
echo '<br>';
?>
</body>
</html>