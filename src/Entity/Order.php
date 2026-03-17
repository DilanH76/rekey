<?php
namespace App\Entity;

use App\Entity\Ad;
use App\Entity\User;
use \DateTime;

/**
 * Représente une commande (un achat simulé) dans l'application ReKey
 */
class Order {

    private ?int $id_order;
    private string $reference;
    private DateTime $date_order;

    // Clés étrangères
    private int $id_ads;
    private int $id_user;

    // Objets relationnels (ORM)
    private ?Ad $ad = null;     // Le jeu qui a été acheté
    private ?User $user = null; // L'acheteur

    /**
     * Constructeur de l'entité Order
     */
    public function __construct(string $reference, DateTime $date_order, int $id_ads, int $id_user, ?int $id_order = null)
    {
        $this->reference = $reference;
        $this->date_order = $date_order;
        $this->id_ads = $id_ads;
        $this->id_user = $id_user;
        $this->id_order = $id_order;
    }

    // =========================================================
    // GETTERS & SETTERS
    // =========================================================



    public function getIdOrder(): ?int
    {
        return $this->id_order;
    }

    public function setIdOrder(?int $id_order): self
    {
        $this->id_order = $id_order;

        return $this;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDateOrder(): DateTime
    {
        return $this->date_order;
    }

    public function setDateOrder(DateTime $date_order): self
    {
        $this->date_order = $date_order;

        return $this;
    }

    public function getIdAds(): int
    {
        return $this->id_ads;
    }

    public function setIdAds(int $id_ads): self
    {
        $this->id_ads = $id_ads;

        return $this;
    }

    public function getIdUser(): int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    // =========================================================
    // GETTERS & SETTERS (Objets relationnels)
    // =========================================================

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
?>