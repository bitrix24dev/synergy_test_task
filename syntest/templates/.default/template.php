<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
?>

<h1>Лиды</h1>
<?foreach($arResult["leads"] as $lead):?>
    <?=$lead["ID"]." ".$lead["TITLE"]?><br>
<?endforeach;?>


<h1>Элементы</h1>
<?foreach($arResult["elements"] as $element):?>
    <?=$element["ID"]." ".$element["NAME"]." ".$element["SSYLKA_NA_PASPORT_OBEKTA_V_IAS_UGD"]?><br>
<?endforeach;?>
