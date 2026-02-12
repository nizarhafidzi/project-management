<?php

namespace Modules\Project\Enums;

enum ProjectStatus: string
{
    case Active = 'Active';
    case Completed = 'Completed';
    case OnHold = 'On-Hold';
}
