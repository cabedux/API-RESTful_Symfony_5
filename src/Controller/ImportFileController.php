<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ImportFileController
 * @package App\Controller
 *
 * @Route(path="/import/")
 */
class ImportFileController extends AbstractController{

    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }
    /**
     * @Route("file", name="select_file", methods={"GET"})
     */
    public function select_file(): Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("importer", name="import_file", methods={"POST"})
     */
    public function import_file(Request $request): Response
    {
        $file = $request->files->get('file');
        $content_file = json_decode(file_get_contents($file),true);

        foreach ($content_file as $books){
            foreach ($books as $book){
                $this->bookRepository->saveBook($book);
            }
        }

        return $this->render('ok.html.twig');
    }
}