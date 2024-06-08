# Reading Recommendation System

## Table of Contents
1. [Introduction](#introduction)
2. [Technologies Used](#technologies-used)
3. [Installation](#installation)
   - [Prerequisites](#prerequisites)
   - [Steps to Install](#steps-to-install)
4. [Running the Project](#running-the-project)
5. [Running Tests](#running-tests)

## Introduction
This project is a reading recommendation system API built using Laravel 10. It includes functionality for creating, updating, deleting, and retrieving books, as well as managing reading intervals and fetching the top books based on unique pages read.

## Technologies Used
- **PHP**: 8.2
- **Laravel**: 10
- **MySQL**: 5.7
- **SQLite**: (for testing)
- **Docker**: for containerization
- **Docker Compose**: for managing multi-container Docker applications
- **Nginx**: as a web server

## Installation

### Prerequisites
- Docker and Docker Compose installed on your system.

### Steps to Install
1. **Clone the Repository:**
   git clone https://github.com/kamal-asran/reading_recommendation_system.git
   cd reading_recommendation_system 

2. **Set Up Environment Variables:**
    cp .env.example .env 

3. **Build and Start the Docker Containers:**
    docker-compose up --build -d 

4. **Install PHP Dependencies:**
    docker-compose exec app composer install

5. **Generate Application Key:**
    docker-compose exec app php artisan key:generate 

6. **Run Database Migrations and Seeders:**
    docker-compose exec app php artisan migrate --seed   

### Running the Project     
    Access the Application: The application should now be running at http://localhost:8000.

### Running Tests     
    Run the Tests: docker-compose exec app php artisan  test --filter BookServiceTest