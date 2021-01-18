<?php
namespace App\Controller;
use App\Repository\BookRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class BookController
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    /**
     * @Route("book", name="add_book", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        
        $data = json_decode($request->getContent(), true);

        $isbn = $data['isbn'];
        $title = $data['title'];

        if (empty($isbn) || empty($title)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->bookRepository->saveBook($data);

        return new JsonResponse(['status' => '201'], Response::HTTP_CREATED);
    }

    /**
     * @Route("books", name="get_all_books", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $books = $this->bookRepository->findAll();
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'isbn' => $book->getIsbn(),
                'title' => $book->getTitle(),
                'subtitle' => $book->getSubtitle(),
                'published' => $book->getPublished(),
                'publisher' => $book->getPublisher(),
                'pages' => $book->getPages(),
                'description' => $book->getDescription(),
                'website' => $book->getWebsite(),
                'category' => $book->getCategory(),
                'author' => $book->getAuthor()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("book/{id}", name="get_by_isbn", methods={"GET"})
     */
    public function getByISBN($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['isbn' => $id]);

        $data = [
            'id' => $book->getId(),
            'isbn' => $book->getIsbn(),
            'title' => $book->getTitle(),
            'subtitle' => $book->getSubtitle(),
            'published' => $book->getPublished(),
            'publisher' => $book->getPublisher(),
            'pages' => $book->getPages(),
            'description' => $book->getDescription(),
            'website' => $book->getWebsite(),
            'category' => $book->getCategory(),
            'author' => $book->getAuthor()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("book/category/{name}", name="get_by_category", methods={"GET"})
     */
    public function getByCategory($name): JsonResponse
    {
        $books = $this->bookRepository->findBy(['category' => $name]);
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'isbn' => $book->getIsbn(),
                'title' => $book->getTitle(),
                'subtitle' => $book->getSubtitle(),
                'published' => $book->getPublished(),
                'publisher' => $book->getPublisher(),
                'pages' => $book->getPages(),
                'description' => $book->getDescription(),
                'website' => $book->getWebsite(),
                'category' => $book->getCategory(),
                'author' => $book->getAuthor()
            ];
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("book/{id}", name="delete_Book", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $book = $this->bookRepository->findOneBy(['id' => $id]);

        $this->bookRepository->removeBook($book);

        return new JsonResponse(['status' => 200], Response::HTTP_OK);
    }

     /**
     * @Route("book/published/{year}", name="get_by_category", methods={"GET"})
     */
    public function getByYear($year): JsonResponse
    {
        $books = $this->bookRepository->findAllLowerThanYear($year);
        $data = [];

        foreach ($books as $book) {
            $data[] = [
                'id' => $book->getId(),
                'isbn' => $book->getIsbn(),
                'title' => $book->getTitle(),
                'subtitle' => $book->getSubtitle(),
                'published' => $book->getPublished(),
                'publisher' => $book->getPublisher(),
                'pages' => $book->getPages(),
                'description' => $book->getDescription(),
                'website' => $book->getWebsite(),
                'category' => $book->getCategory(),
                'author' => $book->getAuthor()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}