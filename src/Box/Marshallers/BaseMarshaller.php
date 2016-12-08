<?php

namespace Box\Marshallers;

use PhpJsonMarshaller\Marshaller\JsonMarshaller;
use PhpJsonMarshaller\Decoder\ClassDecoder;
use PhpJsonMarshaller\Reader\DoctrineAnnotationReader;

class BaseMarshaller
{
    protected $marshaller = null;

    public function getMarshaller()
    {
        if (is_null($this->marshaller)) {
            $this->marshaller = new JsonMarshaller(new ClassDecoder(new DoctrineAnnotationReader()));
        }

        return $this->marshaller;
    }
}
