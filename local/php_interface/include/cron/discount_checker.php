<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../../..");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
Loader::includeModule("iblock");
Loader::includeModule("catalog");

$iblockId = 0;              /** Необходимо заменить ID инфоблока каталога */
$arPrice = array("BASE");   /** Необходимо указать необходимые типы цен */
$userGroups = array(2);     /** Необходимо указать группы пользователей */
$siteId = 's1';             /** Необходимо указать SITE_ID */

$prices = \CIBlockPriceTools::GetCatalogPrices($iblockId, $arPrice);
$select = [
    "ID",
    "IBLOCK_ID",
];
$filter = [
    "IBLOCK_ID" => $iblockId,
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
    $element["PRICES"] = $optimalPrice = \CCatalogProduct::GetOptimalPrice($element["ID"], 1, $userGroups, 'N', array(), $siteId, array());
}

foreach ($elements as $el) {
    if ($el["PRICES"]['RESULT_PRICE']["PERCENT"] > 0)
    {
        CIBlockElement::SetPropertyValuesEx($el["ID"], $el["IBLOCK_ID"], array("DISCOUNT" => $el["PRICES"]['RESULT_PRICE']["PERCENT"]));
    }
}