# 🎬 FrameX - Movie & TV Show Management Platform

[![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)

## 📖 Overview

FrameX is a comprehensive web application developed as part of the **Web Programming** course at **Mataram University**. This project demonstrates proficiency in modern web development technologies and best practices, showcasing the skills acquired during Semester 4 of the Informatics Engineering program.

The application serves as a personal media archive for tracking movies and TV shows across multiple streaming services, addressing the modern challenge of managing viewing history and preferences in the era of proliferating streaming platforms.

## ✨ Features

### 🎯 Core Features
- **Movie Management**: Browse, search, and save movies with detailed information
- **TV Show Tracking**: Manage TV series and track viewing progress
- **User Profiles**: Personalized experience with user accounts and watchlists
- **Advanced Search**: Search across movies and TV shows with filters
- **Responsive Design**: Optimized for desktop, tablet, and mobile devices

### 🔍 Search & Discovery
- **Multi-platform Search**: Search across movies and TV shows simultaneously
- **Advanced Filtering**: Filter by content type (movies, TV shows, or both)
- **Pagination**: Navigate through large result sets efficiently
- **Keyboard Shortcuts**: Quick search access with Ctrl/Cmd + K

### 👥 User Management
- **User Registration & Authentication**: Secure user account system
- **Personal Watchlists**: Save and organize favorite content
- **Profile Management**: Update personal information and preferences
- **Activity Logging**: Track user interactions and system events

### 🛠️ Admin Panel
- **Content Management**: Add, edit, and delete movies and TV shows
- **User Management**: Manage user accounts and roles
- **Activity Monitoring**: View system logs and user activities
- **Database Administration**: Comprehensive data management tools

## 🏗️ Project Structure

```
FrameX/
├── Admin/                 # Admin panel and management tools
│   ├── database/
│   │   ├── framex.sql   # Database schema
│   ├── index.php         # Admin dashboard
│   ├── movie.php         # Movie management
│   ├── tv.php           # TV show management
│   ├── user.php         # User management
│   ├── log.php          # Activity logs
│   └── ...              # CRUD operations for content
├── API/                  # API endpoints for TMDB integration
│   ├── api_search.php    # Search functionality
│   ├── api_movie_id.php  # Movie details
│   ├── api_tv_id.php     # TV show details
│   └── ...              # Various API endpoints
├── Assets/               # Static assets
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   └── images/          # Images and media
├── Auth/                 # Authentication system
│   ├── login.php        # User login
│   ├── register.php     # User registration
│   └── logout.php       # User logout
├── Conf/                 # Configuration files
│   ├── database.php     # Database connection
│   └── info.php         # Application settings
├── Public/               # Public-facing pages
│   ├── index.php        # Homepage
│   ├── about.php        # About page
│   ├── details.php      # Content details
│   ├── search.php       # Search results
│   ├── profile.php      # User profile
│   └── ...              # Other public pages
└── README.md            # Project documentation
```

## 🚀 Installation

### Prerequisites
- **XAMPP** (Apache + MySQL + PHP) or similar local server environment
- **PHP 8.0+** with PDO extension
- **MySQL 8.0+** or MariaDB 10.0+
- **Web browser** with JavaScript enabled

### Setup Instructions

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/framex.git
   cd framex
   ```

2. **Configure XAMPP**
   - Start Apache and MySQL services in XAMPP Control Panel
   - Ensure services are running on default ports (Apache: 80, MySQL: 3306)

3. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `framex`
   - Import the `framex.sql` file to set up the database schema

4. **Configuration**
   - Edit `Conf/database.php` if you need to change database credentials:
   ```php
   $host = 'localhost';
   $dbname = 'framex';
   $username = 'root'; 
   $password = '';    
   ```

5. **Access the Application**
   - Open your web browser
   - Navigate to `http://localhost/framex/Public/`
   - The application should now be running

## 🛠️ Technologies Used

### Frontend
- **HTML5**: Semantic markup and structure
- **CSS3**: Styling and responsive design
- **JavaScript**: Interactive functionality and API calls
- **Tailwind CSS**: Utility-first CSS framework for rapid UI development

### Backend
- **PHP**: Server-side scripting and application logic
- **MySQL**: Relational database management
- **PDO**: Database abstraction layer for secure database operations

### APIs & Services
- **TMDB API**: The Movie Database API for movie and TV show data
- **XAMPP**: Local development environment

### Development Tools
- **Git**: Version control
- **phpMyAdmin**: Database management interface

## 📱 Features in Detail

### Search Functionality
- **Multi-type Search**: Search movies, TV shows, or both simultaneously
- **Real-time Results**: Instant search results with pagination
- **Advanced Filters**: Filter by content type and other criteria
- **Keyboard Navigation**: Quick access with keyboard shortcuts

### User Experience
- **Responsive Design**: Optimized for all device sizes
- **Modern UI**: Clean, intuitive interface with smooth animations
- **Accessibility**: Keyboard navigation and screen reader support
- **Performance**: Optimized loading times and efficient data handling

### Admin Features
- **Content Management**: Full CRUD operations for movies and TV shows
- **User Administration**: Manage user accounts, roles, and permissions
- **System Monitoring**: Activity logs and system health monitoring
- **Data Import/Export**: Bulk data management capabilities

## 🔧 API Endpoints

The application integrates with TMDB API through various endpoints:

- `api_search.php` - Search movies and TV shows
- `api_movie_id.php` - Get detailed movie information
- `api_tv_id.php` - Get detailed TV show information
- `api_popular.php` - Get popular content
- `api_trending.php` - Get trending content
- `api_upcoming.php` - Get upcoming movies
- And many more...

## 👥 Development Team

This project was developed by students of Mataram University's Informatics Engineering program:

- **Ahmad Ramadhani R** - Project Lead & Backend Developer
- **Sucitasari Rahmadani** - Frontend Developer  
- **Rengganis Cahya Andini** - UI/UX Designer

## 📚 Learning Objectives

### Technical Skills Demonstrated
- Full-stack web development with PHP and MySQL
- Responsive design implementation
- API integration and data management
- User authentication and session management
- Database design and optimization

### Soft Skills Developed
- Team collaboration and project management
- Problem-solving and critical thinking
- User experience design principles
- Documentation and code organization
- Version control with Git

## 🔒 Security Features

- **Input Validation**: Comprehensive input sanitization and validation
- **SQL Injection Protection**: Prepared statements and parameterized queries
- **XSS Prevention**: Proper output escaping and content security
- **Session Management**: Secure session handling and authentication
- **Error Handling**: Graceful error handling without information disclosure

## 🌐 Browser Compatibility

- **Chrome**: 90+
- **Firefox**: 88+
- **Safari**: 14+
- **Edge**: 90+
- **Mobile browsers**: iOS Safari, Chrome Mobile

## 📄 License

This project is developed as part of academic coursework at Mataram University. All rights reserved.

## 🤝 Contributing

This is an academic project developed for educational purposes. However, suggestions and feedback are welcome through issues and discussions.

## 📞 Support

For support or questions regarding this project:
- Create an issue in the GitHub repository
- Contact the development team through the university
- Refer to the project documentation and manual

## 🔄 Version History

- **v1.0.0** - Initial release with core features
- Complete movie and TV show management
- User authentication and profiles
- Admin panel with full CRUD operations
- Search functionality with TMDB integration

---

**Developed with ❤️ by Informatics Engineering Students at Mataram University** 
