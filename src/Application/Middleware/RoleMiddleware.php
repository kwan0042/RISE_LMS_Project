<?php 

declare(strict_types=1);

namespace App\Application\Middleware;

use Slim\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class RoleMiddleware implements Middleware
{
    private $allowedRoles;

    public function __construct(array $allowedRoles)
    {
        $this->allowedRoles = $allowedRoles;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        // Check if the user's role is allowed on this route
        $userRole = $_SESSION['user_role'];

        // Check if the user's role is in the allowed roles array
        if (in_array($userRole, $this->allowedRoles)) {
            // If the user's role is allowed, proceed with the next middleware or the route handler
            return $handler->handle($request);
        } else {
            // If the user's role is not allowed, return an error response
            // If the user is not logged in, redirect to the home page
            $response = new Response();
            return $response->withHeader('Location', '/')->withStatus(302);
        }
    }
}

?>