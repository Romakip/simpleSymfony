<?php


namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("admin/", name="admin")
     */
    public function index()
    {

        return $this->render('admin/login.html.twig');
    }

    public function login()
    {
        $a = 5;

    }

    public function logout()
    {
        $a = 5;
    }
}