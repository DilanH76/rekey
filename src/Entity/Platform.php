<?php
namespace App\Entity;

/**
 * Représente une plateforme de jeu dans l'application ReKey (ex: Steam, PlayStation 5, Xbox...)
 */
class Platform {

    private ?int $id_platform;
    private string $label;
    private string $icon_svg;

    /**
     * Constructeur de l'entité Platform
     * @param string $icon_svg Le code SVG (ou le nom de la classe d'icône) de la plateforme
     * @param string $label Le nom de la plateforme
     * @param int|null $id_platform L'identifiant unique (null par défaut lors de la création)
     */
    public function __construct(string $icon_svg, string $label, ?int $id_platform = null)
    {
        $this->icon_svg = $icon_svg;
        $this->label = $label;
        $this->id_platform = $id_platform;
    }

    // =========================================================
    // GETTERS & SETTERS
    // =========================================================

    public function getIdPlatform(): ?int
    {
        return $this->id_platform;
    }

    public function setIdPlatform(?int $id_platform): self
    {
        $this->id_platform = $id_platform;
        return $this;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getIconSvg(): string
    {
        return $this->icon_svg;
    }

    public function setIconSvg(string $icon_svg): self
    {
        $this->icon_svg = $icon_svg;
        return $this;
    }
}
?>