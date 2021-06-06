<?php

namespace Extiverse\Api\JsonApi\Parser;

use Swis\JsonApi\Client\Interfaces\TypeMapperInterface;
use Swis\JsonApi\Client\Parsers\CollectionParser;
use Swis\JsonApi\Client\Parsers\DocumentParser as Parser;
use Swis\JsonApi\Client\Parsers\ErrorCollectionParser;
use Swis\JsonApi\Client\Parsers\ErrorParser;
use Swis\JsonApi\Client\Parsers\JsonapiParser;
use Swis\JsonApi\Client\Parsers\LinksParser;
use Swis\JsonApi\Client\Parsers\MetaParser;
use Swis\JsonApi\Client\TypeMapper;

class DocumentParser extends Parser
{
    public static function create(TypeMapperInterface $typeMapper = null): Parser
    {
        $metaParser = new MetaParser();
        $linksParser = new LinksParser($metaParser);
        $itemParser = new ItemParser($typeMapper ?? new TypeMapper(), $linksParser, $metaParser);

        return new static(
            $itemParser,
            new CollectionParser($itemParser),
            new ErrorCollectionParser(
                new ErrorParser($linksParser, $metaParser)
            ),
            $linksParser,
            new JsonapiParser($metaParser),
            $metaParser
        );
    }
}
