# R.I.S.E. Academy LMS Front-end Development Plan

Welcome to the next phase of the Rise Academy project. This guide aims to provide clarity on our progress and guide you through the next steps. For documentation and files from the previous phases, please request them from the client Rebecca Chambers as we are unable to download the entire google drive folder at once.

## **1. Accomplishments this Semester**

### **Student Views**

- **Prototypes to Design**: Converted previous phase prototypes into functional HTML and CSS.
- **Integration with TWIG**: Transferred the HTML and CSS designs into TWIG Templates.
- **Backend Connection**: Rendered the TWIG templates using a PHP client to connect to the backend service.
- **Mobile Optimization**: Ensured all designs are responsive on various devices.

**Login**:

- Incorporated student-specific functionality.

**My Workspace**:

- **Index Page**: Concentrated on Course Selection.
- **Workbook**: Integrated a ToDo List feature.

## **2. Recommendations Moving Forward**

Dive in by continuing with the **student role**. Because of the depth associated with each role, it's advisable to tackle them sequentially. Pending segments for the student role are:

- My Assignments
- My Facilitators

Due to time limitations this semester we had to work quickly to have a product for the end of the semester. We did our best given these constraints however in my opinion as project manager everything could use a bit of organization before getting too invested in developing more features. I think developing some standards and naming conventions would really help with further work on the project.

HTML/CSS classes and ids could definitely use some better standardization. These items could also use better segregation (smaller page specific css files rather than including all the styles for every page in a large css file). The styles especially need some work. It would be best to use as much of the bootstrap functionality as possible then overriding certain parts of that style to match the theme. https://getbootstrap.com/docs/5.0/customize/overview/.

Fonts should also be checked to ensure that we can use them freely on the web site. One of the previous phases selected this font. Also using a web font for the icons would be better as it allows colors to be changed. Both of these were out of scope for us considering how much we had to do in such a short time.

## **3. Future Work**

**Roles to be Developed**:

- **Facilitator (Teachers)**
- **Administrator**: Enrolling students and teachers, and other admin functionalities.

### Advanced Systems Pending:

- Native messaging system.
- Native video conferencing.
- Comprehensive native calendar.
- OAuth integration (Synchronize Rise Academy email/login with the LMS login).
- In-app word processing (Note: Client hinted at possibly embedding Google Docs).
- User profile section
  - Uploading images, etc...


Due to the scale of these advanced systems, we recommend addressing them in the final stages of the project.

## **4. Building on Our Framework**

We utilized the Slim PHP framework (Slim 4 Skeleton). Here's a detailed breakdown:

- **Templates**: Situated in the "templates" directory. Each template inherits from `common.twig` files, providing common styles/components for the views.
  - Ex:
    - common.twig = common styles/components for all pages
    - login/common.twig = common styles/components for all login pages (inherits from common.twig)
- **Logic**: Various functionalities, such as:
  - **Middleware for Permission Checks**: `src/Application/Middleware/AuthMiddleware.php` and `src/Application/Middleware/RoleMiddleware.php`.
  - **Session Initiation**: Managed through `src/Application/Middleware/SessionMiddleware.php`.
  - **Login Action**: View `src/Application/Actions/LoginUserAction.php` for specifics.
  - **Template Rendering with Backend Content**: Refer to `src/Application/Student/LoadMyWorkspaceWithCoursesAction.php` and `src/Application/Student/Course/LoadWorkbookWithItemsAction.php` for more details.
- **Routes**: Defined in `app/routes.php`. Route groupings and naming conventions ensure easy mapping.
- **Web Service**: Change the web service URL in `app/settings.php`. For the retrieval mechanism of this URL, see `LoadMyWorkspaceWithItemsAction.php` where you can access it via `$webServicePath = $this->settings->get('webServicePath');`.
- **Static Assets**: Located under the "public" directory:
  - CSS: `public/css`
    - See the style/implementation guide (figma or PDF) for information on how to implement certain components
  - JS: `public/js`
  - Images: `public/img`
  - Fonts: `public/fonts`
  - Documents: `public/docs`
- **Dependencies**: Managed through Composer. After installation, vendor files reside in the `vendor` directory.

### **Launching the Application**:

1. Install [Composer](https://getcomposer.org/).
2. Navigate to the project folder.
3. Execute `composer install` for dependencies.
4. Use `composer start` to initiate the server.
5. Access `http://localhost:8080`.

The login I have been using was provided by the backend team. It should work if the backend team does not change anything:

​		**Username**: navi1

​		**Password**: password1

**Note**: If using a different server, ensure the document root is set to "public" and .htaccess and rewrite rules are properly configured.

## **5. Support**

For additional assistance or queries, reach out to tyler.mchugh@gmail.com

## **6. Closing Thoughts**

With the foundation established, we look forward to witnessing the innovations you'll introduce. Wishing you success on this journey with Rise Academy's learning platform.