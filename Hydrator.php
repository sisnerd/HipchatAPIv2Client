<?php

namespace GorkaLaucirica\HipchatAPIv2Client;

/**
 * Trait Hydrator
 *
 * @package Nerdery\JobsBundle\Traits
 */
trait Hydrator
{
    /**
     * @param array $properties Expects $propertyName => $propertyValue
     * @param string|null $store Class property to store $properties array
     * @return array
     */
    function hydrate(array $properties = [], $store = null)
    {
        $response = [
            'set'          => [],
            'unknown'      => [],
            'inaccessible' => [],
        ];

        if (is_array($properties) && !empty($properties)) {
            foreach ($properties as $name => $value) {
                $setterName = ucfirst($name);
                if (stristr($name, '_')) {
                    $setterName = preg_replace_callback('`_(.){1}`', function ($match) {
                        return strtoupper($match[1]);
                    }, $setterName);
                }
                $setMethod = "set$setterName";
                if (method_exists($this, $setMethod)) {
                    $reflectionMethod = new \ReflectionMethod($this, $setMethod);
                    if ($reflectionMethod->isPublic()) {
                        $this->$setMethod($value);
                        $response['set'][] = $name;
                    } else {
                        $visibility                              = ($reflectionMethod->isPrivate())
                            ? 'private'
                            : 'protected';
                        $response['inaccessible'][$visibility][] = $name;
                    }
                } else {
                    $response['unknown'][] = $name;
                }
            }

            if (is_string($store)) {
                $this->$store = $properties;
            }
        }

        return $response;
    }
}