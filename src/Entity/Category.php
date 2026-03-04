<?php

namespace App\Entity;

/**
 * Représente une catégorie de jeu dans l'application ReKey (ex: Action, RPG, FPS...)
 */

class Category {

    private ?int $id_category;
    private string $label;

    /**
     * Constructeur de l'entité Category
     * @param string $label Le nom de la catégorie
     * @param int|null $id_category L'identifiant unique (null par défaut lors de la création avant l'insertion en BDD)
     */
    public function __construct(string $label, ?int $id_category = null)
    {
        $this->label = $label;
        $this->id_category = $id_category;
    }

    // =========================================================
    // GETTERS & SETTERS
    // =========================================================

    public function getIdCategory(): ?int
    {
        return $this->id_category;
    }

    public function setIdCategory(?int $id_category): self
    {
        $this->id_category = $id_category;

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
}

?>