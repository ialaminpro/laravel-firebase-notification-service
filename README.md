# Laravel Firebase Notification Service (v1)

This project is a **Laravel-based microservice** designed to handle **Firebase Cloud Messaging (FCM)** for sending notifications. It supports both **single notifications** and **multicast notifications** in a scalable, event-driven architecture (EDA), adhering to modern development best practices including **DRY**, **SRP**, **KISS**, **Separation of Concerns**, and **SOLID principles**.

## Table of Contents

1. [Features](#features)
2. [Technologies Used](#technologies-used)
3. [Project Architecture](#project-architecture)
4. [Installation](#installation)
5. [Configuration](#configuration)
6. [Usage](#usage)
7. [How It Works](#how-it-works)
8. [Advanced Usage](#advanced-usage)
9. [Key Highlights](#key-highlights)
10. [Running Tests](#running-tests)
8. [Docker Support](#docker-support)
9. [Contributing](#contributing)


## Features

- **Versioned API** (`v1`) for clean API management.
- **Single Notification**: Send notifications to a single device using its FCM token.
- **Multicast Notification**: Send notifications to multiple devices simultaneously using their FCM tokens.
- **Event-Driven Architecture (EDA)**: Utilizes Laravel's event system for handling notifications, providing flexibility and scalability.
- **Detailed Logging**: Logs errors and successes for better tracking and debugging.
- **Queue Support**: Notifications are queued for asynchronous processing.
- **Separation of Concerns**: Firebase logic is separated into services for maintainability and scalability.
- **Customizable Notifications**: Supports additional data (like sound, icon, etc.) along with title and body.
- **Multilingual, Scalable**: Easily adaptable to other microservices in a distributed system.

## Technologies Used

- Laravel 11
- Firebase Cloud Messaging (FCM)
- Docker
- PHP
- MySQL
- Composer
- PHPUnit for testing
- Redis/Queue Drivers

## Project Architecture

This project follows a clean, **service-oriented architecture** that promotes scalability and maintainability:

1. **Event-Driven**: Notifications are triggered by events (`NotificationEvent`), ensuring loose coupling.
2. **Separation of Concerns**:
    - Controllers handle HTTP requests.
    - Services manage Firebase communication.
    - Events and listeners handle notification logic.
3. **API Versioning**: Current version is `v1`, ensuring future API upgrades don't break compatibility.

### Directory Structure:
- `app/Http/Controllers/API/V1/NotificationController.php`: Handles incoming requests.
- `app/Services/FirebaseService.php`: Encapsulates Firebase notification logic.
- `app/Events/NotificationEvent.php`: Event for triggering notifications.
- `app/Listeners/SendFirebaseNotification.php`: Listens to events and sends notifications via Firebase.

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/ialaminpro/laravel-firebase-notification-service.git
cd laravel-firebase-notification-service
```

### 2. Install Dependencies

```bash
composer install
```

### 3. Configure Environment

- Copy the `.env.example` file to `.env` and fill in your Firebase credentials:

```bash
cp .env.example .env
```

- Add your Firebase credentials in the `.env` file:

```env
FIREBASE_CREDENTIALS=/path/to/your/firebase_credentials.json
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Set Up Firebase Configuration

Publish the Firebase configuration file:

```bash
php artisan vendor:publish --provider="Kreait\Laravel\Firebase\ServiceProvider"
```

### 6. Run Migrations (if required for further persistence)

```bash
php artisan migrate
```

### 7. Run the Application

```bash
php artisan serve
```

### 8. (Optional) Set Up Queues

If you want to process notifications asynchronously, set up queues:

- In `.env`, set your queue driver:

```env
QUEUE_CONNECTION=redis  # or any other driver
```

- Start the queue worker:

```bash
php artisan queue:work
```

## Configuration
- Configure your Firebase project in `config/firebase.php` with the appropriate credentials.
- Ensure the `firebase.projects` configuration is correctly set for the different apps you want to handle notifications for.

## Usage
### Sending a Single Notification

To send a notification to a single user, make a POST request to the following endpoint:

```
POST /api/v1/notifications/send
```

**Request Body Example**:
```json
{
  "app": "app",
  "token": "DEVICE_FCM_TOKEN",
  "title": "Notification Title",
  "body": "Notification Body",
  "extraData": {
    "key1": "value1",
    "key2": "value2"
  }
}
```

### Sending Multicast Notifications

To send a notification to multiple users, make a POST request to:

```
POST /api/v1/notifications/multicast
```

**Request Body Example**:
```json
{
  "app": "app",
  "tokens": ["DEVICE_FCM_TOKEN_1", "DEVICE_FCM_TOKEN_2"],
  "title": "Multicast Notification Title",
  "body": "Multicast Notification Body",
  "extraData": {
    "key1": "value1",
    "key2": "value2"
  }
}
```

## How It Works

1. **Single Notification**: The user sends a request to `/send-notification` with a single device token. The API triggers a `NotificationEvent`, and a `SendFirebaseNotification` listener processes the event, sending the notification to the specified token using Firebase Cloud Messaging.

2. **Multicast Notification**: The user sends a request to `/send-multicast` with multiple device tokens. The API triggers the same `NotificationEvent`, but the listener detects multiple tokens and uses Firebase's multicast capabilities to send the notification to all devices.

## Advanced Usage

- **Queueing Notifications**: Add `ShouldQueue` to the `SendFirebaseNotification` listener for asynchronous notification sending. This offloads the work to a background worker, ensuring better performance and scalability.
- **Event Broadcasting**: You can extend the architecture to broadcast events to other microservices using a message broker (like RabbitMQ or Kafka) for a fully distributed system.

## Running Tests

To run the test suite, execute the following command:

```bash
php artisan test
```

You can also generate coverage reports by running:

```bash
php -d zend_extension=xdebug.so -d xdebug.mode=coverage ./vendor/bin/phpunit --coverage-html reports
```

## Docker Support

This project includes a `Makefile` for easy Docker management. Below are some useful commands:

- **Build and start the containers**:
  ```bash
  make fresh
  ```

- **Stop all containers**:
  ```bash
  make stop
  ```

- **SSH into the PHP container**:
  ```bash
  make ssh
  ```

## Key Highlights

1. **Event-Driven Architecture**: The notification logic is decoupled from the main application logic, promoting scalability and allowing easy integration into any microservices architecture.
2. **SOLID Principles**: Each component adheres to SOLID principles, ensuring high maintainability and readability:
    - **Single Responsibility Principle (SRP)**: Each class handles a specific concern (e.g., `FirebaseService` only deals with Firebase logic).
    - **Open/Closed Principle (OCP)**: The code is open for extension but closed for modification, making it easy to extend functionality without changing core logic.
3. **Multicast Capability**: Supports sending notifications to multiple devices in a single request, making it ideal for push notifications in real-time applications.
4. **Asynchronous Processing**: Ready to scale with support for asynchronous job processing via Laravel queues.

## Contributing

If you'd like to contribute, please fork the repository and make changes via pull requests. All contributions are welcome, whether it's improving documentation, fixing bugs, or adding new features.


**Note**: Ensure that your Firebase project is properly set up with the necessary credentials, and the device tokens you use for testing are valid.

## Author

- **Al Amin** - [LinkedIn](https://www.linkedin.com/in/ialaminpro) | [Portfolio](https://al-amin.xyz)

Feel free to reach out to me if you'd like to collaborate or discuss job opportunities in backend development, microservices, or cloud architecture. For further details or inquiries, feel free to contact [ialamin.pro@gmail.com].


## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.
