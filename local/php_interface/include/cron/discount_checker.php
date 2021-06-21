<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/..");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
Loader::includeModule("iblock");
Loader::includeModule("catalog");

$prices = \CIBlockPriceTools::GetCatalogPrices(0, array("BASE")); /** Необходимо заменить ID инфоблока каталога */
$select = [
    "ID",
    "IBLOCK_ID",
];
$filter = [
    "IBLOCK_ID" => 0, /** Необходимо заменить ID инфоблока каталога */
];

foreach ($prices as $value) {
    if (!$value['CAN_VIEW'] && !$value['CAN_BUY']) {
        continue;
    }
    $select[] = $value["SELECT"];
}

$elementsIterator = \CIBlockElement::GetList(
    ["ID" => "ASC"],
    $filter,
    false,
    false,
    $select
);

$elements = [];
while ($item = $elementsIterator->Fetch()) {
    $elements[] = $item;
}
foreach ($elements as & $element) {
    $element["PRICES"] = $optimalPrice = \CCatalogProduct::GetOptimalPrice($element["ID"], 1, array(2), 'N', array(), 's1', array());
}

foreach ($elements as $el) {
    if ($el['RESULT_PRICE']["PERCENT"] > 0)
    {
        CIBlockElement::SetPropertyValuesEx($el["ID"], $el["IBLOCK_ID"], array("DISCOUNT" => $el['RESULT_PRICE']["PERCENT"]));
    }
}