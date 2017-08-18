<?php

namespace GorkaLaucirica\HipchatAPIv2Client\Model;

use GorkaLaucirica\HipchatAPIv2Client\Hydrator;

/**
 * Class ModelBase
 *
 * @package GorkaLaucirica\HipchatAPIv2Client\Model
 */
class ModelBase
{
    use Hydrator;

    /**
     * ModelBase constructor.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->hydrate($data);
    }
}