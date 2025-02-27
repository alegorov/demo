<?php

namespace App\Http\Crud\Service;

enum ModelStatus: int {
    case Allowed = 0;
    case Prohibited = 1;
}
