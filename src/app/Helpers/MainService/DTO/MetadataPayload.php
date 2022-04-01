<?php

namespace App\Helpers\MainService\DTO;

use JsonSerializable;

final class MetadataPayload implements JsonSerializable
{
    public function __construct(public string $action, public int $tmpUid)
    {

    }

    public function jsonSerialize(): array
    {
        return [
            'action' => $this->action, 'tmp_uid' => $this->tmpUid
        ];
    }
}
