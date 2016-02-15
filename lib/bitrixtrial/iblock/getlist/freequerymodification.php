<?php
 /**
  * ----------------------------------------------------
  * | Автор: Андрей Рыжов (Dune) <info@rznw.ru>         |
  * | Сайт: www.rznw.ru                                 |
  * | Телефон: +7 (4912) 51-10-23                       |
  * | Дата: 26.06.2015                                      
  * ----------------------------------------------------
  *
  * Пример использования:
  *
  *
  *
  * Добавление информации из картинки
  *
    Rzn\Library\BitrixTrial\Iblock\GetList\FreeQueryModification::setQueryParts([
        'select' => ', IMG.SUBDIR as IMG_SUBDIR, IMG.FILE_NAME as FILE_NAME
            ,IMG.HEIGHT as FILE_HEIGHT
            ,IMG.WIDTH  as FILE_WIDTH
            ,IMG.FILE_SIZE as FILE_SIZE
            ,IMG.CONTENT_TYPE as FILE_CONTENT_TYPE
            ,IMG.ORIGINAL_NAME as FILE_ORIGINAL_NAME
            ,IMG.DESCRIPTION as FILE_DESCRIPTION
            ',
        'from'   => '  LEFT JOIN b_file IMG ON (BE.PREVIEW_PICTURE = IMG.ID AND IMG.HEIGHT = 200)',
        'where'   => ' AND IMG.HEIGHT = 200',
    ]);

    $rsElements = Rzn\Library\BitrixTrial\Iblock\GetList\FreeQueryModification::GetList($arSort, $filter, false, $arNavParams, $arSelect);
*/

namespace Rzn\Library\BitrixTrial\Iblock\GetList;
use CIBlockElement;
use COption;
use CPageOption;
use CModule;
use CCatalogProduct;
use CIBlockResult;
use CDBResult;

class FreeQueryModification extends CIBlockElement
{
    protected static $queryParts = ['select', 'where', 'from'];
    static public function setQueryParts($parts)
    {
        foreach ($parts as $key => $part) {
            self::$queryParts[$key] = $part;
        }
    }

    /**
     * List of elements.
     *
     * @param array $arOrder
     * @param array $arFilter
     * @param bool|array $arGroupBy
     * @param bool|array $arNavStartParams
     * @param array $arSelectFields
     * @return CIBlockResult
     */
    function GetList($arOrder=Array("SORT"=>"ASC"), $arFilter=Array(), $arGroupBy=false, $arNavStartParams=false, $arSelectFields=Array())
    {
        /*
        Filter combinations:
        CHECK_PERMISSIONS="N" - check permissions of the current user to the infoblock
            MIN_PERMISSION="R" - when permissions check, then minimal access level
        SHOW_HISTORY="N" - add history items to list
            SHOW_NEW="N" - if not add history items, then add new, but not published elements
        */
        global $DB, $USER, $APPLICATION;
        $MAX_LOCK = intval(COption::GetOptionString("workflow","MAX_LOCK_TIME","60"));
        $uid = is_object($USER)? intval($USER->GetID()): 0;

        $arIblockElementFields = Array(
            "ID"=>"BE.ID",
            "TIMESTAMP_X"=>$DB->DateToCharFunction("BE.TIMESTAMP_X"),
            "TIMESTAMP_X_UNIX"=>'UNIX_TIMESTAMP(BE.TIMESTAMP_X)',
            "MODIFIED_BY"=>"BE.MODIFIED_BY",
            "DATE_CREATE"=>$DB->DateToCharFunction("BE.DATE_CREATE"),
            "DATE_CREATE_UNIX"=>'UNIX_TIMESTAMP(BE.DATE_CREATE)',
            "CREATED_BY"=>"BE.CREATED_BY",
            "IBLOCK_ID"=>"BE.IBLOCK_ID",
            "IBLOCK_SECTION_ID"=>"BE.IBLOCK_SECTION_ID",
            "ACTIVE"=>"BE.ACTIVE",
            "ACTIVE_FROM"=>(
            CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "-")!="-"
                ?
                $DB->DateToCharFunction("BE.ACTIVE_FROM", CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "SHORT"))
                :
                "IF(EXTRACT(HOUR_SECOND FROM BE.ACTIVE_FROM)>0, ".$DB->DateToCharFunction("BE.ACTIVE_FROM", "FULL").", ".$DB->DateToCharFunction("BE.ACTIVE_FROM", "SHORT").")"
            ),
            "ACTIVE_TO"=>(
            CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "-")!="-"
                ?
                $DB->DateToCharFunction("BE.ACTIVE_TO", CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "SHORT"))
                :
                "IF(EXTRACT(HOUR_SECOND FROM BE.ACTIVE_TO)>0, ".$DB->DateToCharFunction("BE.ACTIVE_TO", "FULL").", ".$DB->DateToCharFunction("BE.ACTIVE_TO", "SHORT").")"
            ),
            "DATE_ACTIVE_FROM"=>(
            CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "-")!="-"
                ?
                $DB->DateToCharFunction("BE.ACTIVE_FROM", CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "SHORT"))
                :
                "IF(EXTRACT(HOUR_SECOND FROM BE.ACTIVE_FROM)>0, ".$DB->DateToCharFunction("BE.ACTIVE_FROM", "FULL").", ".$DB->DateToCharFunction("BE.ACTIVE_FROM", "SHORT").")"
            ),
            "DATE_ACTIVE_TO"=>(
            CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "-")!="-"
                ?
                $DB->DateToCharFunction("BE.ACTIVE_TO", CPageOption::GetOptionString("iblock", "FORMAT_ACTIVE_DATES", "SHORT"))
                :
                "IF(EXTRACT(HOUR_SECOND FROM BE.ACTIVE_TO)>0, ".$DB->DateToCharFunction("BE.ACTIVE_TO", "FULL").", ".$DB->DateToCharFunction("BE.ACTIVE_TO", "SHORT").")"
            ),
            "SORT"=>"BE.SORT",
            "NAME"=>"BE.NAME",
            "PREVIEW_PICTURE"=>"BE.PREVIEW_PICTURE",
            "PREVIEW_TEXT"=>"BE.PREVIEW_TEXT",
            "PREVIEW_TEXT_TYPE"=>"BE.PREVIEW_TEXT_TYPE",
            "DETAIL_PICTURE"=>"BE.DETAIL_PICTURE",
            "DETAIL_TEXT"=>"BE.DETAIL_TEXT",
            "DETAIL_TEXT_TYPE"=>"BE.DETAIL_TEXT_TYPE",
            "SEARCHABLE_CONTENT"=>"BE.SEARCHABLE_CONTENT",
            "WF_STATUS_ID"=>"BE.WF_STATUS_ID",
            "WF_PARENT_ELEMENT_ID"=>"BE.WF_PARENT_ELEMENT_ID",
            "WF_LAST_HISTORY_ID"=>"BE.WF_LAST_HISTORY_ID",
            "WF_NEW"=>"BE.WF_NEW",
            "LOCK_STATUS"=>"if (BE.WF_DATE_LOCK is null, 'green', if(DATE_ADD(BE.WF_DATE_LOCK, interval ".$MAX_LOCK." MINUTE)<now(), 'green', if(BE.WF_LOCKED_BY=".$uid.", 'yellow', 'red')))",
            "WF_LOCKED_BY"=>"BE.WF_LOCKED_BY",
            "WF_DATE_LOCK"=>$DB->DateToCharFunction("BE.WF_DATE_LOCK"),
            "WF_COMMENTS"=>"BE.WF_COMMENTS",
            "IN_SECTIONS"=>"BE.IN_SECTIONS",
            "SHOW_COUNTER"=>"BE.SHOW_COUNTER",
            "SHOW_COUNTER_START"=>"BE.SHOW_COUNTER_START",
            "CODE"=>"BE.CODE",
            "TAGS"=>"BE.TAGS",
            "XML_ID"=>"BE.XML_ID",
            "EXTERNAL_ID"=>"BE.XML_ID",
            "TMP_ID"=>"BE.TMP_ID",
            "USER_NAME"=>"concat('(',U.LOGIN,') ',ifnull(U.NAME,''),' ',ifnull(U.LAST_NAME,''))",
            "LOCKED_USER_NAME"=>"concat('(',UL.LOGIN,') ',ifnull(UL.NAME,''),' ',ifnull(UL.LAST_NAME,''))",
            "CREATED_USER_NAME"=>"concat('(',UC.LOGIN,') ',ifnull(UC.NAME,''),' ',ifnull(UC.LAST_NAME,''))",
            "LANG_DIR"=>"L.DIR",
            "LID"=>"B.LID",
            "IBLOCK_TYPE_ID"=>"B.IBLOCK_TYPE_ID",
            "IBLOCK_CODE"=>"B.CODE",
            "IBLOCK_NAME"=>"B.NAME",
            "IBLOCK_EXTERNAL_ID"=>"B.XML_ID",
            "DETAIL_PAGE_URL"=>"B.DETAIL_PAGE_URL",
            "LIST_PAGE_URL"=>"B.LIST_PAGE_URL",
            "CREATED_DATE"=>$DB->DateFormatToDB("YYYY.MM.DD", "BE.DATE_CREATE"),
            "BP_PUBLISHED"=>"if(BE.WF_STATUS_ID = 1, 'Y', 'N')",
        );

        $bDistinct = false;

        FreeQueryModification::PrepareGetList(
            $arIblockElementFields,
            $arJoinProps,
            $bOnlyCount,
            $bDistinct,

            $arSelectFields,
            $sSelect,
            $arAddSelectFields,

            $arFilter,
            $sWhere,
            $sSectionWhere,
            $arAddWhereFields,

            $arGroupBy,
            $sGroupBy,

            $arOrder,
            $arSqlOrder,
            $arAddOrderByFields,

            $arIBlockFilter,
            $arIBlockMultProps,
            $arIBlockConvProps,
            $arIBlockAllProps,
            $arIBlockNumProps,
            $arIBlockLongProps
        );

        $arFilterIBlocks = isset($arFilter["IBLOCK_ID"])? array($arFilter["IBLOCK_ID"]): array();
        //******************FROM PART********************************************
        $sFrom = "";
        foreach($arJoinProps["FPS"] as $iblock_id => $iPropCnt)
        {
            $sFrom .= "\t\t\tINNER JOIN b_iblock_element_prop_s".$iblock_id." FPS".$iPropCnt." ON FPS".$iPropCnt.".IBLOCK_ELEMENT_ID = BE.ID\n";
            $arFilterIBlocks[$iblock_id] = $iblock_id;
        }

        foreach($arJoinProps["FP"] as $propID => $db_prop)
        {
            $i = $db_prop["CNT"];

            if($db_prop["bFullJoin"])
                $sFrom .= "\t\t\tINNER JOIN b_iblock_property FP".$i." ON FP".$i.".IBLOCK_ID = B.ID AND ".
                    (
                    IntVal($propID)>0?
                        " FP".$i.".ID=".IntVal($propID)."\n":
                        " FP".$i.".CODE='".$DB->ForSQL($propID, 200)."'\n"
                    );
            else
                $sFrom .= "\t\t\tLEFT JOIN b_iblock_property FP".$i." ON FP".$i.".IBLOCK_ID = B.ID AND ".
                    (
                    IntVal($propID)>0?
                        " FP".$i.".ID=".IntVal($propID)."\n":
                        " FP".$i.".CODE='".$DB->ForSQL($propID, 200)."'\n"
                    );

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        foreach($arJoinProps["FPV"] as $propID => $db_prop)
        {
            $i = $db_prop["CNT"];

            if($db_prop["MULTIPLE"]=="Y")
                $bDistinct = true;

            if($db_prop["VERSION"]==2)
                $strTable = "b_iblock_element_prop_m".$db_prop["IBLOCK_ID"];
            else
                $strTable = "b_iblock_element_property";

            if($db_prop["bFullJoin"])
                $sFrom .= "\t\t\tINNER JOIN ".$strTable." FPV".$i." ON FPV".$i.".IBLOCK_PROPERTY_ID = FP".$db_prop["JOIN"].".ID AND FPV".$i.".IBLOCK_ELEMENT_ID = BE.ID\n";
            else
                $sFrom .= "\t\t\tLEFT JOIN ".$strTable." FPV".$i." ON FPV".$i.".IBLOCK_PROPERTY_ID = FP".$db_prop["JOIN"].".ID AND FPV".$i.".IBLOCK_ELEMENT_ID = BE.ID\n";

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        foreach($arJoinProps["FPEN"] as $propID => $db_prop)
        {
            $i = $db_prop["CNT"];

            if($db_prop["VERSION"] == 2 && $db_prop["MULTIPLE"] == "N")
            {
                if($db_prop["bFullJoin"])
                    $sFrom .= "\t\t\tINNER JOIN b_iblock_property_enum FPEN".$i." ON FPEN".$i.".PROPERTY_ID = ".$db_prop["ORIG_ID"]." AND FPS".$db_prop["JOIN"].".PROPERTY_".$db_prop["ORIG_ID"]." = FPEN".$i.".ID\n";
                else
                    $sFrom .= "\t\t\tLEFT JOIN b_iblock_property_enum FPEN".$i." ON FPEN".$i.".PROPERTY_ID = ".$db_prop["ORIG_ID"]." AND FPS".$db_prop["JOIN"].".PROPERTY_".$db_prop["ORIG_ID"]." = FPEN".$i.".ID\n";
            }
            else
            {
                if($db_prop["bFullJoin"])
                    $sFrom .= "\t\t\tINNER JOIN b_iblock_property_enum FPEN".$i." ON FPEN".$i.".PROPERTY_ID = FPV".$db_prop["JOIN"].".IBLOCK_PROPERTY_ID AND FPV".$db_prop["JOIN"].".VALUE_ENUM = FPEN".$i.".ID\n";
                else
                    $sFrom .= "\t\t\tLEFT JOIN b_iblock_property_enum FPEN".$i." ON FPEN".$i.".PROPERTY_ID = FPV".$db_prop["JOIN"].".IBLOCK_PROPERTY_ID AND FPV".$db_prop["JOIN"].".VALUE_ENUM = FPEN".$i.".ID\n";
            }

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        foreach($arJoinProps["BE"] as $propID => $db_prop)
        {
            $i = $db_prop["CNT"];

            $sFrom .= "\t\t\tLEFT JOIN b_iblock_element BE".$i." ON BE".$i.".ID = ".
                (
                $db_prop["VERSION"]==2 && $db_prop["MULTIPLE"]=="N"?
                    "FPS".$db_prop["JOIN"].".PROPERTY_".$db_prop["ORIG_ID"]
                    :"FPV".$db_prop["JOIN"].".VALUE_NUM"
                ).
                (
                $arFilter["SHOW_HISTORY"] != "Y"?
                    " AND ((BE.WF_STATUS_ID=1 AND BE.WF_PARENT_ELEMENT_ID IS NULL)".($arFilter["SHOW_NEW"]=="Y"? " OR BE.WF_NEW='Y'": "").")":
                    ""
                )."\n";

            if($db_prop["bJoinIBlock"])
                $sFrom .= "\t\t\tLEFT JOIN b_iblock B".$i." ON B".$i.".ID = BE".$i.".IBLOCK_ID\n";

            if($db_prop["bJoinSection"])
                $sFrom .= "\t\t\tLEFT JOIN b_iblock_section BS".$i." ON BS".$i.".ID = BE".$i.".IBLOCK_SECTION_ID\n";

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        foreach($arJoinProps["BE_FPS"] as $iblock_id => $db_prop)
        {
            $sFrom .= "\t\t\tLEFT JOIN b_iblock_element_prop_s".$iblock_id." JFPS".$db_prop["CNT"]." ON JFPS".$db_prop["CNT"].".IBLOCK_ELEMENT_ID = BE".$db_prop["JOIN"].".ID\n";

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        foreach($arJoinProps["BE_FP"] as $propID => $db_prop)
        {
            $i = $db_prop["CNT"];
            list($propID, $link) = explode("~", $propID, 2);

            if($db_prop["bFullJoin"])
                $sFrom .= "\t\t\tINNER JOIN b_iblock_property JFP".$i." ON JFP".$i.".IBLOCK_ID = BE".$db_prop["JOIN"].".IBLOCK_ID AND ".
                    (
                    IntVal($propID)>0?
                        " JFP".$i.".ID=".IntVal($propID)."\n":
                        " JFP".$i.".CODE='".$DB->ForSQL($propID, 200)."'\n"
                    );
            else
                $sFrom .= "\t\t\tLEFT JOIN b_iblock_property JFP".$i." ON JFP".$i.".IBLOCK_ID = BE".$db_prop["JOIN"].".IBLOCK_ID AND ".
                    (
                    IntVal($propID)>0?
                        " JFP".$i.".ID=".IntVal($propID)."\n":
                        " JFP".$i.".CODE='".$DB->ForSQL($propID, 200)."'\n"
                    );

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        foreach($arJoinProps["BE_FPV"] as $propID => $db_prop)
        {
            $i = $db_prop["CNT"];
            list($propID, $link) = explode("~", $propID, 2);

            if($db_prop["MULTIPLE"]=="Y")
                $bDistinct = true;

            if($db_prop["VERSION"]==2)
                $strTable = "b_iblock_element_prop_m".$db_prop["IBLOCK_ID"];
            else
                $strTable = "b_iblock_element_property";

            if($db_prop["bFullJoin"])
                $sFrom .= "\t\t\tINNER JOIN ".$strTable." JFPV".$i." ON JFPV".$i.".IBLOCK_PROPERTY_ID = JFP".$db_prop["JOIN"].".ID AND JFPV".$i.".IBLOCK_ELEMENT_ID = BE".$db_prop["BE_JOIN"].".ID\n";
            else
                $sFrom .= "\t\t\tLEFT JOIN ".$strTable." JFPV".$i." ON JFPV".$i.".IBLOCK_PROPERTY_ID = JFP".$db_prop["JOIN"].".ID AND JFPV".$i.".IBLOCK_ELEMENT_ID = BE".$db_prop["BE_JOIN"].".ID\n";

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        foreach($arJoinProps["BE_FPEN"] as $propID => $db_prop)
        {
            $i = $db_prop["CNT"];
            list($propID, $link) = explode("~", $propID, 2);

            if($db_prop["VERSION"] == 2 && $db_prop["MULTIPLE"] == "N")
            {
                if($db_prop["bFullJoin"])
                    $sFrom .= "\t\t\tINNER JOIN b_iblock_property_enum JFPEN".$i." ON JFPEN".$i.".PROPERTY_ID = ".$db_prop["ORIG_ID"]." AND JFPS".$db_prop["JOIN"].".PROPERTY_".$db_prop["ORIG_ID"]." = JFPEN".$i.".ID\n";
                else
                    $sFrom .= "\t\t\tLEFT JOIN b_iblock_property_enum JFPEN".$i." ON JFPEN".$i.".PROPERTY_ID = ".$db_prop["ORIG_ID"]." AND JFPS".$db_prop["JOIN"].".PROPERTY_".$db_prop["ORIG_ID"]." = JFPEN".$i.".ID\n";
            }
            else
            {
                if($db_prop["bFullJoin"])
                    $sFrom .= "\t\t\tINNER JOIN b_iblock_property_enum JFPEN".$i." ON JFPEN".$i.".PROPERTY_ID = JFPV".$db_prop["JOIN"].".IBLOCK_PROPERTY_ID AND JFPV".$db_prop["JOIN"].".VALUE_ENUM = JFPEN".$i.".ID\n";
                else
                    $sFrom .= "\t\t\tLEFT JOIN b_iblock_property_enum JFPEN".$i." ON JFPEN".$i.".PROPERTY_ID = JFPV".$db_prop["JOIN"].".IBLOCK_PROPERTY_ID AND JFPV".$db_prop["JOIN"].".VALUE_ENUM = JFPEN".$i.".ID\n";
            }

            if($db_prop["IBLOCK_ID"])
                $arFilterIBlocks[$db_prop["IBLOCK_ID"]] = $db_prop["IBLOCK_ID"];
        }

        if(strlen($arJoinProps["BES"]))
        {
            $sFrom .= "\t\t\t".$arJoinProps["BES"]."\n";
        }

        if(strlen($arJoinProps["FC"]))
        {
            $sFrom .= "\t\t\t".$arJoinProps["FC"]."\n";
        }

        if($arJoinProps["RV"])
            $sFrom .= "\t\t\tLEFT JOIN b_rating_voting RV ON RV.ENTITY_TYPE_ID = 'IBLOCK_ELEMENT' AND RV.ENTITY_ID = BE.ID\n";
        if($arJoinProps["RVU"])
            $sFrom .= "\t\t\tLEFT JOIN b_rating_vote RVU ON RVU.ENTITY_TYPE_ID = 'IBLOCK_ELEMENT' AND RVU.ENTITY_ID = BE.ID AND RVU.USER_ID = ".$uid."\n";
        if($arJoinProps["RVV"])
            $sFrom .= "\t\t\t".($arJoinProps["RVV"]["bFullJoin"]? "INNER": "LEFT")." JOIN b_rating_vote RVV ON RVV.ENTITY_TYPE_ID = 'IBLOCK_ELEMENT' AND RVV.ENTITY_ID = BE.ID\n";

        //******************END OF FROM PART********************************************

        $bCatalogSort = false;
        if(count($arAddSelectFields)>0 || count($arAddWhereFields)>0 || count($arAddOrderByFields)>0)
        {
            if(CModule::IncludeModule("catalog"))
            {
                $res_catalog = CCatalogProduct::GetQueryBuildArrays($arAddOrderByFields, $arAddWhereFields, $arAddSelectFields);
                if(
                    $sGroupBy==""
                    && !$bOnlyCount
                    && !(is_object($this) && isset($this->strField))
                )
                    $sSelect .= $res_catalog["SELECT"]." ";
                $sFrom .= str_replace("LEFT JOIN", "\n\t\t\tLEFT JOIN", $res_catalog["FROM"])."\n";
                //$sWhere .= $res_catalog["WHERE"]." "; moved to MkFilter
                if(is_array($res_catalog["ORDER"]) && count($res_catalog["ORDER"]))
                {
                    $bCatalogSort = true;
                    foreach($res_catalog["ORDER"] as $i=>$val)
                        $arSqlOrder[$i] = $val;
                }
            }
        }

        $i = array_search("CREATED_BY_FORMATTED", $arSelectFields);
        if ($i !== false)
        {
            if (
                $sSelect
                && $sGroupBy==""
                && !$bOnlyCount
                && !(is_object($this) && isset($this->strField))
            )
            {
                $sSelect .= ",UC.NAME UC_NAME, UC.LAST_NAME UC_LAST_NAME, UC.SECOND_NAME UC_SECOND_NAME, UC.EMAIL UC_EMAIL, UC.ID UC_ID, UC.LOGIN UC_LOGIN";
            }
            else
            {
                unset($arSelectFields[$i]);
            }
        }

        $sOrderBy = "";
        foreach($arSqlOrder as $i=>$val)
        {
            if(strlen($val))
            {
                if($sOrderBy=="")
                    $sOrderBy = " ORDER BY ";
                else
                    $sOrderBy .= ",";

                $sOrderBy .= $val." ";
            }
        }

        if(strlen(trim($sSelect))<=0)
            $sSelect = "0 as NOP ";

        $bDistinct = $bDistinct || (isset($arFilter["INCLUDE_SUBSECTIONS"]) && $arFilter["INCLUDE_SUBSECTIONS"] == "Y");

        if($bDistinct)
            $sSelect = str_replace("%%_DISTINCT_%%", "DISTINCT", $sSelect);
        else
            $sSelect = str_replace("%%_DISTINCT_%%", "", $sSelect);

        $sFrom = "
			b_iblock B
			INNER JOIN b_lang L ON B.LID=L.LID
			INNER JOIN b_iblock_element BE ON BE.IBLOCK_ID = B.ID
			".ltrim($sFrom, "\t\n")
            .(in_array("USER_NAME", $arSelectFields)? "\t\t\tLEFT JOIN b_user U ON U.ID=BE.MODIFIED_BY\n": "")
            .(in_array("LOCKED_USER_NAME", $arSelectFields)? "\t\t\tLEFT JOIN b_user UL ON UL.ID=BE.WF_LOCKED_BY\n": "")
            .(in_array("CREATED_USER_NAME", $arSelectFields) || in_array("CREATED_BY_FORMATTED", $arSelectFields)? "\t\t\tLEFT JOIN b_user UC ON UC.ID=BE.CREATED_BY\n": "")."
		";

        //--------------------------------------------
        //      Добавление свободных частей запроса
        //--------------------------------------------
        $sFrom   .= self::$queryParts['from'];
        $sSelect .= self::$queryParts['select'];
        $sWhere  .= self::$queryParts['where'];
        //--------------------------------------------
        //--------------------------------------------


        $strSql = "
			FROM ".$sFrom."
			WHERE 1=1 "
            .$sWhere."
			".$sGroupBy."
		";

        if(isset($this) && is_object($this) && isset($this->strField))
        {
            $this->sFrom = $sFrom;
            $this->sWhere = $sWhere;
            return "SELECT ".$sSelect.$strSql;
        }

        if($bOnlyCount)
        {
            $res = $DB->Query("SELECT ".$sSelect.$strSql, false, "FILE: ".__FILE__."<br> LINE: ".__LINE__);
            $res = $res->Fetch();
            return $res["CNT"];
        }

        if(is_array($arNavStartParams))
        {
            $nTopCount = intval($arNavStartParams["nTopCount"]);
            $nElementID = intval($arNavStartParams["nElementID"]);

            if($nTopCount > 0)
            {
                $strSql = "SELECT ".$sSelect.$strSql.$sOrderBy." LIMIT ".$nTopCount;
                $res = $DB->Query($strSql);
            }
            elseif(
                $nElementID > 0
                && $sGroupBy == ""
                && $sOrderBy != ""
                && strpos($sSelect, "BE.ID") !== false
                && !$bCatalogSort
            )
            {
                $nPageSize = intval($arNavStartParams["nPageSize"]);

                if($nPageSize > 0)
                {
                    $DB->Query("SET @rank=0");
                    $DB->Query("
						SELECT @rank:=el1.rank
						FROM (
							SELECT @rank:=@rank+1 AS rank, el0.*
							FROM (
								SELECT ".$sSelect.$strSql.$sOrderBy."
							) el0
						) el1
						WHERE el1.ID = ".$nElementID."
					");
                    $DB->Query("SET @rank2=0");

                    $res = $DB->Query("
						SELECT *
						FROM (
							SELECT @rank2:=@rank2+1 AS RANK, el0.*
							FROM (
								SELECT ".$sSelect.$strSql.$sOrderBy."
							) el0
						) el1
						WHERE el1.RANK between @rank-$nPageSize and @rank+$nPageSize
					");
                }
                else
                {
                    $DB->Query("SET @rank=0");
                    $res = $DB->Query("
						SELECT el1.*
						FROM (
							SELECT @rank:=@rank+1 AS RANK, el0.*
							FROM (
								SELECT ".$sSelect.$strSql.$sOrderBy."
							) el0
						) el1
						WHERE el1.ID = ".$nElementID."
					");
                }
            }
            else
            {
                if($sGroupBy == "")
                {
                    $res_cnt = $DB->Query("SELECT COUNT(".($bDistinct? "DISTINCT BE.ID": "'x'").") as C ".$strSql);
                    $res_cnt = $res_cnt->Fetch();
                    $cnt = $res_cnt["C"];
                }
                else
                {
                    $res_cnt = $DB->Query("SELECT 'x' ".$strSql);
                    $cnt = $res_cnt->SelectedRowsCount();
                }

                $strSql = "SELECT ".$sSelect.$strSql.$sOrderBy;
                $res = new CDBResult();
                $res->NavQuery($strSql, $cnt, $arNavStartParams);
            }
        }
        else//if(is_array($arNavStartParams))
        {
            $strSql = "SELECT ".$sSelect.$strSql.$sOrderBy;
            $res = $DB->Query($strSql, false, "FILE: ".__FILE__."<br> LINE: ".__LINE__);
        }

        $res = new CIBlockResult($res);
        $res->SetIBlockTag($arFilterIBlocks);
        $res->arIBlockMultProps = $arIBlockMultProps;
        $res->arIBlockConvProps = $arIBlockConvProps;
        $res->arIBlockAllProps  = $arIBlockAllProps;
        $res->arIBlockNumProps = $arIBlockNumProps;
        $res->arIBlockLongProps = $arIBlockLongProps;

        return $res;
    }
}