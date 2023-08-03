<?php

namespace Ernandesrs\LapiPayment\Models;

class Document
{
    /**
     * Document type
     *
     * @var string
     */
    private string $type;

    /**
     * Document number
     *
     * @var integer
     */
    private int $number;

    /**
     * Document is CPF
     *
     * @param integer $number
     * @return Document
     */
    public static function cpf(int $number)
    {
        $new = new Document;

        $new->type = 'cpf';
        $new->number = $number;

        return $new;
    }

    /**
     * Document is CNH
     *
     * @param integer $number
     * @return Document
     */
    public static function cnh(int $number)
    {
        $new = new Document;

        $new->type = 'cnh';
        $new->number = $number;

        return $new;
    }

    /**
     * Document is CNPJ
     *
     * @param integer $number
     * @return Document
     */
    public static function cnpj(int $number)
    {
        $new = new Document;

        $new->type = 'cnpj';
        $new->number = $number;

        return $new;
    }

    /**
     * Get
     *
     * @param string $key
     * @return null|int|string
     */
    public function __get(string $key)
    {
        return $this->$key ?? null;
    }
}