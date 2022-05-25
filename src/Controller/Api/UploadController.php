<?php

namespace App\Controller\Api;

use App\Entity\MediaPicture;
use App\Service\ErrorFormatterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadController extends AbstractController
{
    private $repository;
    private $details;
    private $errorService;
    private $violations = [];

    public function __construct(EntityManagerInterface $repository, Security $security, ErrorFormatterService $errorService)
    {
        $this->repository = $repository;
        $this->security = $security;
        $this->errorService = $errorService;
    }

    public function __invoke(Request $request)
    {
        $uploadedFile = $request->files->get('file');
        $product = $request->request->get('product');

        if (!$uploadedFile) {
            dd($uploadedFile, $product);
            $message = 'No file uploaded';
            $property_path = 'file';
            $this->details.= $this->errorService->addDetailError($this->details, $property_path, $message);
            $this->violations[] = $this->errorService->addViolationError($property_path, $message);
            if ($this->violations) {
                return $this->json($this->errorService->ErrorPersist($this->violations, $this->details), 422);
            }
        }
        dd($uploadedFile, $product);
        //$data->setName($uploadedFile->getClientOriginalName());

        //return $data;
    }
}
