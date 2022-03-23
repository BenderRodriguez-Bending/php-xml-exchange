<?php

namespace App\API\XML;

use App\API\XMLBodyController;

class XMLBody extends XMLBodyController
{
    const TYPE_POLICY = [
        1 => 'cTypePolisBSO',
        2 => 'cTypePolisEPolis',
        3 => 'cTypePolisEBSO',
    ];

    const IGNORE_TAGS = [
        '_token',
        'Product',
        'ContractId',
        'contract_type',
        'AddPerson',
        'bso_title_prolong',
        'Person',
        'Insurer__infoData'
    ];

    static function bodyXml($xml, $data)
    {
        foreach ($data->all() as $tag_one => $tag_content_one){
            if (self::nestedContent($tag_content_one)){
                $xml->startElement($tag_one);
                foreach ($tag_content_one as $tag_two => $tag_content_two){
                    if (self::nestedContent($tag_content_two)){
                        if (!in_array($tag_two, self::IGNORE_TAGS)){
                            $xml->startElement($tag_two);
                        }
                        foreach ($tag_content_two as $tag_free => $tag_content_free){
                            if (self::nestedContent($tag_content_free)){
                                is_int($tag_free) ? $xml->startElement($tag_two) : $xml->startElement($tag_free);
                                foreach ($tag_content_free as $tag_four => $tag_content_four){
                                    self::writeElement($xml, $tag_four, $tag_content_four);
                                }
                                $xml->endElement();
                            }else{
                                self::writeElement($xml, $tag_free, $tag_content_free);
                            }
                        }
                        if (!in_array($tag_two, self::IGNORE_TAGS)){
                            $xml->endElement();
                        }
                    }else{
                        self::writeElement($xml, $tag_two, $tag_content_two);
                    }
                }
                $xml->endElement();
            }else{
                self::writeElement($xml, $tag_one, $tag_content_one);
            }
        }
    }

    static function writeElement($xml, $tag, $content)
    {
        if (!in_array($tag, self::IGNORE_TAGS) && !empty($content)){
            $xml->writeElement($tag, $content);
        }
    }
    static function nestedContent($element): bool
    {
        return is_array($element) || is_object($element);
    }
}
