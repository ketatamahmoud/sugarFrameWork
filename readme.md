# Project Title

This project aims to facilitate the development of basic web applications by implementing an architecture based on the Model-View-Controller (MVC) pattern. It incorporates the TWIG template engine and provides command-line tools for generating Controllers, Views, and Entities automatically from an existing MySQL database.

## Project Description

The project follows a directory structure that organizes the various components as follows:

- The `app` directory contains the application-specific files:
    - `routes.php`: This file defines the routes for the application.
    - The `views` directory holds the view templates, including an `admin` subdirectory for admin-specific views.

- The `composer.json` and `composer.lock` files are related to Composer, a dependency management tool for PHP.

- The `core` directory includes the core components of the project:
    - `AbstractController.php` and `AbstractModel.php` provide abstract base classes for Controllers and Models.
    - `App.php` is responsible for bootstrapping the application.
    - `bootstrap.php` sets up any necessary configurations or initializations.
    - `Database.php` handles database connections and operations.
    - `Request.php` represents an HTTP request.
    - `Router.php` handles routing logic.
    - The `sugarCommandLine` directory contains command-line tools for scaffolding components:
        - Various `.txt` files are templates for generating code.
        - PHP files such as `ControllerMaker.php`, `ModelMaker.php`, `RouterMaker.php`, and `ViewMaker.php` implement the command-line functionality.

- The `public` directory serves as the public document root of the web application. It typically contains the entry point for the application, such as `index.php`.


## Usage

To get started with the project, follow these steps:

1. Install the project dependencies by running `composer install`.
2. Configure the database connection settings in `core/Database.php`. or use the command-line tool to generate the connection settings, by running `php core/sugarCommandLine/DatabaseConfigMaker.php`.
3. Define the routes for your application in `app/routes.php` or use the command-line tool to generate the routes, by running `php core/sugarCommandLine/RouterMaker.php`.
4. Utilize the provided command-line tools in `core/sugarCommandLine` to create Controllers, Models, Routers, and Views as needed.
5. Run the application using a web server, with the document root pointing to the `public` directory. by running `php -S localhost:8080 -t public`.
6. Navigate to the application in your browser.
7. Enjoy!

## Command-Line Tool

![Command-Line Tool](img/Screenshot%20from%202023-06-29%2023-53-17.png)

Here is a screenshot of the command-line tool in action. The tool provides a user-friendly interface for creating Controllers, Models, Routers, and Views. It automates the process of generating code templates, saving developers valuable time and effort.


## Contributing

Contributions to the project are welcome! If you encounter any issues or have suggestions for improvements, please open an issue or submit a pull request.

