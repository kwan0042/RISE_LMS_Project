<?php

declare(strict_types=1);

use App\Application\Actions\User\LoginUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Middleware\IsLoggedInMiddleware;
use App\Application\Middleware\IsNotLoggedInMiddleware;
use App\Application\Middleware\RoleMiddleware;
use App\Application\Middleware\StudentHasCourseMiddleware;
use App\Application\Actions\Student\LoadMyWorkspaceWithCoursesAction;
use App\Application\Actions\Student\Course\LoadWorkbookWithItemsAction;
use Slim\Views\Twig;

return function (App $app) {

    // CORS Pre-Flight OPTIONS Request Handler
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        return $response;
    });

    // Login routes
    $app->group('/login', function (Group $group) {

        // Login index (role selection)
        $group->get('', function (Request $request, Response $response) {
            if (isset($_SESSION['user_id'])) {
                return $response->withHeader('Location', '/')->withStatus(302);
            }
            // Add a html files contents to the response body
            return $this->get(Twig::class)->render($response, 'login/index.twig', []);
        })->setName('login');

        // Student login
        $group->get('/student', function (Request $request, Response $response) {
            if (isset($_SESSION['user_id'])) {
                return $response->withHeader('Location', '/')->withStatus(302);
            }
            // Add a html files contents to the response body
            return $this->get(Twig::class)->render($response, 'login/student.twig', []);
        })->setName('login.student');

        // Student login submission
        // Maybe students need their own login action?
        $group->post('/student', LoginUserAction::class);

        // Facilitator login
        $group->get('/facilitator', function (Request $request, Response $response) {
            return $response->withHeader('Location', '/')->withStatus(302);
        })->setName('login.facilitator');

        // Administrator login
        $group->get('/admin', function (Request $request, Response $response) {
            return $response->withHeader('Location', '/')->withStatus(302);
        })->setName('login.administrator');
        
        // Forgot password
        // Not implemented yet
        $group->get('/forgot_password', function (Request $request, Response $response) {
            if (isset($_SESSION['user_id'])) {
                return $response->withHeader('Location', '/')->withStatus(302);
            }
            // Add a html files contents to the response body
            return $this->get(Twig::class)->render($response, 'login/forgotPassword.twig', []);
        })->setName('login.forgot_password');
    })->add(IsNotLoggedInMiddleware::class);

    // Logout route
    // This route is not protected by the IsLoggedInMiddleware because
    // the user already has to be logged in for it to have any effect
    // Get and post requests are allowed
    $app->map(['GET', 'POST'], '/logout', function (Request $request, Response $response) {
        session_destroy();
        return $response->withHeader('Location', '/login')->withStatus(302);
    });

    // Home route
    $app->group('/', function (Group $group) {

        // Home index
        // Redirect to the appropriate home view depending on the user's role
        // For now, if the user is not a student, just write some text to the response body
        $group->get('', function (Request $request, Response $response) {
            switch ($_SESSION['user_role']) {
                case 'student':
                    return $response->withHeader('Location', '/my_workspace')->withStatus(302);
                    break;
                case 'facilitator':
                    $view = 'teacher';
                    break;
                case 'admin':
                    $view = 'admin';
                    break;
                default:
                    $view = '';
                    break;
            }

            $response->getBody()->write($view.' home view');
            return $response;
        });

        // My Workspace
        $group->group('my_workspace', function (Group $group) {
            
            // My Workspace index (course selection page)
            // Use the LoadMyWorkspaceWithCoursesAction action to load the courses and render the view
            $group->get('', LoadMyWorkspaceWithCoursesAction::class)->setName('my_workspace');
            
            // My Workspace course
            $group->group('/{courseCode}', function (Group $group) {

                // My Workspace course index
                // Redirect to the workbook view
                $group->get('', function (Request $request, Response $response, array $args) {
                    return $response->withHeader('Location', '/my_workspace/' . $args['courseCode'] . '/workbook')->withStatus(302);
                })->setName('my_workspace.course');
                
                // My Workspace course workbook
                // Use the LoadWorkbookWithItemsAction action to load the workbook and render the view
                $group->get('/workbook', LoadWorkbookWithItemsAction::class)->setName('my_workspace.course.workbook');
                
                // My Workspace course outline
                // Redirect to the course outline pdf for now
                    // Backend made the decision to not implement the course outline view and use a pdf instead
                    // but I think it would be too labour intensive for administration to have to upload a pdf
                    // so maybe it can be reimpelemented later
                $group->get('/outline', function (Request $request, Response $response) {
                    return $response->withHeader('Location', '/docs/courses/' . $args['courseCode'] . '_outline.pdf')->withStatus(302);
                })->setName('my_workspace.course.outline');
    
            // Adding this middleware ensures that the user is a student
            // This group will also need another middleware to ensure that the student is enrolled in the course
            // but that middleware is not implemented yet
            })->add(new RoleMiddleware(['student']));
        
        // Both of these roles have access to the my_workspace route
        // I can't remember is the admin role has a "my workspace" but I think not
        })->add(new RoleMiddleware(['student', 'facilitator']));
    
    // This middleware ensures that the user is logged in
    // and redirects to the login page if they are not
    })->add(new IsLoggedInMiddleware());
};
