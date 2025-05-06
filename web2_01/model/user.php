<?php 
class User {
    private $id = null;
    private $name = null;
    private $lastName = null;
    private $birthDay = null;
    private $birthMonth = null;
    private $birthYear = null;
    private $password = null;
    private $email = null;
    private $role = null;

    public function __construct(
        $id,
        $name,
        $lastName,
        $birthDay,
        $birthMonth,
        $birthYear,
        $password,
        $email
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->lastName = $lastName;
        $this->birthDay = $birthDay;
        $this->birthMonth = $birthMonth;
        $this->birthYear = $birthYear;
        $this->password = $password;
        $this->email = $email;
    }

    public function getId(): ?int {
        return $this->id;
    }
    public function setId(?int $id): void {
        $this->id = $id;
    }

    // Name
    public function getName(): ?string {
        return $this->name;
    }
    public function setName(?string $name): void {
        $this->name = $name;
    }

    // Last Name
    public function getLastName(): ?string {
        return $this->lastName;
    }
    public function setLastName(?string $lastName): void {
        $this->lastName = $lastName;
    }

    // Birth Day
    public function getBirthDay(): ?int {
        return $this->birthDay;
    }
    public function setBirthDay(?int $birthDay): void {
        $this->birthDay = $birthDay;
    }

    // Birth Month
    public function getBirthMonth(): ?int {
        return $this->birthMonth;
    }
    public function setBirthMonth(?int $birthMonth): void {
        $this->birthMonth = $birthMonth;
    }

    // Birth Year
    public function getBirthYear(): ?int {
        return $this->birthYear;
    }
    public function setBirthYear(?int $birthYear): void {
        $this->birthYear = $birthYear;
    }

    // Password
    public function getPassword(): ?string {
        return $this->password;
    }
    public function setPassword(?string $password): void {
        // Optional: hash the password here
        $this->password = $password;
    }

    // Email
    public function getEmail(): ?string {
        return $this->email;
    }
    public function setEmail(?string $email): void {
        $this->email = $email;
    }
     public function getRole(): ?int {
        return $this->role;
    }
    public function setRole(?int $role): void {
        $this->role = $role;
    }

}