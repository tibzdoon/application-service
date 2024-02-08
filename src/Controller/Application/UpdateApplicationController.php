<?php

namespace App\Controller\Application;


use ApiPlatform\Validator\Exception\ValidationException;
use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
class UpdateApplicationController extends AbstractController
{
    //TODO: use DTO instead Request
    public function __invoke(
        Request $request,
        Application $application,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager
    ): Application {
        //TODO: move to DTO
        $constraint = new Collection([
            'status' => new Choice(choices: Application::STATUSES),
            'comment' => new NotBlank()
        ]);

        $data = $request->toArray();

        $errors = $validator->validate($data, $constraint);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $application->setStatus($data['status']);
        $application->setComment($data['comment']);

        $entityManager->flush();

        return $application;
    }
}