<?php

class WalletNotFoundException extends \Exception
{
}

class SumNotFoundException extends \Exception
{
}

class TokenNotFoundException extends \Exception
{
}

class SmsParser
{
    private $string;
    private $wallet;
    private $sum;
    private $token;

    public function __construct(string $string)
    {
        $this->string = $string;

        $this->findSum();
        $this->findWallet();
        $this->findToken();
    }

    private function clearString(string $remove): void
    {
        $this->string = str_replace($remove, "", $this->string);
    }

    private function findWallet(): void
    {
        if (!preg_match('/\d{11,20}/m', $this->string, $matches)) {
            throw new WalletNotFoundException();
        }

        $this->wallet = $matches[0];
        $this->clearString($this->wallet);
    }

    private function findSum(): void
    {
        if (!preg_match('/(\d+[,.]\d{1,2})|(\d+\s?[р])/mu', $this->string, $matches)) {
            throw new SumNotFoundException();
        }

        $this->sum = (float)str_replace(',', '.', $matches[0]);
        $this->clearString($matches[0]);
    }

    private function findToken(): void
    {
        if (!preg_match('/\d{4,6}/m', $this->string, $matches)) {
            throw new TokenNotFoundException();
        }

        $this->token = $matches[0];
        $this->clearString($this->token);
    }

    public function getWallet(): string
    {
        return $this->wallet;
    }

    public function getSum(): float
    {
        return $this->sum;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
