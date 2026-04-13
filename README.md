
# 🌐 Entrepreneurconnect - Integrated Web Platform

## 📖 Overview

**Entrepreneurconnect** is a robust, full-stack PHP web ecosystem designed for community engagement and service tracking. Developed as part of the **PIDEV – 2nd Year pre-engineering Program**.

The project leverages a clean **MVC (Model-View-Controller)** architecture to ensure scalability, maintainability, and clear separation of concerns between business logic, data, and user interface.

## 🚀 Key Features

### 📊 Data Visualization & Analytics
- **Interactive Charts**: Dynamic data representation using **Chart.js** for distribution and status analytics.
- **Animated Insights**: Real-time animation and interactive legends for platform metrics.

### 🤖 AI-Powered Assistant
- **Real-time Chatbot**: Intelligent support via a built-in AI assistant powered by the **ZukiJourney API**.
- **Interactive Engagement**: Instant responses to user queries within the front-office interface.

### 🏥 Consultation Management
- **Professional Booking**: Users can submit and track medical or professional consultations.
- **Status Tracking**: Real-time updates on consultation status (`pending`, `confirmed`, etc.).
- **Dynamic Search**: High-performance search by title and multi-column sorting.

### 📰 Content & Engagement
- **Article Ecosystem**: Full-featured blog system for sharing insights and news.
- **Giphy Integration**: Expressive communication with a built-in GIF picker powered by the **Giphy API**.
- **Discussion Threads**: Interactive comment sections to foster community engagement.
- **Collaboration Portal**: Dedicated modules for managing collaborative projects.

### 📅 Event Coordination
- **Event Lifecycle**: end-to-end management of community events.
- **Participant Tracking**: Manage and monitor event registrations.

### 🔐 Security & User Management
- **Secure Authentication**: Robust registration and login system with role management.
- **Two-Factor Authentication (2FA)**: Email-based secondary verification for enhanced account security.
- **Account Activation**: Automated email workflows for new user verification.
- **Profile Customization**: Dynamic profile image handling and user preferences.

### 🔔 Technical Highlights & API Integration
- **Real-time Notifications**: Dedicated API for fetching, counting, and managing user notifications.
- **Automated Mailing**: Full integration with **PHPMailer** for transactional emails (registrations, alerts).
- **AJAX-Ready Backend**: Optimized for asynchronous frontend updates via AJAX.
- **Secure Persistence**: Database interactions handled via PDO with prepared statements for SQL injection prevention.

### ✉️ Notification Engine
- **PHPMailer Integration**: Automated email notifications for registrations and updates.

## 🏗️ Architecture & Tech Stack

- **Backend**: PHP 8.x (Vanilla MVC)
- **Database**: MySQL 8.0+
- **Mailing**: PHPMailer
- **External APIs & Libraries**: 
    - **ZukiJourney AI** (Chatbot Agent)
    - **Giphy API** (Media/GIF Selector)
    - **Chart.js** (Data Visualization)
    - **PHPMailer** (Email Engine)
- **Frontend**: HTML5, CSS3, JavaScript, Axios
- **API**: Integrated RESTful API layer for mobile/desktop synchronization.

## 📂 Project Structure

```text
integration_moez/
├── controller/     # MVC: Business logic handlers
├── model/          # MVC: Data entities and DB interaction
├── view/           # MVC: UI templates and assets
├── api/            # 🌐 REST API: Notification and sync endpoints
├── PHPMailer/      # ✉️ Mail Engine: SMTP email delivery
├── lib/            # Library dependencies
├── config.php      # Base DB connectivity
├── mail_config.php # SMTP server credentials
└── README.md       # Project documentation
```

## ⚙️ Installation & Setup

### Prerequisites
- PHP >= 8.0
- MySQL Server
- Web Server (Apache/Nginx)

### 1. Database Configuration
1. Create a new MySQL database named `mydbname` (or customize in `config.php`).
2. Import the project SQL schema (usually found in a `database/` folder or provided separately).

### 2. Environment Setup
1. Clone the repository to your web root (e.g., `htdocs` for XAMPP).
2. Configure your database credentials in `config.php`:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "mydbname";
   ```
3. Configure your SMTP settings in `mail_config.php` for email functionality.

### 3. Running the App
1. Start your local server (XAMPP/WAMP).
2. Navigate to `http://localhost/integration_moez` in your browser.

## 🤝 Contributions

Nous remercions tous ceux qui ont contribué à ce projet !

### Contributeurs

Les personnes suivantes ont contribué à ce projet :

- [yassine gabsi](https://github.com/yassinegab) - gestion des blogs
- [moez touil](https://github.com/moeztouilll) - gestion de collaboration
- [dhia din djebbi](https://github.com/DhiaDjebbi) - gestion users 
- [maryem bennour](https://github.com/meriembenn) - gestion de projet
- [takwa boutaib](https://github.com/Takouabettaieb) - gestion de consultation
- [eya boughdiri](https://github.com/utilisateur3) - gestion des evenements

---
<p align="center">
  Built with ❤️ for the <b>Esprit Engineering Community</b>
</p>
