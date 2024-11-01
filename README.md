# Association Management System

Welcome to the Association Management System, a comprehensive tool for managing companies, services, and courses within an association. This application streamlines the administrative tasks and offers a clear overview of the services provided, empowering you to manage your association effectively.

## About the Project

This is an OctoberCMS plugin initially developed to manage learning courses and registrations for CNA (Confederazione Nazionale dell'Artigianato e della piccola e media impresa), a trade association representing and protecting the interests of artisan enterprises and Small and Medium Enterprises (SMEs) at agencies and institutions.

## Features

### Dashboard with Global Search
- **Global Search Functionality**: Quickly search for any entity within the system, including companies, courses, teachers, and people.
- **Overview of Key Metrics**: Gain instant insight into the status of courses, modules, and company information.

### Company Registrations
- **Company Profiles**: Register and manage detailed information about companies.
- **ATECO Codes**: Include ATECO codes (Italian classification codes) to categorize companies by their business activities.

### Type of Services Offered
- **Service Categories**: Manage and assign different types of services offered by the association to companies.
- **Service Details**: Add descriptions, pricing, and other relevant details about the services available.

### Courses
- **Course Management**: Create and manage courses for different types of services.
- **Course Details**: Include course descriptions, prerequisites, and general information.

### Teachers
- **Teacher Profiles**: Maintain detailed profiles of the teachers, including their expertise and course assignments.

### Modules
- **Course Modules**: Define modules for each course, specifying the content, date, and resources.
- **Learning Tokens**: Assign learning tokens to modules that students can earn by participating.
- **Attachments**: Add files or links to resources, documents, or presentations for each module.

### People Data Management
- **Company Members**: Manage the data of individuals belonging to registered companies.
- **Contact Information**: Store and update personal information, roles, and participation details.

### Calendar of Modules
- **Calendar View**: Visualize all scheduled modules in a comprehensive calendar view.
- **Module Scheduling**: Easily add, modify, or remove modules and their respective schedules.
- **Integration with Dashboard**: Display upcoming modules and events directly on the dashboard for easy access.

## Installation

1. **Clone the Repository**:
   ```sh
   cd /youroctoberinstall/Plugin
   mkdir martinimultimedia
   cd martinimultimedia
   git clone https://github.com/inalto/asso.git
   ```
2. **Navigate to the Project Directory**:
   ```sh
   cd /youroctoberinstall/
   php artisan october:migrate
   ```

## Usage

- **Dashboard**: Navigate through the intuitive interface to access all features.
- **Global Search**: Use the search bar at the top of the dashboard to quickly find relevant information.
- **Calendar**: View and manage all modules and schedules to keep up-to-date with the upcoming sessions.

## Contribution

We welcome contributions from the community! Please follow these steps to contribute:

1. Fork the repository.
2. Create a new branch (`git checkout -b feature/YourFeature`).
3. Commit your changes (`git commit -m 'Add YourFeature'`).
4. Push to the branch (`git push origin feature/YourFeature`).
5. Open a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.

## Contact

For any questions or issues, please contact us at [webmaster@inalto.org](mailto:webmaster@inalto.org).


