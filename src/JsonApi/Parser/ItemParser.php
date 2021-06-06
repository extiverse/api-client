<?php

namespace Extiverse\Api\JsonApi\Parser;

use Swis\JsonApi\Client\Interfaces\ItemInterface;

class ItemParser extends \Swis\JsonApi\Client\Parsers\ItemParser
{
    public function parse($data): ItemInterface
    {
        if (is_object($data)
            && property_exists($data, 'relationships')) {
            foreach($data->relationships as $relations => $relationships) {
                if (empty($relationships)) unset($data->relationships->$relations);
            }
        }

        return parent::parse($data);
    }
}
