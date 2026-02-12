<?php

namespace Modules\Project\Enums;

enum ProjectType: string
{
    case Internal = 'Internal';
    case External = 'External';

    public function code(): string
    {
        return match ($this) {
            self::Internal => 'INT',
            self::External => 'EXT',
        };
    }
}
