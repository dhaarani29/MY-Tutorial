<?php

namespace Petstore;

/**
 * @SWG\Definition(definition="item", required={"id", "title"})
 */
class SimplePet
{

    /**
     * @SWG\Property(format="int64")
     * @var int
     */
    public $id;

    /**
     * @SWG\Property()
     * @var string
     */
    public $title;

    /**
     * 
     * @SWG\Property()
     * @var string
     */
    public $tag_name;
}
