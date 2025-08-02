<?php

namespace App\Enum;

enum EtatMateriel: string
{
    case NW = 'N';
    case GOOD = 'B';
    case AVERAGE = 'M';
    case DEFECTIVE = 'D';
    case OUT_OF_SERVICE = 'H';

    /**
     * Retourne un tableau des choix pour les formulaires
     */
    public static function getChoices(): array
    {
        return [
            'Neuf' => self::NW,
            'Bon' => self::GOOD,
            'Moyen' => self::AVERAGE,
            'Défaillant' => self::DEFECTIVE,
            'Hors service' => self::OUT_OF_SERVICE,
        ];
    }

    /**
     * Retourne tous les états sous forme de tableau associatif
     */
    public static function getAll(): array
    {
        $states = [];
        foreach (self::cases() as $state) {
            $states[$state->value] = [
                'code' => $state->value,
                'label' => $state->getLabel(),
                'color' => $state->getColor(),
                'icon' => $state->getIcon()
            ];
        }
        return $states;
    }

    /**
     * Retourne le libellé en français de l'état
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::NW => 'Neuf',
            self::GOOD => 'Bon',
            self::AVERAGE => 'Moyen',
            self::DEFECTIVE => 'Défaillant',
            self::OUT_OF_SERVICE => 'Hors service'
        };
    }

    /**
     * Retourne l'icône FontAwesome correspondante
     */
    public function getIcon(): string
    {
        return match ($this) {
            self::NW => 'fa-star',
            self::GOOD => 'fa-check-circle',
            self::AVERAGE => 'fa-exclamation-triangle',
            self::DEFECTIVE => 'fa-times-circle',
            self::OUT_OF_SERVICE => 'fa-ban'
        };
    }

    /**
     * Crée un EquipmentState à partir de son libellé
     */
    public static function fromLabel(string $label): ?self
    {
        foreach (self::cases() as $state) {
            if ($state->getLabel() === $label) {
                return $state;
            }
        }
        return null;
    }

    /**
     * Retourne la classe CSS pour le badge Bootstrap
     */
    public function getBadgeClass(): string
    {
        return 'badge bg-' . $this->getColor();
    }

    /**
     * Retourne la classe CSS Bootstrap pour la couleur
     */
    public function getColor(): string
    {
        return match ($this) {
            self::NW => 'success',
            self::GOOD => 'primary',
            self::AVERAGE => 'warning',
            self::DEFECTIVE => 'danger',
            self::OUT_OF_SERVICE => 'secondary'
        };
    }

    /**
     * Vérifie si l'état nécessite une attention particulière
     */
    public function needsAttention(): bool
    {
        return in_array($this, [self::DEFECTIVE, self::OUT_OF_SERVICE]);
    }

    /**
     * Vérifie si l'équipement est utilisable
     */
    public function isUsable(): bool
    {
        return !in_array($this, [self::DEFECTIVE, self::OUT_OF_SERVICE]);
    }

    /**
     * Retourne la priorité pour le tri (1 = haute priorité)
     */
    public function getPriority(): int
    {
        return match ($this) {
            self::DEFECTIVE => 1,
            self::OUT_OF_SERVICE => 2,
            self::AVERAGE => 3,
            self::GOOD => 4,
            self::NW => 5
        };
    }

    /**
     * Retourne une description détaillée de l'état
     */
    public function getDescription(): string
    {
        return match ($this) {
            self::NW => 'Équipement neuf, jamais utilisé',
            self::GOOD => 'Équipement en bon état de fonctionnement',
            self::AVERAGE => 'Équipement fonctionnel mais avec usure visible',
            self::DEFECTIVE => 'Équipement défaillant nécessitant une réparation',
            self::OUT_OF_SERVICE => 'Équipement hors service, inutilisable'
        };
    }
}
