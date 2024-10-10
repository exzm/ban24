<?php

define('RU_IM', 'im'); //Есть	  Кто? Что?
define('RU_RO', 'rod'); //Нет     Кого? Чего?
define('RU_DA', 'dat'); //Дать	  Кому? Чему?
define('RU_VI', 'vin'); //Винить  Кого? Что?
define('RU_TV', 'tvor'); //Доволен Кем? Чем?
define('RU_PR', 'predl'); //Думать  О ком? О чём? В ком? В чём? Где?
define('RU_GDE', 'gde');
define('RU_KUDA', 'kuda');
define('RU_OTKUDA', 'otkuda');

/**
 * Возвращает правильное окончание слова ending($count,[1,2,0])
 * @param $n
 * @param $titles
 * @return string
 */

function ending(int $n, array $titles)
{
    $cases = [2, 0, 1, 1, 1, 2];
    return $titles[($n % 100 > 4 and $n % 100 < 20) ? 2 : $cases[min($n % 10, 5)]];
}