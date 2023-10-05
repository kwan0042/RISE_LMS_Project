<?php

declare(strict_types=1);

namespace App\Application\Actions\Student;

use App\Application\Actions\Action;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig as Twig;
use App\Application\Settings\SettingsInterface;

class LoadMyWorkspaceWithCoursesAction extends Action
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

        // Get the list of courses enrolled by the student from the web service using curl
        $curlHandle = curl_init($webServicePath . "/getCoursesEnrolledByStudentId/" . $_SESSION['user_id']);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curlHandle);
        $responseCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
        curl_close($curlHandle);

        if ($responseCode != 200) {
            die("Error: Unable to load enrolled courses.");
        }

        $enrolledCourses = [];

        // Loop through the list of courses and get the course details from the web service
        foreach (json_decode($response) as $course) {
            // Get course details from the web service using curl
            $curlHandle = curl_init($webServicePath . "/courses/" . $course->courseCode);
            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curlHandle);
            $responseCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            curl_close($curlHandle);

            if ($responseCode != 200) {
                die("Error: Unable to load course details for: " . $course->courseCode . " from the web service.");
            }

            $courseDetails = json_decode($response);

            $enrolledCourses[] = $courseDetails;

        }

        // Render the my_workspace index template with the list of courses
        return $this->twig->render($this->response, 'student/my_workspace/index.twig', [
            'student' => [
                'firstName' => $_SESSION['user_firstName'],
                'lastName' => $_SESSION['user_lastName'],
            ],
            'courses' => $enrolledCourses,
            
        ]);
    }
}
