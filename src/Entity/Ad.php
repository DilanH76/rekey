<?php
namespace App\Entity;

use \DateTime;
use App\Entity\Platform;
use App\Entity\Category;
use App\Entity\User;

/**
 * Représente une annonce (un jeu à vendre) dans l'application ReKey
 */
class Ad {

    private ?int $id_ads;
    private string $title;
    private string $description;
    private float $price;
    private $cover_image; // BLOB pour l'image de couverture
    private string $game_key;
    private string $status;
    private DateTime $created_at;

    // Les clés étrangères (IDs bruts)
    private int $id_platform;
    private int $id_category;
    private int $id_user;

    // Les objets complets ( astuce ORM )
    private ?Platform $platform = null;
    private ?Category $category = null;
    private ?User $user = null;

    /**
     * Constructeur de l'entité Ad
     */
    public function __construct(string $title, string $description, float $price, $cover_image, string $game_key, string $status, DateTime $created_at, int $id_platform, int $id_category, int $id_user, ?int $id_ads = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->cover_image = $cover_image;
        $this->game_key = $game_key;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->id_platform = $id_platform;
        $this->id_category = $id_category;
        $this->id_user = $id_user;
        $this->id_ads = $id_ads;
    }

    // =========================================================
    // GETTERS & SETTERS (Données classiques)
    // =========================================================

    public function getIdAds(): ?int
    {
        return $this->id_ads; 
    }
    public function setIdAds(?int $id_ads): self 
    { 
        $this->id_ads = $id_ads; 
        return $this; 
    }

    public function getTitle(): string 
    { 
        return $this->title; 
    }
    public function setTitle(string $title): self 
    { 
        $this->title = $title; 
        return $this; 
    }

    public function getDescription(): string 
    { 
        return $this->description; 
    }
    public function setDescription(string $description): self 
    { 
        $this->description = $description; 
        return $this; 
    }

    public function getPrice(): float 
    { 
        return $this->price; 
    }
    public function setPrice(float $price): self 
    { 
        $this->price = $price; 
        return $this; 
    }

    public function getCoverImage() 
    { 
        return $this->cover_image; 
    }
    public function setCoverImage($cover_image): self 
    { 
        $this->cover_image = $cover_image; 
        return $this; 
    }

    public function getGameKey(): string 
    { 
        return $this->game_key; 
    }
    public function setGameKey(string $game_key): self 
    { 
        $this->game_key = $game_key; 
        return $this; 
    }

    public function getStatus(): string 
    { 
        return $this->status; 
    }
    public function setStatus(string $status): self 
    { 
        $this->status = $status; 
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

    public function getIdPlatform(): int 
    { 
        return $this->id_platform; 
    }
    public function setIdPlatform(int $id_platform): self 
    { 
        $this->id_platform = $id_platform; 
        return $this; 
    }

    public function getIdCategory(): int 
    { 
        return $this->id_category; 
    }
    public function setIdCategory(int $id_category): self 
    { 
        $this->id_category = $id_category; 
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

    public function getPlatform(): ?Platform 
    { 
        return $this->platform; 
    }
    public function setPlatform(?Platform $platform): self 
    { 
        $this->platform = $platform; 
        return $this; 
    }

    public function getCategory(): ?Category 
    { 
        return $this->category; 
    }
    public function setCategory(?Category $category): self 
    { 
        $this->category = $category; 
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

    // =========================================================
    // MÉTHODES UTILITAIRES
    // =========================================================

    /**
     * Retourne l'image de couverture formatée pour une balise HTML <img>
     * @return string L'URL de l'image par défaut ou le Base64 de l'image uploadée
     */
    public function getCoverImageBase64(): string
    {
        if (!$this->cover_image) {
            return '/assets/img/default_cover.svg';
        }
        return 'data:image/jpeg;base64,' . base64_encode($this->cover_image);
    }
}
?>