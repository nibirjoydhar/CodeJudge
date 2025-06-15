# CodeJudge

CodeJudge is a modern, secure, and feature-rich online judge system that enables users to solve programming problems and submit their solutions for automated evaluation. Built with Laravel and powered by Judge0, it provides a robust platform for programming practice and competitions.

## 🌟 Key Features

### Problem Management
- Create and manage programming problems with rich text descriptions
- Multiple difficulty levels (Easy, Medium, Hard)
- Detailed problem specifications with input/output formats
- Comprehensive test case management
- Problem categories and tags for better organization

### Code Submission & Evaluation
- Support for multiple programming languages:
  - C++ (GCC 9.4.0)
  - Python (3.8.0)
  - Java (JDK 11.0.4)
- Real-time submission status updates
- Detailed compilation and runtime error reporting
- Performance metrics (execution time, memory usage)
- Submission history with detailed analytics

### Contest System
- Create and manage programming contests
- Real-time contest standings
- Contest-specific problem sets
- Contest duration and time limits
- Contest registration and participation tracking

### User Experience
- Clean, modern, and responsive UI using Tailwind CSS
- Syntax-highlighted code editor
- Dark/Light mode support
- Real-time notifications
- User profiles with statistics
- Global and contest-specific leaderboards

### Discussion & Community
- Problem-specific discussion threads
- Code sharing and review
- User reputation system
- Rich text comments with code snippets

## 🛠 Technical Stack

### Backend
- **Framework**: Laravel 10.x
- **Language**: PHP 8.1+
- **Database**: MySQL 8.0
- **Cache**: Redis 6.0
- **Queue**: Laravel Horizon
- **Search**: Laravel Scout with Meilisearch

### Frontend
- **Templates**: Blade with Laravel Livewire
- **Styling**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js
- **Code Editor**: Monaco Editor
- **Syntax Highlighting**: Prism.js

### Infrastructure
- **Judge System**: Judge0 CE
- **Containerization**: Docker & Docker Compose
- **Web Server**: Nginx
- **Process Manager**: Supervisor

## 📋 Prerequisites

- Docker and Docker Compose
- PHP >= 8.1
- Composer >= 2.0
- Node.js >= 16.x and npm
- MySQL >= 8.0
- Redis >= 6.0

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/nibirjoydhar/CodeJudge
   cd CodeJudge
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install and compile frontend assets**
   ```bash
   npm install
   npm run build
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Start the services**
   ```bash
   docker-compose up -d
   ```

6. **Database setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

## ⚙️ Configuration

### Environment Variables
Configure your `.env` file with the following essential settings:
```env
APP_NAME=CodeJudge
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=codejudge
DB_USERNAME=your_username
DB_PASSWORD=your_password

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

JUDGE0_API_URL=http://localhost:2358
JUDGE0_API_KEY=your_api_key
```

### Judge0 Configuration
The system supports both RapidAPI's Judge0 service and local Judge0 CE instance:
- API Endpoint: http://localhost:2358
- Worker Count: 5
- Maximum Queue Size: 100
- Supported Languages: C++, Python, Java
- Execution Time Limit: 5 seconds
- Memory Limit: 512MB

## 🔒 Security Features

- **Code Execution**
  - Isolated Docker containers for each submission
  - Resource limits (CPU, memory, disk)
  - Network isolation
  - File system restrictions

- **Application Security**
  - CSRF protection
  - XSS prevention
  - SQL injection protection
  - Rate limiting
  - Input validation and sanitization
  - Secure session handling

## 📊 Monitoring & Maintenance

- **Logging**
  - Laravel's built-in logging system
  - Error tracking with Sentry
  - Performance monitoring with New Relic

- **Backup**
  - Automated database backups
  - File system backups
  - Backup rotation and retention

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

Please read our [Contributing Guidelines](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

The MIT License is a permissive license that is short and to the point. It lets people do anything they want with your code as long as they provide attribution back to you and don't hold you liable.

### What you can do with this code:
- ✅ Commercial use
- ✅ Modify
- ✅ Distribute
- ✅ Private use

### Limitations:
- ⚠️ Liability
- ⚠️ Warranty

### Requirements:
- ℹ️ License and copyright notice

For more information about the MIT License, visit [choosealicense.com/licenses/mit/](https://choosealicense.com/licenses/mit/)

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The web framework used
- [Judge0](https://judge0.com) - The code execution engine
- [Tailwind CSS](https://tailwindcss.com) - The CSS framework
- [Docker](https://www.docker.com) - The containerization platform

## 📞 Support

For support, please:
- Open an issue in the GitHub repository
- Contact the maintainers at [your-email@example.com]
- Join our [Discord community](https://discord.gg/your-invite-link)

---

Made with ❤️ by Nibir Joydhar
