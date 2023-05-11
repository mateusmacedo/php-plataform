<?php

declare(strict_types=1);

namespace Core\Infrastructure\Ecotone\Converters;

use Ecotone\Messaging\Attribute\MediaTypeConverter;
use Ecotone\Messaging\Conversion\{Converter, MediaType};
use Ecotone\Messaging\Handler\TypeDescriptor;

#[MediaTypeConverter]
class JsonToPhpConverter implements Converter
{
    public function matches(TypeDescriptor $sourceType, MediaType $sourceMediaType, TypeDescriptor $targetType, MediaType $targetMediaType): bool
    {
        return $sourceMediaType->isCompatibleWith(MediaType::createApplicationJson())
            && $targetMediaType->isCompatibleWith(MediaType::createApplicationXPHP());
    }

    public function convert($source, TypeDescriptor $sourceType, MediaType $sourceMediaType, TypeDescriptor $targetType, MediaType $targetMediaType)
    {
        $data = json_decode($source, true, 512, JSON_THROW_ON_ERROR);
        $commandType = $targetType->getTypeHint();
        return new $commandType(...$data);
    }
}
