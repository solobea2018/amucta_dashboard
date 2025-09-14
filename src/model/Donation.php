<?php
namespace Solobea\Dashboard\model;

class Donation
{
    private $full_name;
    private $email;
    private $phone;
    private $amount;
    private $method;
    private $transaction_id;

    // Getters and Setters
    public function getFullName(): string { return $this->full_name; }
    public function setFullName(string $name) { $this->full_name = $name; }

    public function getEmail(): string { return $this->email; }
    public function setEmail(string $email) { $this->email = $email; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone) { $this->phone = $phone; }

    public function getAmount(): float { return $this->amount; }
    public function setAmount(float $amount) { $this->amount = $amount; }

    public function getMethod(): string { return $this->method; }
    public function setMethod(string $method) { $this->method = $method; }

    public function getTransactionId(): ?string { return $this->transaction_id; }
    public function setTransactionId(?string $id) { $this->transaction_id = $id; }
}
