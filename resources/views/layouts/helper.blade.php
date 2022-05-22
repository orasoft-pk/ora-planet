<?php

function toFixed($v=0, $n=2)
{
    return number_format($v??0, 2, '.', '');
}

?>