<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use App\Application\Actions\Action;    
use App\Application\Settings\SettingsInterface;
use Slim\Views\Twig as Twig;

class LoginUserAction extends Action
{

    private $settings;
    private $twig;

    public function __construct(Twig $twig, SettingsInterface $settings)
    {
        $this->settings = $settings;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {

        // If the user is already logged in, redirect to the home page
        if (isset($_SESSION['user_id'])) {
            return $this->response->withHeader('Location', '/')->withStatus(302);
        }

        $body = $this->request->getParsedBody();
        $userName = $body['username'];
        $password = $body['password'];

        // Crude unencrypted password check until oauth is implemented

        $webServicePath = $this->settings->get('webServicePath');

        $curlHandle = curl_init($webServicePath . "/userDetails");

        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlHandle);
        $responseCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

        if ($responseCode != 200) {
            die("Error: Unable to load user detail data from the web service.");
        }
        
        $usersDetails = json_decode($response);
        // $usersDetails = [
        //     [
        //         'id' => 1,
        //         'userName' => 'guest',
        //         'password' => 'guest',
        //         'role' => 'student',
        //     ],
        // ];

        foreach ($usersDetails as $userDetails) {
            if ($userDetails->userName == $userName) {
                if ($userDetails->password == $password) {
                    $_SESSION['user_name'] = $userName;
                    $_SESSION['user_id'] = $userDetails->userId;
                    $_SESSION['user_role'] = $userDetails->role;
                    $_SESSION['user_firstName'] = ucfirst($userDetails->firstName);
                    $_SESSION['user_lastName'] = ucfirst($userDetails->lastName);
                    return $this->response->withHeader('Location', '/')->withStatus(302);
                } else {
                    return $this->twig->render($this->response, 'login/student.twig', [
                        'error' => 'Incorrect password',
                        'userName' => $userName,
                        'password' => $password,
                    ]);
                }
            }
        }

        return $this->twig->render($this->response, 'login/student.twig', [
            'error' => 'User not found',
            'userName' => $userName,
        ]);
    }
}
