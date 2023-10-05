<?php

declare(strict_types=1);

namespace App\Application\Actions\Student\Course;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig as Twig;
use App\Application\Settings\SettingsInterface;

class LoadWorkbookWithItemsAction extends Action
{

    private $twig;
    private $settings;

    public function __construct(Twig $twig, SettingsInterface $settings)
    {
        $this->twig = $twig;
        $this->settings = $settings;
    }

    protected function action(): Response
    {
        // Get the web service path from settings
        $webServicePath = $this->settings->get('webServicePath');

        // Get the course details from the web service
        $curlHandle = curl_init($webServicePath . "/courses/" . $this->resolveArg('courseCode'));
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlHandle);
        $responseCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

        if ($responseCode != 200) {
            die("Error: Unable to load course details for: " . $course->courseCode . " from the web service.");
        }

        $courseDetails = json_decode($response);

        if ($courseDetails == null) {
            die("Error: Unable to decode course details for: " . $course->courseCode . " from the web service.");
        }

        // Get the workbook for the course from the web service
        $curlHandle = curl_init($webServicePath . "/GetWorkbook/" . $_SESSION['user_id'] . "/" . $this->resolveArg('courseCode'));
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlHandle);
        $responseCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

        if ($responseCode != 200) {
            die("Error: Unable to load course registration data from the web service.");
        }
        
        $workbook = json_decode($response);
        
        // Render the enrolled courses template with the list of courses
        return $this->twig->render($this->response, 'student/my_workspace/course/workbook.twig', [
            'student' => [
                'firstName' => $_SESSION['user_firstName'],
                'lastName' => $_SESSION['user_lastName'],
            ],'workbook' => $workbook,
            'course' => $courseDetails,
        ]);
    }
}
