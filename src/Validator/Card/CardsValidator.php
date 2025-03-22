<?php

namespace App\Validator\Card;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CardsValidator
{
    #[Assert\NotBlank()]
    #[Assert\Choice(choices: ["Carreaux", "Pique", "Trèfle", "Cœur"])]
    public ?string $color = null;

    #[Assert\NotBlank()]
    #[Assert\Choice(choices: ["AS", "2", "3", "4", "5", "6", "7", "8", "9", "10", "Valet", "Dame", "Roi"])]
    public ?string $value = null;

    public function __construct(array $params)
    {
        foreach ($params['cards'] ?? [] as $cardParams) {
            $this->color = $cardParams['color'] ?? null;
            $this->value = $cardParams['value'] ?? null;
        }
    }

    public static function validateCards(array|null $cards, ValidatorInterface $validator): ConstraintViolationList
    {
        $errors = new ConstraintViolationList();
        if (is_null($cards)) {
            $errors->add(new ConstraintViolation(
                message: 'Cards parameter is required.',
                messageTemplate: 'Cards parameter is required.',
                parameters: [],
                root: null,
                propertyPath: 'cards',
                invalidValue: null
            ));
            return $errors;
        }
        $errors = new ConstraintViolationList();

        foreach ($cards['cards'] as $cardParams) {
            $cardValidator = new self(['cards' => [$cardParams]]);
            $cardErrors = $validator->validate($cardValidator);

            if (count($cardErrors) > 0) {
                $errors->addAll($cardErrors);
            }
        }

        return $errors;
    }
}
