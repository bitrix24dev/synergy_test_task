<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}


           
class MyLeadsTable extends \Bitrix\Main\Entity\DataManager
{

    public static function getTableName()
    {
        return 'b_crm_lead';
    }  
    
    public static function getMap()
    {
        return array(
            'ID' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
                'title' => "ID",
            ),   
            'TITLE' => array(
                'data_type' => 'string',
                'title' => "TITLE",
            ),
            'DATE_CREATE' => array(
                'data_type' => 'datetime',
                'title' => "DATE_CREATE",
            ),
        );
    } 
}



Class SynTest extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        $result = parent::onPrepareComponentParams($arParams);
        return $result;
    }

    public function executeComponent()
    {
        if ($this->startResultCache(false)) {
            $this->arResult = $this->getResult();
            $this->includeComponentTemplate();
        }
    }


    private function getResult()
    {
        $result = false;
        $iblock_id = $this->arParams["IBLOCK_ID"];

        // выбираем 10 элементов инфоблока и все свойства
        $dbItems = \Bitrix\Iblock\ElementTable::getList(array(
            'select' => array('ID', 'NAME', 'IBLOCK_ID'),
            'filter' => array('IBLOCK_ID' => $iblock_id),
            'limit' => 10,
        ));
        while ($arItem = $dbItems->fetch()){  
            $dbProperty = \CIBlockElement::getProperty(
                $arItem['IBLOCK_ID'],
                $arItem['ID']
            );

            // выбираем нунжые нам свойства, для теста - одно свойство
            while($arProperty = $dbProperty->Fetch()){                  
                if($arProperty["CODE"] == "SSYLKA_NA_PASPORT_OBEKTA_V_IAS_UGD")
                {
                    $arItem["SSYLKA_NA_PASPORT_OBEKTA_V_IAS_UGD"] = $arProperty["VALUE"];
                }    
            }
            $arItems[] = $arItem;
        }
        $result["elements"] = $arItems;


        // попробуем работать с лидами через ORM, выбираем лиды, которые были созданы сегодня        
        $dbItems = MyLeadsTable::getList(array(
            'select' => array('ID', 'TITLE', 'DATE_CREATE'),
            'filter' => array('>=DATE_CREATE' => date("d.m.Y 00:00:00"), '<DATE_CREATE'=>date("d.m.Y 23:59:59")),
            'limit' => 10,
        ));
        while ($arItem = $dbItems->fetch()){  
            $arLeads[]  = $arItem;
        }    
        $result["leads"] = $arLeads;
        return $result;        
    }
    
}
