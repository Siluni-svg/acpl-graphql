<?php

namespace App\Enum;

enum StatusEnum: string
{
    case DRAFT = 'draft';
    case IN_REVIEW = 'in_review';
    case PUBLISHED = 'published';
    case REJECTED = 'rejected';
}
