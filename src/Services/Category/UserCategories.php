<?php
declare(strict_types=1);

namespace App\Services\Category;

use App\Repository\CategoryRepository;
use Symfony\Component\Security\Core\Security;

class UserCategories
{
    private $security;

    private $categoryRepository;

    public function __construct(Security $security, CategoryRepository $categoryRepository)
    {
        $this->security = $security;
        $this->categoryRepository = $categoryRepository;
    }

    public function getCategories()
    {
        $userId = $this->security->getUser()
                                 ->getId()
        ;

        return $this->categoryRepository->findByUserId($userId);
    }
}