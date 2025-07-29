<?php
namespace Mapathe;

use Mapathe\Abstract\AbstractEntity;

class Response extends AbstractEntity
{

    protected ?string $data;
    protected string $message ;
    protected Statut $status ;
    protected int $code ;

    public function __construct(
        string $message = '',
        Statut $status = Statut::ERROR,
        int $code = 404,
        ?string $data = null,

    ) {
        $this->data = $data;
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
    }


    public function toArray(): array
    {
        return [
            'data' => $this->data,
            'status' => $this->status,
            'code' => $this->code,
            'message' => $this->message,
        ];
    }
    public static function toObject(array $data): static
    {
        return new static();
    }
}
