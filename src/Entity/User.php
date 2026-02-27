<?php
namespace App\Entity;

use \DateTime;

class User {
    private ?int $id_user;
    private string $last_name;
    private string $first_name;
    private string $pseudo;
    private string $email;
    private string $password;
    private bool $is_admin;
    private DateTime $created_at;
    private $avatar;

    public function __construct(string $last_name, string $first_name, string $pseudo, string $email, string $password, DateTime $created_at, $avatar, bool $is_admin=false, ?int $id_user=null)
    {
        $this->id_user=$id_user;
        $this->last_name=$last_name;
        $this->first_name=$first_name;
        $this->pseudo=$pseudo;
        $this->email=$email;
        $this->password=$password;
        $this->is_admin=$is_admin;
        $this->created_at=$created_at;
        $this->avatar=$avatar;

    }


    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(?int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsAdmin(): bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTime $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getavatarBase64(): string
    {
        if (!$this->avatar) return "";
        // TODO Mettre une image de profil par défaut si l'utilisateur n'en a pas
        // return 'chemin/vers/assets/img/default-avatar.png';
        return 'data:image/jpeg;base64,' . base64_encode($this->avatar);

    }
}




?>