<?php

declare(strict_types=1);

namespace App\Tests\App\Home\Controller;

use App\Home\Controller\Home;
use App\Home\Model\HomeRepo;
use App\Home\Repository\BooksRepo;
use App\Home\Repository\AuthorsRepo;
use Sys\Template\TemplateInterface;
use Sys\Tests\StubRequest;
use Sys\Tests\StubContainer;
use Sys\Tests\StubTemplate;
use PHPUnit\Framework\TestCase;
use HttpSoft\Response\JsonResponse;

class HomeTest extends TestCase
{
    /**
     * Тест __invoke: успешный возврат данных
     * Использует StubRequest, StubTemplate, StubContainer
     */
    public function testInvokeReturnsViewWithData(): void
    {
        $books = [['id' => 1, 'title' => 'Book 1']];
        $count_branches = 3;
        $authors = [['id' => 1, 'name' => 'Author 1']];
        $count_authors = 5;
        $bestPost = (object)['body' => 'Best post content'];

        // 1. Мокаем репозиторий
        $repo = $this->createMock(HomeRepo::class);
        $repo->method('getBranches')
            ->with(4)
            ->willReturn([$books, $count_branches]);
        $repo->method('getAuthors')
            ->with(4)
            ->willReturn([$authors, $count_authors]);
        $repo->method('getBestPost')
            ->willReturn($bestPost);

        // 2. Создаём stub шаблона с результатом render()
        $template = StubTemplate::stub()
            ->withDefaultResult('<html>home view</html>')
            ->create();

        // 3. Создаём контейнер и регистрируем шаблон
        $container = StubContainer::empty()
            ->addInstance(TemplateInterface::class, $template);

        // 4. Регистрируем контейнер как глобальный (для функции view())
        $GLOBALS['_container'] = $container;

        // 5. Создаём контроллер и подменяем request через StubRequest
        $controller = new Home();

        $requestProp = new \ReflectionProperty($controller, 'request');
        $requestProp->setAccessible(true);
        $request = StubRequest::get()->create();
        $requestProp->setValue($controller, $request);

        // 6. Вызываем контроллер и проверяем результат
        $result = $controller($repo);

        $this->assertIsString($result);
    }

    /**
     * Тест __invoke: getBestPost возвращает null
     */
    public function testInvokeWithNullPost(): void
    {
        $repo = $this->createMock(HomeRepo::class);
        $repo->method('getBranches')
            ->with(4)
            ->willReturn([[], 0]);
        $repo->method('getAuthors')
            ->with(4)
            ->willReturn([[], 0]);
        $repo->method('getBestPost')
            ->willReturn(null);

        // $template = StubTemplate::stub()->createContainer();

        //  // 2. Создаём stub шаблона с результатом render()
        // $template = StubTemplate::stub()
        //     ->withDefaultResult('<html>home view</html>')
        //     ->create();

        // // 3. Создаём контейнер и регистрируем шаблон
        // $container = StubContainer::empty()
        //     ->addInstance(TemplateInterface::class, $template);

        // // 4. Регистрируем контейнер как глобальный (для функции view())
        // $GLOBALS['_container'] = $container;
        // // $this->mockTemplate('<html>home view</html>');

        $controller = new Home();

        $requestProp = new \ReflectionProperty($controller, 'request');
        $requestProp->setAccessible(true);
        $request = StubRequest::get()->create();
        $requestProp->setValue($controller, $request);

        $result = $controller($repo);

        $this->assertIsString($result);
    }

    /**
     * Тест works: AJAX-запрос возвращает JsonResponse
     */
    public function testWorksReturnsJsonResponseForAjax(): void
    {
        $request = StubRequest::get()
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->create();

        $booksRepo = $this->createMock(BooksRepo::class);
        $data = [
            'counter' => 10,
            'content' => '<div>Books content</div>',
        ];
        $booksRepo->method('get')
            ->with($request)
            ->willReturn(['home/books', $data]);

        $controller = new Home();

        $requestProp = new \ReflectionProperty($controller, 'request');
        $requestProp->setAccessible(true);
        $requestProp->setValue($controller, $request);

        $result = $controller->works($booksRepo);

        $this->assertInstanceOf(JsonResponse::class, $result);

        $body = json_decode((string) $result->getBody(), true);
        $this->assertTrue($body['success']);
        $this->assertEquals(10, $body['result']['counter']);
        $this->assertEquals('<div>Books content</div>', $body['result']['content']);
    }

    /**
     * Тест works: не-AJAX запрос возвращает строку (view)
     */
    public function testWorksReturnsViewForNonAjax(): void
    {
        $request = StubRequest::get()->create();
        $booksRepo = $this->createMock(BooksRepo::class);
        $data = [
            'counter' => 5,
            'content' => '<div>Books</div>',
        ];
        $booksRepo->method('get')
            ->with($request)
            ->willReturn(['home/books', $data]);

        $controller = new Home();

        $requestProp = new \ReflectionProperty($controller, 'request');
        $requestProp->setAccessible(true);
        $requestProp->setValue($controller, $request);

        $result = $controller->works($booksRepo);

        $this->assertIsString($result);
    }

    /**
     * Тест authors: AJAX-запрос с user_id
     */
    public function testAuthorsReturnsJsonResponseForAjax(): void
    {
        $request = StubRequest::get()
            ->withHeader('X-Requested-With', 'XMLHttpRequest')
            ->create();

        $authorsRepo = $this->createMock(AuthorsRepo::class);
        $data = [
            'counter' => 3,
            'content' => '<div>Authors content</div>',
        ];
        $authorsRepo->method('get')
            ->with($request, 42)
            ->willReturn(['home/authors', $data]);

        $controller = new Home();

        $requestProp = new \ReflectionProperty($controller, 'request');
        $requestProp->setAccessible(true);
        $requestProp->setValue($controller, $request);

        // Устанавливаем user
        $userProp = new \ReflectionProperty($controller, 'user');
        $userProp->setAccessible(true);
        $userProp->setValue($controller, (object)['id' => 42]);

        $result = $controller->authors($authorsRepo);

        $this->assertInstanceOf(JsonResponse::class, $result);

        $body = json_decode((string) $result->getBody(), true);
        $this->assertTrue($body['success']);
        $this->assertEquals(3, $body['result']['counter']);
        $this->assertEquals('<div>Authors content</div>', $body['result']['content']);
    }

    /**
     * Тест authors: не-AJAX запрос с null user
     */
    public function testAuthorsReturnsViewWithNullUser(): void
    {
        $request = StubRequest::get()->create();
        $authorsRepo = $this->createMock(AuthorsRepo::class);
        $data = [
            'counter' => 0,
            'content' => '<div>No authors</div>',
        ];
        $authorsRepo->method('get')
            ->with($request, null)
            ->willReturn(['home/authors', $data]);

        $controller = new Home();

        $requestProp = new \ReflectionProperty($controller, 'request');
        $requestProp->setAccessible(true);
        $requestProp->setValue($controller, $request);

        // user = null
        $userProp = new \ReflectionProperty($controller, 'user');
        $userProp->setAccessible(true);
        $userProp->setValue($controller, null);

        $result = $controller->authors($authorsRepo);

        $this->assertIsString($result);
    }

    /**
     * Тест private method ajax: проверяет структуру JSON-ответа
     */
    public function testAjaxMethodReturnsCorrectStructure(): void
    {
        $controller = new Home();
        $method = new \ReflectionMethod($controller, 'ajax');
        $method->setAccessible(true);

        $data = [
            'counter' => 42,
            'content' => '<p>Some content</p>',
        ];

        $result = $method->invoke($controller, $data);

        $this->assertInstanceOf(JsonResponse::class, $result);

        $body = json_decode((string) $result->getBody(), true);
        $this->assertTrue($body['success']);
        $this->assertEquals(42, $body['result']['counter']);
        $this->assertEquals('<p>Some content</p>', $body['result']['content']);
    }
}
