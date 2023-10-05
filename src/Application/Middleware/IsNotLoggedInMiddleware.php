<?php 

declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class IsNotLoggedInMiddleware implements Middleware
{
    public function process(Request $request, RequestHandler $handler): Response
    {

        // Check if the user is logged in
        if (!$this->isLoggedIn()) {
            // If the user is not logged in, proceed with the next middleware or the route handler
            return $handler->handle($request);
        } else {
            // If the user is logged in, redirect to the root route
            $response = new Response();
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    }

    private function isLoggedIn(): bool
    {
        // Check if the user is logged in
        return isset($_SESSION['user_id']);
    }
}

?>