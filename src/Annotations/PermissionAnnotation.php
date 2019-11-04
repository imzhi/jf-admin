<?php

namespace Imzhi\JFAdmin\Annotations;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
final class PermissionAnnotation
{
    /**
     * @var string
     */
    public $name;
}
