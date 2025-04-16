<?php declare(strict_types=1);

namespace Common\Enum;

enum BranchAuthorStatus: int
{
    case Participant = 100;
    case Invited = 70;
    case Candidate = 50;
    case Refused = 30;
    case Ban = 10;

    public static function getStatusString(int $role)
    {
        return match ($role) {
            self::Participant->value => 'participant',
            self::Invited->value => 'invited',
            self::Candidate->value => 'candidate',
            self::Refused->value => 'refused',
            self::Ban->value => 'banned',
        };
    }
}
