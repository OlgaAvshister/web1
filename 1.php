<?php
session_start();
if(!isset($_SESSION['results']))$_SESSION['results']=[];
$style="<style>
table{
    border-collapse: collapse;                                          
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
  }
  table tr {
    border: 1px solid;                                                
}

tr.head
{
    background-color: #009879;
}
tr.data
{
    background-color: #c09879
}

</style>";
/**
 * Валидация данных
 *
 * @return bool
 */
function validate($x,$y,$r)
{

    if($x===null || $y===null || $r===null)
    {
        echo "<h3>Не заданы X или Y или R</h3>";
        return false;
    }
    if(str_contains((string)$x, "e") || !((int)$x==$x && $x>=-5 && $x<=3))
    {
        echo "<h3>X не прошел валидацию ($x)</h3>";
        return false;
    }
    if(str_contains((string)$y, "e") || !(is_numeric($y) && $y>=-5 && $y<=5))
    {
        echo "<h3>Y не прошел валидацию</h3>";
        return false;
    }
    if(!in_array($r,[1,2,3,4,5]))
    {
        echo "<h3>R не прошел валидацию</h3>";
        return false;
    }
    return true;
}

function checkCoordinates($x, $y, $r) {
    if (
    	($x>=0 && $y>=0) && (($x <= $r) && ($x >= 0 && $y <= $r/2) ) ||
       ($x<=0 && $y>=0) &&  (($x <= 0) && ($y <= $x/2+$r/2) ) ||
        ($x>=0 && $y<=0) &&  (($x**2 + $y**2) <= (($r/2)**2) )
        ) return true;
    else return false;
}

$time_start = microtime(true);
header("Content-Type: text/html; charset=UTF-8");

//Получаем входные данные из GET запроса
$x=$_GET['X-input'] ?? null;
$y=$_GET['Y-input'] ?? null;
$r=$_GET['R-input'] ?? null;
$time=$_GET['time'] ?? null;
$time_zone=$_GET['timezone'] ?? null;
if(!validate($x,$y,$r) || !$time || $time_zone==null)
{
    echo "<h1>Валидация данных не прошла<h2>";
    exit;
}
$result=checkCoordinates($x,$y,$r);
if ($result) $result = 'Да';
else $result = 'Нет';
$date=gmdate('d.m.Y H:i:s',$time-$time_zone*60);
$time_end = microtime(true);
$total=$time_end-$time_start;
$current=compact('x','y','r','result','time','total');

$str = "X=$x, Y=$y, R=$r, Результат=$result, Текущее время: $date, Время выполнения :$total\n";

$out="<table><tr class=\"head\"><td>Текущий результат:
<tr class=\"head\"><td>$str
<tr class=\"data\"><td>Предыдущие результаты:";
foreach($_SESSION['results'] as $result)
{
    $date=gmdate('d.m.Y H:i:s',$result['time']-$time_zone*60);
    $str = "X=$result[x], Y=$result[y], R=$result[r], Результат=$result[result], Текущее время: $date, Время выполнения :$result[total]\n";
    $out.="<tr class=\"data\"><td>$str";
}
array_unshift($_SESSION['results'],$current);
$out.='</table>';

echo $style.$out;